<?php

namespace App\Models;

use CodeIgniter\Model;

class BatchJobs extends Model
{
    protected $table = 'batch_jobs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'job_name',
        'job_description',
        'schedule',
        'execution_duration',
        'last_run',
        'next_run',
        'job_status',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'job_name' => 'required|max_length[100]',
        'job_description' => 'permit_empty|string',
        'schedule' => 'permit_empty|max_length[100]',
        'execution_duration' => 'permit_empty|max_length[50]',
        'last_run' => 'permit_empty|valid_date[Y-m-d H:i:s]',
        'next_run' => 'permit_empty|valid_date[Y-m-d H:i:s]',
        'job_status' => 'permit_empty|max_length[50]',
    ];

    public function getJobsByApplication($applicationId, $status = null)
    {
        $query = $this->where('application_id', $applicationId);
        if ($status) {
            $query->where('job_status', $status);
        }
        return $query->orderBy('job_name', 'ASC')->findAll();
    }

    public function getEnabledJobs($applicationId)
    {
        return $this->where('application_id', $applicationId)
                   ->where('job_status', 'ENABLED')
                   ->findAll();
    }
}
