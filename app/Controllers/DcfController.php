<?php

namespace App\Controllers;

use App\Models\Dcf;
use App\Models\Department;
use App\Models\DcfPart;
use App\Models\DcfQuestion;
use App\Models\DcfQuestionOption;
use App\Models\DcfResponse;
use App\Models\DcfResponseAnswer;
use App\Models\UserRole;

class DcfController extends BaseController
{
    private const QUESTION_TYPES_WITH_OPTIONS = ['multiple_choice', 'checkbox', 'dropdown'];

    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();
        $departmentModel = new Department();

        $data['dcfs'] = $dcfModel
            ->select('dcfs.*, departments.department_name')
            ->join('departments', 'departments.id = dcfs.department_id', 'left')
            ->orderBy('dcfs.title', 'ASC')
            ->findAll();
        $data['departments'] = $departmentModel->orderBy('department_name', 'ASC')->findAll();
        $data['title'] = 'DCF Management';

        return view('dcf/index', $data);
    }

    public function create()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $departmentModel = new Department();
        $data['departments'] = $departmentModel->orderBy('department_name', 'ASC')->findAll();
        $data['userRoles'] = $this->getAvailableRoles();
        $data['parts'] = [];
        $data['title'] = 'Create DCF';
        return view('dcf/create', $data);
    }

    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();
        $partModel = new DcfPart();
        $questionModel = new DcfQuestion();
        $optionModel = new DcfQuestionOption();
        $db = \Config\Database::connect();

        $title = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $dueDate = $this->request->getPost('due_date');
        $departmentId = $this->request->getPost('department_id');
        $respondentsNeeded = $this->request->getPost('respondents_needed');

        $titleValue = is_string($title) ? trim($title) : '';
        // Generate a unique name from title + timestamp to satisfy UNIQUE constraint
        $nameValue = preg_replace('/[^a-z0-9]+/i', '_', strtolower($titleValue)) . '_' . time();
        
        $data = [
            'title' => $titleValue,
            'name' => $nameValue,
            'description' => is_string($description) ? trim($description) : '',
            'due_date' => is_string($dueDate) ? trim($dueDate) : '',
            'department_id' => (int) $departmentId,
            'respondents_needed' => (is_numeric($respondentsNeeded) && (int) $respondentsNeeded > 0) ? (int) $respondentsNeeded : null,
        ];

        // Log incoming data for debugging
        log_message('debug', 'DCF Store - Title: ' . $data['title']);
        log_message('debug', 'DCF Store - Raw Parts: ' . json_encode($this->request->getPost('parts')));

        $allowedRoleKeys = $this->getAllowedRoleKeys();
        $parts = $this->normalizePartsInput($this->request->getPost('parts'), $allowedRoleKeys);
        if (isset($parts['error'])) {
            log_message('error', 'Part normalization failed: ' . $parts['error']);
            return redirect()->back()->withInput()->with('errors', [$parts['error']]);
        }

        log_message('debug', 'Normalized parts count: ' . count($parts));

        $db->transBegin();

        // Try inserting without skipping validation to get better error messages
        log_message('debug', 'About to insert DCF with data: ' . json_encode($data));
        $dcfId = $dcfModel->insert($data);
        
        if (!$dcfId) {
            $db->transRollback();
            $errors = $dcfModel->errors();
            log_message('error', 'Failed to insert DCF. Model errors: ' . json_encode($errors));
            
            if (!empty($errors)) {
                return redirect()->back()->withInput()->with('errors', $errors);
            } else {
                return redirect()->back()->withInput()->with('errors', ['Failed to create DCF record. Please check all required fields.']);
            }
        }

        log_message('debug', 'DCF created with ID: ' . $dcfId);

        if (count($parts) > 0) {
            if (!$this->persistPartsAndQuestions((int) $dcfId, $parts, $partModel, $questionModel, $optionModel)) {
                $db->transRollback();
                log_message('error', 'Failed to persist parts/questions');
                return redirect()->back()->withInput()->with('errors', ['Failed to save DCF parts and questions.']);
            }
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            log_message('error', 'Transaction failed');
            return redirect()->back()->withInput()->with('errors', ['Failed to create DCF.']);
        }

        $db->transCommit();
        log_message('info', 'DCF created successfully with ID: ' . $dcfId);
        return redirect()->to('/dcf')->with('success', 'DCF created successfully');
    }

    public function edit($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();
        $departmentModel = new Department();
        $partModel = new DcfPart();
        $questionModel = new DcfQuestion();
        $optionModel = new DcfQuestionOption();
        $data['dcf'] = $dcfModel->find($id);

        if (!$data['dcf']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("DCF $id not found");
        }

        $parts = $this->buildPartsWithQuestions((int) $id, $partModel, $questionModel, $optionModel);

        $data['departments'] = $departmentModel->orderBy('department_name', 'ASC')->findAll();
        $data['userRoles'] = $this->getAvailableRoles();
        $data['parts'] = $parts;
        $data['title'] = 'Edit DCF';
        return view('dcf/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();
        $partModel = new DcfPart();
        $questionModel = new DcfQuestion();
        $optionModel = new DcfQuestionOption();
        $db = \Config\Database::connect();

        $title = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $dueDate = $this->request->getPost('due_date');
        $departmentId = $this->request->getPost('department_id');
        $respondentsNeeded = $this->request->getPost('respondents_needed');

        $titleValue = is_string($title) ? trim($title) : '';
        // Generate a unique name from title + timestamp to satisfy UNIQUE constraint
        $nameValue = preg_replace('/[^a-z0-9]+/i', '_', strtolower($titleValue)) . '_' . time();
        
        $data = [
            'title' => $titleValue,
            'name' => $nameValue,
            'description' => is_string($description) ? trim($description) : '',
            'due_date' => is_string($dueDate) ? trim($dueDate) : '',
            'department_id' => (int) $departmentId,
            'respondents_needed' => (is_numeric($respondentsNeeded) && (int) $respondentsNeeded > 0) ? (int) $respondentsNeeded : null,
        ];

        $allowedRoleKeys = $this->getAllowedRoleKeys();
        $parts = $this->normalizePartsInput($this->request->getPost('parts'), $allowedRoleKeys);
        if (isset($parts['error'])) {
            return redirect()->back()->withInput()->with('errors', [$parts['error']]);
        }

        $db->transBegin();

        if (!$dcfModel->update($id, $data)) {
            $db->transRollback();
            $errors = $dcfModel->errors();
            log_message('error', 'Failed to update DCF. Model errors: ' . json_encode($errors));
            
            if (!empty($errors)) {
                return redirect()->back()->withInput()->with('errors', $errors);
            } else {
                return redirect()->back()->withInput()->with('errors', ['Failed to update DCF record.']);
            }
        }

        $existingQuestions = $questionModel->where('dcf_id', $id)->findAll();
        foreach ($existingQuestions as $existingQuestion) {
            $optionModel->where('question_id', $existingQuestion['id'])->delete();
        }
        $questionModel->where('dcf_id', $id)->delete();
        $partModel->where('dcf_id', $id)->delete();

        if (count($parts) > 0) {
            if (!$this->persistPartsAndQuestions((int) $id, $parts, $partModel, $questionModel, $optionModel)) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['Failed to save DCF parts and questions.']);
            }
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['Failed to update DCF.']);
        }

        $db->transCommit();
        return redirect()->to('/dcf')->with('success', 'DCF updated successfully');
    }

    public function delete($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();

        if ($dcfModel->delete($id)) {
            return redirect()->to('/dcf')->with('success', 'DCF deleted successfully');
        }

        return redirect()->to('/dcf')->with('error', 'Failed to delete DCF');
    }

    public function questions()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $questionModel = new DcfQuestion();

        $data['questions'] = $questionModel
            ->select('dcf_questions.id, dcf_questions.dcf_id, dcfs.department_id, dcf_questions.question_text')
            ->join('dcfs', 'dcfs.id = dcf_questions.dcf_id', 'left')
            ->orderBy('dcf_questions.id', 'DESC')
            ->findAll();

        $data['title'] = 'DCF Questions';

        return view('dcf/questions', $data);
    }

    public function pastQuestionsByDepartment($departmentId)
    {
        if (!session()->get('user_id')) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if (session()->get('usertype') !== 'superadmin') {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Forbidden']);
        }

        $departmentId = (int) $departmentId;
        if ($departmentId <= 0) {
            return $this->response->setJSON(['success' => true, 'questions' => []]);
        }

        $questionModel = new DcfQuestion();
        $optionModel = new DcfQuestionOption();

        $rows = $questionModel
            ->select('dcf_questions.id, dcf_questions.dcf_id, dcf_questions.question_text, dcf_questions.role_key, dcf_questions.is_required, dcf_questions.answer_type, dcf_questions.rate_min, dcf_questions.rate_max, dcf_questions.grid_rows, dcf_questions.grid_columns, dcfs.department_id')
            ->join('dcfs', 'dcfs.id = dcf_questions.dcf_id', 'inner')
            ->where('dcfs.department_id', $departmentId)
            ->orderBy('dcf_questions.id', 'DESC')
            ->findAll();

        foreach ($rows as &$row) {
            $opts = $optionModel
                ->select('option_text')
                ->where('question_id', $row['id'])
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();

            $row['options'] = array_values(array_map(static fn($opt) => $opt['option_text'] ?? '', $opts));
        }
        unset($row);

        return $this->response->setJSON(['success' => true, 'questions' => $rows]);
    }

    public function details($id)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $dcfModel = new Dcf();
        $departmentModel = new Department();
        $partModel = new DcfPart();
        $questionModel = new DcfQuestion();
        $optionModel = new DcfQuestionOption();
        $responseModel = new DcfResponse();
        $answerModel = new DcfResponseAnswer();

        $dcf = $dcfModel->find($id);
        if (!$dcf) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("DCF $id not found");
        }

        $department = $departmentModel->find($dcf['department_id']);
        $parts = $this->buildPartsWithQuestions((int) $id, $partModel, $questionModel, $optionModel);
        $questions = [];
        foreach ($parts as $part) {
            foreach ($part['questions'] as $question) {
                $questions[] = $question;
            }
        }

        $selectedRoleFilter = trim((string) $this->request->getGet('role'));
        if ($selectedRoleFilter === '') {
            $selectedRoleFilter = 'all';
        }

        $roleFilterOptions = $this->getRoleFilterOptions();
        $allowedFilterValues = array_merge(['all', 'unassigned'], array_column($roleFilterOptions, 'role_key'));
        if (!in_array($selectedRoleFilter, $allowedFilterValues, true)) {
            $selectedRoleFilter = 'all';
        }

        $totalResponseCount = $responseModel->where('dcf_id', $id)->countAllResults();

        $filteredResponseModel = new DcfResponse();
        $filteredResponseModel->where('dcf_id', $id);
        $this->applyRespondentRoleFilterToModel($filteredResponseModel, $selectedRoleFilter);
        $responseCount = $filteredResponseModel->countAllResults();

        $responsesModel = new DcfResponse();
        $responsesModel->where('dcf_id', $id);
        $this->applyRespondentRoleFilterToModel($responsesModel, $selectedRoleFilter);
        $responses = $responsesModel->orderBy('submitted_at', 'DESC')->findAll();

        $responseAnswersQuery = $answerModel
            ->select('dcf_response_answers.response_id, dcf_response_answers.question_id, dcf_response_answers.answer_text, dcf_questions.question_text, dcf_questions.answer_type')
            ->join('dcf_responses', 'dcf_responses.id = dcf_response_answers.response_id')
            ->join('dcf_questions', 'dcf_questions.id = dcf_response_answers.question_id', 'left')
            ->where('dcf_responses.dcf_id', $id)
            ->orderBy('dcf_response_answers.response_id', 'ASC')
            ->orderBy('dcf_response_answers.question_id', 'ASC');

        $this->applyRespondentRoleFilterToModel($responseAnswersQuery, $selectedRoleFilter, 'dcf_responses.respondent_role');
        $responseAnswersRows = $responseAnswersQuery->findAll();

        $responseAnswersMap = [];
        foreach ($responseAnswersRows as $row) {
            $responseId = (int) ($row['response_id'] ?? 0);
            if ($responseId <= 0) {
                continue;
            }

            if (!isset($responseAnswersMap[$responseId])) {
                $responseAnswersMap[$responseId] = [];
            }

            $questionType = (string) ($row['answer_type'] ?? 'short_answer');
            $formattedAnswer = $this->formatResponseAnswerForDetails((string) ($row['answer_text'] ?? ''), $questionType);

            $responseAnswersMap[$responseId][] = [
                'question_id' => (int) ($row['question_id'] ?? 0),
                'question_text' => (string) ($row['question_text'] ?? 'Question'),
                'answer_type' => $questionType,
                'answer_text' => $formattedAnswer,
            ];
        }

        $analytics = [];
        foreach ($questions as $question) {
            $questionId = $question['id'];
            $answersQuery = $answerModel
                ->select('dcf_response_answers.answer_text')
                ->join('dcf_responses', 'dcf_responses.id = dcf_response_answers.response_id')
                ->where('dcf_responses.dcf_id', $id)
                ->where('dcf_response_answers.question_id', $questionId);

            $this->applyRespondentRoleFilterToModel($answersQuery, $selectedRoleFilter, 'dcf_responses.respondent_role');
            $answers = $answersQuery->findAll();

            $isTextQuestion = in_array($question['answer_type'], ['short_answer', 'paragraph', 'wysiwyg'], true);

            $analytics[$questionId] = [
                'question' => $question,
                'answers' => $answers,
                'summary' => $this->generateAnswerSummary($question, $answers),
                'textInsights' => $isTextQuestion ? $this->buildTextInsights($answers, $question['answer_type']) : null,
            ];
        }

        $baseUrl = base_url();
        $shareUrl = $baseUrl . '/dcf/form/' . $dcf['share_token'];

        $data = [
            'dcf' => $dcf,
            'department' => $department,
            'parts' => $parts,
            'questions' => $questions,
            'totalResponseCount' => $totalResponseCount,
            'responseCount' => $responseCount,
            'responses' => $responses,
            'responseAnswersMap' => $responseAnswersMap,
            'analytics' => $analytics,
            'selectedRoleFilter' => $selectedRoleFilter,
            'roleFilterOptions' => $roleFilterOptions,
            'shareUrl' => $shareUrl,
            'title' => 'DCF Details - ' . $dcf['title']
        ];

        return view('dcf/details', $data);
    }

    private function applyRespondentRoleFilterToModel($model, string $selectedRoleFilter, string $column = 'respondent_role')
    {
        if ($selectedRoleFilter === 'all') {
            return $model;
        }

        if ($selectedRoleFilter === 'unassigned') {
            $model->groupStart()
                ->where($column, null)
                ->orWhere($column, '')
                ->groupEnd();

            return $model;
        }

        $model->where($column, $selectedRoleFilter);
        return $model;
    }

    private function getRoleFilterOptions(): array
    {
        return array_map(static function ($role) {
            return [
                'role_key' => (string) ($role['role_key'] ?? ''),
                'role_name' => (string) ($role['role_name'] ?? ($role['role_key'] ?? '')),
            ];
        }, $this->getAvailableRoles());
    }

    private function generateAnswerSummary($question, $answers)
    {
        $type = $question['answer_type'];
        
        if (in_array($type, ['multiple_choice', 'dropdown', 'rate_me'])) {
            $counts = [];
            foreach ($answers as $ans) {
                $val = $ans['answer_text'];
                $counts[$val] = ($counts[$val] ?? 0) + 1;
            }
            if ($type === 'rate_me') {
                ksort($counts, SORT_NUMERIC);
            } else {
                arsort($counts);
            }
            return $counts;
        }

        if ($type === 'checkbox') {
            $counts = [];
            foreach ($answers as $ans) {
                $decoded = json_decode($ans['answer_text'], true);
                if (is_array($decoded)) {
                    foreach ($decoded as $item) {
                        $counts[$item] = ($counts[$item] ?? 0) + 1;
                    }
                }
            }
            arsort($counts);
            return $counts;
        }

        if ($type === 'advance_checkbox') {
            $counts = [];
            foreach ($answers as $ans) {
                $decoded = json_decode($ans['answer_text'], true);
                if (is_array($decoded)) {
                    foreach ($decoded as $rowSelections) {
                        if (!is_array($rowSelections)) {
                            continue;
                        }
                        foreach ($rowSelections as $item) {
                            $counts[$item] = ($counts[$item] ?? 0) + 1;
                        }
                    }
                }
            }
            arsort($counts);
            return $counts;
        }

        return array_map(fn($a) => $a['answer_text'], $answers);
    }

    private function buildTextInsights(array $answers, string $answerType): array
    {
        $responses = [];

        foreach ($answers as $answer) {
            $raw = isset($answer['answer_text']) ? (string) $answer['answer_text'] : '';
            if (trim($raw) === '') {
                continue;
            }

            $normalized = $answerType === 'wysiwyg'
                ? html_entity_decode(strip_tags($raw), ENT_QUOTES | ENT_HTML5, 'UTF-8')
                : html_entity_decode($raw, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $normalized = preg_replace('/\s+/u', ' ', trim($normalized));
            if ($normalized !== '') {
                $responses[] = $normalized;
            }
        }

        $stopWords = [
            'the', 'and', 'for', 'that', 'this', 'with', 'from', 'have', 'has', 'had', 'are', 'was', 'were', 'will', 'would',
            'could', 'should', 'about', 'into', 'over', 'under', 'than', 'then', 'them', 'they', 'you', 'your', 'our', 'ours',
            'their', 'theirs', 'his', 'her', 'hers', 'its', 'what', 'when', 'where', 'which', 'while', 'there', 'here', 'also',
            'only', 'very', 'just', 'more', 'most', 'some', 'such', 'each', 'any', 'all', 'not', 'can', 'may', 'might', 'shall',
            'to', 'of', 'in', 'on', 'at', 'by', 'is', 'it', 'as', 'an', 'be', 'or', 'if', 'we', 'us', 'a'
        ];
        $stopWordsLookup = array_fill_keys($stopWords, true);

        $wordCounts = [];
        $phraseCounts = [
            2 => [],
            3 => [],
            4 => [],
            5 => [],
        ];
        $sentenceCounts = [];
        $sentenceDisplay = [];

        foreach ($responses as $response) {
            $lower = function_exists('mb_strtolower') ? mb_strtolower($response, 'UTF-8') : strtolower($response);
            $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $lower);
            $tokens = preg_split('/\s+/u', trim((string) $clean)) ?: [];
            $filteredTokens = [];

            foreach ($tokens as $token) {
                $token = trim($token);
                if ($token === '' || isset($stopWordsLookup[$token])) {
                    continue;
                }
                if (function_exists('mb_strlen') ? mb_strlen($token, 'UTF-8') < 3 : strlen($token) < 3) {
                    continue;
                }
                $wordCounts[$token] = ($wordCounts[$token] ?? 0) + 1;
                $filteredTokens[] = $token;
            }

            $tokenCount = count($filteredTokens);
            for ($n = 2; $n <= 5; $n++) {
                if ($tokenCount < $n) {
                    continue;
                }

                for ($index = 0; $index <= $tokenCount - $n; $index++) {
                    $phrase = implode(' ', array_slice($filteredTokens, $index, $n));
                    $phraseCounts[$n][$phrase] = ($phraseCounts[$n][$phrase] ?? 0) + 1;
                }
            }

            $sentences = preg_split('/(?<=[.!?])\s+|\R+/u', $response) ?: [];
            foreach ($sentences as $sentence) {
                $sentence = trim(preg_replace('/\s+/u', ' ', $sentence));
                if ($sentence === '') {
                    continue;
                }

                $normalizedKey = function_exists('mb_strtolower')
                    ? mb_strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $sentence), 'UTF-8')
                    : strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $sentence));
                $normalizedKey = trim(preg_replace('/\s+/u', ' ', $normalizedKey));

                if ($normalizedKey === '') {
                    continue;
                }

                $wordCount = count(preg_split('/\s+/u', $normalizedKey));
                if ($wordCount < 3) {
                    continue;
                }

                if (!isset($sentenceDisplay[$normalizedKey])) {
                    if (function_exists('mb_strlen') && mb_strlen($sentence, 'UTF-8') > 120) {
                        $sentenceDisplay[$normalizedKey] = mb_substr($sentence, 0, 120, 'UTF-8') . '…';
                    } elseif (strlen($sentence) > 120) {
                        $sentenceDisplay[$normalizedKey] = substr($sentence, 0, 120) . '…';
                    } else {
                        $sentenceDisplay[$normalizedKey] = $sentence;
                    }
                }

                $sentenceCounts[$normalizedKey] = ($sentenceCounts[$normalizedKey] ?? 0) + 1;
            }
        }

        arsort($wordCounts);
        arsort($sentenceCounts);
        for ($n = 2; $n <= 5; $n++) {
            arsort($phraseCounts[$n]);
            $phraseCounts[$n] = array_slice($phraseCounts[$n], 0, 25, true);
        }

        $topWords = array_slice($wordCounts, 0, 15, true);
        $topSentences = [];
        foreach (array_slice($sentenceCounts, 0, 10, true) as $key => $count) {
            $topSentences[$sentenceDisplay[$key] ?? $key] = $count;
        }

        return [
            'responses_count' => count($responses),
            'word_counts' => $topWords,
            'phrase_counts' => [
                '2' => $phraseCounts[2],
                '3' => $phraseCounts[3],
                '4' => $phraseCounts[4],
                '5' => $phraseCounts[5],
            ],
            'sentence_counts' => $topSentences,
        ];
    }

    private function formatResponseAnswerForDetails(string $answerText, string $answerType): string
    {
        if (in_array($answerType, ['checkbox', 'advance_checkbox'], true)) {
            $decoded = json_decode($answerText, true);
            if (is_array($decoded)) {
                $flattened = [];
                $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($decoded));
                foreach ($iterator as $value) {
                    if (is_scalar($value)) {
                        $flattened[] = trim((string) $value);
                    }
                }

                $flattened = array_values(array_filter($flattened, static fn($item) => $item !== ''));
                if (!empty($flattened)) {
                    return implode(', ', $flattened);
                }
            }
        }

        if ($answerType === 'wysiwyg') {
            $plainText = html_entity_decode(strip_tags($answerText), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $plainText = trim(preg_replace('/\s+/u', ' ', $plainText));
            return $plainText !== '' ? $plainText : 'N/A';
        }

        $normalized = trim(preg_replace('/\s+/u', ' ', html_entity_decode($answerText, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        return $normalized !== '' ? $normalized : 'N/A';
    }

    private function normalizePartsInput($rawParts, array $allowedRoleKeys): array
    {
        if (!is_array($rawParts)) {
            return [];
        }

        $normalizedParts = [];
        $allowedTypes = ['multiple_choice', 'checkbox', 'dropdown', 'short_answer', 'paragraph', 'rate_me', 'wysiwyg', 'advance_checkbox'];

        foreach ($rawParts as $partRow) {
            if (!is_array($partRow)) {
                continue;
            }

            $partTitle = isset($partRow['title']) && is_string($partRow['title'])
                ? trim($partRow['title'])
                : '';
            $partDescription = isset($partRow['description']) && is_string($partRow['description'])
                ? trim($partRow['description'])
                : '';
            $partRoleKey = isset($partRow['role_key']) && is_string($partRow['role_key'])
                ? trim($partRow['role_key'])
                : 'all';
            if ($partRoleKey === '') {
                $partRoleKey = 'all';
            }

            if (!in_array($partRoleKey, $allowedRoleKeys, true)) {
                return ['error' => 'Invalid part role provided.'];
            }
            $rawQuestions = isset($partRow['questions']) && is_array($partRow['questions']) ? $partRow['questions'] : [];

            $normalizedQuestions = [];
            foreach ($rawQuestions as $row) {
                if (!is_array($row)) {
                    continue;
                }

                $questionText = isset($row['question_text']) && is_string($row['question_text'])
                    ? trim($row['question_text'])
                    : '';

                if ($questionText === '') {
                    continue;
                }

                $answerType = isset($row['answer_type']) && is_string($row['answer_type'])
                    ? trim($row['answer_type'])
                    : 'short_answer';

                if (!in_array($answerType, $allowedTypes, true)) {
                    return ['error' => 'Invalid question answer type provided.'];
                }

                $isRequired = !empty($row['is_required']) ? 1 : 0;
                $questionRoleKey = isset($row['role_key']) && is_string($row['role_key'])
                    ? trim($row['role_key'])
                    : '';
                if ($questionRoleKey !== '' && !in_array($questionRoleKey, $allowedRoleKeys, true)) {
                    return ['error' => 'Invalid question role provided.'];
                }
                $options = [];
                $rateMin = null;
                $rateMax = null;
                $gridRows = [];
                $gridColumns = [];

                if (in_array($answerType, self::QUESTION_TYPES_WITH_OPTIONS, true)) {
                    $rawOptions = isset($row['options']) && is_array($row['options']) ? $row['options'] : [];
                    foreach ($rawOptions as $opt) {
                        $optText = is_string($opt) ? trim($opt) : '';
                        if ($optText !== '') {
                            $options[] = $optText;
                        }
                    }

                    if (count($options) === 0) {
                        return ['error' => 'Multiple choice, checkbox, and dropdown questions require at least one option.'];
                    }
                }

                if ($answerType === 'rate_me') {
                    $rawMin = $row['rate_min'] ?? null;
                    $rawMax = $row['rate_max'] ?? null;

                    $rateMin = is_numeric($rawMin) ? (int) $rawMin : 1;
                    $rateMax = is_numeric($rawMax) ? (int) $rawMax : 10;

                    if ($rateMin < 1 || $rateMax < 1 || $rateMin > $rateMax) {
                        return ['error' => 'Rate Me range is invalid. Minimum must be at least 1 and not greater than maximum.'];
                    }
                }

                if ($answerType === 'advance_checkbox') {
                    $rawRows = $row['grid_rows'] ?? [];
                    $rawColumns = $row['grid_columns'] ?? [];

                    $gridRows = is_array($rawRows)
                        ? $rawRows
                        : preg_split('/\r\n|\r|\n/', (string) $rawRows);
                    $gridColumns = is_array($rawColumns)
                        ? $rawColumns
                        : preg_split('/\r\n|\r|\n/', (string) $rawColumns);

                    $gridRows = array_values(array_filter(array_map('trim', $gridRows), 'strlen'));
                    $gridColumns = array_values(array_filter(array_map('trim', $gridColumns), 'strlen'));

                    if (count($gridRows) === 0 || count($gridColumns) === 0) {
                        return ['error' => 'Advance Checkbox questions require at least one row and one column.'];
                    }
                }

                $normalizedQuestions[] = [
                    'question_text' => $questionText,
                    'role_key' => $questionRoleKey,
                    'is_required' => $isRequired,
                    'answer_type' => $answerType,
                    'options' => $options,
                    'rate_min' => $rateMin,
                    'rate_max' => $rateMax,
                    'grid_rows' => $gridRows,
                    'grid_columns' => $gridColumns,
                ];
            }

            if ($partTitle === '' && $partDescription === '' && count($normalizedQuestions) === 0) {
                continue;
            }

            if ($partTitle === '') {
                return ['error' => 'Part title is required for each part.'];
            }

            $normalizedParts[] = [
                'title' => $partTitle,
                'description' => $partDescription,
                'role_key' => $partRoleKey,
                'questions' => $normalizedQuestions,
            ];
        }

        return $normalizedParts;
    }

    private function persistPartsAndQuestions(
        int $dcfId,
        array $parts,
        DcfPart $partModel,
        DcfQuestion $questionModel,
        DcfQuestionOption $optionModel
    ): bool
    {
        foreach ($parts as $partIndex => $part) {
            $partId = $partModel->insert([
                'dcf_id' => $dcfId,
                'title' => $part['title'],
                'description' => $part['description'],
                'role_key' => $part['role_key'] ?? 'all',
                'sort_order' => $partIndex + 1,
            ]);

            if (!$partId) {
                log_message('error', 'Failed to insert part: ' . json_encode($part));
                log_message('error', 'Part errors: ' . json_encode($partModel->errors()));
                return false;
            }

            foreach ($part['questions'] as $index => $question) {
                $questionRoleKey = is_string($question['role_key'] ?? null) && trim((string) ($question['role_key'] ?? '')) !== ''
                    ? trim((string) $question['role_key'])
                    : null;

                $questionData = [
                    'dcf_id' => $dcfId,
                    'part_id' => (int) $partId,
                    'question_text' => $question['question_text'],
                    'role_key' => $questionRoleKey,
                    'is_required' => $question['is_required'],
                    'answer_type' => $question['answer_type'],
                    'rate_min' => $question['answer_type'] === 'rate_me' ? $question['rate_min'] : null,
                    'rate_max' => $question['answer_type'] === 'rate_me' ? $question['rate_max'] : null,
                    'grid_rows' => $question['answer_type'] === 'advance_checkbox'
                        ? json_encode($question['grid_rows'] ?? [])
                        : null,
                    'grid_columns' => $question['answer_type'] === 'advance_checkbox'
                        ? json_encode($question['grid_columns'] ?? [])
                        : null,
                    'sort_order' => $index + 1,
                ];

                $questionId = $questionModel->insert($questionData);

                if (!$questionId) {
                    log_message('error', 'Failed to insert question: ' . json_encode($questionData));
                    log_message('error', 'Question errors: ' . json_encode($questionModel->errors()));
                    return false;
                }

                if (isset($question['options']) && is_array($question['options']) && count($question['options']) > 0) {
                    foreach ($question['options'] as $optIndex => $optionText) {
                        $optionData = [
                            'question_id' => (int) $questionId,
                            'option_text' => $optionText,
                            'sort_order' => $optIndex + 1,
                        ];

                        $ok = $optionModel->insert($optionData);

                        if (!$ok) {
                            log_message('error', 'Failed to insert option: ' . json_encode($optionData));
                            log_message('error', 'Option errors: ' . json_encode($optionModel->errors()));
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    private function buildPartsWithQuestions(int $dcfId, DcfPart $partModel, DcfQuestion $questionModel, DcfQuestionOption $optionModel): array
    {
        $parts = $partModel
            ->where('dcf_id', $dcfId)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $questions = $questionModel
            ->where('dcf_id', $dcfId)
            ->orderBy('part_id', 'ASC')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $questionsByPart = [];
        foreach ($questions as $question) {
            $question['options'] = $optionModel
                ->where('question_id', $question['id'])
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();

            $partKey = (string) ($question['part_id'] ?? 0);
            if (!isset($questionsByPart[$partKey])) {
                $questionsByPart[$partKey] = [];
            }
            $questionsByPart[$partKey][] = $question;
        }

        $result = [];
        foreach ($parts as $part) {
            $partKey = (string) $part['id'];
            $part['questions'] = $questionsByPart[$partKey] ?? [];
            $result[] = $part;
            unset($questionsByPart[$partKey]);
        }

        if (isset($questionsByPart['0']) && count($questionsByPart['0']) > 0) {
            $result[] = [
                'id' => 0,
                'dcf_id' => $dcfId,
                'title' => 'General',
                'description' => '',
                'sort_order' => 999999,
                'questions' => $questionsByPart['0'],
            ];
            unset($questionsByPart['0']);
        }

        return $result;
    }

    public function parts()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        $partModel = new DcfPart();
        $dcfModel = new Dcf();
        $departmentModel = new Department();

        // Fetch all parts with their DCF and department info
        $data['parts'] = $partModel
            ->select('dcf_parts.*, dcfs.title as dcf_title, dcfs.department_id, departments.department_name')
            ->join('dcfs', 'dcfs.id = dcf_parts.dcf_id', 'inner')
            ->join('departments', 'departments.id = dcfs.department_id', 'left')
            ->orderBy('departments.department_name', 'ASC')
            ->orderBy('dcf_parts.dcf_id', 'ASC')
            ->orderBy('dcf_parts.sort_order', 'ASC')
            ->findAll();

        $data['departments'] = $departmentModel->orderBy('department_name', 'ASC')->findAll();
        $data['title'] = 'Parts Management';

        return view('dcf/parts', $data);
    }

    public function pastPartsByDepartment($departmentId)
    {
        if (!session()->get('user_id')) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if (session()->get('usertype') !== 'superadmin') {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Forbidden']);
        }

        $departmentId = (int) $departmentId;
        if ($departmentId <= 0) {
            return $this->response->setJSON(['success' => true, 'parts' => []]);
        }

        $partModel = new DcfPart();
        $questionModel = new DcfQuestion();
        $optionModel = new DcfQuestionOption();

        // Fetch all parts for the department with their questions and options
        $rows = $partModel
            ->select('dcf_parts.*, dcfs.department_id')
            ->join('dcfs', 'dcfs.id = dcf_parts.dcf_id', 'inner')
            ->where('dcfs.department_id', $departmentId)
            ->orderBy('dcf_parts.dcf_id', 'DESC')
            ->orderBy('dcf_parts.sort_order', 'ASC')
            ->orderBy('dcf_parts.id', 'ASC')
            ->findAll();

        foreach ($rows as &$part) {
            // Fetch questions for this part
            $questions = $questionModel
                ->where('dcf_id', $part['dcf_id'])
                ->where('part_id', $part['id'])
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();

            foreach ($questions as &$question) {
                // Fetch options for this question
                $opts = $optionModel
                    ->select('option_text')
                    ->where('question_id', $question['id'])
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();

                $question['options'] = array_values(array_map(static fn($opt) => $opt['option_text'] ?? '', $opts));
            }
            unset($question);

            $part['questions'] = $questions;
        }
        unset($part);

        return $this->response->setJSON(['success' => true, 'parts' => $rows]);
    }

    public function uploadImage()
    {
        // Allow both authenticated users and public form submissions
        helper('filesystem');

        try {
            $file = $this->request->getFile('file');
            
            if (!$file || !$file->isValid()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'error' => 'No valid file uploaded'
                ]);
            }

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'error' => 'Invalid file type. Only images are allowed.'
                ]);
            }

            // Validate file size (max 5MB)
            if ($file->getSize() > 5242880) {
                return $this->response->setStatusCode(400)->setJSON([
                    'error' => 'File size too large. Maximum size is 5MB.'
                ]);
            }

            // Create upload directory if it doesn't exist
            $uploadPath = FCPATH . 'uploads/dcf-images';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newName = $file->getRandomName();
            
            // Move file to upload directory
            if ($file->move($uploadPath, $newName)) {
                $fileUrl = base_url('uploads/dcf-images/' . $newName);
                
                return $this->response->setJSON([
                    'location' => $fileUrl
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'error' => 'Failed to upload file'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Upload error: ' . $e->getMessage()
            ]);
        }
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

    private function getAllowedRoleKeys(): array
    {
        $keys = array_column($this->getAvailableRoles(), 'role_key');
        $keys[] = 'all';
        $keys = array_values(array_unique(array_filter(array_map('strval', $keys), static fn($key) => $key !== '')));

        return $keys;
    }
}
