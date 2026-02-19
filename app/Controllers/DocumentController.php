<?php

namespace App\Controllers;

use App\Models\Document;
use App\Models\DocumentNote;
use App\Models\DocumentFile;
use App\Models\DocumentAlert;

class DocumentController extends BaseController
{
    protected $documentModel;
    protected $documentNoteModel;
    protected $documentFileModel;
    protected $documentAlertModel;

    public function __construct()
    {
        $this->documentModel = new Document();
        $this->documentNoteModel = new DocumentNote();
        $this->documentFileModel = new DocumentFile();
        $this->documentAlertModel = new DocumentAlert();
    }

    public function index()
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $page = (int) ($this->request->getGet('page') ?? 1);
        $page = max(1, $page);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $data['documents'] = $this->documentModel->getDocuments($perPage, $offset);
        $data['total'] = $this->documentModel->countDocuments();
        $data['currentPage'] = $page;
        $data['perPage'] = $perPage;
        $data['title'] = 'Document Management';

        return view('documents/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $data['title'] = 'Create Document';
        return view('documents/create', $data);
    }

    public function store()
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $data = [
            'title' => trim((string) $this->request->getPost('title')),
            'subject' => trim((string) $this->request->getPost('subject')),
            'description' => $this->request->getPost('description'),
            'created_by' => session()->get('user_id') ?: null,
        ];

        if ($this->documentModel->insert($data)) {
            return redirect()->to('/documents')->with('success', 'Document created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->documentModel->errors());
    }

    public function edit($id)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $document = $this->documentModel->find($id);
        if (!$document) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Document $id not found");
        }

        $data['document'] = $document;
        $data['title'] = 'Edit Document';

        return view('documents/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $document = $this->documentModel->find($id);
        if (!$document) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Document $id not found");
        }

        $data = [
            'title' => trim((string) $this->request->getPost('title')),
            'subject' => trim((string) $this->request->getPost('subject')),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->documentModel->update($id, $data)) {
            return redirect()->to('/documents')->with('success', 'Document updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->documentModel->errors());
    }

    public function delete($id)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $document = $this->documentModel->find($id);
        if (!$document) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Document $id not found");
        }

        $files = $this->documentFileModel->where('document_id', $id)->findAll();
        foreach ($files as $file) {
            $absolutePath = FCPATH . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $file['file_path']), '\\/');
            if (is_file($absolutePath)) {
                @unlink($absolutePath);
            }
        }

        if ($this->documentModel->delete($id)) {
            return redirect()->to('/documents')->with('success', 'Document deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete document');
    }

    public function batchDelete()
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $ids = $this->request->getPost('document_ids');
        if (!is_array($ids) || empty($ids)) {
            return redirect()->to('/documents')->with('error', 'No documents selected');
        }

        $deletedCount = 0;

        foreach ($ids as $id) {
            $documentId = (int) $id;
            if ($documentId <= 0) {
                continue;
            }

            $document = $this->documentModel->find($documentId);
            if (!$document) {
                continue;
            }

            $files = $this->documentFileModel->where('document_id', $documentId)->findAll();
            foreach ($files as $file) {
                $absolutePath = FCPATH . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $file['file_path']), '\\/');
                if (is_file($absolutePath)) {
                    @unlink($absolutePath);
                }
            }

            if ($this->documentModel->delete($documentId)) {
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            return redirect()->to('/documents')->with('success', $deletedCount . ' document(s) deleted successfully');
        }

        return redirect()->to('/documents')->with('error', 'No documents were deleted');
    }

    public function details($id)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $document = $this->documentModel->getWithMeta($id);
        if (!$document) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Document $id not found");
        }

        $data['document'] = $document;
        $data['alerts'] = $this->documentAlertModel->getAlertsByDocument($id);
        $data['notes'] = $this->documentNoteModel->getNotesByDocument($id);
        $data['files'] = $this->documentFileModel->getFilesByDocument($id);
        $data['title'] = 'Document Details';

        return view('documents/details', $data);
    }

    public function addNote($documentId)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $document = $this->documentModel->find($documentId);
        if (!$document) {
            return redirect()->to('/documents')->with('error', 'Document not found');
        }

        $data = [
            'document_id' => $documentId,
            'user_id' => session()->get('user_id') ?: null,
            'note' => trim((string) $this->request->getPost('note')),
        ];

        if ($this->documentNoteModel->insert($data)) {
            return redirect()->to('/documents/details/' . $documentId)->with('success', 'Note added successfully');
        }

        return redirect()->to('/documents/details/' . $documentId)
            ->with('error', implode(', ', $this->documentNoteModel->errors()));
    }

    public function deleteNote($documentId, $id)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $note = $this->documentNoteModel->find($id);
        if (!$note || (int) $note['document_id'] !== (int) $documentId) {
            return redirect()->to('/documents/details/' . $documentId)->with('error', 'Note not found');
        }

        if ($note['user_id'] != session()->get('user_id') && session()->get('usertype') !== 'superadmin') {
            return redirect()->to('/documents/details/' . $documentId)->with('error', 'Unauthorized to delete this note');
        }

        if ($this->documentNoteModel->delete($id)) {
            return redirect()->to('/documents/details/' . $documentId)->with('success', 'Note deleted successfully');
        }

        return redirect()->to('/documents/details/' . $documentId)->with('error', 'Failed to delete note');
    }

    public function uploadFile($documentId)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $document = $this->documentModel->find($documentId);
        if (!$document) {
            return redirect()->to('/documents')->with('error', 'Document not found');
        }

        $files = $this->request->getFileMultiple('files');
        if (!$files) {
            return redirect()->to('/documents/details/' . $documentId)->with('error', 'No files selected');
        }

        $uploadDir = FCPATH . 'uploads/documents';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $inserted = 0;

        foreach ($files as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                continue;
            }

            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);

            $data = [
                'document_id' => $documentId,
                'uploaded_by' => session()->get('user_id') ?: null,
                'original_name' => $file->getClientName(),
                'stored_name' => $newName,
                'file_path' => 'uploads/documents/' . $newName,
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ];

            if ($this->documentFileModel->insert($data)) {
                $inserted++;
            }
        }

        if ($inserted > 0) {
            return redirect()->to('/documents/details/' . $documentId)->with('success', $inserted . ' file(s) uploaded successfully');
        }

        return redirect()->to('/documents/details/' . $documentId)->with('error', 'No file uploaded');
    }

    public function deleteFile($documentId, $id)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $file = $this->documentFileModel->find($id);
        if (!$file || (int) $file['document_id'] !== (int) $documentId) {
            return redirect()->to('/documents/details/' . $documentId)->with('error', 'File not found');
        }

        $absolutePath = FCPATH . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $file['file_path']), '\\/');
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }

        if ($this->documentFileModel->delete($id)) {
            return redirect()->to('/documents/details/' . $documentId)->with('success', 'File deleted successfully');
        }

        return redirect()->to('/documents/details/' . $documentId)->with('error', 'Failed to delete file');
    }

    public function addAlert($documentId)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $document = $this->documentModel->find($documentId);
        if (!$document) {
            return redirect()->to('/documents')->with('error', 'Document not found');
        }

        $data = [
            'document_id' => $documentId,
            'alert_date' => $this->request->getPost('alert_date'),
            'alert_time' => $this->request->getPost('alert_time') ?: null,
            'description' => $this->request->getPost('description'),
            'created_by' => session()->get('user_id') ?: null,
        ];

        if ($this->documentAlertModel->insert($data)) {
            return redirect()->to('/documents/details/' . $documentId)->with('success', 'Alert created successfully');
        }

        return redirect()->to('/documents/details/' . $documentId)
            ->with('error', implode(', ', $this->documentAlertModel->errors()));
    }

    public function deleteAlert($documentId, $id)
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $alert = $this->documentAlertModel->find($id);
        if (!$alert || (int) $alert['document_id'] !== (int) $documentId) {
            return redirect()->to('/documents/details/' . $documentId)->with('error', 'Alert not found');
        }

        if ($this->documentAlertModel->delete($id)) {
            return redirect()->to('/documents/details/' . $documentId)->with('success', 'Alert deleted successfully');
        }

        return redirect()->to('/documents/details/' . $documentId)->with('error', 'Failed to delete alert');
    }
    public function alerts()
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        $year = (int) ($this->request->getGet('year') ?? date('Y'));
        $month = (int) ($this->request->getGet('month') ?? date('m'));

        // Validate year and month
        if ($month < 1) $month = 1;
        if ($month > 12) $month = 12;
        if ($year < 2000) $year = 2000;
        if ($year > 2100) $year = 2100;

        $data['alerts'] = $this->documentAlertModel->getAlertsForMonth($year, $month);
        $data['upcomingAlerts'] = $this->documentAlertModel->getUpcomingAlerts(30);
        $data['currentYear'] = $year;
        $data['currentMonth'] = $month;
        $data['title'] = 'Document Alerts Calendar';

        return view('documents/alerts', $data);
    }

    private function ensureSuperadmin()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        return null;
    }
}
