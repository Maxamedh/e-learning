<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        $session = \Config\Services::session();
        
        // If already logged in, redirect to dashboard
        if ($session->has('user')) {
            $user = $session->get('user');
            if ($user['role'] === 'student') {
                return redirect()->to('portal/dashboard');
            }
        }

        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            if (empty($email) || empty($password)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Email and password are required');
            }

            $user = $this->userModel->getUserByEmail($email);

            if (!$user) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid email or password');
            }

            // Verify password
            if (!password_verify($password, $user['password_hash'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid email or password');
            }

            if (!$user['is_active']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Your account has been deactivated');
            }

            // Check if user is student
            if ($user['role'] !== 'student') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please use admin login for this account');
            }

            // Update last login
            $this->userModel->update($user['id'], [
                'last_login' => date('Y-m-d H:i:s')
            ]);

            // Create session
            $sessionData = [
                'id' => $user['id'],
                'uuid' => $user['uuid'],
                'email' => $user['email'],
                'role' => $user['role'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'profile_picture' => $user['profile_picture'] ?? null,
            ];

            $session->set('user', $sessionData);
            $session->setFlashdata('success', 'Welcome back, ' . $user['first_name'] . '!');

            return redirect()->to('portal/dashboard');
        }

        $data['title'] = 'Login - E-LOOX Academy';
        return view('portal/auth/login', $data);
    }

    public function register()
    {
        $session = \Config\Services::session();
        
        // If already logged in, redirect to dashboard
        if ($session->has('user')) {
            return redirect()->to('portal/dashboard');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'first_name' => 'required|min_length[2]|max_length[50]',
                'last_name' => 'required|min_length[2]|max_length[50]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]|max_length[255]',
                'confirm_password' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'email' => $this->request->getPost('email'),
                'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'student',
                'is_active' => 1,
                'email_verified' => 0,
            ];

            if ($this->userModel->insert($data)) {
                $session->setFlashdata('success', 'Registration successful! Please login.');
                return redirect()->to('portal/login');
            } else {
                return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
            }
        }

        $data['title'] = 'Register - E-LOOX Academy';
        return view('portal/auth/register', $data);
    }

    public function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        return redirect()->to('portal/login')
            ->with('success', 'You have been logged out successfully');
    }
}

