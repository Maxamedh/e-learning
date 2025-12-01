<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $session = \Config\Services::session();
        $userSession = $session->get('user');
        
        log_message('debug', 'Profile::index called. User session: ' . ($userSession ? 'exists' : 'not found'));
        
        if (!$userSession) {
            log_message('warning', 'Profile access denied - no user session');
            return redirect()->to('admin/login')->with('error', 'Please login to continue');
        }

        $user = $this->userModel->find($userSession['id']);
        
        if (!$user) {
            log_message('error', 'Profile access denied - user not found in database. ID: ' . $userSession['id']);
            return redirect()->to('admin/dashboard')->with('error', 'User not found');
        }

        log_message('debug', 'Profile page loaded for user: ' . $user['email']);

        $data = [
            'title' => 'My Profile',
            'user' => $user,
        ];

        return view('admin/profile/index', $data);
    }

    public function update()
    {
        $session = \Config\Services::session();
        $userSession = $session->get('user');
        
        if (!$userSession) {
            return redirect()->to('admin/login')->with('error', 'Please login to continue');
        }

        $user = $this->userModel->find($userSession['id']);
        
        if (!$user) {
            return redirect()->to('admin/profile')->with('error', 'User not found');
        }

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $user['id'] . ']',
            'phone_number' => 'permit_empty|max_length[20]',
            'bio' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle profile picture upload
        $profilePicture = $user['profile_picture'] ?? null;
        $profilePictureFile = $this->request->getFile('profile_picture');
        
        if ($profilePictureFile && $profilePictureFile->isValid() && !$profilePictureFile->hasMoved()) {
            // Ensure directory exists
            $uploadPath = ROOTPATH . 'public/uploads/profiles/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Delete old profile picture if exists
            if ($profilePicture && file_exists(ROOTPATH . 'public/' . str_replace(base_url(), '', $profilePicture))) {
                $oldFile = ROOTPATH . 'public/' . str_replace(base_url(), '', $profilePicture);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            
            // Move new file
            $newName = $profilePictureFile->getRandomName();
            if ($profilePictureFile->move($uploadPath, $newName)) {
                $profilePicture = rtrim(base_url(), '/') . '/uploads/profiles/' . $newName;
            }
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number') ?: null,
            'bio' => $this->request->getPost('bio') ?: null,
        ];

        if ($profilePicture) {
            $data['profile_picture'] = $profilePicture;
        }

        if ($this->userModel->update($user['id'], $data)) {
            // Update session with new data
            $updatedUser = $this->userModel->find($user['id']);
            $sessionData = [
                'id' => $updatedUser['id'],
                'uuid' => $updatedUser['uuid'],
                'email' => $updatedUser['email'],
                'role' => $updatedUser['role'],
                'first_name' => $updatedUser['first_name'],
                'last_name' => $updatedUser['last_name'],
                'profile_picture' => $updatedUser['profile_picture'] ?? null,
                'is_admin' => $updatedUser['role'] === 'admin',
                'is_instructor' => $updatedUser['role'] === 'instructor',
            ];
            $session->set('user', $sessionData);
            
            return redirect()->to('admin/profile')->with('success', 'Profile updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update profile.');
        }
    }

    public function changePassword()
    {
        $session = \Config\Services::session();
        $userSession = $session->get('user');
        
        if (!$userSession) {
            return redirect()->to('admin/login')->with('error', 'Please login to continue');
        }

        $user = $this->userModel->find($userSession['id']);
        
        if (!$user) {
            return redirect()->to('admin/profile')->with('error', 'User not found');
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]|max_length[255]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verify current password
        if (!password_verify($currentPassword, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if ($this->userModel->update($user['id'], ['password_hash' => $newHash])) {
            return redirect()->to('admin/profile')->with('success', 'Password changed successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to change password.');
        }
    }
}

