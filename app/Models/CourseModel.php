<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'course_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id', 'title', 'subtitle', 'description', 'instructor_id', 
        'category_id', 'price', 'discount_price', 'language', 'level', 
        'thumbnail_url', 'promo_video_url', 'total_duration', 'total_lectures', 
        'total_students', 'avg_rating', 'total_reviews', 'status', 
        'requirements', 'learning_outcomes', 'target_audience', 
        'is_free', 'is_featured', 'published_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[200]',
        'instructor_id' => 'required',
        'status' => 'in_list[draft,published,pending,rejected]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getCoursesWithInstructor($status = null, $limit = null)
    {
        $builder = $this->select('courses.*, users.first_name, users.last_name, categories.name as category_name')
            ->join('users', 'users.user_id = courses.instructor_id')
            ->join('categories', 'categories.category_id = courses.category_id', 'left');
        
        if ($status) {
            $builder->where('courses.status', $status);
        }
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->orderBy('courses.created_at', 'DESC')->findAll();
    }

    public function getCourseById($courseId)
    {
        $builder = $this->db->table('courses c');
        $builder->select('c.*, u.first_name, u.last_name, u.profile_picture_url, cat.name as category_name');
        $builder->join('users u', 'u.user_id = c.instructor_id', 'left');
        $builder->join('categories cat', 'cat.category_id = c.category_id', 'left');
        $builder->where('c.course_id', $courseId);
        
        return $builder->get()->getRowArray();
    }

    public function getCoursesByInstructor($instructorId)
    {
        return $this->where('instructor_id', $instructorId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getPublishedCourses($limit = null)
    {
        $builder = $this->where('status', 'published');
        if ($limit) {
            $builder->limit($limit);
        }
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }
}
