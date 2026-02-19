<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationRelatedData extends Model
{
    protected $table = 'application_related_data';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'title',
        'link',
        'description',
        'relation',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'title' => 'required|max_length[150]',
        'link' => 'permit_empty|valid_url|max_length[255]',
        'description' => 'permit_empty|string',
        'relation' => 'permit_empty|max_length[100]',
    ];

    public function getByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)
            ->orderBy('id', 'DESC')
            ->findAll();
    }
}
