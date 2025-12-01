<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Setup extends BaseController
{
    /**
     * Create initial admin user
     * Access this once to create your admin account
     * URL: /admin/setup/create-admin
     */
    public function createAdmin()
    {
        // Security: Only allow if no admin exists yet
        $userModel = new UserModel();
        $existingAdmin = $userModel->where('role', 'admin')->first();
        
        if ($existingAdmin) {
            return redirect()->to('admin/login')
                ->with('error', 'Admin user already exists. Please login instead.');
        }

        // Get data from request
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');

        // Validate
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
        ]);

        if (!$validation->run($this->request->getPost())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Create admin user
        helper('uuid');
        $userData = [
            'uuid' => generate_uuid(),
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'admin',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'is_active' => true,
            'email_verified' => true,
        ];

        if ($userModel->insert($userData)) {
            return redirect()->to('admin/login')
                ->with('success', 'Admin account created successfully! Email: ' . $email . ', Password: ' . $password);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create admin user: ' . implode(', ', $userModel->errors()));
    }

    /**
     * Setup page - shows form to create admin
     */
    public function index()
    {
        $userModel = new UserModel();
        $existingAdmin = $userModel->where('role', 'admin')->first();
        
        if ($existingAdmin) {
            return redirect()->to('admin/login')
                ->with('info', 'Admin user already exists. Please login.');
        }

        $data['title'] = 'Setup Admin Account';
        return view('admin/setup', $data);
    }
}

