<?php

namespace App\Models;

use CodeIgniter\Model;

class Workstation extends Model
{
    protected $table = 'workstations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['workstation_code', 'location_id'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'workstation_code' => 'required|max_length[50]|is_unique[workstations.workstation_code,id,{id}]',
        'location_id' => 'permit_empty|integer',
    ];
}
