<?php

namespace App\Models;

use CodeIgniter\Model;

class Application extends Model
{
    protected $table = 'applications';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'app_code',
        'app_name',
        'description',
        'department_id',
        'owner_name',
        'business_criticality',
        'repository_url',
        'production_url',
        'version',
        'status_id',
        'date_created',
        'last_updated',
    ];
    protected $useTimestamps = false;

    protected $validationRules = [
        'app_name' => 'required|max_length[150]',
        'app_code' => 'required|max_length[30]|is_unique[applications.app_code,id,{id}]',
        'description' => 'permit_empty|string',
        'department_id' => 'permit_empty|integer',
        'owner_name' => 'permit_empty|max_length[100]',
        'business_criticality' => 'permit_empty|in_list[High,Medium,Low]',
        'repository_url' => 'permit_empty|max_length[255]',
        'production_url' => 'permit_empty|max_length[255]',
        'version' => 'permit_empty|max_length[20]',
        'status_id' => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'app_name' => [
            'required' => 'Application name is required',
            'max_length' => 'Application name cannot exceed 150 characters',
        ],
        'app_code' => [
            'required' => 'Application code is required',
            'max_length' => 'Application code cannot exceed 30 characters',
            'is_unique' => 'Application code must be unique',
        ],
        'business_criticality' => [
            'in_list' => 'Business criticality must be High, Medium, or Low',
        ],
    ];

    public function getWithDepartment($id = null)
    {
        $db = \Config\Database::connect();
        
        if ($id) {
            $sql = "SELECT a.*, d.department_name, s.status_name 
                    FROM applications a 
                    LEFT JOIN departments d ON d.id = a.department_id 
                    LEFT JOIN application_status s ON s.id = a.status_id 
                    WHERE a.id = ?";
            return $db->query($sql, [$id])->getRowArray();
        }

        $sql = "SELECT a.*, d.department_name, s.status_name 
                FROM applications a 
                LEFT JOIN departments d ON d.id = a.department_id 
                LEFT JOIN application_status s ON s.id = a.status_id 
                ORDER BY a.created_at DESC";
        return $db->query($sql)->getResultArray();
    }

    public function getApplications($limit = null, $offset = 0)
    {
        $db = \Config\Database::connect();
        
        // Ensure offset is not negative
        $offset = max(0, intval($offset));
        
        $sql = "SELECT a.*, d.department_name, s.status_name 
                FROM applications a 
                LEFT JOIN departments d ON d.id = a.department_id 
                LEFT JOIN application_status s ON s.id = a.status_id 
                ORDER BY a.created_at DESC";

        if ($limit && $limit > 0) {
            $limit = intval($limit);
            $sql .= " LIMIT " . $offset . " , " . $limit;
        }

        return $db->query($sql)->getResultArray();
    }

    public function countApplications()
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT COUNT(*) as total FROM applications")->getRow();
        return $result->total ?? 0;
    }

    public function searchApplications($keyword)
    {
        $db = \Config\Database::connect();
        $searchTerm = '%' . $keyword . '%';
        
        $sql = "SELECT a.*, d.department_name, s.status_name 
                FROM applications a 
                LEFT JOIN departments d ON d.id = a.department_id 
                LEFT JOIN application_status s ON s.id = a.status_id 
                WHERE a.app_name LIKE ? 
                   OR a.app_code LIKE ? 
                   OR a.owner_name LIKE ? 
                ORDER BY a.created_at DESC";
        
        return $db->query($sql, [$searchTerm, $searchTerm, $searchTerm])->getResultArray();
    }
}
