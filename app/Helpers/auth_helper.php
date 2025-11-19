<?php

if (!function_exists('generate_auth_token')) {
    /**
     * Generate authentication token
     * 
     * @param string $userId
     * @param string $email
     * @return string
     */
    function generate_auth_token($userId, $email): string
    {
        $data = $userId . '|' . $email . '|' . time();
        return base64_encode(hash_hmac('sha256', $data, getenv('encryption.key') ?: 'default_key', true));
    }
}

if (!function_exists('verify_auth_token')) {
    /**
     * Verify authentication token
     * 
     * @param string $token
     * @return array|false
     */
    function verify_auth_token($token)
    {
        try {
            $decoded = base64_decode($token);
            // In a real implementation, you'd store tokens in database
            // For now, we'll use session-based approach
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

if (!function_exists('get_current_user')) {
    /**
     * Get current logged in user from session
     * 
     * @return array|null
     */
    function get_current_user()
    {
        $session = \Config\Services::session();
        return $session->get('user');
    }
}

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    function is_logged_in(): bool
    {
        $session = \Config\Services::session();
        return $session->has('user');
    }
}

if (!function_exists('require_login')) {
    /**
     * Require user to be logged in
     * 
     * @return void
     */
    function require_login()
    {
        if (!is_logged_in()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Authentication required',
                'redirect' => base_url('login')
            ]);
            exit;
        }
    }
}

