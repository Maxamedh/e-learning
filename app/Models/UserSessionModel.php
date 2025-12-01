<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSessionModel extends Model
{
    protected $table = 'user_sessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'session_token', 'device_type', 'ip_address', 
        'user_agent', 'expires_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function getActiveSessions($userId)
    {
        return $this->where('user_id', $userId)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function invalidateSession($sessionToken)
    {
        return $this->where('session_token', $sessionToken)
            ->set(['expires_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    public function cleanupExpiredSessions()
    {
        return $this->where('expires_at <', date('Y-m-d H:i:s'))
            ->delete();
    }
}
