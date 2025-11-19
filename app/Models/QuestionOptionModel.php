<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionOptionModel extends Model
{
    protected $table = 'question_options';
    protected $primaryKey = 'option_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'question_id', 'option_text', 'is_correct', 'sort_order'
    ];

    protected $useTimestamps = false;
}

