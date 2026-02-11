<?php

namespace App\Models;

use CodeIgniter\Model;

class Technology extends Model
{
    protected $table = 'technologies';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['technology_name'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'technology_name' => 'required|max_length[100]|is_unique[technologies.technology_name,id,{id}]',
    ];
}
