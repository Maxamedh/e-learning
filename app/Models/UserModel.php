<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'uuid', 'email', 'password_hash', 'role', 'first_name', 'last_name', 
        'profile_picture', 'phone_number', 'date_of_birth', 'bio', 
        'is_active', 'email_verified', 'last_login'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'role' => 'required|in_list[student,instructor,admin]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateUuid', 'hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function generateUuid(array $data)
    {
        if (!isset($data['data']['uuid']) || empty($data['data']['uuid'])) {
            helper('uuid');
            $data['data']['uuid'] = generate_uuid();
        }
        return $data;
    }

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password_hash']) && !empty($data['data']['password_hash'])) {
            // Only hash if it's not already hashed (check length)
            if (strlen($data['data']['password_hash']) < 60) {
                $data['data']['password_hash'] = password_hash($data['data']['password_hash'], PASSWORD_DEFAULT);
            }
        }
        return $data;
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getUserByUuid($uuid)
    {
        return $this->where('uuid', $uuid)->first();
    }

    public function getActiveUsers($role = null)
    {
        $builder = $this->where('is_active', true);
        if ($role) {
            $builder->where('role', $role);
        }
        return $builder->findAll();
    }

    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }

    public function getAdminUsers()
    {
        return $this->where('role', 'admin')->where('is_active', true)->findAll();
    }

    public function getInstructorUsers()
    {
        return $this->where('role', 'instructor')->where('is_active', true)->findAll();
    }

    public function getStudentUsers()
    {
        return $this->where('role', 'student')->where('is_active', true)->findAll();
    }
}
