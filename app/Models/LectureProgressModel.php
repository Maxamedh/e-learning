<?php

namespace App\Models;

use CodeIgniter\Model;

class LectureProgressModel extends Model
{
    protected $table            = 'lecture_progress';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'enrollment_id', 'lecture_id', 'is_completed', 
        'video_progress', 'total_video_duration', 'last_position', 'completed_at'
    ];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = null;
    protected $updatedField  = 'updated_at';

    public function getProgressByEnrollment($enrollmentId)
    {
        return $this->where('enrollment_id', $enrollmentId)->findAll();
    }

    public function getProgressByLecture($enrollmentId, $lectureId)
    {
        return $this->where('enrollment_id', $enrollmentId)
            ->where('lecture_id', $lectureId)
            ->first();
    }
}

