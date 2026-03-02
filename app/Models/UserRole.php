<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['role_name', 'role_key', 'description'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'role_name' => 'required|max_length[100]|is_unique[user_roles.role_name,id,{id}]',
        'role_key' => 'required|max_length[50]|alpha_dash|is_unique[user_roles.role_key,id,{id}]',
        'description' => 'permit_empty|max_length[255]',
    ];

    public function getRoleKeyList(): array
    {
        return array_values(array_column($this->orderBy('role_name', 'ASC')->findAll(), 'role_key'));
    }
}
