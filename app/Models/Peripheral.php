<?php

namespace App\Models;

use CodeIgniter\Model;

class Peripheral extends Model
{
    protected $table            = 'peripherals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'asset_id', 'asset_tag', 'peripheral_type_id', 'brand', 'model', 'serial_number',
        'department_id', 'location_id', 'assigned_to_user_id', 'workstation_id',
        'status', 'condition_status', 'criticality', 'purchase_date', 'warranty_expiry',
        'vendor', 'last_maintenance_date', 'next_maintenance_due', 'notes'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = false;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'asset_id'            => 'required|numeric',
        'asset_tag'           => 'required|is_unique[peripherals.asset_tag,id,{id}]',
        'peripheral_type_id'  => 'required|numeric',
        'brand'               => 'permit_empty|string',
        'model'               => 'permit_empty|string',
        'serial_number'       => 'permit_empty|is_unique[peripherals.serial_number,id,{id}]',
        'department_id'       => 'required|numeric',
        'location_id'         => 'required|numeric',
        'assigned_to_user_id' => 'permit_empty|numeric',
        'workstation_id'      => 'permit_empty|numeric',
        'status'              => 'required|in_list[available,in_use,standby,under_repair,retired,lost]',
        'condition_status'    => 'required|in_list[new,good,fair,damaged]',
        'criticality'         => 'required|in_list[low,medium,high]',
        'purchase_date'       => 'permit_empty|valid_date',
        'warranty_expiry'     => 'permit_empty|valid_date',
        'vendor'              => 'permit_empty|string',
        'last_maintenance_date' => 'permit_empty|valid_date',
        'next_maintenance_due'  => 'permit_empty|valid_date',
        'notes'               => 'permit_empty|string',
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $afterFind      = [];
    protected $afterDelete    = [];
}
