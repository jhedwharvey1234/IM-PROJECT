<?php

namespace App\Models;

use CodeIgniter\Model;

class DcfResponse extends Model
{
    protected $table = 'dcf_responses';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'dcf_id',
        'respondent_name',
        'respondent_mobile',
        'respondent_email',
        'submitted_at',
        'ip_address',
        'user_agent',
    ];

    protected $useTimestamps = false;
}
