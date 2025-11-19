<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentSubmissionModel extends Model
{
    protected $table = 'assignment_submissions';
    protected $primaryKey = 'submission_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'submission_id', 'assignment_id', 'user_id', 'submission_text', 
        'file_url', 'file_name', 'grade', 'feedback', 'graded_by', 
        'graded_at', 'status'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'submitted_at';
}

