<?php

namespace App\Models;

use CodeIgniter\Model;

class Server extends Model
{
    protected $table = 'servers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['server_name', 'server_type', 'ip_address', 'location', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;

    protected $validationRules = [
        'server_name' => 'required|max_length[100]',
        'server_type' => 'permit_empty|max_length[50]',
        'ip_address' => 'permit_empty|max_length[50]',
        'location' => 'permit_empty|max_length[100]',
        'status' => 'permit_empty|in_list[ACTIVE,INACTIVE]',
    ];
}
