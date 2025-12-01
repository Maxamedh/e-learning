<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    protected $courseModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        try {
            // Get featured courses
            $featuredCourses = $this->courseModel->where('is_featured', 1)
                ->where('status', 'published')
                ->orderBy('created_at', 'DESC')
                ->limit(6)
                ->findAll();

            // Get popular courses (by student count)
            $popularCourses = $this->courseModel->where('status', 'published')
                ->orderBy('total_students', 'DESC')
                ->limit(6)
                ->findAll();

            // Get categories
            $categories = $this->categoryModel->where('is_active', 1)
                ->orderBy('name', 'ASC')
                ->findAll();

            // Get recent courses
            $recentCourses = $this->courseModel->where('status', 'published')
                ->orderBy('created_at', 'DESC')
                ->limit(8)
                ->findAll();

            // Get instructor info for courses
            $userModel = new \App\Models\UserModel();
            
            if (!empty($featuredCourses)) {
                foreach ($featuredCourses as &$course) {
                    $instructor = $userModel->find($course['instructor_id'] ?? null);
                    $course['instructor_name'] = ($instructor['first_name'] ?? '') . ' ' . ($instructor['last_name'] ?? '');
                }
            }
            
            if (!empty($popularCourses)) {
                foreach ($popularCourses as &$course) {
                    $instructor = $userModel->find($course['instructor_id'] ?? null);
                    $course['instructor_name'] = ($instructor['first_name'] ?? '') . ' ' . ($instructor['last_name'] ?? '');
                }
            }
            
            if (!empty($recentCourses)) {
                foreach ($recentCourses as &$course) {
                    $instructor = $userModel->find($course['instructor_id'] ?? null);
                    $course['instructor_name'] = ($instructor['first_name'] ?? '') . ' ' . ($instructor['last_name'] ?? '');
                }
            }

            $data = [
                'title' => 'E-LOOX Academy - Online Learning Platform',
                'featuredCourses' => $featuredCourses ?? [],
                'popularCourses' => $popularCourses ?? [],
                'recentCourses' => $recentCourses ?? [],
                'categories' => $categories ?? [],
            ];

            return view('portal/home/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Home controller error: ' . $e->getMessage());
            // Return view with empty data if there's an error
            $data = [
                'title' => 'E-LOOX Academy - Online Learning Platform',
                'featuredCourses' => [],
                'popularCourses' => [],
                'recentCourses' => [],
                'categories' => [],
            ];
            return view('portal/home/index', $data);
        }
    }
}

