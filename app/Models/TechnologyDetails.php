<?php

namespace App\Models;

use CodeIgniter\Model;

class TechnologyDetails extends Model
{
    protected $table = 'technology_details';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'programming_language',
        'framework',
        'frontend_technology',
        'database_type',
        'middleware',
        'container_technology',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'programming_language' => 'permit_empty|max_length[100]',
        'framework' => 'permit_empty|max_length[100]',
        'frontend_technology' => 'permit_empty|max_length[100]',
        'database_type' => 'permit_empty|max_length[100]',
        'middleware' => 'permit_empty|max_length[255]',
        'container_technology' => 'permit_empty|max_length[100]',
    ];

    public function getTechDetailsByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)->first();
    }
}
