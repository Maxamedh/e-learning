<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;

class EnrollmentController extends BaseController
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
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        $user = get_current_user();
        $enrollments = $this->enrollmentModel->getUserEnrollments($user['user_id']);
        
        return ajax_response(true, 'Enrollments retrieved', ['enrollments' => $enrollments]);
    }

    public function create()
    {
        helper(['auth', 'uuid']);
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        $user = get_current_user();
        $courseId = $this->request->getPost('course_id');

        if (!$courseId) {
            return ajax_response(false, 'Course ID required', null, 400);
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isEnrolled($user['user_id'], $courseId)) {
            return ajax_response(false, 'Already enrolled in this course', null, 400);
        }

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return ajax_response(false, 'Course not found', null, 404);
        }

        $enrollmentType = $course['is_free'] ? 'free' : 'paid';

        $data = [
            'enrollment_id' => generate_uuid(),
            'user_id' => $user['user_id'],
            'course_id' => $courseId,
            'enrollment_type' => $enrollmentType,
            'completion_status' => 'in_progress',
        ];

        if (!$this->enrollmentModel->insert($data)) {
            return ajax_response(false, 'Failed to enroll: ' . implode(', ', $this->enrollmentModel->errors()), null, 400);
        }

        // Update course student count
        $this->courseModel->update($courseId, [
            'total_students' => $course['total_students'] + 1
        ]);

        $enrollment = $this->enrollmentModel->find($data['enrollment_id']);
        return ajax_response(true, 'Enrolled successfully', ['enrollment' => $enrollment]);
    }

    public function delete($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Enrollment ID required', null, 400);
        }

        $user = get_current_user();
        $enrollment = $this->enrollmentModel->find($id);

        if (!$enrollment) {
            return ajax_response(false, 'Enrollment not found', null, 404);
        }

        if ($enrollment['user_id'] !== $user['user_id'] && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        if (!$this->enrollmentModel->delete($id)) {
            return ajax_response(false, 'Failed to unenroll', null, 400);
        }

        return ajax_response(true, 'Unenrolled successfully');
    }
}

