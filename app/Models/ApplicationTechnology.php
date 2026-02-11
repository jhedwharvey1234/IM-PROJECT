<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationTechnology extends Model
{
    protected $table = 'application_technologies';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['application_id', 'technology_id'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'technology_id' => 'required|integer',
    ];

    public function getTechnologiesByApplication($applicationId)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT t.* FROM technologies t 
                INNER JOIN application_technologies at ON t.id = at.technology_id 
                WHERE at.application_id = ?";
        return $db->query($sql, [$applicationId])->getResultArray();
    }
}
