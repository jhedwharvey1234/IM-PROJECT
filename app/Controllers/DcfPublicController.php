<?php

namespace App\Controllers;

use App\Models\Dcf;
use App\Models\DcfPart;
use App\Models\DcfQuestion;
use App\Models\DcfQuestionOption;
use App\Models\DcfResponse;
use App\Models\DcfResponseAnswer;

class DcfPublicController extends BaseController
{
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

        $validation = \Config\Services::validation();
        $validation->setRules([
            'respondent_name' => 'required|max_length[255]',
            'respondent_mobile' => 'permit_empty|max_length[50]',
            'respondent_email' => 'required|valid_email|max_length[255]',
            'user_consent' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $validation->getErrors()]);
        }

        $responseModel = new DcfResponse();
        $answerModel = new DcfResponseAnswer();
        $questionModel = new DcfQuestion();

        $respondentEmailRaw = $this->request->getPost('respondent_email');
        $respondentEmail = is_string($respondentEmailRaw) ? strtolower(trim($respondentEmailRaw)) : '';

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
            'user_consent' => $userConsent,
            'consent_timestamp' => $userConsent ? date('Y-m-d H:i:s') : null,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'submitted_at' => date('Y-m-d H:i:s')
        ]);

        $answers = $this->request->getPost('answers');
        $questions = $questionModel->where('dcf_id', $dcf['id'])->findAll();

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

        return $this->response->setJSON(['success' => true, 'message' => 'Form submitted successfully.']);
    }
}
