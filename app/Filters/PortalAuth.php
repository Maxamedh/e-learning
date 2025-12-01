<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PortalAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        // Check if user is logged in
        if (!$user) {
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'success' => false,
                    'message' => 'Authentication required',
                    'redirect' => base_url('portal/login')
                ])->setStatusCode(401);
            }
            return redirect()->to('portal/login')->with('error', 'Please login to continue');
        }
        
        // Check if user is student
        if (!isset($user['role']) || $user['role'] !== 'student') {
            if ($request->isAJAX()) {
                return service('response')->setJSON([
                    'success' => false,
                    'message' => 'Access denied. Student account required.',
                    'redirect' => base_url('admin/login')
                ])->setStatusCode(403);
            }
            return redirect()->to('admin/login')->with('error', 'Access denied. Please use student account.');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}

