<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscussionModel extends Model
{
    protected $table            = 'discussions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id', 'user_id', 'title', 'content', 
        'is_question', 'is_pinned', 'is_resolved', 'upvotes', 'downvotes'
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
        'is_question' => 'permit_empty',
    ];

    protected $skipValidation = false;

    public function getDiscussionsWithUser($courseId, $limit = null)
    {
        $builder = $this->db->table('discussions d');
        $builder->select('d.*, u.first_name, u.last_name, u.profile_picture, u.role');
        $builder->join('users u', 'u.id = d.user_id', 'left');
        $builder->where('d.course_id', $courseId);
        $builder->orderBy('d.is_pinned', 'DESC');
        $builder->orderBy('d.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
}
