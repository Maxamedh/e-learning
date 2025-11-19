<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use CodeIgniter\HTTP\ResponseInterface;

class EnrollmentApiController extends BaseApiController
{
    protected $enrollmentModel;
    protected $courseModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
    }

    public function index()
    {
        $this->authenticate();
        $user_id = $this->request->getGet('user_id') ?? $this->currentUser['user_id'];
        $course_id = $this->request->getGet('course_id');

        // Users can only see their own enrollments unless admin
        if ($user_id !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        $builder = $this->enrollmentModel->select('enrollments.*, courses.title as course_title, users.first_name, users.last_name')
            ->join('courses', 'courses.course_id = enrollments.course_id')
            ->join('users', 'users.user_id = enrollments.user_id')
            ->where('enrollments.user_id', $user_id);

        if ($course_id) $builder->where('enrollments.course_id', $course_id);

        $enrollments = $builder->findAll();
        return $this->respond(['status' => 'success', 'data' => $enrollments]);
    }

    public function create()
    {
        $this->authenticate();
        if (!$this->requirePermission(['student', 'admin'])) return;

        $data = $this->request->getJSON(true);
        $data['user_id'] = $data['user_id'] ?? $this->currentUser['user_id'];
        $data['enrollment_type'] = $data['enrollment_type'] ?? 'free';

        // Check if already enrolled
        $existing = $this->enrollmentModel->where('user_id', $data['user_id'])
            ->where('course_id', $data['course_id'])->first();
        if ($existing) {
            return $this->fail('Already enrolled in this course', 400);
        }

        helper('uuid');
        $data['enrollment_id'] = generate_uuid();

        if ($this->enrollmentModel->insert($data)) {
            // Update course student count
            $course = $this->courseModel->find($data['course_id']);
            $this->courseModel->update($data['course_id'], [
                'total_students' => ($course['total_students'] ?? 0) + 1
            ]);

            return $this->respondCreated(['status' => 'success', 'message' => 'Enrolled successfully', 'data' => $this->enrollmentModel->find($data['enrollment_id'])]);
        }
        return $this->failValidationErrors($this->enrollmentModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        $enrollment = $this->enrollmentModel->find($id);
        if (!$enrollment) return $this->failNotFound('Enrollment not found');

        if ($enrollment['user_id'] !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        $data = $this->request->getJSON(true);
        if ($this->enrollmentModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Enrollment updated', 'data' => $this->enrollmentModel->find($id)]);
        }
        return $this->failValidationErrors($this->enrollmentModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        $enrollment = $this->enrollmentModel->find($id);
        if (!$enrollment) return $this->failNotFound('Enrollment not found');

        if ($enrollment['user_id'] !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        if ($this->enrollmentModel->delete($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Enrollment deleted']);
        }
        return $this->fail('Failed to delete enrollment');
    }
}

