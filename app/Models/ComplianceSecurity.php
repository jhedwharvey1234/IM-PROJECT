<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplianceSecurity extends Model
{
    protected $table = 'compliance_security';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'data_classification',
        'personal_data_flag',
        'compliance_requirements',
        'authentication_type',
        'encryption_enabled',
        'encryption_method',
        'vulnerability_scan_status',
        'last_vulnerability_scan',
        'last_security_review',
        'security_certifications',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'data_classification' => 'permit_empty|max_length[50]',
        'personal_data_flag' => 'permit_empty|in_list[0,1]',
        'compliance_requirements' => 'permit_empty|max_length[255]',
        'authentication_type' => 'permit_empty|max_length[100]',
        'encryption_enabled' => 'permit_empty|in_list[0,1]',
        'encryption_method' => 'permit_empty|max_length[100]',
        'vulnerability_scan_status' => 'permit_empty|max_length[50]',
        'last_vulnerability_scan' => 'permit_empty|valid_date[Y-m-d]',
        'last_security_review' => 'permit_empty|valid_date[Y-m-d]',
        'security_certifications' => 'permit_empty|max_length[255]',
    ];

    public function getComplianceByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)->first();
    }
}
