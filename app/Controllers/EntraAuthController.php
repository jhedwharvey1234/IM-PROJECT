<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserRole;
use CodeIgniter\HTTP\ResponseInterface;

class EntraAuthController extends BaseController
{
    private const SESSION_STATE_KEY = 'entra_auth_state';
    private const SESSION_NONCE_KEY = 'entra_auth_nonce';
    private const COOKIE_STATE_KEY = 'entra_auth_state';
    private const COOKIE_NONCE_KEY = 'entra_auth_nonce';

    public function login()
    {
        $config = $this->getEntraConfig();
        if (!$config['enabled']) {
            return redirect()->to(site_url('login'))->with('error', 'Microsoft sign-in is not enabled.');
        }

        if ($config['client_id'] === '' || $config['tenant'] === '') {
            return redirect()->to(site_url('login'))->with('error', 'Microsoft sign-in is not configured correctly.');
        }

        $state = bin2hex(random_bytes(24));
        $nonce = bin2hex(random_bytes(24));

        session()->set(self::SESSION_STATE_KEY, $state);
        session()->set(self::SESSION_NONCE_KEY, $nonce);

        $this->setTransientCookie(self::COOKIE_STATE_KEY, $state);
        $this->setTransientCookie(self::COOKIE_NONCE_KEY, $nonce);

        $authorizeUrl = sprintf(
            'https://login.microsoftonline.com/%s/oauth2/v2.0/authorize',
            rawurlencode($config['tenant'])
        );

        $query = http_build_query([
            'client_id' => $config['client_id'],
            'response_type' => 'code',
            'redirect_uri' => $config['redirect_uri'],
            'response_mode' => 'query',
            'scope' => implode(' ', $config['scopes']),
            'state' => $state,
            'nonce' => $nonce,
        ]);

        return redirect()->to($authorizeUrl . '?' . $query);
    }

    public function callback()
    {
        $config = $this->getEntraConfig();
        if (!$config['enabled']) {
            return redirect()->to(site_url('login'))->with('error', 'Microsoft sign-in is not enabled.');
        }

        $expectedState = (string) session()->get(self::SESSION_STATE_KEY);
        $expectedNonce = (string) session()->get(self::SESSION_NONCE_KEY);

        if ($expectedState === '') {
            $expectedState = (string) $this->request->getCookie(self::COOKIE_STATE_KEY);
        }

        if ($expectedNonce === '') {
            $expectedNonce = (string) $this->request->getCookie(self::COOKIE_NONCE_KEY);
        }

        session()->remove(self::SESSION_STATE_KEY);
        session()->remove(self::SESSION_NONCE_KEY);
        $this->clearTransientCookie(self::COOKIE_STATE_KEY);
        $this->clearTransientCookie(self::COOKIE_NONCE_KEY);

        $state = trim((string) $this->request->getGet('state'));
        if ($expectedState === '' || $state === '' || !hash_equals($expectedState, $state)) {
            return redirect()->to(site_url('login'))->with('error', 'Invalid Microsoft sign-in state.');
        }

        $error = trim((string) $this->request->getGet('error'));
        if ($error !== '') {
            $description = trim((string) $this->request->getGet('error_description'));
            return redirect()->to(site_url('login'))->with('error', $description !== '' ? $description : 'Microsoft sign-in failed.');
        }

        $code = trim((string) $this->request->getGet('code'));
        if ($code === '') {
            return redirect()->to(site_url('login'))->with('error', 'Missing authorization code from Microsoft.');
        }

        $tokenResponse = $this->exchangeCodeForTokens($code, $config);
        if (!$tokenResponse['success']) {
            return redirect()->to(site_url('login'))->with('error', $tokenResponse['message']);
        }

        $idToken = (string) ($tokenResponse['tokens']['id_token'] ?? '');
        if ($idToken === '') {
            return redirect()->to(site_url('login'))->with('error', 'Microsoft did not return an ID token.');
        }

        $claimsResult = $this->validateIdToken($idToken, $expectedNonce, $config);
        if (!$claimsResult['success']) {
            return redirect()->to(site_url('login'))->with('error', $claimsResult['message']);
        }

        $claims = $claimsResult['claims'];
        $accessToken = (string) ($tokenResponse['tokens']['access_token'] ?? '');
        $graphProfile = $this->fetchGraphUserProfile($accessToken);

        $userResult = $this->linkOrProvisionUser($claims, $config, $graphProfile);
        if (!$userResult['success']) {
            return redirect()->to(site_url('login'))->with('error', $userResult['message']);
        }

        $user = $userResult['user'];
        $actualRole = (string) ($user['usertype'] ?? '');
        $sessionRole = strtolower($actualRole) === 'readandwrite' ? 'superadmin' : $actualRole;

        session()->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'usertype' => $sessionRole,
            'usertype_actual' => $actualRole,
            'auth_provider' => 'entra',
        ]);

        return redirect()->to(site_url('dashboard'));
    }

    public function logout()
    {
        $config = $this->getEntraConfig();
        if (!$config['enabled']) {
            return redirect()->to(site_url('login'));
        }

        $postLogoutRedirect = $config['post_logout_redirect_uri'] !== ''
            ? $config['post_logout_redirect_uri']
            : site_url('login');

        $logoutUrl = sprintf(
            'https://login.microsoftonline.com/%s/oauth2/v2.0/logout?post_logout_redirect_uri=%s',
            rawurlencode($config['tenant']),
            rawurlencode($postLogoutRedirect)
        );

        return redirect()->to($logoutUrl);
    }

    private function exchangeCodeForTokens(string $code, array $config): array
    {
        if ($config['client_secret'] === '') {
            return [
                'success' => false,
                'message' => 'Microsoft sign-in client secret is missing.',
            ];
        }

        $tokenUrl = sprintf('https://login.microsoftonline.com/%s/oauth2/v2.0/token', rawurlencode($config['tenant']));

        try {
            $client = service('curlrequest', [
                'timeout' => 20,
                'http_errors' => false,
            ]);

            $response = $client->post($tokenUrl, [
                'form_params' => [
                    'client_id' => $config['client_id'],
                    'client_secret' => $config['client_secret'],
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $config['redirect_uri'],
                    'scope' => implode(' ', $config['scopes']),
                ],
            ]);

            $status = $response->getStatusCode();
            $body = (string) $response->getBody();
            $json = json_decode($body, true);

            if ($status < 200 || $status >= 300 || !is_array($json)) {
                return [
                    'success' => false,
                    'message' => 'Microsoft token exchange failed.',
                ];
            }

            if (!empty($json['error'])) {
                return [
                    'success' => false,
                    'message' => (string) ($json['error_description'] ?? 'Microsoft token response returned an error.'),
                ];
            }

            return [
                'success' => true,
                'tokens' => $json,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Microsoft token exchange error: ' . $e->getMessage(),
            ];
        }
    }

    private function validateIdToken(string $idToken, string $expectedNonce, array $config): array
    {
        $parts = explode('.', $idToken);
        if (count($parts) !== 3) {
            return ['success' => false, 'message' => 'Invalid ID token format.'];
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
        $header = json_decode($this->base64UrlDecode($encodedHeader), true);
        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        $signature = $this->base64UrlDecode($encodedSignature);

        if (!is_array($header) || !is_array($payload) || !is_string($signature)) {
            return ['success' => false, 'message' => 'Invalid ID token payload.'];
        }

        $kid = (string) ($header['kid'] ?? '');
        if ($kid === '') {
            return ['success' => false, 'message' => 'ID token missing key identifier.'];
        }

        $metadataResult = $this->fetchOpenIdConfiguration($config['tenant']);
        if (!$metadataResult['success']) {
            return ['success' => false, 'message' => $metadataResult['message']];
        }

        $metadata = $metadataResult['metadata'];
        $jwksUri = (string) ($metadata['jwks_uri'] ?? '');
        if ($jwksUri === '') {
            return ['success' => false, 'message' => 'OpenID metadata missing jwks_uri.'];
        }

        $jwksResult = $this->fetchJson($jwksUri);
        if (!$jwksResult['success']) {
            return ['success' => false, 'message' => $jwksResult['message']];
        }

        $keys = $jwksResult['json']['keys'] ?? [];
        if (!is_array($keys) || empty($keys)) {
            return ['success' => false, 'message' => 'No JWKS keys available for token validation.'];
        }

        $pem = $this->resolvePemFromJwks($keys, $kid);
        if ($pem === null) {
            return ['success' => false, 'message' => 'Unable to resolve signing key for token.'];
        }

        $signingInput = $encodedHeader . '.' . $encodedPayload;
        $verified = openssl_verify($signingInput, $signature, $pem, OPENSSL_ALGO_SHA256);
        if ($verified !== 1) {
            return ['success' => false, 'message' => 'Invalid ID token signature.'];
        }

        $audience = (string) ($payload['aud'] ?? '');
        if ($audience !== $config['client_id']) {
            return ['success' => false, 'message' => 'Invalid ID token audience.'];
        }

        $issuer = (string) ($payload['iss'] ?? '');
        if (!$this->isValidIssuer($issuer, $config['tenant'])) {
            return ['success' => false, 'message' => 'Invalid ID token issuer.'];
        }

        $now = time();
        $exp = isset($payload['exp']) ? (int) $payload['exp'] : 0;
        $nbf = isset($payload['nbf']) ? (int) $payload['nbf'] : 0;

        if ($exp !== 0 && $exp < ($now - 60)) {
            return ['success' => false, 'message' => 'ID token is expired.'];
        }

        if ($nbf !== 0 && $nbf > ($now + 60)) {
            return ['success' => false, 'message' => 'ID token is not yet valid.'];
        }

        $nonce = (string) ($payload['nonce'] ?? '');
        if ($expectedNonce === '' || $nonce === '' || !hash_equals($expectedNonce, $nonce)) {
            return ['success' => false, 'message' => 'Invalid ID token nonce.'];
        }

        return [
            'success' => true,
            'claims' => $payload,
        ];
    }

    private function linkOrProvisionUser(array $claims, array $config, array $graphProfile = []): array
    {
        $oid = trim((string) ($claims['oid'] ?? ''));
        $email = trim((string) ($claims['email'] ?? ''));
        if ($email === '') {
            $email = trim((string) ($claims['preferred_username'] ?? ''));
        }
        if ($email === '') {
            $email = trim((string) ($claims['upn'] ?? ''));
        }
        $email = strtolower($email);

        if ($oid === '' && $email === '') {
            return [
                'success' => false,
                'message' => 'Microsoft account is missing both object ID and email.',
            ];
        }

        $displayName = trim((string) ($claims['name'] ?? ''));
        $usernameSeed = $displayName !== '' ? $displayName : ($email !== '' ? explode('@', $email)[0] : 'entra_user');

        $userModel = new User();
        $db = \Config\Database::connect();
        $hasOidColumn = $db->fieldExists('entra_object_id', 'users');

        $user = null;
        if ($hasOidColumn && $oid !== '') {
            $user = $userModel->where('entra_object_id', $oid)->first();
        }

        if (!$user && $email !== '') {
            $user = $userModel->where('email', $email)->first();
        }

        $resolvedRole = $this->resolveLocalRoleFromClaims($claims, $config);
        $entraProfileData = $this->buildEntraProfileData($claims, $graphProfile);

        if ($user) {
            $updates = [];

            if ($hasOidColumn && $oid !== '' && (string) ($user['entra_object_id'] ?? '') !== $oid) {
                $updates['entra_object_id'] = $oid;
            }

            if ($email !== '' && strtolower((string) ($user['email'] ?? '')) !== $email) {
                $updates['email'] = $email;
            }

            if ($resolvedRole !== '' && (string) ($user['usertype'] ?? '') !== $resolvedRole) {
                $updates['usertype'] = $resolvedRole;
            }

            $newUsername = $this->generateUniqueUsername($usernameSeed, $userModel, (int) $user['id']);
            if ($newUsername !== '' && (string) ($user['username'] ?? '') !== $newUsername) {
                $updates['username'] = $newUsername;
            }

            foreach ($entraProfileData as $field => $value) {
                $currentValue = array_key_exists($field, $user) ? $user[$field] : null;
                if ((string) ($currentValue ?? '') !== (string) ($value ?? '')) {
                    $updates[$field] = $value;
                }
            }

            if (!empty($updates)) {
                $userModel->update((int) $user['id'], $updates);
                $user = $userModel->find((int) $user['id']);
            }

            return ['success' => true, 'user' => $user];
        }

        $username = $this->generateUniqueUsername($usernameSeed, $userModel, null);
        if ($username === '') {
            $username = 'entra_user_' . substr(md5((string) microtime(true)), 0, 8);
        }

        $createData = [
            'username' => $username,
            'email' => $email !== '' ? $email : ('entra+' . substr(md5($oid), 0, 12) . '@local.invalid'),
            'password' => bin2hex(random_bytes(16)),
            'usertype' => $resolvedRole,
        ];

        if ($hasOidColumn && $oid !== '') {
            $createData['entra_object_id'] = $oid;
        }

        $createData = array_merge($createData, $entraProfileData);

        $insertedId = $userModel->insert($createData);
        if (!$insertedId) {
            return [
                'success' => false,
                'message' => 'Failed to provision local user from Microsoft account.',
            ];
        }

        $createdUser = $userModel->find((int) $insertedId);
        return ['success' => true, 'user' => $createdUser];
    }

    private function fetchGraphUserProfile(string $accessToken): array
    {
        if (trim($accessToken) === '') {
            return [];
        }

        try {
            $client = service('curlrequest', [
                'timeout' => 20,
                'http_errors' => false,
            ]);

            $response = $client->get('https://graph.microsoft.com/v1.0/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
            ]);

            $status = $response->getStatusCode();
            if ($status < 200 || $status >= 300) {
                return [];
            }

            $json = json_decode((string) $response->getBody(), true);
            return is_array($json) ? $json : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function buildEntraProfileData(array $claims, array $graphProfile): array
    {
        $displayName = $this->normalizeNullableString($graphProfile['displayName'] ?? ($claims['name'] ?? null), 150);
        $upn = $this->normalizeNullableString(
            $graphProfile['userPrincipalName'] ?? ($claims['preferred_username'] ?? ($claims['upn'] ?? null)),
            255
        );

        return [
            'entra_display_name' => $displayName,
            'entra_user_principal_name' => $upn,
            'entra_given_name' => $this->normalizeNullableString($graphProfile['givenName'] ?? null, 100),
            'entra_surname' => $this->normalizeNullableString($graphProfile['surname'] ?? null, 100),
            'entra_job_title' => $this->normalizeNullableString($graphProfile['jobTitle'] ?? null, 150),
            'entra_department' => $this->normalizeNullableString($graphProfile['department'] ?? null, 150),
            'entra_office_location' => $this->normalizeNullableString($graphProfile['officeLocation'] ?? null, 150),
            'entra_mobile_phone' => $this->normalizeNullableString($graphProfile['mobilePhone'] ?? null, 50),
            'entra_business_phones' => $this->normalizeBusinessPhones($graphProfile['businessPhones'] ?? null),
        ];
    }

    private function normalizeNullableString($value, int $maxLength): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $normalized = trim($value);
        if ($normalized === '') {
            return null;
        }

        if (function_exists('mb_substr')) {
            return mb_substr($normalized, 0, $maxLength);
        }

        return substr($normalized, 0, $maxLength);
    }

    private function normalizeBusinessPhones($phones): ?string
    {
        if (!is_array($phones)) {
            return null;
        }

        $clean = [];
        foreach ($phones as $phone) {
            if (!is_string($phone)) {
                continue;
            }

            $trimmed = trim($phone);
            if ($trimmed !== '') {
                $clean[] = $trimmed;
            }
        }

        if (empty($clean)) {
            return null;
        }

        return json_encode(array_values($clean));
    }

    private function resolveLocalRoleFromClaims(array $claims, array $config): string
    {
        $availableRoles = $this->getAvailableRoleKeys();
        $roleMap = $this->parseRoleMap($config['role_map']);

        $candidates = [];
        $rolesClaim = $claims['roles'] ?? [];
        if (is_string($rolesClaim) && trim($rolesClaim) !== '') {
            $candidates[] = trim($rolesClaim);
        } elseif (is_array($rolesClaim)) {
            foreach ($rolesClaim as $value) {
                if (is_string($value) && trim($value) !== '') {
                    $candidates[] = trim($value);
                }
            }
        }

        $groupsClaim = $claims['groups'] ?? [];
        if (is_array($groupsClaim)) {
            foreach ($groupsClaim as $value) {
                if (is_string($value) && trim($value) !== '') {
                    $candidates[] = trim($value);
                }
            }
        }

        foreach ($candidates as $candidate) {
            $normalizedCandidate = strtolower($candidate);

            if (isset($roleMap[$normalizedCandidate])) {
                $mapped = $roleMap[$normalizedCandidate];
                if (in_array($mapped, $availableRoles, true)) {
                    return $mapped;
                }
            }

            if (in_array($normalizedCandidate, $availableRoles, true)) {
                return $normalizedCandidate;
            }
        }

        $defaultRole = strtolower(trim((string) $config['default_role']));
        if ($defaultRole !== '' && in_array($defaultRole, $availableRoles, true)) {
            return $defaultRole;
        }

        return !empty($availableRoles) ? $availableRoles[0] : 'readonly';
    }

    private function generateUniqueUsername(string $seed, User $userModel, ?int $excludeId): string
    {
        $base = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '_', $seed)));
        $base = trim($base, '_');
        if ($base === '') {
            $base = 'entra_user';
        }

        $candidate = $base;
        $counter = 1;

        while ($counter <= 1000) {
            $existing = $userModel->where('username', $candidate)->first();
            if (!$existing || ($excludeId !== null && (int) ($existing['id'] ?? 0) === $excludeId)) {
                return $candidate;
            }

            $counter++;
            $candidate = $base . '_' . $counter;
        }

        return $base . '_' . substr(md5((string) microtime(true)), 0, 6);
    }

    private function parseRoleMap(string $raw): array
    {
        $map = [];
        if (trim($raw) === '') {
            return $map;
        }

        $pairs = explode(',', $raw);
        foreach ($pairs as $pair) {
            $parts = explode(':', $pair, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $source = strtolower(trim($parts[0]));
            $target = strtolower(trim($parts[1]));
            if ($source === '' || $target === '') {
                continue;
            }

            $map[$source] = $target;
        }

        return $map;
    }

    private function getAvailableRoleKeys(): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('user_roles')) {
            return ['readonly', 'readandwrite', 'superadmin'];
        }

        $roleModel = new UserRole();
        $rows = $roleModel->findAll();
        $keys = [];

        foreach ($rows as $row) {
            $key = strtolower(trim((string) ($row['role_key'] ?? '')));
            if ($key !== '') {
                $keys[] = $key;
            }
        }

        return array_values(array_unique($keys));
    }

    private function fetchOpenIdConfiguration(string $tenant): array
    {
        $url = sprintf(
            'https://login.microsoftonline.com/%s/v2.0/.well-known/openid-configuration',
            rawurlencode($tenant)
        );

        return $this->fetchJson($url);
    }

    private function fetchJson(string $url): array
    {
        try {
            $client = service('curlrequest', [
                'timeout' => 20,
                'http_errors' => false,
            ]);

            $response = $client->get($url);
            $status = $response->getStatusCode();
            $body = (string) $response->getBody();
            $json = json_decode($body, true);

            if ($status < 200 || $status >= 300 || !is_array($json)) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch remote identity metadata.',
                ];
            }

            return [
                'success' => true,
                'json' => $json,
                'metadata' => $json,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Identity metadata request failed: ' . $e->getMessage(),
            ];
        }
    }

    private function resolvePemFromJwks(array $keys, string $kid): ?string
    {
        foreach ($keys as $key) {
            if (!is_array($key)) {
                continue;
            }

            if ((string) ($key['kid'] ?? '') !== $kid) {
                continue;
            }

            $x5c = $key['x5c'][0] ?? null;
            if (!is_string($x5c) || trim($x5c) === '') {
                return null;
            }

            $cert = chunk_split($x5c, 64, "\n");
            return "-----BEGIN CERTIFICATE-----\n" . $cert . "-----END CERTIFICATE-----\n";
        }

        return null;
    }

    private function isValidIssuer(string $issuer, string $tenant): bool
    {
        if ($issuer === '') {
            return false;
        }

        $normalizedTenant = strtolower(trim($tenant));
        if (in_array($normalizedTenant, ['common', 'organizations', 'consumers'], true)) {
            return (bool) preg_match('#^https://login\.microsoftonline\.com/[^/]+/v2\.0$#i', $issuer);
        }

        $allowed = [
            'https://login.microsoftonline.com/' . $tenant . '/v2.0',
            'https://sts.windows.net/' . $tenant . '/',
        ];

        return in_array($issuer, $allowed, true);
    }

    private function base64UrlDecode(string $input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder > 0) {
            $input .= str_repeat('=', 4 - $remainder);
        }

        $decoded = base64_decode(strtr($input, '-_', '+/'), true);
        return $decoded === false ? '' : $decoded;
    }

    private function getEntraConfig(): array
    {
        $enabledRaw = getenv('ENTRA_ENABLED');
        $enabled = filter_var($enabledRaw !== false ? $enabledRaw : 'false', FILTER_VALIDATE_BOOLEAN);

        $tenant = trim((string) (getenv('ENTRA_TENANT_ID') ?: 'common'));
        $clientId = trim((string) (getenv('ENTRA_CLIENT_ID') ?: ''));
        $clientSecret = trim((string) (getenv('ENTRA_CLIENT_SECRET') ?: ''));

        $redirectUri = trim((string) (getenv('ENTRA_REDIRECT_URI') ?: site_url('auth/entra/callback')));
        $postLogoutRedirectUri = trim((string) (getenv('ENTRA_POST_LOGOUT_REDIRECT_URI') ?: site_url('login')));

        $scopesRaw = trim((string) (getenv('ENTRA_SCOPES') ?: 'openid profile email offline_access User.Read'));
        $scopes = array_values(array_filter(array_map('trim', preg_split('/\s+/', $scopesRaw) ?: []), static fn($scope) => $scope !== ''));
        if (empty($scopes)) {
            $scopes = ['openid', 'profile', 'email', 'offline_access'];
        }

        return [
            'enabled' => $enabled,
            'tenant' => $tenant,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'post_logout_redirect_uri' => $postLogoutRedirectUri,
            'scopes' => $scopes,
            'role_map' => (string) (getenv('ENTRA_ROLE_MAP') ?: ''),
            'default_role' => (string) (getenv('ENTRA_DEFAULT_ROLE') ?: 'readonly'),
        ];
    }

    private function setTransientCookie(string $name, string $value): void
    {
        $isSecure = $this->request->isSecure();
        setcookie($name, $value, [
            'expires' => time() + 900,
            'path' => '/',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    private function clearTransientCookie(string $name): void
    {
        $isSecure = $this->request->isSecure();
        setcookie($name, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}
