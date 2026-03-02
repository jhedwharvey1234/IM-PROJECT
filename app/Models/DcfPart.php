<?php

namespace App\Models;

use CodeIgniter\Model;

class DcfPart extends Model
{
    protected $table = 'dcf_parts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'dcf_id',
        'title',
        'description',
        'role_key',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
