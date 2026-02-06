<?php

namespace App\Models;

use CodeIgniter\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['department_name'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'department_name' => 'required|max_length[100]|is_unique[departments.department_name,id,{id}]',
    ];
}
