<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class CourseController extends BaseController
{
    protected $courseModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Get all courses
     */
    public function index()
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        $status = $this->request->getGet('status');
        $limit = $this->request->getGet('limit');
        
        $courses = $this->courseModel->getCoursesWithInstructor($status, $limit);
        
        return ajax_response(true, 'Courses retrieved', ['courses' => $courses]);
    }

    /**
     * Get single course
     */
    public function show($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Course ID required', null, 400);
        }

        $course = $this->courseModel->getCourseById($id);
        
        if (!$course) {
            return ajax_response(false, 'Course not found', null, 404);
        }

        return ajax_response(true, 'Course retrieved', ['course' => $course]);
    }

    /**
     * Create course
     */
    public function create()
    {
        helper(['auth', 'uuid']);
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        $user = get_current_user();
        if ($user['user_type'] !== 'instructor' && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Only instructors can create courses', null, 403);
        }

        $data = [
            'course_id' => generate_uuid(),
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'description' => $this->request->getPost('description'),
            'instructor_id' => $user['user_id'],
            'category_id' => $this->request->getPost('category_id'),
            'price' => $this->request->getPost('price') ?: 0.00,
            'discount_price' => $this->request->getPost('discount_price'),
            'language' => $this->request->getPost('language') ?: 'English',
            'level' => $this->request->getPost('level') ?: 'beginner',
            'thumbnail_url' => $this->request->getPost('thumbnail_url'),
            'promo_video_url' => $this->request->getPost('promo_video_url'),
            'status' => $this->request->getPost('status') ?: 'draft',
            'requirements' => $this->request->getPost('requirements'),
            'learning_outcomes' => $this->request->getPost('learning_outcomes'),
            'target_audience' => $this->request->getPost('target_audience'),
            'is_free' => $this->request->getPost('is_free') ?: false,
            'is_featured' => $this->request->getPost('is_featured') ?: false,
        ];

        if (!$this->courseModel->insert($data)) {
            return ajax_response(false, 'Failed to create course: ' . implode(', ', $this->courseModel->errors()), null, 400);
        }

        $course = $this->courseModel->getCourseById($data['course_id']);
        return ajax_response(true, 'Course created successfully', ['course' => $course]);
    }

    /**
     * Update course
     */
    public function update($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Course ID required', null, 400);
        }

        $user = get_current_user();
        $course = $this->courseModel->find($id);

        if (!$course) {
            return ajax_response(false, 'Course not found', null, 404);
        }

        // Check permission
        if ($course['instructor_id'] !== $user['user_id'] && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        $data = [];
        $fields = ['title', 'subtitle', 'description', 'category_id', 'price', 'discount_price', 
                   'language', 'level', 'thumbnail_url', 'promo_video_url', 'status', 
                   'requirements', 'learning_outcomes', 'target_audience', 'is_free', 'is_featured'];
        
        foreach ($fields as $field) {
            if ($this->request->getPost($field) !== null) {
                $data[$field] = $this->request->getPost($field);
            }
        }

        if (empty($data)) {
            return ajax_response(false, 'No data to update', null, 400);
        }

        if (!$this->courseModel->update($id, $data)) {
            return ajax_response(false, 'Failed to update course: ' . implode(', ', $this->courseModel->errors()), null, 400);
        }

        $course = $this->courseModel->getCourseById($id);
        return ajax_response(true, 'Course updated successfully', ['course' => $course]);
    }

    /**
     * Delete course
     */
    public function delete($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Course ID required', null, 400);
        }

        $user = get_current_user();
        $course = $this->courseModel->find($id);

        if (!$course) {
            return ajax_response(false, 'Course not found', null, 404);
        }

        // Check permission
        if ($course['instructor_id'] !== $user['user_id'] && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        if (!$this->courseModel->delete($id)) {
            return ajax_response(false, 'Failed to delete course', null, 400);
        }

        return ajax_response(true, 'Course deleted successfully');
    }
}

