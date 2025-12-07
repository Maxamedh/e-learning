<?php

namespace App\Controllers\Instructor;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CategoryModel;
use App\Models\EnrollmentModel;
use App\Models\SectionModel;
use App\Models\LectureModel;
use App\Models\UserModel;

class Courses extends BaseController
{
    protected $courseModel;
    protected $categoryModel;
    protected $enrollmentModel;
    protected $sectionModel;
    protected $lectureModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->sectionModel = new SectionModel();
        $this->lectureModel = new LectureModel();
    }

    public function index()
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'instructor') {
            return redirect()->to('admin/login')->with('error', 'Access denied.');
        }

        $courses = $this->courseModel->select('courses.*, categories.name as category_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->where('courses.instructor_id', $user['id'])
            ->orderBy('courses.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'My Courses',
            'courses' => $courses,
        ];

        return view('instructor/courses/index', $data);
    }

    public function view($id)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'instructor') {
            return redirect()->to('admin/login')->with('error', 'Access denied.');
        }

        $course = $this->courseModel->getCourseById($id);
        
        if (!$course || $course['instructor_id'] != $user['id']) {
            return redirect()->to('instructor/courses')->with('error', 'Course not found.');
        }

        // Get enrolled students
        $enrollments = $this->enrollmentModel->select('enrollments.*, users.first_name, users.last_name, users.email, users.profile_picture')
            ->join('users', 'users.id = enrollments.user_id', 'left')
            ->where('enrollments.course_id', $id)
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->findAll();

        // Get sections and lectures
        $sections = $this->sectionModel->getAllSectionsByCourse($id);
        foreach ($sections as &$section) {
            $section['lectures'] = $this->lectureModel->getAllLecturesBySection($section['id']);
        }

        $data = [
            'title' => 'Course: ' . $course['title'],
            'course' => $course,
            'enrollments' => $enrollments,
            'sections' => $sections,
        ];

        return view('instructor/courses/view', $data);
    }

    public function edit($id)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'instructor') {
            return redirect()->to('admin/login')->with('error', 'Access denied.');
        }

        $course = $this->courseModel->getCourseById($id);
        
        if (!$course || $course['instructor_id'] != $user['id']) {
            return redirect()->to('instructor/courses')->with('error', 'Course not found or you do not have permission to edit this course.');
        }

        // Load sections and lectures for this course
        $sections = $this->sectionModel->getAllSectionsByCourse($id);
        
        // Get lectures for each section
        foreach ($sections as &$section) {
            $section['lectures'] = $this->lectureModel->getAllLecturesBySection($section['id']);
        }
        
        $categories = $this->categoryModel->getActiveCategories();

        $data = [
            'title' => 'Edit Course',
            'course' => $course,
            'categories' => $categories,
            'sections' => $sections,
            'isInstructor' => true, // Flag to indicate this is instructor editing
        ];
        
        return view('admin/courses/edit', $data);
    }

    public function update($id)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'instructor') {
            return redirect()->to('admin/login')->with('error', 'Access denied.');
        }

        $course = $this->courseModel->getCourseById($id);
        
        if (!$course || $course['instructor_id'] != $user['id']) {
            return redirect()->to('instructor/courses')->with('error', 'Course not found or you do not have permission to edit this course.');
        }
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'category_id' => 'required|numeric',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'level' => 'required|in_list[beginner,intermediate,advanced,all]',
            'status' => 'required|in_list[draft,published,unpublished,pending]',
        ];
        
        // Add file validation only if files are uploaded
        $thumbnailFile = $this->request->getFile('thumbnail');
        $promoVideoFile = $this->request->getFile('promo_video');
        
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            $rules['thumbnail'] = 'uploaded[thumbnail]|max_size[thumbnail,5120]|ext_in[thumbnail,jpg,jpeg,png,gif]';
        }
        if ($promoVideoFile && $promoVideoFile->isValid() && !$promoVideoFile->hasMoved()) {
            $rules['promo_video'] = 'uploaded[promo_video]|max_size[promo_video,102400]|ext_in[promo_video,mp4,webm,mov]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle thumbnail upload
        $thumbnailUrl = $this->request->getPost('thumbnail_url') ?: $course['thumbnail_url'];
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            $newName = $thumbnailFile->getRandomName();
            $thumbnailFile->move(ROOTPATH . 'public/uploads/thumbnails', $newName);
            $thumbnailUrl = base_url('uploads/thumbnails/' . $newName);
        }
        
        // Handle promo video upload
        $promoVideoUrl = $this->request->getPost('promo_video_url') ?: $course['promo_video_url'];
        if ($promoVideoFile && $promoVideoFile->isValid() && !$promoVideoFile->hasMoved()) {
            $newName = $promoVideoFile->getRandomName();
            $promoVideoFile->move(ROOTPATH . 'public/uploads/promo-videos', $newName);
            $promoVideoUrl = base_url('uploads/promo-videos/' . $newName);
        }
        
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'category_id' => $this->request->getPost('category_id'),
            'price' => $this->request->getPost('is_free') ? 0 : $this->request->getPost('price'),
            'discount_price' => $this->request->getPost('is_free') ? null : ($this->request->getPost('discount_price') ?: null),
            'level' => $this->request->getPost('level'),
            'language' => $this->request->getPost('language') ?: 'English',
            'status' => $this->request->getPost('status'),
            'is_free' => $this->request->getPost('is_free') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'thumbnail_url' => $thumbnailUrl ?: null,
            'promo_video_url' => $promoVideoUrl,
            // Instructor cannot change instructor_id - keep original
            'instructor_id' => $course['instructor_id'],
        ];
        
        // Handle JSON fields
        if ($this->request->getPost('requirements')) {
            $data['requirements'] = json_encode(explode("\n", $this->request->getPost('requirements')));
        }
        if ($this->request->getPost('learning_outcomes')) {
            $data['learning_outcomes'] = json_encode(explode("\n", $this->request->getPost('learning_outcomes')));
        }
        
        if ($this->courseModel->update($id, $data)) {
            return redirect()->to('instructor/courses')->with('success', 'Course updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update course.');
        }
    }
}

