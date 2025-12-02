<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\EnrollmentModel;
use App\Models\LectureProgressModel;
use App\Models\LectureModel;
use App\Models\CourseModel;

class ProgressController extends BaseApiController
{
    protected $enrollmentModel;
    protected $progressModel;
    protected $lectureModel;
    protected $courseModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->progressModel = new LectureProgressModel();
        $this->lectureModel = new LectureModel();
        $this->courseModel = new CourseModel();
    }

    /**
     * Mark lecture as started
     */
    public function start()
    {
        // Try session authentication first (for portal)
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user) {
            // Try API token authentication
            $this->authenticate();
            if (!$this->requirePermission(['student', 'admin'])) return;
            $userId = $this->currentUser['user_id'] ?? $this->currentUser['id'] ?? null;
        } else {
            $userId = $user['id'];
        }

        if (!$userId) {
            return $this->fail('Authentication required', 401);
        }

        $data = $this->request->getJSON(true);
        $lectureId = $data['lecture_id'] ?? null;
        $courseId = $data['course_id'] ?? null;

        if (!$lectureId || !$courseId) {
            return $this->fail('Lecture ID and Course ID are required', 400);
        }

        // Get enrollment
        $enrollment = $this->enrollmentModel->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return $this->fail('Enrollment not found', 404);
        }

        // Get lecture
        $lecture = $this->lectureModel->find($lectureId);
        if (!$lecture) {
            return $this->fail('Lecture not found', 404);
        }

        // Check if progress exists
        $progress = $this->progressModel->where('enrollment_id', $enrollment['id'])
            ->where('lecture_id', $lectureId)
            ->first();

        if (!$progress) {
            // Create new progress record
            $progressData = [
                'enrollment_id' => $enrollment['id'],
                'lecture_id' => $lectureId,
                'is_completed' => 0,
                'video_progress' => 0,
                'total_video_duration' => $lecture['video_duration'] ?? 0,
                'last_position' => 0,
            ];

            if (!$this->progressModel->insert($progressData)) {
                return $this->fail('Failed to create progress record', 500);
            }
        }

        return $this->respond(['status' => 'success', 'message' => 'Progress started']);
    }

    /**
     * Update video progress
     */
    public function update()
    {
        // Try session authentication first (for portal)
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user) {
            // Try API token authentication
            $this->authenticate();
            if (!$this->requirePermission(['student', 'admin'])) return;
            $userId = $this->currentUser['user_id'] ?? $this->currentUser['id'] ?? null;
        } else {
            $userId = $user['id'];
        }

        if (!$userId) {
            return $this->fail('Authentication required', 401);
        }

        $data = $this->request->getJSON(true);
        $lectureId = $data['lecture_id'] ?? null;
        $courseId = $data['course_id'] ?? null;
        $videoProgress = $data['video_progress'] ?? 0;
        $lastPosition = $data['last_position'] ?? 0;
        $totalDuration = $data['total_duration'] ?? 0;

        if (!$lectureId || !$courseId) {
            return $this->fail('Lecture ID and Course ID are required', 400);
        }

        // Get enrollment
        $enrollment = $this->enrollmentModel->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return $this->fail('Enrollment not found', 404);
        }

        // Get or create progress
        $progress = $this->progressModel->where('enrollment_id', $enrollment['id'])
            ->where('lecture_id', $lectureId)
            ->first();

        $updateData = [
            'video_progress' => (int)$videoProgress,
            'last_position' => (int)$lastPosition,
        ];

        if ($totalDuration > 0) {
            $updateData['total_video_duration'] = (int)$totalDuration;
        }

        // Mark as completed if video progress is >= 90% of total duration
        if ($totalDuration > 0 && $videoProgress > 0) {
            $progressPercentage = ($videoProgress / $totalDuration) * 100;
            $isCompleted = ($progress['is_completed'] ?? 0) == 1;
            if ($progressPercentage >= 90 && !$isCompleted) {
                $updateData['is_completed'] = 1;
                $updateData['completed_at'] = date('Y-m-d H:i:s');
            }
        }

        if ($progress) {
            // Update existing progress
            if (!$this->progressModel->update($progress['id'], $updateData)) {
                return $this->fail('Failed to update progress', 500);
            }
        } else {
            // Create new progress
            $updateData['enrollment_id'] = $enrollment['id'];
            $updateData['lecture_id'] = $lectureId;
            $updateData['is_completed'] = $updateData['is_completed'] ?? 0;
            if (!$this->progressModel->insert($updateData)) {
                return $this->fail('Failed to create progress', 500);
            }
        }

        // Update enrollment progress percentage
        $this->updateEnrollmentProgress($enrollment['id'], $courseId);

        return $this->respond(['status' => 'success', 'message' => 'Progress updated']);
    }

    /**
     * Mark lecture as complete
     */
    public function complete()
    {
        // Try session authentication first (for portal)
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user) {
            // Try API token authentication
            $this->authenticate();
            if (!$this->requirePermission(['student', 'admin'])) return;
            $userId = $this->currentUser['user_id'] ?? $this->currentUser['id'] ?? null;
        } else {
            $userId = $user['id'];
        }

        if (!$userId) {
            return $this->fail('Authentication required', 401);
        }

        $data = $this->request->getJSON(true);
        $lectureId = $data['lecture_id'] ?? null;
        $courseId = $data['course_id'] ?? null;

        if (!$lectureId || !$courseId) {
            return $this->fail('Lecture ID and Course ID are required', 400);
        }

        // Get enrollment
        $enrollment = $this->enrollmentModel->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return $this->fail('Enrollment not found', 404);
        }

        // Get or create progress
        $progress = $this->progressModel->where('enrollment_id', $enrollment['id'])
            ->where('lecture_id', $lectureId)
            ->first();

        $updateData = [
            'is_completed' => 1,
            'completed_at' => date('Y-m-d H:i:s'),
        ];

        if ($progress) {
            // Update existing progress
            if (!$this->progressModel->update($progress['id'], $updateData)) {
                return $this->fail('Failed to update progress', 500);
            }
        } else {
            // Create new progress
            $lecture = $this->lectureModel->find($lectureId);
            $updateData['enrollment_id'] = $enrollment['id'];
            $updateData['lecture_id'] = $lectureId;
            $updateData['video_progress'] = $lecture['video_duration'] ?? 0;
            $updateData['total_video_duration'] = $lecture['video_duration'] ?? 0;
            $updateData['last_position'] = $lecture['video_duration'] ?? 0;
            
            if (!$this->progressModel->insert($updateData)) {
                return $this->fail('Failed to create progress', 500);
            }
        }

        // Update enrollment progress percentage
        $this->updateEnrollmentProgress($enrollment['id'], $courseId);

        return $this->respond(['status' => 'success', 'message' => 'Lecture marked as complete']);
    }

    /**
     * Update enrollment progress percentage
     */
    private function updateEnrollmentProgress($enrollmentId, $courseId)
    {
        // Get total lectures for course
        $totalLectures = $this->lectureModel->select('COUNT(*) as total')
            ->join('course_sections', 'course_sections.id = lectures.section_id')
            ->where('course_sections.course_id', $courseId)
            ->where('lectures.is_published', 1)
            ->first();

        $totalLecturesCount = $totalLectures['total'] ?? 0;

        if ($totalLecturesCount > 0) {
            // Get completed lectures
            $completedLectures = $this->progressModel->where('enrollment_id', $enrollmentId)
                ->where('is_completed', 1)
                ->countAllResults();

            // Calculate progress percentage
            $progressPercentage = ($completedLectures / $totalLecturesCount) * 100;

            // Update enrollment
            $this->enrollmentModel->update($enrollmentId, [
                'progress_percentage' => round($progressPercentage, 2)
            ]);

            // Mark as completed if 100%
            if ($progressPercentage >= 100) {
                $this->enrollmentModel->update($enrollmentId, [
                    'completed_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}

