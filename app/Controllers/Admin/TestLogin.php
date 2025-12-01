<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class TestLogin extends BaseController
{
    public function index()
    {
        // Only for development
        if (ENVIRONMENT === 'production') {
            return 'Not available in production';
        }

        $userModel = new UserModel();
        $admin = $userModel->where('email', 'admin@elooxacademy.com')->first();
        
        $output = "<h2>Admin User Check</h2>";
        
        if ($admin) {
            $output .= "<p>✓ Admin user found</p>";
            $output .= "<p>ID: " . $admin['id'] . "</p>";
            $output .= "<p>Email: " . $admin['email'] . "</p>";
            $output .= "<p>Role: " . $admin['role'] . "</p>";
            $output .= "<p>Is Active: " . ($admin['is_active'] ? 'Yes' : 'No') . "</p>";
            $output .= "<p>Password Hash Length: " . strlen($admin['password_hash']) . "</p>";
            $output .= "<p>Password Hash (first 20 chars): " . substr($admin['password_hash'], 0, 20) . "...</p>";
            
            // Test password verification
            $testPassword = 'admin123';
            $verify = password_verify($testPassword, $admin['password_hash']);
            $output .= "<p>Password 'admin123' verification: " . ($verify ? '✓ CORRECT' : '✗ FAILED') . "</p>";
            
            if (!$verify) {
                $output .= "<h3>Fixing password...</h3>";
                $newHash = password_hash($testPassword, PASSWORD_DEFAULT);
                $userModel->update($admin['id'], ['password_hash' => $newHash]);
                $output .= "<p>✓ Password hash updated</p>";
                $output .= "<p>New hash verification: " . (password_verify($testPassword, $newHash) ? '✓ CORRECT' : '✗ FAILED') . "</p>";
            }
        } else {
            $output .= "<p>✗ Admin user NOT found</p>";
            $output .= "<p><a href='" . base_url('setup-database') . "'>Setup Database</a></p>";
        }
        
        $output .= "<hr>";
        $output .= "<p><a href='" . base_url('admin/login') . "'>Go to Login</a></p>";
        
        return $output;
    }
}

