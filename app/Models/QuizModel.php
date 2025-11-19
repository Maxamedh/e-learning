<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizModel extends Model
{
    protected $table            = 'quizzes';
    protected $primaryKey       = 'quiz_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'quiz_id', 'lecture_id', 'title', 'description', 'quiz_type', 
        'time_limit', 'passing_score', 'max_attempts', 
        'shuffle_questions', 'show_correct_answers'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'time_limit' => 'int',
        'passing_score' => 'int',
        'max_attempts' => 'int',
        'shuffle_questions' => 'boolean',
        'show_correct_answers' => 'boolean',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'lecture_id' => 'required',
        'title' => 'required|min_length[2]|max_length[200]',
        'quiz_type' => 'in_list[practice,graded,survey]',
    ];

    protected $skipValidation = false;
}
