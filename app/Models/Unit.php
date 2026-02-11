<?php

namespace App\Models;

use CodeIgniter\Model;

class Unit extends Model
{
    protected $table            = 'units';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'unit_name',
        'unit_type',
        'asset_id',
        'peripheral_id',
        'notes',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = false;

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'unit_name' => 'required|string',
        'unit_type' => 'required|in_list[asset,peripheral,both]',
        'asset_id' => 'permit_empty|numeric',
        'peripheral_id' => 'permit_empty|numeric',
        'notes' => 'permit_empty|string',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
