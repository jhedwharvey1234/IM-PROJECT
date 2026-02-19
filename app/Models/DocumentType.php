<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentType extends Model
{
    protected $table = 'document_types';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'document_category_id',
        'name',
        'description',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'document_category_id' => 'required|integer|greater_than[0]',
        'name' => 'required|max_length[150]',
        'description' => 'permit_empty|string|max_length[2000]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];
}
