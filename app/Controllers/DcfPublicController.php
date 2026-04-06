<?php

namespace App\Controllers;

use App\Models\Dcf;
use App\Models\DcfPart;
use App\Models\DcfQuestion;
use App\Models\DcfQuestionOption;
use App\Models\DcfResponse;
use App\Models\DcfResponseAnswer;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserRole;

class DcfPublicController extends BaseController
{
    private const ALL_ROLES_KEY = 'all';

    private function isPastDueDate(?string $dueDate): bool
    {
        if (!is_string($dueDate) || trim($dueDate) === '') {
            return false;
        }

        $dueTimestamp = strtotime($dueDate . ' 23:59:59');
        if ($dueTimestamp === false) {
            return false;
        }

        return time() > $dueTimestamp;
    }

    public function form($shareToken)
    {
        $dcfModel = new Dcf();
        $dcf = $dcfModel->where('share_token', $shareToken)->first();

        if (!$dcf) {
            return redirect()->to('/')->with('error', 'Invalid or expired DCF link.');
        }

        $partModel = new DcfPart();
        $questionModel = new DcfQuestion();
        $parts = $partModel
            ->where('dcf_id', $dcf['id'])
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $questions = $questionModel
            ->where('dcf_id', $dcf['id'])
            ->orderBy('part_id', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $optionModel = new DcfQuestionOption();
        $questionsByPart = [];
        foreach ($questions as &$question) {
            $question['options'] = $optionModel->where('question_id', $question['id'])->orderBy('sort_order', 'ASC')->findAll();
            $partKey = (string) ($question['part_id'] ?? 0);
            if (!isset($questionsByPart[$partKey])) {
                $questionsByPart[$partKey] = [];
            }
            $questionsByPart[$partKey][] = $question;
        }
        unset($question);

        $partsWithQuestions = [];
        foreach ($parts as $part) {
            $partKey = (string) $part['id'];
            $part['questions'] = $questionsByPart[$partKey] ?? [];
            $partsWithQuestions[] = $part;
            unset($questionsByPart[$partKey]);
        }

        if (isset($questionsByPart['0']) && count($questionsByPart['0']) > 0) {
            $partsWithQuestions[] = [
                'id' => 0,
                'title' => 'General',
                'description' => '',
                'questions' => $questionsByPart['0'],
            ];
        }

        $data = [
            'dcf' => $dcf,
            'questions' => $questions,
            'parts' => $partsWithQuestions,
            'userRoles' => $this->getAvailableRoles(),
            'jobTitles' => $this->getAvailableJobTitles(),
            'isPastDue' => $this->isPastDueDate($dcf['due_date'] ?? null),
            'title' => $dcf['title']
        ];

        return view('dcf/public_form', $data);
    }

    public function submit($shareToken)
    {
        $dcfModel = new Dcf();
        $dcf = $dcfModel->where('share_token', $shareToken)->first();

        if (!$dcf) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid DCF']);
        }

        if ($this->isPastDueDate($dcf['due_date'] ?? null)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This form is past its due date and is no longer accepting responses.'
            ]);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'respondent_name' => 'required|max_length[255]',
            'respondent_mobile' => 'permit_empty|max_length[50]',
            'respondent_email' => 'required|valid_email|max_length[255]',
            'respondent_role' => 'permit_empty|max_length[255]',
            'user_consent' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $validation->getErrors()]);
        }

        $responseModel = new DcfResponse();
        $answerModel = new DcfResponseAnswer();
        $questionModel = new DcfQuestion();
        $partModel = new DcfPart();

        $respondentEmailRaw = $this->request->getPost('respondent_email');
        $respondentEmail = is_string($respondentEmailRaw) ? strtolower(trim($respondentEmailRaw)) : '';
        $selectedRoleRaw = $this->request->getPost('respondent_role');
        $selectedRole = is_string($selectedRoleRaw) ? trim($selectedRoleRaw) : '';

        $registeredUser = $this->findRegisteredUserByEmail($respondentEmail);
        $detectedRoleKeys = isset($registeredUser['role_keys']) && is_array($registeredUser['role_keys'])
            ? array_values(array_filter(array_map(static fn($value) => trim((string) $value), $registeredUser['role_keys']), static fn($value) => $value !== ''))
            : [];

        if (count($detectedRoleKeys) === 0 && $selectedRole === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select your role.']);
        }

        $effectiveRoleKeys = $detectedRoleKeys;
        if ($selectedRole !== '') {
            $effectiveRoleKeys[] = $selectedRole;
        }

        $effectiveRoleKeys = array_values(array_unique(array_filter(array_map(static fn($value) => trim((string) $value), $effectiveRoleKeys), static fn($value) => $value !== '')));
        $primaryEffectiveRole = $selectedRole !== ''
            ? $selectedRole
            : ($detectedRoleKeys[0] ?? self::ALL_ROLES_KEY);

        foreach ($effectiveRoleKeys as $roleKey) {
            if (!$this->isAllowedRoleKey($roleKey)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Selected role is invalid.']);
            }
        }

        $existingResponse = $responseModel
            ->where('dcf_id', $dcf['id'])
            ->where('respondent_email', $respondentEmail)
            ->first();

        if ($existingResponse) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'This email has already submitted this form.'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $userConsent = $this->request->getPost('user_consent') ? 1 : 0;

        $responseId = $responseModel->insert([
            'dcf_id' => $dcf['id'],
            'respondent_name' => $this->request->getPost('respondent_name'),
            'respondent_mobile' => $this->request->getPost('respondent_mobile'),
            'respondent_email' => $respondentEmail,
            'respondent_role' => $primaryEffectiveRole,
            'user_consent' => $userConsent,
            'consent_timestamp' => $userConsent ? date('Y-m-d H:i:s') : null,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'submitted_at' => date('Y-m-d H:i:s')
        ]);

        $answers = $this->request->getPost('answers');
        $partRoleMap = [];
        $parts = $partModel->where('dcf_id', $dcf['id'])->findAll();
        foreach ($parts as $part) {
            $partRole = (string) ($part['role_key'] ?? self::ALL_ROLES_KEY);
            $partId = (int) ($part['id'] ?? 0);
            if ($partId > 0) {
                $partRoleMap[$partId] = $partRole;
            }
        }

        $questions = $questionModel->where('dcf_id', $dcf['id'])->findAll();
        $questions = array_values(array_filter($questions, function ($question) use ($effectiveRoleKeys, $partRoleMap) {
            $partId = (int) ($question['part_id'] ?? 0);
            $partRole = $partRoleMap[$partId] ?? self::ALL_ROLES_KEY;
            $targetRole = $this->getEffectiveRoleKey((string) ($question['role_key'] ?? ''), (string) $partRole);

            return $this->canAccessRole($targetRole, $effectiveRoleKeys);
        }));

        foreach ($questions as $question) {
            $questionId = $question['id'];
            $isRequired = $question['is_required'];
            $answer = $answers[$questionId] ?? null;

            $answerText = '';
            if (is_array($answer)) {
                $answerText = json_encode($answer);
            } else {
                $answerText = $answer ?? '';
            }

            if ($isRequired) {
                $isEmpty = ($answer === null || $answer === '' || (is_array($answer) && count($answer) === 0));
                if ($question['answer_type'] === 'wysiwyg') {
                    $plainText = trim(strip_tags((string) $answerText));
                    $isEmpty = ($plainText === '');
                }

                if ($isEmpty) {
                    $db->transRollback();
                    return $this->response->setJSON(['success' => false, 'message' => 'Please answer all required questions']);
                }
            }

            if ($question['answer_type'] === 'rate_me' && $answerText !== '') {
                if (!is_numeric($answerText)) {
                    $db->transRollback();
                    return $this->response->setJSON(['success' => false, 'message' => 'Invalid rate value provided.']);
                }

                $rateValue = (int) $answerText;
                $rateMin = isset($question['rate_min']) && is_numeric($question['rate_min']) ? (int) $question['rate_min'] : 1;
                $rateMax = isset($question['rate_max']) && is_numeric($question['rate_max']) ? (int) $question['rate_max'] : 10;

                if ($rateValue < $rateMin || $rateValue > $rateMax) {
                    $db->transRollback();
                    return $this->response->setJSON(['success' => false, 'message' => 'Rate value must be between ' . $rateMin . ' and ' . $rateMax . '.']);
                }
            }

            $answerModel->insert([
                'response_id' => $responseId,
                'question_id' => $questionId,
                'answer_text' => $answerText
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to submit response']);
        }

        $respondentsNeeded = isset($dcf['respondents_needed']) ? (int) $dcf['respondents_needed'] : 0;
        if ($respondentsNeeded > 0) {
            $currentResponseCount = (int) $responseModel->where('dcf_id', $dcf['id'])->countAllResults();

            if ($currentResponseCount >= $respondentsNeeded) {
                $notificationModel = new Notification();
                $existing = $notificationModel
                    ->where('source_type', 'dcf_respondent_target')
                    ->where('source_id', (int) $dcf['id'])
                    ->first();

                if (!$existing) {
                    $notificationModel->insert([
                        'type' => 'dcf_target_reached',
                        'title' => 'DCF Respondent Target Reached',
                        'message' => '"' . ($dcf['title'] ?? 'DCF Form') . '" reached the target of ' . $respondentsNeeded . ' respondents.',
                        'source_type' => 'dcf_respondent_target',
                        'source_id' => (int) $dcf['id'],
                        'related_url' => site_url('dcf/details/' . (int) $dcf['id']),
                        'is_read' => 0,
                    ]);
                }
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Form submitted successfully.']);
    }

    public function emailRole($shareToken)
    {
        $dcfModel = new Dcf();
        $dcf = $dcfModel->where('share_token', $shareToken)->first();
        if (!$dcf) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Invalid DCF link.',
            ]);
        }

        $email = trim(strtolower((string) $this->request->getGet('email')));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'success' => true,
                'registered' => false,
                'role_key' => null,
            ]);
        }

        $registeredUser = $this->findRegisteredUserByEmail($email);

        return $this->response->setJSON([
            'success' => true,
            'registered' => (bool) ($registeredUser['registered'] ?? false),
            'role_key' => $registeredUser['role_key'] ?? null,
            'role_keys' => $registeredUser['role_keys'] ?? [],
        ]);
    }

    private function canAccessRole(string $targetRoleKey, array $currentRoleKeys): bool
    {
        $target = $this->normalizeRoleKey($targetRoleKey);
        if ($target === '' || $target === self::ALL_ROLES_KEY) {
            return true;
        }

        if (empty($currentRoleKeys)) {
            return false;
        }

        foreach ($currentRoleKeys as $roleKey) {
            $current = $this->normalizeRoleKey((string) $roleKey);
            if ($current !== '' && $this->roleKeysMatch($target, $current)) {
                return true;
            }
        }

        return false;
    }

    private function roleKeysMatch(string $leftRoleKey, string $rightRoleKey): bool
    {
        $left = $this->normalizeRoleKey($leftRoleKey);
        $right = $this->normalizeRoleKey($rightRoleKey);

        if ($left === '' || $right === '') {
            return false;
        }

        if ($left === $right) {
            return true;
        }

        if ($this->canonicalizeRoleKey($left) === $this->canonicalizeRoleKey($right)) {
            return true;
        }

        $shorterLength = min(strlen($left), strlen($right));
        if ($shorterLength < 45) {
            return false;
        }

        return str_starts_with($left, $right) || str_starts_with($right, $left);
    }

    private function canonicalizeRoleKey(string $value): string
    {
        $normalized = $this->normalizeRoleKey($value);
        if ($normalized === '') {
            return '';
        }

        $alnumSpaced = preg_replace('/[^a-z0-9]+/', ' ', $normalized);
        $collapsed = trim(preg_replace('/\s+/', ' ', (string) $alnumSpaced));
        if ($collapsed === '') {
            return '';
        }

        $tokens = explode(' ', $collapsed);
        $canonicalTokens = array_map(static function (string $token): string {
            $token = trim($token);
            if ($token === '') {
                return '';
            }

            if (strlen($token) > 3 && str_ends_with($token, 's')) {
                return substr($token, 0, -1);
            }

            return $token;
        }, $tokens);

        $canonicalTokens = array_values(array_filter($canonicalTokens, static fn($token) => $token !== ''));
        return implode(' ', $canonicalTokens);
    }

    private function getEffectiveRoleKey(string $questionRoleKey, string $partRoleKey): string
    {
        $questionRole = trim($questionRoleKey);
        if ($questionRole !== '') {
            return $questionRole;
        }

        $partRole = trim($partRoleKey);
        return $partRole !== '' ? $partRole : self::ALL_ROLES_KEY;
    }

    private function findRegisteredUserByEmail(string $email): array
    {
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'registered' => false,
                'role_key' => null,
            ];
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id, users.usertype, users.user_role_id, users.email, users.entra_user_principal_name, users.entra_mail, users.entra_job_title, user_roles.role_key AS added_role_key');
        $builder->join('user_roles', 'user_roles.id = users.user_role_id', 'left');
        $builder->groupStart()
            ->where('LOWER(users.email)', $email)
            ->orWhere('LOWER(users.entra_user_principal_name)', $email)
            ->orWhere('LOWER(users.entra_mail)', $email)
            ->groupEnd();

        $user = $builder->get()->getRowArray();

        if (!is_array($user)) {
            return [
                'registered' => false,
                'role_key' => null,
            ];
        }

        $addedRole = trim((string) ($user['added_role_key'] ?? ''));
        $jobTitle = trim((string) ($user['entra_job_title'] ?? ''));

        $roleKeys = array_values(array_unique(array_filter([$addedRole, $jobTitle], static fn($value) => $value !== '')));
        $role = $addedRole !== '' ? $addedRole : $jobTitle;
        return [
            'registered' => true,
            'role_key' => $role !== '' ? $role : null,
            'role_keys' => $roleKeys,
        ];
    }

    private function getAvailableRoles(): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('user_roles')) {
            return [];
        }

        $roleModel = new UserRole();
        $roles = $roleModel->orderBy('role_name', 'ASC')->findAll();

        $excludedRoleKeys = ['readonly', 'readandwrite', 'superadmin'];
        return array_values(array_filter($roles, static function ($role) use ($excludedRoleKeys) {
            $roleKey = strtolower(trim((string) ($role['role_key'] ?? '')));
            return $roleKey !== '' && !in_array($roleKey, $excludedRoleKeys, true);
        }));
    }

    private function isAllowedRoleKey(string $roleKey): bool
    {
        $normalized = $this->normalizeRoleKey($roleKey);
        if ($normalized === self::ALL_ROLES_KEY) {
            return true;
        }

        $keys = array_column($this->getAvailableRoles(), 'role_key');
        
        // Also check against Azure job titles
        $db = \Config\Database::connect();
        if ($db->tableExists('users')) {
            $jobTitles = $db->table('users')
                ->distinct()
                ->select('entra_job_title')
                ->where('entra_job_title IS NOT NULL')
                ->where('entra_job_title !=', '')
                ->get()
                ->getResultArray();
            
            $jobTitleValues = array_map(static fn($row) => trim((string) ($row['entra_job_title'] ?? '')), $jobTitles);
            $keys = array_merge($keys, array_filter($jobTitleValues, static fn($title) => $title !== ''));
        }

        $normalizedKeys = array_map(fn($key) => $this->normalizeRoleKey((string) $key), $keys);
        return in_array($normalized, $normalizedKeys, true);
    }

    private function normalizeRoleKey(string $value): string
    {
        $trimmed = trim($value);
        if ($trimmed === '') {
            return '';
        }

        $singleSpaced = preg_replace('/\s+/', ' ', $trimmed);
        return strtolower((string) $singleSpaced);
    }

    private function getAvailableJobTitles(): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('users')) {
            return [];
        }

        $result = $db->table('users')
            ->distinct()
            ->select('entra_job_title as job_title')
            ->where('entra_job_title IS NOT NULL')
            ->where('entra_job_title !=', '')
            ->orderBy('entra_job_title', 'ASC')
            ->get()
            ->getResultArray();

        return $result;
    }
}
