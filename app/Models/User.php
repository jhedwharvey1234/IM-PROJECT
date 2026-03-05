<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'usertype',
        'user_role_id',
        'entra_object_id',
        'entra_display_name',
        'entra_user_principal_name',
        'entra_account_enabled',
        'entra_last_password_change_at',
        'entra_user_type',
        'entra_given_name',
        'entra_surname',
        'entra_job_title',
        'entra_company_name',
        'entra_department',
        'entra_employee_id',
        'entra_office_location',
        'entra_city',
        'entra_state',
        'entra_postal_code',
        'entra_country',
        'entra_mobile_phone',
        'entra_mail',
        'entra_identities',
        'entra_manager_object_id',
        'entra_manager_display_name',
        'entra_manager_user_principal_name',
        'entra_manager_mail',
        'entra_business_phones',
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
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'permit_empty|min_length[8]',
        'usertype' => 'required|in_list[superadmin,readandwrite,readonly]',
        'user_role_id' => 'permit_empty|integer',

    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $afterFind      = [];
    protected $afterDelete    = [];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}