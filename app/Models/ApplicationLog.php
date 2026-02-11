<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationLog extends Model
{
    protected $table = 'application_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['application_id', 'action', 'performed_by', 'action_date'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'action' => 'permit_empty|max_length[100]',
        'performed_by' => 'permit_empty|max_length[100]',
    ];

    public function getLogsByApplication($applicationId, $limit = 50)
    {
        $db = \Config\Database::connect();
        $limit = intval($limit);
        $sql = "SELECT * FROM application_logs 
                WHERE application_id = ? 
                ORDER BY action_date DESC 
                LIMIT " . $limit;
        return $db->query($sql, [$applicationId])->getResultArray();
    }
}
