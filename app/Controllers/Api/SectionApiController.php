<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\SectionModel;
use App\Models\CourseModel;

class SectionApiController extends BaseApiController
{
    protected $sectionModel;
    protected $courseModel;

    public function __construct()
    {
        $this->sectionModel = new SectionModel();
        $this->courseModel = new CourseModel();
    }

    public function index()
    {
        $this->authenticate();
        $course_id = $this->request->getGet('course_id');
        if (!$course_id) return $this->fail('course_id required', 400);

        $sections = $this->sectionModel->getSectionsByCourse($course_id);
        return $this->respond(['status' => 'success', 'data' => $sections]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $section = $this->sectionModel->find($id);
        if (!$section) return $this->failNotFound('Section not found');
        return $this->respond(['status' => 'success', 'data' => $section]);
    }

    public function create()
    {
        $this->authenticate();
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        $data = $this->request->getJSON(true);
        // Verify course ownership
        $course = $this->courseModel->find($data['course_id']);
        if (!$course) return $this->failNotFound('Course not found');
        if ($this->currentUser['user_type'] === 'instructor' && $course['instructor_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('Access denied');
        }

        helper('uuid');
        $data['section_id'] = generate_uuid();
        if ($this->sectionModel->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Section created', 'data' => $this->sectionModel->find($data['section_id'])]);
        }
        return $this->failValidationErrors($this->sectionModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        $section = $this->sectionModel->find($id);
        if (!$section) return $this->failNotFound('Section not found');

        $course = $this->courseModel->find($section['course_id']);
        if ($this->currentUser['user_type'] === 'instructor' && $course['instructor_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('Access denied');
        }
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        $data = $this->request->getJSON(true);
        if ($this->sectionModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Section updated', 'data' => $this->sectionModel->find($id)]);
        }
        return $this->failValidationErrors($this->sectionModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        $section = $this->sectionModel->find($id);
        if (!$section) return $this->failNotFound('Section not found');

        $course = $this->courseModel->find($section['course_id']);
        if ($this->currentUser['user_type'] === 'instructor' && $course['instructor_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('Access denied');
        }
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        if ($this->sectionModel->delete($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Section deleted']);
        }
        return $this->fail('Failed to delete section');
    }
}

