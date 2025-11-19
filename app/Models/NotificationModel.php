<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notification_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'notification_id', 'user_id', 'title', 'message', 'notification_type',
        'related_entity_type', 'related_entity_id', 'is_read'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'created_at';
}

