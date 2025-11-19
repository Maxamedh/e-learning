<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\AssignmentModel;
use App\Models\AssignmentSubmissionModel;

class AssignmentApiController extends BaseApiController
{
    protected $assignmentModel;
    protected $submissionModel;

    public function __construct()
    {
        $this->assignmentModel = new AssignmentModel();
        $this->submissionModel = new \App\Models\AssignmentSubmissionModel();
    }

    public function index()
    {
        $this->authenticate();
        $course_id = $this->request->getGet('course_id');
        if (!$course_id) return $this->fail('course_id required', 400);

        $assignments = $this->assignmentModel->where('course_id', $course_id)->orderBy('created_at', 'DESC')->findAll();
        return $this->respond(['status' => 'success', 'data' => $assignments]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $assignment = $this->assignmentModel->find($id);
        if (!$assignment) return $this->failNotFound('Assignment not found');
        return $this->respond(['status' => 'success', 'data' => $assignment]);
    }

    public function create()
    {
        $this->authenticate();
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        $data = $this->request->getJSON(true);
        helper('uuid');
        $data['assignment_id'] = generate_uuid();

        if ($this->assignmentModel->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Assignment created', 'data' => $this->assignmentModel->find($data['assignment_id'])]);
        }
        return $this->failValidationErrors($this->assignmentModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        $data = $this->request->getJSON(true);
        if ($this->assignmentModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Assignment updated', 'data' => $this->assignmentModel->find($id)]);
        }
        return $this->failValidationErrors($this->assignmentModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        if (!$this->requirePermission(['instructor', 'admin'])) return;

        if ($this->assignmentModel->delete($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Assignment deleted']);
        }
        return $this->fail('Failed to delete assignment');
    }
}

