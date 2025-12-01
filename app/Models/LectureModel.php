<?php

namespace App\Models;

use CodeIgniter\Model;

class LectureModel extends Model
{
    protected $table            = 'lectures';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'section_id', 'title', 'description', 'content_type', 
        'video_url', 'video_duration', 'article_content', 'resources', 
        'order_index', 'is_preview', 'is_published'
    ];

    protected $useTimestamps = false; // Disable auto timestamps - database handles it with DEFAULT CURRENT_TIMESTAMP
    protected $dateFormat    = 'datetime';
    protected $createdField  = null;
    protected $updatedField  = null;

    protected $validationRules = [
        'section_id' => 'required',
        'title' => 'required|min_length[2]|max_length[255]',
        'content_type' => 'in_list[video,article,quiz,assignment,live]',
    ];

    public function getLecturesBySection($sectionId)
    {
        return $this->where('section_id', $sectionId)
            ->where('is_published', true)
            ->orderBy('order_index', 'ASC')
            ->findAll();
    }

    public function getAllLecturesBySection($sectionId)
    {
        return $this->where('section_id', $sectionId)
            ->orderBy('order_index', 'ASC')
            ->findAll();
    }

    public function getCourseLectures($courseId)
    {
        return $this->select('lectures.*, course_sections.title as section_title')
            ->join('course_sections', 'course_sections.id = lectures.section_id')
            ->where('course_sections.course_id', $courseId)
            ->where('lectures.is_published', true)
            ->orderBy('course_sections.order_index', 'ASC')
            ->orderBy('lectures.order_index', 'ASC')
            ->findAll();
    }
}
