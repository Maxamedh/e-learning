<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $role = $this->request->getGet('role');
        $search = $this->request->getGet('search');
        
        // Create a new query builder instance
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        
        // Apply role filter
        if (!empty($role) && $role !== 'all' && $role !== '') {
            $builder->where('role', $role);
        }
        
        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }
        
        // Get results
        $users = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
        
        $data = [
            'title' => 'Users Management',
            'users' => $users,
            'role' => $role ?? '',
            'search' => $search ?? '',
        ];
        
        return view('admin/users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create User',
        ];
        
        return view('admin/users/create', $data);
    }

    public function store()
    {
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'role' => 'required|in_list[student,instructor,admin]',
        ];
        
        // Add file validation only if file is uploaded
        $profileFile = $this->request->getFile('profile_picture');
        if ($profileFile && $profileFile->isValid() && !$profileFile->hasMoved()) {
            $rules['profile_picture'] = 'uploaded[profile_picture]|max_size[profile_picture,2048]|ext_in[profile_picture,jpg,jpeg,png,gif]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle profile picture upload
        $profilePictureUrl = $this->request->getPost('profile_picture_url');
        if ($profileFile && $profileFile->isValid() && !$profileFile->hasMoved()) {
            $newName = $profileFile->getRandomName();
            $profileFile->move(ROOTPATH . 'public/uploads/profiles/', $newName);
            $profilePictureUrl = base_url('uploads/profiles/' . $newName);
        }
        
        $data = [
            'email' => $this->request->getPost('email'),
            'password_hash' => $this->request->getPost('password'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role' => $this->request->getPost('role'),
            'phone_number' => $this->request->getPost('phone_number'),
            'date_of_birth' => $this->request->getPost('date_of_birth') ?: null,
            'profile_picture' => $profilePictureUrl,
            'bio' => $this->request->getPost('bio'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'email_verified' => $this->request->getPost('email_verified') ? 1 : 0,
        ];
        
        if ($this->userModel->insert($data)) {
            return redirect()->to('admin/users')->with('success', 'User created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create user.');
        }
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User not found.');
        }
        
        $data = [
            'title' => 'Edit User',
            'user' => $user,
        ];
        
        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User not found.');
        }
        
        $rules = [
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'role' => 'required|in_list[student,instructor,admin]',
        ];
        
        // Add file validation only if file is uploaded
        $profileFile = $this->request->getFile('profile_picture');
        if ($profileFile && $profileFile->isValid() && !$profileFile->hasMoved()) {
            $rules['profile_picture'] = 'uploaded[profile_picture]|max_size[profile_picture,2048]|ext_in[profile_picture,jpg,jpeg,png,gif]';
        }
        
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle profile picture upload
        $profilePictureUrl = $this->request->getPost('profile_picture_url') ?: $user['profile_picture'];
        if ($profileFile && $profileFile->isValid() && !$profileFile->hasMoved()) {
            $uploadPath = ROOTPATH . 'public/uploads/profiles/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $newName = $profileFile->getRandomName();
            if ($profileFile->move($uploadPath, $newName)) {
                $profilePictureUrl = rtrim(base_url(), '/') . '/uploads/profiles/' . $newName;
            }
        }
        
        $data = [
            'email' => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role' => $this->request->getPost('role'),
            'phone_number' => $this->request->getPost('phone_number') ?: null,
            'date_of_birth' => $this->request->getPost('date_of_birth') ?: null,
            'profile_picture' => $profilePictureUrl,
            'bio' => $this->request->getPost('bio') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'email_verified' => $this->request->getPost('email_verified') ? 1 : 0,
        ];
        
        // Only update password if provided
        if ($this->request->getPost('password')) {
            $data['password_hash'] = $this->request->getPost('password');
        }
        
        // Skip model validation and use controller validation instead
        $this->userModel->skipValidation(true);
        
        if ($this->userModel->update($id, $data)) {
            return redirect()->to('admin/users')->with('success', 'User updated successfully!');
        } else {
            $errors = $this->userModel->errors();
            log_message('error', 'User update failed: ' . json_encode($errors));
            return redirect()->back()->withInput()->with('error', 'Failed to update user: ' . implode(', ', $errors));
        }
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User not found.');
        }
        
        // Prevent deleting yourself
        $session = \Config\Services::session();
        $currentUser = $session->get('user');
        if ($currentUser && $currentUser['id'] == $id) {
            return redirect()->to('admin/users')->with('error', 'You cannot delete your own account.');
        }
        
        if ($this->userModel->delete($id)) {
            return redirect()->to('admin/users')->with('success', 'User deleted successfully!');
        } else {
            return redirect()->to('admin/users')->with('error', 'Failed to delete user.');
        }
    }
}

