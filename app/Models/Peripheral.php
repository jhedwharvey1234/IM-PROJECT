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
        'asset_id', 'unit_id', 'peripheral_type_id', 'brand', 'model', 'model_number', 'serial_number',
        'device_image', 'department_id', 'location_id', 'assigned_to_user_id', 'workstation_id',
        'status', 'condition_status', 'criticality', 'purchase_date', 'purchase_cost', 'order_number',
        'supplier', 'qty', 'requestable', 'byod', 'warranty_expiry', 'vendor'
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
        'asset_id'            => 'permit_empty|numeric',
        'unit_id'             => 'permit_empty|numeric',
        'peripheral_type_id'  => 'required|numeric',
        'brand'               => 'permit_empty|string',
        'model'               => 'permit_empty|string',
        'model_number'        => 'permit_empty|string',
        'serial_number'       => 'permit_empty|is_unique[peripherals.serial_number,id,{id}]',
        'device_image'        => 'permit_empty|string',
        'department_id'       => 'permit_empty|numeric',
        'location_id'         => 'permit_empty|numeric',
        'assigned_to_user_id' => 'permit_empty|numeric',
        'workstation_id'      => 'permit_empty|numeric',
        'status'              => 'required|in_list[available,in_use,standby,under_repair,retired,lost]',
        'condition_status'    => 'required|in_list[new,good,fair,damaged]',
        'criticality'         => 'required|in_list[low,medium,high]',
        'purchase_date'       => 'permit_empty|valid_date',
        'purchase_cost'       => 'permit_empty|decimal',
        'order_number'        => 'permit_empty|string',
        'supplier'            => 'permit_empty|string',
        'qty'                 => 'permit_empty|integer',
        'requestable'         => 'permit_empty|in_list[0,1]',
        'byod'                => 'permit_empty|in_list[0,1]',
        'warranty_expiry'     => 'permit_empty|valid_date',
        'vendor'              => 'permit_empty|string',
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
