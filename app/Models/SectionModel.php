<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $table            = 'course_sections';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id', 'title', 'description', 'order_index', 'is_published'
    ];

    protected $useTimestamps = false; // Disable auto timestamps - database handles it with DEFAULT CURRENT_TIMESTAMP
    protected $dateFormat    = 'datetime';
    protected $createdField  = null;
    protected $updatedField  = null;

    protected $validationRules = [
        'course_id' => 'required',
        'title' => 'required|min_length[2]|max_length[255]',
    ];

    public function getSectionsByCourse($courseId)
    {
        return $this->where('course_id', $courseId)
            ->where('is_published', true)
            ->orderBy('order_index', 'ASC')
            ->findAll();
    }

    public function getAllSectionsByCourse($courseId)
    {
        return $this->where('course_id', $courseId)
            ->orderBy('order_index', 'ASC')
            ->findAll();
    }
}
