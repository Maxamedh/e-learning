<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\UserModel;
use App\Models\LectureProgressModel;
use App\Models\LectureModel;

class Enrollments extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $userModel;
    protected $progressModel;
    protected $lectureModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->userModel = new UserModel();
        $this->progressModel = new LectureProgressModel();
        $this->lectureModel = new LectureModel();
    }

    public function index()
    {
        $courseId = $this->request->getGet('course_id');
        $userId = $this->request->getGet('user_id');
        $search = $this->request->getGet('search');
        
        // Create a new query builder instance
        $db = \Config\Database::connect();
        $builder = $db->table('enrollments');
        $builder->select('enrollments.*, users.first_name, users.last_name, users.email, courses.title as course_title, courses.thumbnail_url');
        $builder->join('users', 'users.id = enrollments.user_id', 'left');
        $builder->join('courses', 'courses.id = enrollments.course_id', 'left');
        
        // Apply filters
        if (!empty($courseId)) {
            $builder->where('enrollments.course_id', $courseId);
        }
        
        if (!empty($userId)) {
            $builder->where('enrollments.user_id', $userId);
        }
        
        if (!empty($search)) {
            $builder->groupStart()
                ->like('users.first_name', $search)
                ->orLike('users.last_name', $search)
                ->orLike('users.email', $search)
                ->orLike('courses.title', $search)
                ->groupEnd();
        }
        
        // Get results
        $enrollments = $builder->orderBy('enrollments.enrolled_at', 'DESC')->get()->getResultArray();
        
        // Calculate progress for each enrollment
        foreach ($enrollments as &$enrollment) {
            try {
                // Get total lectures for this course
                $totalLectures = $this->lectureModel->select('COUNT(*) as total')
                    ->join('course_sections', 'course_sections.id = lectures.section_id')
                    ->where('course_sections.course_id', $enrollment['course_id'])
                    ->where('lectures.is_published', 1)
                    ->first();
                
                $totalLecturesCount = isset($totalLectures['total']) ? (int)$totalLectures['total'] : 0;
                
                // Get completed lectures
                $completedLectures = $this->progressModel->where('enrollment_id', $enrollment['id'])
                    ->where('is_completed', 1)
                    ->countAllResults();
                
                // Calculate progress percentage
                if ($totalLecturesCount > 0) {
                    $progressPercentage = ($completedLectures / $totalLecturesCount) * 100;
                    $enrollment['progress_percentage'] = round($progressPercentage, 2);
                    $enrollment['completed_lectures'] = (int)$completedLectures;
                    $enrollment['total_lectures'] = $totalLecturesCount;
                } else {
                    $enrollment['progress_percentage'] = 0;
                    $enrollment['completed_lectures'] = 0;
                    $enrollment['total_lectures'] = 0;
                }
            } catch (\Exception $e) {
                // If calculation fails, set defaults
                $enrollment['progress_percentage'] = 0;
                $enrollment['completed_lectures'] = 0;
                $enrollment['total_lectures'] = 0;
            }
        }
        
        // Get courses for filter dropdown
        $courses = $this->courseModel->select('id, title')->orderBy('title', 'ASC')->findAll();
        
        $data = [
            'title' => 'Enrollments Management',
            'enrollments' => $enrollments,
            'courses' => $courses,
            'course_id' => $courseId ?? '',
            'user_id' => $userId ?? '',
            'search' => $search ?? '',
        ];
        
        return view('admin/enrollments/index', $data);
    }

    public function create()
    {
        $courses = $this->courseModel->select('id, title')->orderBy('title', 'ASC')->findAll();
        $students = $this->userModel->where('role', 'student')->select('id, first_name, last_name, email')->orderBy('first_name', 'ASC')->findAll();
        
        $data = [
            'title' => 'Create Enrollment',
            'courses' => $courses,
            'students' => $students,
        ];
        
        return view('admin/enrollments/create', $data);
    }

    public function store()
    {
        $rules = [
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Check if already enrolled
        $existing = $this->enrollmentModel->where('user_id', $this->request->getPost('user_id'))
            ->where('course_id', $this->request->getPost('course_id'))
            ->first();
        
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'User is already enrolled in this course.');
        }
        
        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'course_id' => $this->request->getPost('course_id'),
            'enrolled_at' => date('Y-m-d H:i:s'),
            'progress_percentage' => 0,
        ];
        
        if ($this->enrollmentModel->insert($data)) {
            // Update course student count
            $course = $this->courseModel->find($data['course_id']);
            if ($course) {
                $this->courseModel->update($data['course_id'], [
                    'total_students' => ($course['total_students'] ?? 0) + 1
                ]);
            }
            
            return redirect()->to('admin/enrollments')->with('success', 'Enrollment created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create enrollment.');
        }
    }

    public function delete($id)
    {
        $enrollment = $this->enrollmentModel->find($id);
        
        if (!$enrollment) {
            return redirect()->to('admin/enrollments')->with('error', 'Enrollment not found.');
        }
        
        // Update course student count
        $course = $this->courseModel->find($enrollment['course_id']);
        if ($course && ($course['total_students'] ?? 0) > 0) {
            $this->courseModel->update($enrollment['course_id'], [
                'total_students' => ($course['total_students'] ?? 0) - 1
            ]);
        }
        
        if ($this->enrollmentModel->delete($id)) {
            return redirect()->to('admin/enrollments')->with('success', 'Enrollment deleted successfully!');
        } else {
            return redirect()->to('admin/enrollments')->with('error', 'Failed to delete enrollment.');
        }
    }
}

