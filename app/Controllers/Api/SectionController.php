<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SectionModel;
use App\Models\CourseModel;

class SectionController extends BaseController
{
    protected $sectionModel;
    protected $courseModel;

    public function __construct()
    {
        $this->sectionModel = new SectionModel();
        $this->courseModel = new CourseModel();
    }

    public function getByCourse($courseId = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$courseId) {
            return ajax_response(false, 'Course ID required', null, 400);
        }

        $sections = $this->sectionModel->getSectionsByCourse($courseId);
        return ajax_response(true, 'Sections retrieved', ['sections' => $sections]);
    }

    public function show($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Section ID required', null, 400);
        }

        $section = $this->sectionModel->find($id);
        if (!$section) {
            return ajax_response(false, 'Section not found', null, 404);
        }

        return ajax_response(true, 'Section retrieved', ['section' => $section]);
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

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return ajax_response(false, 'Course not found', null, 404);
        }

        if ($course['instructor_id'] !== $user['user_id'] && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        $data = [
            'section_id' => generate_uuid(),
            'course_id' => $courseId,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_free_preview' => $this->request->getPost('is_free_preview') ?: false,
        ];

        if (!$this->sectionModel->insert($data)) {
            return ajax_response(false, 'Failed to create section: ' . implode(', ', $this->sectionModel->errors()), null, 400);
        }

        $section = $this->sectionModel->find($data['section_id']);
        return ajax_response(true, 'Section created successfully', ['section' => $section]);
    }

    public function update($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Section ID required', null, 400);
        }

        $user = get_current_user();
        $section = $this->sectionModel->find($id);

        if (!$section) {
            return ajax_response(false, 'Section not found', null, 404);
        }

        $course = $this->courseModel->find($section['course_id']);
        if ($course['instructor_id'] !== $user['user_id'] && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        $data = [];
        $fields = ['title', 'description', 'sort_order', 'is_free_preview'];
        
        foreach ($fields as $field) {
            if ($this->request->getPost($field) !== null) {
                $data[$field] = $this->request->getPost($field);
            }
        }

        if (empty($data)) {
            return ajax_response(false, 'No data to update', null, 400);
        }

        if (!$this->sectionModel->update($id, $data)) {
            return ajax_response(false, 'Failed to update section: ' . implode(', ', $this->sectionModel->errors()), null, 400);
        }

        $section = $this->sectionModel->find($id);
        return ajax_response(true, 'Section updated successfully', ['section' => $section]);
    }

    public function delete($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Section ID required', null, 400);
        }

        $user = get_current_user();
        $section = $this->sectionModel->find($id);

        if (!$section) {
            return ajax_response(false, 'Section not found', null, 404);
        }

        $course = $this->courseModel->find($section['course_id']);
        if ($course['instructor_id'] !== $user['user_id'] && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        if (!$this->sectionModel->delete($id)) {
            return ajax_response(false, 'Failed to delete section', null, 400);
        }

        return ajax_response(true, 'Section deleted successfully');
    }
}

