<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\Api\AuthController;

class BaseApiController extends BaseController
{
    use ResponseTrait;

    protected $currentUser = null;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        // Authentication will be called manually in each method or via beforeFilter
    }

    /**
     * Authenticate request using token
     */
    protected function authenticate()
    {
        $token = $this->request->getHeaderLine('X-Auth-Token');
        $username = $this->request->getHeaderLine('X-Username');

        if (empty($token) || empty($username)) {
            $this->failUnauthorized('Authentication required');
            return;
        }

        $authController = new AuthController();
        $user = $authController->validateToken($token, $username);

        if (!$user) {
            $this->failUnauthorized('Invalid or expired token');
            return;
        }

        $this->currentUser = $user;
    }

    /**
     * Get current authenticated user
     */
    protected function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Check if user has permission
     */
    protected function hasPermission($requiredType)
    {
        if (!$this->currentUser) {
            return false;
        }

        if (is_array($requiredType)) {
            return in_array($this->currentUser['user_type'], $requiredType);
        }

        return $this->currentUser['user_type'] === $requiredType;
    }

    /**
     * Require permission or fail
     */
    protected function requirePermission($requiredType)
    {
        if (!$this->hasPermission($requiredType)) {
            $this->failForbidden('Insufficient permissions');
            return false;
        }
        return true;
    }
}

