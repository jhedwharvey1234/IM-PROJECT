<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentCategory extends Model
{
    protected $table = 'document_categories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'name',
        'description',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[150]|is_unique[document_categories.name,id,{id}]',
        'description' => 'permit_empty|string|max_length[2000]',
        'is_active' => 'permit_empty|in_list[0,1]',
    ];
}
