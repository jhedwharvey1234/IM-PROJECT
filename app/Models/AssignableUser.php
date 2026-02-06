<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignableUser extends Model
{
    protected $table = 'assignable_users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['full_name'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'full_name' => 'required|max_length[150]|is_unique[assignable_users.full_name,id,{id}]',
    ];
}
