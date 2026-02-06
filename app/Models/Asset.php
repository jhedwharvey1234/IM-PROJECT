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
    protected $allowedFields    = ['tracking_number', 'box_number', 'barcode', 'sender', 'recipient', 'address', 'date_sent', 'date_in_transit', 'date_received', 'date_rejected', 'description', 'status'];

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
        'tracking_number' => 'permit_empty|is_unique[assets.tracking_number,id,{id}]',
        'box_number'      => 'permit_empty',
        'barcode'         => 'permit_empty|is_unique[assets.barcode,id,{id}]',
        'sender'          => 'required|string',
        'recipient'       => 'required|string',
        'address'         => 'required|string',
        'date_sent'       => 'permit_empty|valid_date',
        'date_in_transit' => 'permit_empty|valid_date',
        'date_received'   => 'permit_empty|valid_date',
        'date_rejected'   => 'permit_empty|valid_date',
        'description'     => 'permit_empty|string',
        'status'          => 'required|in_list[pending,in_transit,completed,rejected]',
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
