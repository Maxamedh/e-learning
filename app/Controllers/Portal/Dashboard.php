<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\LectureProgressModel;

class Dashboard extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $progressModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->progressModel = new LectureProgressModel();
    }

    public function index()
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login');
        }

        // Get user's enrollments
        $enrollments = $this->enrollmentModel->select('enrollments.*, courses.title, courses.thumbnail_url, courses.instructor_id, users.first_name, users.last_name')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->where('enrollments.user_id', $user['id'])
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->findAll();

        // Get recently accessed courses
        $recentCourses = array_slice($enrollments, 0, 6);

        // Get learning stats
        $totalCourses = count($enrollments);
        $completedCourses = 0;
        $inProgressCourses = 0;
        
        foreach ($enrollments as $enrollment) {
            if ($enrollment['progress_percentage'] >= 100) {
                $completedCourses++;
            } elseif ($enrollment['progress_percentage'] > 0) {
                $inProgressCourses++;
            }
        }

        $data = [
            'title' => 'My Dashboard - E-LOOX Academy',
            'enrollments' => $enrollments,
            'recentCourses' => $recentCourses,
            'totalCourses' => $totalCourses,
            'completedCourses' => $completedCourses,
            'inProgressCourses' => $inProgressCourses,
        ];

        return view('portal/dashboard/index', $data);
    }

    public function myCourses()
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login');
        }

        $enrollments = $this->enrollmentModel->select('enrollments.*, courses.title, courses.thumbnail_url, courses.instructor_id, courses.total_duration, courses.total_lectures, users.first_name, users.last_name')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->where('enrollments.user_id', $user['id'])
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'My Courses - E-LOOX Academy',
            'enrollments' => $enrollments,
        ];

        return view('portal/dashboard/my-courses', $data);
    }
}

