<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'question_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'question_id', 'quiz_id', 'question_text', 'question_type', 
        'points', 'sort_order', 'explanation'
    ];

    protected $useTimestamps = false;
}

