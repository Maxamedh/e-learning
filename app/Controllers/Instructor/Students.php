<?php

namespace App\Controllers\Instructor;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;

class Students extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;
    protected $userModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'instructor') {
            return redirect()->to('admin/login')->with('error', 'Access denied.');
        }

        $courseId = $this->request->getGet('course');
        
        // Get instructor's courses
        $courses = $this->courseModel->where('instructor_id', $user['id'])->findAll();
        $courseIds = array_column($courses, 'id');

        // Get enrollments
        $enrollments = [];
        if (!empty($courseIds)) {
            $builder = $this->enrollmentModel->select('enrollments.*, users.first_name, users.last_name, users.email, users.profile_picture, courses.title as course_title, courses.id as course_id')
                ->join('users', 'users.id = enrollments.user_id', 'left')
                ->join('courses', 'courses.id = enrollments.course_id', 'left')
                ->whereIn('enrollments.course_id', $courseIds);
            
            if ($courseId) {
                $builder->where('enrollments.course_id', $courseId);
            }
            
            $enrollments = $builder->orderBy('enrollments.enrolled_at', 'DESC')
                ->findAll();
        }

        $data = [
            'title' => 'My Students',
            'enrollments' => $enrollments,
            'courses' => $courses,
            'selectedCourseId' => $courseId,
        ];

        return view('instructor/students/index', $data);
    }
}

