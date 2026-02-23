<?php

namespace App\Models;

use CodeIgniter\Model;

class DcfQuestion extends Model
{
    protected $table = 'dcf_questions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'dcf_id',
        'part_id',
        'question_text',
        'is_required',
        'answer_type',
        'rate_min',
        'rate_max',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
