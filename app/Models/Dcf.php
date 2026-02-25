<?php

namespace App\Models;

use CodeIgniter\Model;

class Dcf extends Model
{
    protected $table = 'dcfs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'title',
        'name',
        'description',
        'due_date',
        'department_id',
        'respondents_needed',
        'share_token',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|max_length[255]',
        'name' => 'required|max_length[150]|is_unique[dcfs.name,id,{id}]',
        'description' => 'permit_empty|string|max_length[5000]',
        'due_date' => 'required',
        'department_id' => 'required|integer',
        'respondents_needed' => 'permit_empty|integer|greater_than_equal_to[1]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Title is required'
        ],
        'name' => [
            'required' => 'Name is required',
            'is_unique' => 'This DCF name already exists'
        ],
        'due_date' => [
            'required' => 'Due date is required'
        ],
        'department_id' => [
            'required' => 'Department is required',
            'integer' => 'Invalid department selected'
        ],
        'respondents_needed' => [
            'integer' => 'Respondents needed must be a valid whole number',
            'greater_than_equal_to' => 'Respondents needed must be at least 1'
        ]
    ];

    protected $beforeInsert = ['generateShareToken'];

    protected function generateShareToken(array $data): array
    {
        if (!isset($data['data']['share_token'])) {
            $data['data']['share_token'] = bin2hex(random_bytes(32));
        }
        return $data;
    }
}
