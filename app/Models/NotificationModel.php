<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'title', 'message', 'type',
        'related_entity_type', 'related_entity_id', 'is_read'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    
    protected $validationRules = [
        'user_id' => 'required|integer',
        'title' => 'required|max_length[255]',
        'message' => 'required',
        'type' => 'in_list[system,course,payment,announcement]',
    ];
}

