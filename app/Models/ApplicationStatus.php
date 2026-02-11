<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationStatus extends Model
{
    protected $table = 'application_status';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['status_name'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'status_name' => 'required|max_length[50]|is_unique[application_status.status_name,id,{id}]',
    ];
}
