<?php

namespace App\Models;

use CodeIgniter\Model;

class InfrastructureDetails extends Model
{
    protected $table = 'infrastructure_details';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'server_type',
        'operating_system',
        'os_version',
        'cpu_cores',
        'memory_gb',
        'storage_gb',
        'database_server',
        'database_type',
        'storage_location',
        'backup_location',
        'dr_server_id',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'server_type' => 'permit_empty|max_length[100]',
        'operating_system' => 'permit_empty|max_length[100]',
        'os_version' => 'permit_empty|max_length[50]',
        'cpu_cores' => 'permit_empty|integer',
        'memory_gb' => 'permit_empty|integer',
        'storage_gb' => 'permit_empty|integer',
        'database_server' => 'permit_empty|max_length[100]',
        'database_type' => 'permit_empty|max_length[50]',
        'storage_location' => 'permit_empty|max_length[255]',
        'backup_location' => 'permit_empty|max_length[255]',
        'dr_server_id' => 'permit_empty|integer',
    ];

    public function getInfrastructureByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)->first();
    }
}
