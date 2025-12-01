<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        // Debug: Log session ID and all session data
        log_message('debug', 'AdminAuth Filter - Session ID: ' . session_id());
        log_message('debug', 'AdminAuth Filter - All session keys: ' . implode(', ', array_keys($_SESSION ?? [])));
        
        $user = $session->get('user');
        
        // Check if user is logged in
        if (!$user) {
            log_message('debug', 'AdminAuth: No user in session. Session data: ' . json_encode($_SESSION ?? []));
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'success' => false,
                    'message' => 'Authentication required',
                    'redirect' => base_url('admin/login')
                ])->setStatusCode(401);
            }
            return redirect()->to('admin/login')->with('error', 'Please login to continue');
        }
        
        log_message('debug', 'AdminAuth: User found in session - ID: ' . ($user['id'] ?? 'none') . ', Role: ' . ($user['role'] ?? 'none'));

        // Check if user is admin or instructor (staff)
        if (!isset($user['role']) || !in_array($user['role'], ['admin', 'instructor'])) {
            log_message('debug', 'AdminAuth: Invalid role - ' . ($user['role'] ?? 'none'));
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'success' => false,
                    'message' => 'Access denied. Admin privileges required.',
                    'redirect' => base_url('portal')
                ])->setStatusCode(403);
            }
            return redirect()->to('portal')->with('error', 'Access denied. Admin privileges required.');
        }

        // Verify user is still active
        $userModel = new UserModel();
        $currentUser = $userModel->find($user['id']);
        
        if (!$currentUser || !$currentUser['is_active']) {
            $session->destroy();
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'success' => false,
                    'message' => 'Your account has been deactivated',
                    'redirect' => base_url('admin/login')
                ])->setStatusCode(403);
            }
            return redirect()->to('admin/login')->with('error', 'Your account has been deactivated');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

