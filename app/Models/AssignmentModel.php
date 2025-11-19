<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentModel extends Model
{
    protected $table            = 'assignments';
    protected $primaryKey       = 'assignment_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'assignment_id', 'course_id', 'title', 'description', 'instructions', 
        'max_score', 'due_date', 'allow_late_submissions', 
        'max_file_size', 'allowed_file_types'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'max_score' => 'int',
        'max_file_size' => 'int',
        'allow_late_submissions' => 'boolean',
        'due_date' => 'datetime',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'course_id' => 'required',
        'title' => 'required|min_length[2]|max_length[200]',
    ];

    protected $skipValidation = false;
}
