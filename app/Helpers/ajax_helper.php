<?php

if (!function_exists('ajax_response')) {
    /**
     * Standard AJAX response helper
     * 
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    function ajax_response($success = true, $message = '', $data = null, $statusCode = 200)
    {
        $response = \Config\Services::response();
        $response->setStatusCode($statusCode);
        $response->setHeader('Content-Type', 'application/json');
        
        $output = [
            'success' => $success,
            'message' => $message,
        ];
        
        if ($data !== null) {
            $output['data'] = $data;
        }
        
        return $response->setJSON($output);
    }
}

if (!function_exists('get_ajax_headers')) {
    /**
     * Get AJAX request headers (token and username)
     * 
     * @return array
     */
    function get_ajax_headers()
    {
        $request = \Config\Services::request();
        $session = \Config\Services::session();
        
        $user = $session->get('user');
        
        return [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-Auth-Token' => $request->getHeaderLine('X-Auth-Token') ?: ($user ? generate_auth_token($user['user_id'], $user['email']) : ''),
            'X-Username' => $request->getHeaderLine('X-Username') ?: ($user ? $user['email'] : ''),
            'X-CSRF-TOKEN' => csrf_hash(),
        ];
    }
}

if (!function_exists('validate_ajax_request')) {
    /**
     * Validate AJAX request with token and username
     * 
     * @return bool
     */
    function validate_ajax_request(): bool
    {
        $request = \Config\Services::request();
        
        // Check if it's an AJAX request
        if (!$request->isAJAX()) {
            return false;
        }
        
        // Check CSRF token
        if (!csrf_verify()) {
            return false;
        }
        
        // Check if user is logged in (for protected routes)
        $session = \Config\Services::session();
        if (!$session->has('user')) {
            return false;
        }
        
        // Verify token if provided
        $token = $request->getHeaderLine('X-Auth-Token');
        if ($token && !verify_auth_token($token)) {
            return false;
        }
        
        return true;
    }
}

