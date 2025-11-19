<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\LectureModel;
use App\Models\SectionModel;
use App\Models\CourseModel;

class LectureController extends BaseController
{
    protected $lectureModel;
    protected $sectionModel;
    protected $courseModel;

    public function __construct()
    {
        $this->lectureModel = new LectureModel();
        $this->sectionModel = new SectionModel();
        $this->courseModel = new CourseModel();
    }

    public function getBySection($sectionId = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$sectionId) {
            return ajax_response(false, 'Section ID required', null, 400);
        }

        $lectures = $this->lectureModel->getLecturesBySection($sectionId);
        return ajax_response(true, 'Lectures retrieved', ['lectures' => $lectures]);
    }

    public function show($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Lecture ID required', null, 400);
        }

        $lecture = $this->lectureModel->find($id);
        if (!$lecture) {
            return ajax_response(false, 'Lecture not found', null, 404);
        }

        return ajax_response(true, 'Lecture retrieved', ['lecture' => $lecture]);
    }

    public function create()
    {
        helper(['auth', 'uuid']);
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        $user = get_current_user();
        $sectionId = $this->request->getPost('section_id');

        if (!$sectionId) {
            return ajax_response(false, 'Section ID required', null, 400);
        }

        $section = $this->sectionModel->find($sectionId);
        if (!$section) {
            return ajax_response(false, 'Section not found', null, 404);
        }

        $course = $this->courseModel->find($section['course_id']);
        if ($course['instructor_id'] !== $user['user_id'] && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        $data = [
            'lecture_id' => generate_uuid(),
            'section_id' => $sectionId,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'content_type' => $this->request->getPost('content_type') ?: 'video',
            'video_url' => $this->request->getPost('video_url'),
            'video_duration' => $this->request->getPost('video_duration'),
            'article_content' => $this->request->getPost('article_content'),
            'downloadable_resources' => $this->request->getPost('downloadable_resources') ? json_encode($this->request->getPost('downloadable_resources')) : null,
            'sort_order' => $this->request->getPost('sort_order') ?: 0,
            'is_preview' => $this->request->getPost('is_preview') ?: false,
            'is_published' => $this->request->getPost('is_published') !== null ? (bool)$this->request->getPost('is_published') : true,
        ];

        if (!$this->lectureModel->insert($data)) {
            return ajax_response(false, 'Failed to create lecture: ' . implode(', ', $this->lectureModel->errors()), null, 400);
        }

        // Update course lecture count
        $this->courseModel->update($course['course_id'], [
            'total_lectures' => $course['total_lectures'] + 1
        ]);

        $lecture = $this->lectureModel->find($data['lecture_id']);
        return ajax_response(true, 'Lecture created successfully', ['lecture' => $lecture]);
    }

    public function update($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Lecture ID required', null, 400);
        }

        $user = get_current_user();
        $lecture = $this->lectureModel->find($id);

        if (!$lecture) {
            return ajax_response(false, 'Lecture not found', null, 404);
        }

        $section = $this->sectionModel->find($lecture['section_id']);
        $course = $this->courseModel->find($section['course_id']);
        
        if ($course['instructor_id'] !== $user['user_id'] && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        $data = [];
        $fields = ['title', 'description', 'content_type', 'video_url', 'video_duration', 
                   'article_content', 'downloadable_resources', 'sort_order', 'is_preview', 'is_published'];
        
        foreach ($fields as $field) {
            if ($this->request->getPost($field) !== null) {
                if ($field === 'downloadable_resources' && is_array($this->request->getPost($field))) {
                    $data[$field] = json_encode($this->request->getPost($field));
                } else {
                    $data[$field] = $this->request->getPost($field);
                }
            }
        }

        if (empty($data)) {
            return ajax_response(false, 'No data to update', null, 400);
        }

        if (!$this->lectureModel->update($id, $data)) {
            return ajax_response(false, 'Failed to update lecture: ' . implode(', ', $this->lectureModel->errors()), null, 400);
        }

        $lecture = $this->lectureModel->find($id);
        return ajax_response(true, 'Lecture updated successfully', ['lecture' => $lecture]);
    }

    public function delete($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Lecture ID required', null, 400);
        }

        $user = get_current_user();
        $lecture = $this->lectureModel->find($id);

        if (!$lecture) {
            return ajax_response(false, 'Lecture not found', null, 404);
        }

        $section = $this->sectionModel->find($lecture['section_id']);
        $course = $this->courseModel->find($section['course_id']);
        
        if ($course['instructor_id'] !== $user['user_id'] && $user['user_type'] !== 'admin') {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        if (!$this->lectureModel->delete($id)) {
            return ajax_response(false, 'Failed to delete lecture', null, 400);
        }

        // Update course lecture count
        $this->courseModel->update($course['course_id'], [
            'total_lectures' => max(0, $course['total_lectures'] - 1)
        ]);

        return ajax_response(true, 'Lecture deleted successfully');
    }
}

