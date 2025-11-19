<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserApiController extends BaseApiController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $this->authenticate();
        if (!$this->requirePermission(['admin'])) return;

        $page = $this->request->getGet('page') ?? 1;
        $limit = $this->request->getGet('limit') ?? 10;
        $type = $this->request->getGet('user_type');
        $search = $this->request->getGet('search');

        $builder = $this->userModel;
        if ($type) $builder->where('user_type', $type);
        if ($search) {
            $builder->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        $total = $builder->countAllResults(false);
        $users = $builder->orderBy('created_at', 'DESC')->paginate($limit, 'default', $page);

        // Remove password hashes
        foreach ($users as &$user) {
            unset($user['password_hash']);
        }

        return $this->respond([
            'status' => 'success',
            'data' => $users,
            'pagination' => ['current_page' => $page, 'total_pages' => ceil($total / $limit), 'total_items' => $total]
        ]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        // Users can view their own profile, admin can view any
        if ($this->currentUser['user_id'] !== $id && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        $user = $this->userModel->find($id);
        if (!$user) return $this->failNotFound('User not found');
        unset($user['password_hash']);
        return $this->respond(['status' => 'success', 'data' => $user]);
    }

    public function create()
    {
        $this->authenticate();
        if (!$this->requirePermission(['admin'])) return;

        $data = $this->request->getJSON(true);
        helper('uuid');
        $data['user_id'] = generate_uuid();
        if (isset($data['password'])) {
            $data['password_hash'] = $data['password'];
            unset($data['password']);
        }

        if ($this->userModel->insert($data)) {
            $user = $this->userModel->find($data['user_id']);
            unset($user['password_hash']);
            return $this->respondCreated(['status' => 'success', 'message' => 'User created', 'data' => $user]);
        }
        return $this->failValidationErrors($this->userModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        if ($this->currentUser['user_id'] !== $id && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        $data = $this->request->getJSON(true);
        if (isset($data['password'])) {
            $data['password_hash'] = $data['password'];
            unset($data['password']);
        }

        if ($this->userModel->update($id, $data)) {
            $user = $this->userModel->find($id);
            unset($user['password_hash']);
            return $this->respond(['status' => 'success', 'message' => 'User updated', 'data' => $user]);
        }
        return $this->failValidationErrors($this->userModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        if (!$this->requirePermission(['admin'])) return;
        if ($this->userModel->delete($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'User deleted']);
        }
        return $this->fail('Failed to delete user');
    }
}
