<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\CourseModel;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class CourseApiController extends BaseApiController
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
        $page = $this->request->getGet('page') ?? 1;
        $limit = $this->request->getGet('limit') ?? 10;
        $status = $this->request->getGet('status');
        $category_id = $this->request->getGet('category_id');
        $search = $this->request->getGet('search');

        $builder = $this->courseModel->select('courses.*, categories.name as category_name, users.first_name, users.last_name')
            ->join('categories', 'categories.category_id = courses.category_id', 'left')
            ->join('users', 'users.user_id = courses.instructor_id', 'left');

        if ($status) {
            $builder->where('courses.status', $status);
        }

        if ($category_id) {
            $builder->where('courses.category_id', $category_id);
        }

        if ($search) {
            $builder->groupStart()
                ->like('courses.title', $search)
                ->orLike('courses.description', $search)
                ->groupEnd();
        }

        $total = $builder->countAllResults(false);
        $courses = $builder->orderBy('courses.created_at', 'DESC')
            ->paginate($limit, 'default', $page);

        return $this->respond([
            'status' => 'success',
            'data' => $courses,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
            ]
        ]);
    }

    /**
     * Get single course
     */
    public function show($id = null)
    {
        $course = $this->courseModel->select('courses.*, categories.name as category_name, users.first_name, users.last_name, users.email as instructor_email')
            ->join('categories', 'categories.category_id = courses.category_id', 'left')
            ->join('users', 'users.user_id = courses.instructor_id', 'left')
            ->find($id);

        if (!$course) {
            return $this->failNotFound('Course not found');
        }

        return $this->respond([
            'status' => 'success',
            'data' => $course,
        ]);
    }

    /**
     * Create new course
     */
    public function create()
    {
        if (!$this->requirePermission(['instructor', 'admin'])) {
            return;
        }

        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'required|min_length[5]|max_length[200]',
            'instructor_id' => 'required',
            'category_id' => 'permit_empty|integer',
            'price' => 'permit_empty|decimal',
            'level' => 'permit_empty|in_list[beginner,intermediate,advanced,all]',
        ]);

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        helper('uuid');
        $courseData = [
            'course_id' => generate_uuid(),
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'description' => $data['description'] ?? null,
            'instructor_id' => $data['instructor_id'],
            'category_id' => $data['category_id'] ?? null,
            'price' => $data['price'] ?? 0.00,
            'discount_price' => $data['discount_price'] ?? null,
            'language' => $data['language'] ?? 'English',
            'level' => $data['level'] ?? 'beginner',
            'status' => $data['status'] ?? 'draft',
            'requirements' => $data['requirements'] ?? null,
            'learning_outcomes' => $data['learning_outcomes'] ?? null,
            'target_audience' => $data['target_audience'] ?? null,
            'is_free' => $data['is_free'] ?? false,
            'is_featured' => $data['is_featured'] ?? false,
        ];

        if ($this->courseModel->insert($courseData)) {
            $course = $this->courseModel->find($courseData['course_id']);
            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Course created successfully',
                'data' => $course,
            ]);
        }

        return $this->fail('Failed to create course', 500);
    }

    /**
     * Update course
     */
    public function update($id = null)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return $this->failNotFound('Course not found');
        }

        // Check permission: instructor can only update their own courses, admin can update any
        if ($this->currentUser['user_type'] === 'instructor' && $course['instructor_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('You can only update your own courses');
        }

        if (!$this->requirePermission(['instructor', 'admin'])) {
            return;
        }

        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'permit_empty|min_length[5]|max_length[200]',
            'level' => 'permit_empty|in_list[beginner,intermediate,advanced,all]',
            'status' => 'permit_empty|in_list[draft,published,pending,rejected]',
        ]);

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        if ($this->courseModel->update($id, $data)) {
            $course = $this->courseModel->find($id);
            return $this->respond([
                'status' => 'success',
                'message' => 'Course updated successfully',
                'data' => $course,
            ]);
        }

        return $this->fail('Failed to update course', 500);
    }

    /**
     * Delete course
     */
    public function delete($id = null)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return $this->failNotFound('Course not found');
        }

        // Check permission
        if ($this->currentUser['user_type'] === 'instructor' && $course['instructor_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('You can only delete your own courses');
        }

        if (!$this->requirePermission(['instructor', 'admin'])) {
            return;
        }

        if ($this->courseModel->delete($id)) {
            return $this->respondDeleted([
                'status' => 'success',
                'message' => 'Course deleted successfully',
            ]);
        }

        return $this->fail('Failed to delete course', 500);
    }
}
