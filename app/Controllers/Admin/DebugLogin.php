<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class DebugLogin extends BaseController
{
    public function test()
    {
        if (ENVIRONMENT === 'production') {
            return 'Not available';
        }

        $output = "<h2>Login Debug Test</h2>";
        
        // Test 1: Check if user exists
        $userModel = new UserModel();
        $user = $userModel->where('email', 'admin@elooxacademy.com')->first();
        
        if ($user) {
            $output .= "<p>✓ User found: " . $user['email'] . "</p>";
            $output .= "<p>Role: " . $user['role'] . "</p>";
            $output .= "<p>Is Active: " . ($user['is_active'] ? 'Yes' : 'No') . "</p>";
            
            // Test 2: Password verification
            $testPass = 'admin123';
            $verify = password_verify($testPass, $user['password_hash']);
            $output .= "<p>Password 'admin123' verification: " . ($verify ? '✓ PASS' : '✗ FAIL') . "</p>";
            
            // Test 3: Session
            $session = \Config\Services::session();
            $output .= "<p>User in session: " . ($session->has('user') ? 'Yes' : 'No') . "</p>";
            if ($session->has('user')) {
                $sessionUser = $session->get('user');
                $output .= "<p>Session user data: " . print_r($sessionUser, true) . "</p>";
            }
            
            // Test 4: Try to set session
            $testData = ['test' => 'value'];
            $session->set('test', $testData);
            $output .= "<p>Session set test: " . ($session->has('test') ? '✓ PASS' : '✗ FAIL') . "</p>";
            
            // Test 5: Routes
            $output .= "<p>Base URL: " . base_url() . "</p>";
            $output .= "<p>Admin Dashboard URL: " . base_url('admin/dashboard') . "</p>";
            
        } else {
            $output .= "<p>✗ User NOT found</p>";
        }
        
        $output .= "<hr>";
        $output .= "<p><a href='" . base_url('admin/login') . "'>Go to Login</a></p>";
        
        return $output;
    }
}

