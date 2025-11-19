<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $table            = 'sections';
    protected $primaryKey       = 'section_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'section_id', 'course_id', 'title', 'description', 
        'sort_order', 'is_free_preview'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'course_id' => 'required',
        'title' => 'required|min_length[2]|max_length[200]',
    ];

    public function getSectionsByCourse($courseId)
    {
        return $this->where('course_id', $courseId)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }
}
