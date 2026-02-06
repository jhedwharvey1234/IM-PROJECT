<?php

namespace App\Models;

use CodeIgniter\Model;

class Location extends Model
{
    protected $table = 'locations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'name' => 'required|max_length[255]|is_unique[locations.name,id,{id}]',
    ];
}
