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
            'respondent_role' => 'permit_empty|max_length[50]',
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
        $detectedRole = $registeredUser['role_key'];
        if (($detectedRole === null || $detectedRole === '') && $selectedRole === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select your role.']);
        }

        $effectiveRole = $detectedRole !== null && $detectedRole !== ''
            ? $detectedRole
            : ($selectedRole !== '' ? $selectedRole : self::ALL_ROLES_KEY);

        if (!$this->isAllowedRoleKey($effectiveRole)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Selected role is invalid.']);
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
            'respondent_role' => $effectiveRole,
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
        $questions = array_values(array_filter($questions, function ($question) use ($effectiveRole, $partRoleMap) {
            $partId = (int) ($question['part_id'] ?? 0);
            $partRole = $partRoleMap[$partId] ?? self::ALL_ROLES_KEY;
            $targetRole = $this->getEffectiveRoleKey((string) ($question['role_key'] ?? ''), (string) $partRole);

            return $this->canAccessRole($targetRole, $effectiveRole);
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
        ]);
    }

    private function canAccessRole(string $targetRoleKey, string $currentRoleKey): bool
    {
        $target = trim($targetRoleKey);
        if ($target === '' || $target === self::ALL_ROLES_KEY) {
            return true;
        }

        return $currentRoleKey !== '' && $target === $currentRoleKey;
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

        $userModel = new User();
        $user = $userModel
            ->select('id, usertype')
            ->where('email', $email)
            ->first();

        if (!is_array($user)) {
            return [
                'registered' => false,
                'role_key' => null,
            ];
        }

        $role = trim((string) ($user['usertype'] ?? ''));
        return [
            'registered' => true,
            'role_key' => $role !== '' ? $role : null,
        ];
    }

    private function getAvailableRoles(): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('user_roles')) {
            return [
                ['role_name' => 'Readonly', 'role_key' => 'readonly'],
                ['role_name' => 'Read and Write', 'role_key' => 'readandwrite'],
                ['role_name' => 'Superadmin', 'role_key' => 'superadmin'],
            ];
        }

        $roleModel = new UserRole();
        return $roleModel->orderBy('role_name', 'ASC')->findAll();
    }

    private function isAllowedRoleKey(string $roleKey): bool
    {
        $normalized = trim($roleKey);
        if ($normalized === self::ALL_ROLES_KEY) {
            return true;
        }

        $keys = array_column($this->getAvailableRoles(), 'role_key');
        return in_array($normalized, $keys, true);
    }
}
