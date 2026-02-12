<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationContact extends Model
{
    protected $table = 'application_contacts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['application_id', 'contact_name', 'email', 'phone', 'role'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'contact_name' => 'permit_empty|max_length[100]',
        'email' => 'permit_empty|valid_email|max_length[100]',
        'phone' => 'permit_empty|max_length[20]',
        'role' => 'permit_empty|max_length[50]',
    ];

    public function getContactsByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)->findAll();
    }
}
