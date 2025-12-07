<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\CategoryModel;
use App\Models\UserModel;

class AllCourses extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;
    protected $categoryModel;
    protected $userModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $courses = $this->courseModel->select('courses.*, users.first_name, users.last_name, categories.name as category_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->orderBy('courses.created_at', 'DESC')
            ->findAll();

        // Get enrollment counts for each course
        foreach ($courses as &$course) {
            $course['enrollment_count'] = $this->enrollmentModel->where('course_id', $course['id'])->countAllResults();
        }

        $data = [
            'title' => 'All Courses',
            'courses' => $courses,
        ];

        return view('admin/all-courses/index', $data);
    }

    public function view($id)
    {
        $course = $this->courseModel->getCourseById($id);
        
        if (!$course) {
            return redirect()->to('admin/all-courses')->with('error', 'Course not found.');
        }

        // Get instructor
        $instructor = $this->userModel->find($course['instructor_id']);
        
        // Get category
        $category = $this->categoryModel->find($course['category_id']);

        // Get enrolled students
        $enrollments = $this->enrollmentModel->select('enrollments.*, users.first_name, users.last_name, users.email, users.profile_picture, users.phone_number')
            ->join('users', 'users.id = enrollments.user_id', 'left')
            ->where('enrollments.course_id', $id)
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Course: ' . $course['title'],
            'course' => $course,
            'instructor' => $instructor,
            'category' => $category,
            'enrollments' => $enrollments,
        ];

        return view('admin/all-courses/view', $data);
    }
}

