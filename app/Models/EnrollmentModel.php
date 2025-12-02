<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table            = 'enrollments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'course_id', 'order_id', 'enrolled_at', 'completed_at', 
        'progress_percentage', 'last_accessed_lecture_id', 
        'certificate_issued', 'certificate_url'
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'enrolled_at';
    protected $updatedField  = null;

    protected $validationRules = [
        'user_id' => 'required',
        'course_id' => 'required',
    ];

    public function getUserEnrollments($userId)
    {
        return $this->select('enrollments.*, courses.title, courses.thumbnail_url, courses.instructor_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.user_id', $userId)
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->findAll();
    }

    public function getCourseEnrollments($courseId)
    {
        return $this->select('enrollments.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.id = enrollments.user_id')
            ->where('enrollments.course_id', $courseId)
            ->findAll();
    }

    public function isEnrolled($userId, $courseId)
    {
        return $this->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first() !== null;
    }
}
