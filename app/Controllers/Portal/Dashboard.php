<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\LectureProgressModel;
use App\Models\LectureModel;
use App\Models\OrderModel;

class Dashboard extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $progressModel;
    protected $lectureModel;
    protected $orderModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->progressModel = new LectureProgressModel();
        $this->lectureModel = new LectureModel();
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login');
        }

        // Get user's enrollments with order status
        $enrollments = $this->enrollmentModel->select('enrollments.*, courses.title, courses.thumbnail_url, courses.instructor_id, courses.total_lectures, users.first_name, users.last_name, orders.status as order_status')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->join('orders', 'orders.id = enrollments.order_id', 'left')
            ->where('enrollments.user_id', $user['id'])
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->findAll();

        // Calculate progress for each enrollment
        foreach ($enrollments as &$enrollment) {
            if (empty($enrollment['order_status']) || $enrollment['order_status'] === 'completed') {
                // Get total lectures for this course
                $totalLectures = $this->lectureModel->select('COUNT(*) as total')
                    ->join('course_sections', 'course_sections.id = lectures.section_id')
                    ->where('course_sections.course_id', $enrollment['course_id'])
                    ->where('lectures.is_published', 1)
                    ->first();
                
                $totalLecturesCount = $totalLectures['total'] ?? 0;
                
                // Get completed lectures
                $completedLectures = $this->progressModel->where('enrollment_id', $enrollment['id'])
                    ->where('is_completed', 1)
                    ->countAllResults();
                
                // Calculate progress percentage
                if ($totalLecturesCount > 0) {
                    $progressPercentage = ($completedLectures / $totalLecturesCount) * 100;
                    $enrollment['progress_percentage'] = round($progressPercentage, 2);
                    $enrollment['completed_lectures'] = $completedLectures;
                    $enrollment['total_lectures'] = $totalLecturesCount;
                } else {
                    $enrollment['progress_percentage'] = 0;
                    $enrollment['completed_lectures'] = 0;
                    $enrollment['total_lectures'] = 0;
                }
            }
        }

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

        $enrollments = $this->enrollmentModel->select('enrollments.*, courses.title, courses.thumbnail_url, courses.instructor_id, courses.duration_hours, courses.total_lectures as course_total_lectures, users.first_name, users.last_name, orders.status as order_status')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->join('orders', 'orders.id = enrollments.order_id', 'left')
            ->where('enrollments.user_id', $user['id'])
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->findAll();

        // Calculate progress for each enrollment
        foreach ($enrollments as &$enrollment) {
            if (empty($enrollment['order_status']) || $enrollment['order_status'] === 'completed') {
                // Get total lectures for this course
                $totalLectures = $this->lectureModel->select('COUNT(*) as total')
                    ->join('course_sections', 'course_sections.id = lectures.section_id')
                    ->where('course_sections.course_id', $enrollment['course_id'])
                    ->where('lectures.is_published', 1)
                    ->first();
                
                $totalLecturesCount = $totalLectures['total'] ?? 0;
                
                // Get completed lectures
                $completedLectures = $this->progressModel->where('enrollment_id', $enrollment['id'])
                    ->where('is_completed', 1)
                    ->countAllResults();
                
                // Calculate progress percentage
                if ($totalLecturesCount > 0) {
                    $progressPercentage = ($completedLectures / $totalLecturesCount) * 100;
                    $enrollment['progress_percentage'] = round($progressPercentage, 2);
                    $enrollment['completed_lectures'] = $completedLectures;
                    $enrollment['total_lectures'] = $totalLecturesCount;
                } else {
                    $enrollment['progress_percentage'] = 0;
                    $enrollment['completed_lectures'] = 0;
                    $enrollment['total_lectures'] = 0;
                }
            }
        }

        $data = [
            'title' => 'My Courses - E-LOOX Academy',
            'enrollments' => $enrollments,
        ];

        return view('portal/dashboard/my-courses', $data);
    }
}

