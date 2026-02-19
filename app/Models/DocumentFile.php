<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentFile extends Model
{
    protected $table = 'document_files';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'document_id',
        'uploaded_by',
        'original_name',
        'stored_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'document_id' => 'required|integer',
        'original_name' => 'required|max_length[255]',
        'stored_name' => 'required|max_length[255]',
        'file_path' => 'required|max_length[255]',
        'mime_type' => 'permit_empty|max_length[100]',
        'file_size' => 'permit_empty|integer',
    ];

    public function getFilesByDocument($documentId)
    {
        return $this->select('document_files.*, users.username')
            ->join('users', 'users.id = document_files.uploaded_by', 'left')
            ->where('document_files.document_id', $documentId)
            ->orderBy('document_files.created_at', 'DESC')
            ->findAll();
    }
}
