<?php

namespace App\Models;

use CodeIgniter\Model;

class Environment extends Model
{
    protected $table = 'environments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['application_id', 'environment_type', 'server_id', 'url', 'version', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'environment_type' => 'permit_empty|max_length[50]',
        'server_id' => 'permit_empty|integer',
        'url' => 'permit_empty|max_length[255]',
        'version' => 'permit_empty|max_length[20]',
        'status' => 'permit_empty|in_list[ACTIVE,INACTIVE]',
    ];

    public function getEnvironmentsByApplication($applicationId)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT e.*, s.server_name FROM environments e 
                LEFT JOIN servers s ON s.id = e.server_id 
                WHERE e.application_id = ?";
        return $db->query($sql, [$applicationId])->getResultArray();
    }
}
