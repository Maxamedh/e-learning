<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\SectionModel;
use App\Models\LectureModel;
use App\Models\LectureProgressModel;
use App\Models\OrderModel;

class Learn extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;
    protected $sectionModel;
    protected $lectureModel;
    protected $progressModel;
    protected $orderModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->sectionModel = new SectionModel();
        $this->lectureModel = new LectureModel();
        $this->progressModel = new LectureProgressModel();
        $this->orderModel = new OrderModel();
    }

    public function index($courseId)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login')->with('error', 'Please login to access courses.');
        }

        // Check enrollment
        $enrollment = $this->enrollmentModel->where('user_id', $user['id'])
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return redirect()->to('courses/' . $courseId)->with('error', 'You must enroll in this course first.');
        }

        // Check order status if enrollment has order_id
        if (!empty($enrollment['order_id'])) {
            $order = $this->orderModel->find($enrollment['order_id']);
            if ($order && $order['status'] === 'pending') {
                return redirect()->to('portal/dashboard')->with('error', 'Your enrollment is pending payment approval. You will be able to access the course once payment is approved.');
            }
            if ($order && $order['status'] !== 'completed') {
                return redirect()->to('portal/dashboard')->with('error', 'Your enrollment payment status is: ' . ucfirst($order['status']) . '. Please contact support.');
            }
        }

        $course = $this->courseModel->getCourseById($courseId);
        
        if (!$course || $course['status'] !== 'published') {
            return redirect()->to('courses')->with('error', 'Course not found.');
        }

        // Get sections and lectures
        $sections = $this->sectionModel->getAllSectionsByCourse($courseId);
        $allLectures = [];
        
        foreach ($sections as &$section) {
            $section['lectures'] = $this->lectureModel->getAllLecturesBySection($section['id']);
            foreach ($section['lectures'] as $lecture) {
                $lecture['section_title'] = $section['title'];
                $allLectures[] = $lecture;
            }
        }

        // Get first lecture or last accessed
        $currentLecture = null;
        if ($enrollment['last_accessed_lecture_id']) {
            $currentLecture = $this->lectureModel->find($enrollment['last_accessed_lecture_id']);
        }
        
        if (!$currentLecture && !empty($allLectures)) {
            $currentLecture = $allLectures[0];
        }

        // Get progress for all lectures
        $lectureProgress = [];
        if (!empty($allLectures)) {
            $progress = $this->progressModel->where('enrollment_id', $enrollment['id'])->findAll();
            
            foreach ($progress as $p) {
                $lectureProgress[$p['lecture_id']] = $p;
            }
        }

        $data = [
            'title' => 'Learn: ' . $course['title'] . ' - E-LOOX Academy',
            'course' => $course,
            'sections' => $sections,
            'allLectures' => $allLectures,
            'currentLecture' => $currentLecture,
            'enrollment' => $enrollment,
            'lectureProgress' => $lectureProgress,
        ];

        return view('portal/learn/index', $data);
    }

    public function lecture($courseId, $lectureId)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login')->with('error', 'Please login to access courses.');
        }

        // Check enrollment
        $enrollment = $this->enrollmentModel->where('user_id', $user['id'])
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return redirect()->to('courses/' . $courseId)->with('error', 'You must enroll in this course first.');
        }

        // Check order status if enrollment has order_id
        if (!empty($enrollment['order_id'])) {
            $order = $this->orderModel->find($enrollment['order_id']);
            if ($order && $order['status'] === 'pending') {
                return redirect()->to('portal/dashboard')->with('error', 'Your enrollment is pending payment approval. You will be able to access the course once payment is approved.');
            }
            if ($order && $order['status'] !== 'completed') {
                return redirect()->to('portal/dashboard')->with('error', 'Your enrollment payment status is: ' . ucfirst($order['status']) . '. Please contact support.');
            }
        }

        $course = $this->courseModel->getCourseById($courseId);
        $lecture = $this->lectureModel->find($lectureId);
        
        if (!$lecture || !$course) {
            return redirect()->to('portal/learn/' . $courseId)->with('error', 'Lecture not found.');
        }

        // Update last accessed lecture
        $this->enrollmentModel->update($enrollment['id'], [
            'last_accessed_lecture_id' => $lectureId
        ]);

        // Mark as started if not already
        $progress = $this->progressModel->where('enrollment_id', $enrollment['id'])
            ->where('lecture_id', $lectureId)
            ->first();

        if (!$progress) {
            $this->progressModel->insert([
                'enrollment_id' => $enrollment['id'],
                'lecture_id' => $lectureId,
                'is_completed' => 0,
                'video_progress' => 0,
                'total_video_duration' => $lecture['video_duration'] ?? 0,
                'last_position' => 0,
            ]);
        }

        // Get sections and lectures for sidebar
        $sections = $this->sectionModel->getAllSectionsByCourse($courseId);
        foreach ($sections as &$section) {
            $section['lectures'] = $this->lectureModel->getAllLecturesBySection($section['id']);
        }

        $data = [
            'title' => $lecture['title'] . ' - ' . $course['title'],
            'course' => $course,
            'lecture' => $lecture,
            'sections' => $sections,
            'enrollment' => $enrollment,
        ];

        return view('portal/learn/lecture', $data);
    }
}

