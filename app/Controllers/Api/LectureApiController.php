<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\LectureModel;
use App\Models\SectionModel;
use App\Models\CourseModel;

class LectureApiController extends BaseApiController
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

    public function index()
    {
        $this->authenticate();
        $section_id = $this->request->getGet('section_id');
        if (!$section_id) return $this->fail('section_id required', 400);

        $lectures = $this->lectureModel->getLecturesBySection($section_id);
        return $this->respond(['status' => 'success', 'data' => $lectures]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $lecture = $this->lectureModel->find($id);
        if (!$lecture) return $this->failNotFound('Lecture not found');
        return $this->respond(['status' => 'success', 'data' => $lecture]);
    }

    public function create()
    {
        $this->authenticate();
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        $data = $this->request->getJSON(true);
        $section = $this->sectionModel->find($data['section_id']);
        if (!$section) return $this->failNotFound('Section not found');

        $course = $this->courseModel->find($section['course_id']);
        if ($this->currentUser['user_type'] === 'instructor' && $course['instructor_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('Access denied');
        }

        helper('uuid');
        $data['lecture_id'] = generate_uuid();
        if ($this->lectureModel->insert($data)) {
            // Update course lecture count
            $this->courseModel->update($course['course_id'], [
                'total_lectures' => ($course['total_lectures'] ?? 0) + 1
            ]);
            return $this->respondCreated(['status' => 'success', 'message' => 'Lecture created', 'data' => $this->lectureModel->find($data['lecture_id'])]);
        }
        return $this->failValidationErrors($this->lectureModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        $lecture = $this->lectureModel->find($id);
        if (!$lecture) return $this->failNotFound('Lecture not found');

        $section = $this->sectionModel->find($lecture['section_id']);
        $course = $this->courseModel->find($section['course_id']);
        if ($this->currentUser['user_type'] === 'instructor' && $course['instructor_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('Access denied');
        }
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        $data = $this->request->getJSON(true);
        if ($this->lectureModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Lecture updated', 'data' => $this->lectureModel->find($id)]);
        }
        return $this->failValidationErrors($this->lectureModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        $lecture = $this->lectureModel->find($id);
        if (!$lecture) return $this->failNotFound('Lecture not found');

        $section = $this->sectionModel->find($lecture['section_id']);
        $course = $this->courseModel->find($section['course_id']);
        if ($this->currentUser['user_type'] === 'instructor' && $course['instructor_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('Access denied');
        }
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        if ($this->lectureModel->delete($id)) {
            $this->courseModel->update($course['course_id'], [
                'total_lectures' => max(0, ($course['total_lectures'] ?? 0) - 1)
            ]);
            return $this->respondDeleted(['status' => 'success', 'message' => 'Lecture deleted']);
        }
        return $this->fail('Failed to delete lecture');
    }
}

