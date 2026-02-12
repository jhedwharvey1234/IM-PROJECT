<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationalMetrics extends Model
{
    protected $table = 'operational_metrics';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'availability_sla',
        'criticality_level',
        'business_impact',
        'peak_users',
        'peak_transactions',
        'monitoring_tool',
        'incident_history',
        'last_incident',
        'mttr_target',
        'rto_target',
        'rpo_target',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'availability_sla' => 'permit_empty|max_length[50]',
        'criticality_level' => 'permit_empty|max_length[50]|in_list[High,Medium,Low]',
        'business_impact' => 'permit_empty|string',
        'peak_users' => 'permit_empty|integer',
        'peak_transactions' => 'permit_empty|integer',
        'monitoring_tool' => 'permit_empty|max_length[100]',
        'incident_history' => 'permit_empty|string',
        'last_incident' => 'permit_empty|valid_date[Y-m-d]',
        'mttr_target' => 'permit_empty|integer',
        'rto_target' => 'permit_empty|integer',
        'rpo_target' => 'permit_empty|integer',
    ];

    public function getMetricsByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)->first();
    }

    public function getCriticalApplications()
    {
        return $this->where('criticality_level', 'High')
                   ->join('applications a', 'a.id = operational_metrics.application_id')
                   ->findAll();
    }
}
