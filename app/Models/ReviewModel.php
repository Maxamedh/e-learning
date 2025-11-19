<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'course_reviews';
    protected $primaryKey       = 'review_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id', 'user_id', 'rating', 'review_title', 
        'review_text', 'is_approved'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'rating' => 'int',
        'is_approved' => 'boolean',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'course_id' => 'required',
        'user_id' => 'required',
        'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
    ];

    protected $skipValidation = false;

    public function getReviewsWithUser($courseId, $limit = null)
    {
        $builder = $this->db->table('course_reviews r');
        $builder->select('r.*, u.first_name, u.last_name, u.profile_picture_url');
        $builder->join('users u', 'u.user_id = r.user_id', 'left');
        $builder->where('r.course_id', $courseId);
        $builder->where('r.is_approved', true);
        $builder->orderBy('r.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
}
