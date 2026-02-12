<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationDependencies extends Model
{
    protected $table = 'application_dependencies';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'dependency_type',
        'dependent_system',
        'dependent_app_id',
        'api_endpoint',
        'integration_type',
        'is_critical',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'dependency_type' => 'required|max_length[50]|in_list[Upstream,Downstream,Integration]',
        'dependent_system' => 'required|max_length[150]',
        'dependent_app_id' => 'permit_empty|integer',
        'api_endpoint' => 'permit_empty|max_length[255]',
        'integration_type' => 'permit_empty|max_length[100]',
        'is_critical' => 'permit_empty|in_list[0,1]',
    ];

    public function getDependenciesByApplication($applicationId, $type = null)
    {
        $query = $this->where('application_id', $applicationId);
        if ($type) {
            $query->where('dependency_type', $type);
        }
        return $query->orderBy('is_critical', 'DESC')->orderBy('id', 'DESC')->findAll();
    }

    public function getCriticalDependencies($applicationId)
    {
        return $this->where('application_id', $applicationId)
                   ->where('is_critical', 1)
                   ->findAll();
    }
}
