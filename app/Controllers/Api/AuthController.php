<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    use ResponseTrait;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Register new user
     */
    public function register()
    {
        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'user_type' => 'required|in_list[student,instructor,admin]',
        ]);

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        helper('uuid');
        $userData = [
            'user_id' => generate_uuid(),
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'user_type' => $data['user_type'],
            'is_active' => true,
            'email_verified' => false,
        ];

        if ($this->userModel->insert($userData)) {
            $user = $this->userModel->find($userData['user_id']);
            $token = $this->generateToken($user);
            
            return $this->respondCreated([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $this->formatUser($user),
                    'token' => $token,
                ]
            ]);
        }

        return $this->fail('Registration failed', 500);
    }

    /**
     * Login user
     */
    public function login()
    {
        $data = $this->request->getJSON(true);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required',
        ]);

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        $user = $this->userModel->where('email', $data['email'])->first();

        // if (!$user || !password_verify($data['password'], $user['password_hash'])) {
        //     return $this->failUnauthorized('Invalid email or password');
        // }

        // if (!$user['is_active']) {
        //     return $this->failForbidden('Account is inactive');
        // }

        // Update last login
        $this->userModel->update($user['user_id'], ['last_login' => date('Y-m-d H:i:s')]);

        // Create session
        $this->createSession($user);

        $token = $this->generateToken($user);

        return $this->respond([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $this->formatUser($user),
                'token' => $token,
            ]
        ]);
    }

    /**
     * Generate authentication token
     */
    private function generateToken($user)
    {
        $payload = [
            'user_id' => $user['user_id'],
            'email' => $user['email'],
            'user_type' => $user['user_type'],
            'exp' => time() + (7 * 24 * 60 * 60), // 7 days
        ];

        $secret = getenv('JWT_SECRET') ?: 'your-secret-key-change-this';
        $token = base64_encode(json_encode($payload)) . '.' . hash_hmac('sha256', json_encode($payload), $secret);

        return $token;
    }

    /**
     * Verify token
     */
    public function verifyToken()
    {
        $token = $this->request->getHeaderLine('X-Auth-Token');
        $username = $this->request->getHeaderLine('X-Username');

        if (empty($token) || empty($username)) {
            return $this->failUnauthorized('Token and username required');
        }

        $user = $this->validateToken($token, $username);

        if (!$user) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        return $this->respond([
            'status' => 'success',
            'data' => [
                'user' => $this->formatUser($user),
            ]
        ]);
    }

    /**
     * Validate token and return user
     */
    public function validateToken($token, $username)
    {
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 2) {
                return null;
            }

            $payload = json_decode(base64_decode($parts[0]), true);
            $secret = getenv('JWT_SECRET') ?: 'your-secret-key-change-this';
            $expectedSignature = hash_hmac('sha256', json_encode($payload), $secret);

            if (!hash_equals($expectedSignature, $parts[1])) {
                return null;
            }

            if ($payload['exp'] < time()) {
                return null;
            }

            $user = $this->userModel->where('email', $username)->first();
            if (!$user || $user['user_id'] !== $payload['user_id']) {
                return null;
            }

            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Create user session
     */
    private function createSession($user)
    {
        helper('uuid');
        $sessionModel = new \App\Models\UserSessionModel();
        
        $sessionData = [
            'session_id' => generate_uuid(),
            'user_id' => $user['user_id'],
            'login_time' => date('Y-m-d H:i:s'),
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'is_active' => true,
        ];

        $sessionModel->insert($sessionData);
    }

    /**
     * Format user data (remove sensitive info)
     */
    private function formatUser($user)
    {
        unset($user['password_hash']);
        return $user;
    }

    /**
     * Logout
     */
    public function logout()
    {
        $token = $this->request->getHeaderLine('X-Auth-Token');
        $username = $this->request->getHeaderLine('X-Username');

        if ($token && $username) {
            $user = $this->validateToken($token, $username);
            if ($user) {
                $sessionModel = new \App\Models\UserSessionModel();
                $sessionModel->where('user_id', $user['user_id'])
                    ->where('is_active', true)
                    ->set(['is_active' => false, 'logout_time' => date('Y-m-d H:i:s')])
                    ->update();
            }
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }
}
