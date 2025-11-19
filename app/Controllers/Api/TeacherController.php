<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class TeacherController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        $teachers = $this->userModel->getActiveUsers('instructor');
        return ajax_response(true, 'Teachers retrieved', ['teachers' => $teachers]);
    }

    public function show($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Teacher ID required', null, 400);
        }

        $teacher = $this->userModel->find($id);
        if (!$teacher || $teacher['user_type'] !== 'instructor') {
            return ajax_response(false, 'Teacher not found', null, 404);
        }

        return ajax_response(true, 'Teacher retrieved', ['teacher' => $teacher]);
    }

    public function create()
    {
        helper(['auth', 'uuid']);
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        $user = get_current_user();
        if ($user['user_type'] !== 'admin') {
            return ajax_response(false, 'Only admins can create teachers', null, 403);
        }

        $data = [
            'user_id' => generate_uuid(),
            'email' => $this->request->getPost('email'),
            'password_hash' => $this->request->getPost('password'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'user_type' => 'instructor',
            'phone_number' => $this->request->getPost('phone_number'),
            'country' => $this->request->getPost('country'),
            'bio' => $this->request->getPost('bio'),
        ];

        if (!$this->userModel->insert($data)) {
            return ajax_response(false, 'Failed to create teacher: ' . implode(', ', $this->userModel->errors()), null, 400);
        }

        $teacher = $this->userModel->find($data['user_id']);
        return ajax_response(true, 'Teacher created successfully', ['teacher' => $teacher]);
    }

    public function update($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Teacher ID required', null, 400);
        }

        $user = get_current_user();
        if ($user['user_type'] !== 'admin' && $user['user_id'] !== $id) {
            return ajax_response(false, 'Permission denied', null, 403);
        }

        $data = [];
        $fields = ['first_name', 'last_name', 'phone_number', 'country', 'bio', 'date_of_birth'];
        
        foreach ($fields as $field) {
            if ($this->request->getPost($field) !== null) {
                $data[$field] = $this->request->getPost($field);
            }
        }

        if ($this->request->getPost('password')) {
            $data['password_hash'] = $this->request->getPost('password');
        }

        if (empty($data)) {
            return ajax_response(false, 'No data to update', null, 400);
        }

        if (!$this->userModel->update($id, $data)) {
            return ajax_response(false, 'Failed to update teacher: ' . implode(', ', $this->userModel->errors()), null, 400);
        }

        $teacher = $this->userModel->find($id);
        return ajax_response(true, 'Teacher updated successfully', ['teacher' => $teacher]);
    }

    public function delete($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Teacher ID required', null, 400);
        }

        $user = get_current_user();
        if ($user['user_type'] !== 'admin') {
            return ajax_response(false, 'Only admins can delete teachers', null, 403);
        }

        if (!$this->userModel->update($id, ['is_active' => false])) {
            return ajax_response(false, 'Failed to delete teacher', null, 400);
        }

        return ajax_response(true, 'Teacher deleted successfully');
    }
}

