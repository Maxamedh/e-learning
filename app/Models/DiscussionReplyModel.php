<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscussionReplyModel extends Model
{
    protected $table = 'discussion_replies';
    protected $primaryKey = 'reply_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'reply_id', 'discussion_id', 'user_id', 'parent_reply_id', 
        'content', 'is_instructor_reply'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

