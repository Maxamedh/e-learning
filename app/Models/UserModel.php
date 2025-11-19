<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'email', 'password_hash', 'first_name', 'last_name', 
        'user_type', 'profile_picture_url', 'bio', 'date_of_birth', 
        'phone_number', 'country', 'timezone', 'email_verified', 
        'is_active', 'last_login'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'email' => 'required|valid_email|is_unique[users.email,user_id,{user_id}]',
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'user_type' => 'required|in_list[student,instructor,admin]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

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

    public function getActiveUsers($type = null)
    {
        $builder = $this->where('is_active', true);
        if ($type) {
            $builder->where('user_type', $type);
        }
        return $builder->findAll();
    }
}
