<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscussionModel extends Model
{
    protected $table            = 'discussions';
    protected $primaryKey       = 'discussion_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'discussion_id', 'course_id', 'user_id', 'title', 'content', 
        'post_type', 'is_pinned', 'is_resolved', 'view_count'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_pinned' => 'boolean',
        'is_resolved' => 'boolean',
        'view_count' => 'int',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'course_id' => 'required',
        'user_id' => 'required',
        'title' => 'required|min_length[3]|max_length[300]',
        'content' => 'required',
        'post_type' => 'in_list[question,discussion,announcement]',
    ];

    protected $skipValidation = false;

    public function getDiscussionsWithUser($courseId, $limit = null)
    {
        $builder = $this->db->table('discussions d');
        $builder->select('d.*, u.first_name, u.last_name, u.profile_picture_url, u.user_type');
        $builder->join('users u', 'u.user_id = d.user_id', 'left');
        $builder->where('d.course_id', $courseId);
        $builder->orderBy('d.is_pinned', 'DESC');
        $builder->orderBy('d.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
}
