<?php

namespace App\Models;

use CodeIgniter\Model;

class DcfResponseAnswer extends Model
{
    protected $table = 'dcf_response_answers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'response_id',
        'question_id',
        'answer_text',
    ];

    protected $useTimestamps = false;
}
