<?php

namespace App\Models;

use CodeIgniter\Model;

class DcfQuestionOption extends Model
{
    protected $table = 'dcf_question_options';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'question_id',
        'option_text',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
