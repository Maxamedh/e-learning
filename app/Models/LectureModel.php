<?php

namespace App\Models;

use CodeIgniter\Model;

class LectureModel extends Model
{
    protected $table            = 'lectures';
    protected $primaryKey       = 'lecture_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'lecture_id', 'section_id', 'title', 'description', 'content_type', 
        'video_url', 'video_duration', 'article_content', 'downloadable_resources', 
        'sort_order', 'is_preview', 'is_published'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'section_id' => 'required',
        'title' => 'required|min_length[2]|max_length[200]',
        'content_type' => 'in_list[video,article,quiz,assignment,live]',
    ];

    public function getLecturesBySection($sectionId)
    {
        return $this->where('section_id', $sectionId)
            ->where('is_published', true)
            ->orderBy('sort_order', 'ASC')
            ->findAll();
    }

    public function getCourseLectures($courseId)
    {
        return $this->select('lectures.*, sections.title as section_title')
            ->join('sections', 'sections.section_id = lectures.section_id')
            ->where('sections.course_id', $courseId)
            ->where('lectures.is_published', true)
            ->orderBy('sections.sort_order', 'ASC')
            ->orderBy('lectures.sort_order', 'ASC')
            ->findAll();
    }
}
