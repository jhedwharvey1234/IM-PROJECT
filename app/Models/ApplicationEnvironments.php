<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationEnvironments extends Model
{
    protected $table = 'application_environments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'environment_type',
        'server_id',
        'ip_address',
        'hostname',
        'url',
        'version',
        'data_center',
        'environment_status',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'environment_type' => 'required|max_length[50]|in_list[Production,UAT,QA,Development,DR]',
        'server_id' => 'permit_empty|integer',
        'ip_address' => 'permit_empty|max_length[50]',
        'hostname' => 'permit_empty|max_length[100]',
        'url' => 'permit_empty|max_length[255]',
        'version' => 'permit_empty|max_length[20]',
        'data_center' => 'permit_empty|max_length[100]',
        'environment_status' => 'permit_empty|max_length[20]',
    ];

    public function getEnvironmentsByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)
                   ->orderBy('environment_type', 'ASC')
                   ->findAll();
    }

    public function getProductionEnvironment($applicationId)
    {
        return $this->where('application_id', $applicationId)
                   ->where('environment_type', 'Production')
                   ->first();
    }
}
