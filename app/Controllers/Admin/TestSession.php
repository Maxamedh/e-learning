<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class TestSession extends BaseController
{
    public function index()
    {
        if (ENVIRONMENT === 'production') {
            return 'Not available';
        }

        $session = \Config\Services::session();
        
        $output = "<h2>Session Test</h2>";
        $output .= "<p>Session ID: " . session_id() . "</p>";
        $output .= "<p>Session Cookie Name: ci_session</p>";
        
        // Check if user is in session
        $user = $session->get('user');
        $output .= "<p>User in session: " . ($user ? 'YES' : 'NO') . "</p>";
        
        if ($user) {
            $output .= "<pre>" . print_r($user, true) . "</pre>";
        }
        
        // Try to set a test value
        if ($this->request->getGet('set') === '1') {
            $session->set('test_value', 'test123');
            $output .= "<p style='color:green;'>✓ Test value set!</p>";
            $output .= "<p><a href='?check=1'>Check if value persists</a></p>";
        }
        
        if ($this->request->getGet('check') === '1') {
            $testValue = $session->get('test_value');
            $output .= "<p>Test value: " . ($testValue ? $testValue : 'NOT FOUND') . "</p>";
        } else {
            $output .= "<p><a href='?set=1'>Set Test Value</a></p>";
        }
        
        // Show all session data
        $output .= "<h3>All Session Data:</h3>";
        $output .= "<pre>" . print_r($_SESSION ?? [], true) . "</pre>";
        
        $output .= "<hr>";
        $output .= "<p><a href='" . base_url('admin/login') . "'>Go to Login</a></p>";
        
        return $output;
    }
}

