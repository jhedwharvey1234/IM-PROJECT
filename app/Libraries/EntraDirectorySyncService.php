<?php

namespace App\Libraries;

use App\Models\User;

class EntraDirectorySyncService
{
    private const BASE_USER_TYPES = ['superadmin', 'readandwrite', 'readonly'];

    private const GRAPH_FIELDS = [
        'id',
        'displayName',
        'givenName',
        'surname',
        'userPrincipalName',
        'accountEnabled',
        'lastPasswordChangeDateTime',
        'identities',
        'userType',
        'jobTitle',
        'companyName',
        'department',
        'employeeId',
        'officeLocation',
        'city',
        'state',
        'postalCode',
        'country',
        'mobilePhone',
        'mail',
        'businessPhones',
    ];

    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function syncAllUsers(?callable $progressCallback = null, ?callable $shouldContinue = null): array
    {
        $config = $this->getConfig();

        $this->emitProgress($progressCallback, [
            'stage' => 'starting',
            'percent' => 1,
            'message' => 'Starting Azure AD sync...',
        ]);

        if (!$config['enabled']) {
            throw new \RuntimeException('ENTRA sync is disabled. Set ENTRA_SYNC_ENABLED=true in .env.');
        }

        if ($config['tenant'] === '' || $config['client_id'] === '' || $config['client_secret'] === '') {
            throw new \RuntimeException('Missing ENTRA app-only credentials in .env (ENTRA_TENANT_ID, ENTRA_CLIENT_ID, ENTRA_CLIENT_SECRET).');
        }

        $this->ensureCanContinue($shouldContinue);

        $token = $this->acquireAccessToken($config);
        $this->emitProgress($progressCallback, [
            'stage' => 'fetch_users',
            'percent' => 3,
            'message' => 'Access token acquired. Fetching users from Microsoft Graph...',
        ]);

        $graphUsers = $this->fetchAllUsers($token, (int) $config['page_size'], $progressCallback, $shouldContinue);

        $this->emitProgress($progressCallback, [
            'stage' => 'fetch_managers',
            'percent' => 35,
            'message' => 'Fetching manager details...',
            'fetched' => count($graphUsers),
        ]);

        $managerMap = $this->fetchManagersMap($token, $graphUsers, $progressCallback, $shouldContinue);

        $stats = [
            'fetched' => count($graphUsers),
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        $totalUsers = max(1, count($graphUsers));
        $processed = 0;

        $this->emitProgress($progressCallback, [
            'stage' => 'sync_users',
            'percent' => 55,
            'message' => sprintf('Syncing %d user(s) to local database...', (int) $stats['fetched']),
            'processed' => 0,
            'total' => (int) $stats['fetched'],
            'stats' => $stats,
        ]);

        foreach ($graphUsers as $graphUser) {
            $this->ensureCanContinue($shouldContinue);

            try {
                $result = $this->syncSingleUser($graphUser, (string) $config['default_main_role'], $managerMap);
                if ($result === 'created') {
                    $stats['created']++;
                } elseif ($result === 'updated') {
                    $stats['updated']++;
                } else {
                    $stats['skipped']++;
                }
            } catch (\Throwable $e) {
                $stats['failed']++;
                if (count($stats['errors']) < 20) {
                    $identifier = (string) ($graphUser['id'] ?? $graphUser['userPrincipalName'] ?? 'unknown-user');
                    $stats['errors'][] = $identifier . ': ' . $e->getMessage();
                }
            }

            $processed++;
            $syncPercent = 55 + (int) floor(($processed / $totalUsers) * 44);
            $identifier = (string) ($graphUser['userPrincipalName'] ?? $graphUser['id'] ?? 'user');

            $this->emitProgress($progressCallback, [
                'stage' => 'sync_users',
                'percent' => min(99, max(55, $syncPercent)),
                'message' => sprintf('Processed %d/%d users (%s)', $processed, (int) $stats['fetched'], $identifier),
                'processed' => $processed,
                'total' => (int) $stats['fetched'],
                'stats' => $stats,
            ]);
        }

        $this->emitProgress($progressCallback, [
            'stage' => 'completed',
            'percent' => 100,
            'message' => 'Azure AD sync completed.',
            'processed' => (int) $stats['fetched'],
            'total' => (int) $stats['fetched'],
            'stats' => $stats,
        ]);

        return $stats;
    }

    private function syncSingleUser(array $graphUser, string $defaultMainRole, array $managerMap = []): string
    {
        $entraObjectId = $this->normalizeNullableString($graphUser['id'] ?? null, 64);
        if ($entraObjectId === null) {
            return 'skipped';
        }

        $manager = $managerMap[$entraObjectId] ?? [];

        $entraMail = $this->normalizeNullableString($graphUser['mail'] ?? null, 191);
        $entraUpn = $this->normalizeNullableString($graphUser['userPrincipalName'] ?? null, 191);
        $resolvedEmail = $entraMail ?? $entraUpn;

        $mapped = [
            'entra_object_id' => $entraObjectId,
            'entra_display_name' => $this->normalizeNullableString($graphUser['displayName'] ?? null, 191),
            'entra_given_name' => $this->normalizeNullableString($graphUser['givenName'] ?? null, 100),
            'entra_surname' => $this->normalizeNullableString($graphUser['surname'] ?? null, 100),
            'entra_user_principal_name' => $entraUpn,
            'entra_account_enabled' => $this->normalizeNullableBool($graphUser['accountEnabled'] ?? null),
            'entra_last_password_change_at' => $this->normalizeNullableDateTime($graphUser['lastPasswordChangeDateTime'] ?? null),
            'entra_identities' => $this->normalizeJson($graphUser['identities'] ?? null),
            'entra_user_type' => $this->normalizeNullableString($graphUser['userType'] ?? null, 50),
            'entra_job_title' => $this->normalizeNullableString($graphUser['jobTitle'] ?? null, 191),
            'entra_company_name' => $this->normalizeNullableString($graphUser['companyName'] ?? null, 191),
            'entra_department' => $this->normalizeNullableString($graphUser['department'] ?? null, 191),
            'entra_employee_id' => $this->normalizeNullableString($graphUser['employeeId'] ?? null, 100),
            'entra_office_location' => $this->normalizeNullableString($graphUser['officeLocation'] ?? null, 191),
            'entra_city' => $this->normalizeNullableString($graphUser['city'] ?? null, 120),
            'entra_state' => $this->normalizeNullableString($graphUser['state'] ?? null, 120),
            'entra_postal_code' => $this->normalizeNullableString($graphUser['postalCode'] ?? null, 30),
            'entra_country' => $this->normalizeNullableString($graphUser['country'] ?? null, 120),
            'entra_mobile_phone' => $this->normalizeNullableString($graphUser['mobilePhone'] ?? null, 64),
            'entra_mail' => $entraMail,
            'entra_business_phones' => $this->normalizeJson($graphUser['businessPhones'] ?? null),
            'entra_manager_object_id' => $this->normalizeNullableString($manager['id'] ?? null, 64),
            'entra_manager_display_name' => $this->normalizeNullableString($manager['displayName'] ?? null, 191),
            'entra_manager_user_principal_name' => $this->normalizeNullableString($manager['userPrincipalName'] ?? null, 191),
            'entra_manager_mail' => $this->normalizeNullableString($manager['mail'] ?? null, 191),
        ];

        $existing = $this->findExistingUser($entraObjectId, $resolvedEmail);

        if ($existing) {
            $changes = [];
            foreach ($mapped as $field => $value) {
                $current = array_key_exists($field, $existing) ? $existing[$field] : null;
                if ((string) ($current ?? '') !== (string) ($value ?? '')) {
                    $changes[$field] = $value;
                }
            }

            if (!empty($changes)) {
                $this->userModel->skipValidation(true)->update((int) $existing['id'], $changes);
                return 'updated';
            }

            return 'skipped';
        }

        $displayNameSeed = $mapped['entra_display_name'] ?? $entraUpn ?? ('entra_user_' . substr(md5($entraObjectId), 0, 8));
        $username = $this->generateUniqueUsername((string) $displayNameSeed);

        $mainRole = strtolower(trim($defaultMainRole));
        if (!in_array($mainRole, self::BASE_USER_TYPES, true)) {
            $mainRole = 'readonly';
        }

        $createData = array_merge([
            'username' => $username,
            'email' => $resolvedEmail ?? ('entra+' . substr(md5($entraObjectId), 0, 12) . '@local.invalid'),
            'password' => bin2hex(random_bytes(16)),
            'usertype' => $mainRole,
        ], $mapped);

        $inserted = $this->userModel->skipValidation(true)->insert($createData);
        if (!$inserted) {
            $fallbackData = $createData;
            $fallbackData['email'] = 'entra+' . substr(md5($entraObjectId . microtime(true)), 0, 12) . '@local.invalid';
            $fallbackInserted = $this->userModel->skipValidation(true)->insert($fallbackData);
            if (!$fallbackInserted) {
                throw new \RuntimeException('Failed to insert user into local database.');
            }
        }

        return 'created';
    }

    private function findExistingUser(string $entraObjectId, ?string $resolvedEmail): ?array
    {
        $existing = $this->userModel->where('entra_object_id', $entraObjectId)->first();
        if ($existing) {
            return $existing;
        }

        if ($resolvedEmail !== null) {
            $existing = $this->userModel->where('email', strtolower($resolvedEmail))->first();
            if ($existing) {
                return $existing;
            }
        }

        return null;
    }

    private function acquireAccessToken(array $config): string
    {
        $tokenUrl = sprintf('https://login.microsoftonline.com/%s/oauth2/v2.0/token', rawurlencode($config['tenant']));

        $client = service('curlrequest', [
            'timeout' => 30,
            'http_errors' => false,
        ]);

        $response = $client->post($tokenUrl, [
            'form_params' => [
                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'grant_type' => 'client_credentials',
                'scope' => 'https://graph.microsoft.com/.default',
            ],
        ]);

        $status = $response->getStatusCode();
        $json = json_decode((string) $response->getBody(), true);

        if ($status < 200 || $status >= 300 || !is_array($json)) {
            throw new \RuntimeException('Failed to acquire Microsoft Graph app-only token.');
        }

        $accessToken = (string) ($json['access_token'] ?? '');
        if ($accessToken === '') {
            $errorText = (string) ($json['error_description'] ?? 'Access token missing from response.');
            throw new \RuntimeException($errorText);
        }

        return $accessToken;
    }

    private function fetchAllUsers(string $accessToken, int $pageSize, ?callable $progressCallback = null, ?callable $shouldContinue = null): array
    {
        $pageSize = max(1, min(999, $pageSize));
        $select = implode(',', self::GRAPH_FIELDS);
        $query = http_build_query([
            '$select' => $select,
            '$top' => $pageSize,
        ]);

        $url = 'https://graph.microsoft.com/v1.0/users?' . $query;
        $allUsers = [];
        $guard = 0;
        $page = 0;

        while ($url !== '' && $guard < 10000) {
            $guard++;
            $page++;

            $this->ensureCanContinue($shouldContinue);

            $client = service('curlrequest', [
                'timeout' => 45,
                'http_errors' => false,
            ]);

            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
            ]);

            $status = $response->getStatusCode();
            $json = json_decode((string) $response->getBody(), true);

            if ($status < 200 || $status >= 300 || !is_array($json)) {
                throw new \RuntimeException('Failed to read users from Microsoft Graph.');
            }

            $pageUsers = $json['value'] ?? [];
            if (is_array($pageUsers)) {
                foreach ($pageUsers as $pageUser) {
                    if (is_array($pageUser)) {
                        $allUsers[] = $pageUser;
                    }
                }
            }

            $next = $json['@odata.nextLink'] ?? null;
            $url = is_string($next) ? trim($next) : '';

            $estimatedPercent = min(34, 5 + ($page * 2));
            $this->emitProgress($progressCallback, [
                'stage' => 'fetch_users',
                'percent' => $estimatedPercent,
                'message' => sprintf('Fetched %d users from Graph (page %d)', count($allUsers), $page),
                'fetched' => count($allUsers),
            ]);
        }

        return $allUsers;
    }

    private function fetchManagersMap(string $accessToken, array $graphUsers, ?callable $progressCallback = null, ?callable $shouldContinue = null): array
    {
        $map = [];
        $userIds = [];

        foreach ($graphUsers as $graphUser) {
            if (!is_array($graphUser)) {
                continue;
            }

            $id = trim((string) ($graphUser['id'] ?? ''));
            if ($id !== '') {
                $userIds[] = $id;
            }
        }

        if (empty($userIds)) {
            return $map;
        }

        $chunks = array_chunk(array_values(array_unique($userIds)), 20);
        $totalChunks = max(1, count($chunks));
        $processedChunks = 0;

        foreach ($chunks as $chunk) {
            $this->ensureCanContinue($shouldContinue);

            $requests = [];

            foreach ($chunk as $id) {
                $requests[] = [
                    'id' => $id,
                    'method' => 'GET',
                    'url' => '/users/' . rawurlencode($id) . '/manager?$select=id,displayName,userPrincipalName,mail',
                ];
            }

            try {
                $client = service('curlrequest', [
                    'timeout' => 45,
                    'http_errors' => false,
                ]);

                $response = $client->post('https://graph.microsoft.com/v1.0/$batch', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'requests' => $requests,
                    ],
                ]);

                $status = $response->getStatusCode();
                $json = json_decode((string) $response->getBody(), true);

                if ($status < 200 || $status >= 300 || !is_array($json)) {
                    continue;
                }

                $responses = $json['responses'] ?? [];
                if (!is_array($responses)) {
                    continue;
                }

                foreach ($responses as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $id = trim((string) ($item['id'] ?? ''));
                    if ($id === '') {
                        continue;
                    }

                    $body = $item['body'] ?? null;
                    if (is_array($body)) {
                        $map[$id] = $body;
                    }
                }
            } catch (\Throwable $e) {
                continue;
            } finally {
                $processedChunks++;
                $managerPercent = 35 + (int) floor(($processedChunks / $totalChunks) * 20);
                $this->emitProgress($progressCallback, [
                    'stage' => 'fetch_managers',
                    'percent' => min(55, max(35, $managerPercent)),
                    'message' => sprintf('Fetched manager data for batch %d/%d', $processedChunks, $totalChunks),
                ]);
            }
        }

        return $map;
    }

    private function emitProgress(?callable $progressCallback, array $payload): void
    {
        if ($progressCallback === null) {
            return;
        }

        try {
            $progressCallback($payload);
        } catch (\Throwable $e) {
        }
    }

    private function ensureCanContinue(?callable $shouldContinue): void
    {
        if ($shouldContinue === null) {
            return;
        }

        try {
            if ($shouldContinue() === false) {
                throw new \RuntimeException('Sync canceled by user.');
            }
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Sync canceled by user.');
        }
    }

    private function generateUniqueUsername(string $seed): string
    {
        $base = strtolower(trim((string) preg_replace('/[^a-z0-9]+/i', '_', $seed)));
        $base = trim($base, '_');

        if ($base === '') {
            $base = 'entra_user';
        }

        $candidate = $base;
        $counter = 1;

        while ($counter <= 1000) {
            $exists = $this->userModel->where('username', $candidate)->first();
            if (!$exists) {
                return $candidate;
            }

            $counter++;
            $candidate = $base . '_' . $counter;
        }

        return $base . '_' . substr(md5((string) microtime(true)), 0, 8);
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

    private function normalizeJson($value): ?string
    {
        if (!is_array($value)) {
            return null;
        }

        $encoded = json_encode($value);
        if (!is_string($encoded) || $encoded === '' || $encoded === '[]') {
            return null;
        }

        return $encoded;
    }

    private function normalizeNullableBool($value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 1 : 0;
        }

        if (is_numeric($value)) {
            return ((int) $value) === 1 ? 1 : 0;
        }

        if (is_string($value)) {
            $normalized = strtolower(trim($value));
            if ($normalized === '') {
                return null;
            }

            if (in_array($normalized, ['true', '1', 'yes', 'enabled'], true)) {
                return 1;
            }

            if (in_array($normalized, ['false', '0', 'no', 'disabled'], true)) {
                return 0;
            }
        }

        return null;
    }

    private function normalizeNullableDateTime($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $normalized = trim($value);
        if ($normalized === '') {
            return null;
        }

        try {
            $dateTime = new \DateTime($normalized);
            return $dateTime->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function getConfig(): array
    {
        $enabledRaw = getenv('ENTRA_SYNC_ENABLED');
        if ($enabledRaw === false || trim((string) $enabledRaw) === '') {
            $enabledRaw = getenv('ENTRA_ENABLED');
        }

        $enabled = filter_var($enabledRaw !== false ? $enabledRaw : 'false', FILTER_VALIDATE_BOOLEAN);

        $pageSizeRaw = getenv('ENTRA_SYNC_PAGE_SIZE');
        $pageSize = is_numeric($pageSizeRaw) ? (int) $pageSizeRaw : 200;

        $defaultMainRole = trim((string) (getenv('ENTRA_SYNC_DEFAULT_MAIN_ROLE') ?: getenv('ENTRA_DEFAULT_ROLE') ?: 'readonly'));

        return [
            'enabled' => $enabled,
            'tenant' => trim((string) (getenv('ENTRA_TENANT_ID') ?: '')),
            'client_id' => trim((string) (getenv('ENTRA_CLIENT_ID') ?: '')),
            'client_secret' => trim((string) (getenv('ENTRA_CLIENT_SECRET') ?: '')),
            'default_main_role' => $defaultMainRole,
            'page_size' => $pageSize,
        ];
    }
}
