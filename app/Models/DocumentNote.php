<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentNote extends Model
{
    protected $table = 'document_notes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'document_id',
        'user_id',
        'note',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'document_id' => 'required|integer',
        'note' => 'required|string|min_length[1]|max_length[5000]',
    ];

    public function getNotesByDocument($documentId)
    {
        return $this->select('document_notes.*, users.username')
            ->join('users', 'users.id = document_notes.user_id', 'left')
            ->where('document_notes.document_id', $documentId)
            ->orderBy('document_notes.created_at', 'DESC')
            ->findAll();
    }
}
