<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid', 'title', 'description', 'short_description', 'instructor_id', 
        'category_id', 'thumbnail_url', 'promo_video_url', 'price', 'discount_price', 
        'level', 'language', 'duration_hours', 'total_lectures', 'total_students', 
        'avg_rating', 'total_reviews', 'status', 'requirements', 'learning_outcomes', 
        'is_free', 'is_featured'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'instructor_id' => 'required',
        'category_id' => 'required',
        'status' => 'in_list[draft,published,unpublished,pending]',
        'level' => 'in_list[beginner,intermediate,advanced,all]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateUuid'];
    protected $beforeUpdate   = [];

    protected function generateUuid(array $data)
    {
        if (!isset($data['data']['uuid']) || empty($data['data']['uuid'])) {
            helper('uuid');
            $data['data']['uuid'] = generate_uuid();
        }
        return $data;
    }

    public function getCoursesWithInstructor($status = null, $limit = null)
    {
        $builder = $this->select('courses.*, users.first_name, users.last_name, categories.name as category_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->join('categories', 'categories.id = courses.category_id', 'left');
        
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
        return $this->select('courses.*, users.first_name, users.last_name, users.profile_picture, categories.name as category_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->where('courses.id', $courseId)
            ->first();
    }

    public function getCourseByUuid($uuid)
    {
        return $this->select('courses.*, users.first_name, users.last_name, users.profile_picture, categories.name as category_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->where('courses.uuid', $uuid)
            ->first();
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

    public function getFeaturedCourses($limit = 5)
    {
        return $this->where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}
