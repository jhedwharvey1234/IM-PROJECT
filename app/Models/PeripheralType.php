<?php

namespace App\Models;

use CodeIgniter\Model;

class PeripheralType extends Model
{
    protected $table = 'peripheral_types';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['type_name'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'type_name' => 'required|max_length[100]|is_unique[peripheral_types.type_name,id,{id}]',
    ];
}
