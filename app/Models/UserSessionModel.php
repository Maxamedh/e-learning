<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSessionModel extends Model
{
    protected $table = 'user_sessions';
    protected $primaryKey = 'session_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'session_id', 'user_id', 'login_time', 'logout_time',
        'ip_address', 'user_agent', 'is_active'
    ];

    protected $useTimestamps = false;
}
