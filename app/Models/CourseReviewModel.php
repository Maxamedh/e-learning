<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseReviewModel extends Model
{
    protected $table = 'course_reviews';
    protected $primaryKey = 'review_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'course_id', 'user_id', 'rating', 'review_title', 
        'review_text', 'is_approved'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

