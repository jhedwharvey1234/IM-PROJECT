<?php

namespace App\Models;

use CodeIgniter\Model;

class LicensingInfo extends Model
{
    protected $table = 'licensing_info';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'application_id',
        'license_type',
        'license_key',
        'license_expiry',
        'vendor_name',
        'vendor_contract_ref',
        'annual_cost',
        'cost_center',
        'purchase_date',
        'renewal_date',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'application_id' => 'required|integer',
        'license_type' => 'permit_empty|max_length[100]',
        'license_key' => 'permit_empty|max_length[255]',
        'license_expiry' => 'permit_empty|valid_date[Y-m-d]',
        'vendor_name' => 'permit_empty|max_length[150]',
        'vendor_contract_ref' => 'permit_empty|max_length[255]',
        'annual_cost' => 'permit_empty|decimal',
        'cost_center' => 'permit_empty|max_length[50]',
        'purchase_date' => 'permit_empty|valid_date[Y-m-d]',
        'renewal_date' => 'permit_empty|valid_date[Y-m-d]',
    ];

    public function getLicensingByApplication($applicationId)
    {
        return $this->where('application_id', $applicationId)->first();
    }

    public function getExpiringLicenses($days = 30)
    {
        $expiryDate = date('Y-m-d', strtotime("+$days days"));
        return $this->where('license_expiry <=', $expiryDate)
                   ->where('license_expiry >=', date('Y-m-d'))
                   ->findAll();
    }
}
