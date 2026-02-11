<?php

namespace App\Models;

use CodeIgniter\Model;

class Asset extends Model
{
    protected $table            = 'assets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['asset_tag', 'box_number', 'barcode', 'device_image', 'serial_number', 'model', 'model_number', 'manufacturer', 'category', 'qty', 'sender', 'recipient', 'address', 'date_updated', 'purchase_date', 'purchase_cost', 'order_number', 'supplier', 'requestable', 'byod', 'department_id', 'location_id', 'workstation_id', 'assigned_to_user_id', 'unit_id', 'description', 'status'];

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
        'asset_tag'       => 'permit_empty|is_unique[assets.asset_tag,id,{id}]',
        'box_number'      => 'permit_empty',
        'barcode'         => 'permit_empty|is_unique[assets.barcode,id,{id}]',
        'device_image'    => 'permit_empty',
        'serial_number'   => 'permit_empty|string',
        'model'           => 'permit_empty|string',
        'model_number'    => 'permit_empty|string',
        'manufacturer'    => 'permit_empty|string',
        'category'        => 'permit_empty|string',
        'qty'             => 'permit_empty|integer',
        'sender'          => 'permit_empty|string',
        'recipient'       => 'permit_empty|string',
        'address'         => 'permit_empty|string',
        'date_updated'    => 'permit_empty|valid_date',
        'purchase_date'   => 'permit_empty|valid_date',
        'purchase_cost'   => 'permit_empty|decimal',
        'order_number'    => 'permit_empty|string',
        'supplier'        => 'permit_empty|string',
        'requestable'     => 'permit_empty|in_list[0,1]',
        'byod'            => 'permit_empty|in_list[0,1]',
        'department_id'   => 'permit_empty|numeric',
        'location_id'     => 'permit_empty|numeric',
        'workstation_id'  => 'permit_empty|numeric',
        'assigned_to_user_id' => 'permit_empty|numeric',
        'unit_id'         => 'permit_empty|numeric',
        'description'     => 'permit_empty|string',
        'status'          => 'permit_empty|in_list[pending,ready to deploy,archived,broken - not fixable,lost/stolen,out for diagnostics,out for repair]',
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
