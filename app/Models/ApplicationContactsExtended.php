<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationContactsExtended extends Model
{
    protected $table = 'application_contacts_extended';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'contact_name',
        'email',
        'phone',
        'role',
        'department',
        'is_primary',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'contact_name' => 'required|max_length[100]',
        'email' => 'permit_empty|valid_email|max_length[100]',
        'phone' => 'permit_empty|max_length[20]',
        'role' => 'permit_empty|max_length[100]',
        'department' => 'permit_empty|max_length[100]',
        'is_primary' => 'permit_empty|in_list[0,1]',
    ];

    public function getContactsByApplication($applicationId, $role = null)
    {
        $query = $this->where('application_id', $applicationId);
        if ($role) {
            $query->where('role', $role);
        }
        return $query->orderBy('is_primary', 'DESC')->orderBy('id', 'DESC')->findAll();
    }

    public function getPrimaryContact($applicationId, $role = null)
    {
        $query = $this->where('application_id', $applicationId)->where('is_primary', 1);
        if ($role) {
            $query->where('role', $role);
        }
        return $query->first();
    }
}
