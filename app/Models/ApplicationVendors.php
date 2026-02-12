<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationVendors extends Model
{
    protected $table = 'application_vendors';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'vendor_name',
        'vendor_type',
        'contact_email',
        'contact_phone',
        'support_hours',
        'sla_details',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'vendor_name' => 'required|max_length[150]',
        'vendor_type' => 'permit_empty|max_length[100]',
        'contact_email' => 'permit_empty|valid_email|max_length[100]',
        'contact_phone' => 'permit_empty|max_length[20]',
        'support_hours' => 'permit_empty|max_length[100]',
        'sla_details' => 'permit_empty|string',
    ];

    public function getVendorsByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)
                   ->orderBy('vendor_name', 'ASC')
                   ->findAll();
    }
}
