<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\CourseReviewModel;
use App\Models\CourseModel;

class ReviewApiController extends BaseApiController
{
    protected $reviewModel;
    protected $courseModel;

    public function __construct()
    {
        $this->reviewModel = new \App\Models\CourseReviewModel();
        $this->courseModel = new \App\Models\CourseModel();
    }

    public function index()
    {
        $this->authenticate();
        $course_id = $this->request->getGet('course_id');
        if (!$course_id) return $this->fail('course_id required', 400);

        $reviews = $this->reviewModel->select('course_reviews.*, users.first_name, users.last_name, users.profile_picture_url')
            ->join('users', 'users.user_id = course_reviews.user_id')
            ->where('course_reviews.course_id', $course_id)
            ->where('course_reviews.is_approved', true)
            ->orderBy('course_reviews.created_at', 'DESC')
            ->findAll();

        return $this->respond(['status' => 'success', 'data' => $reviews]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $review = $this->reviewModel->find($id);
        if (!$review) return $this->failNotFound('Review not found');
        return $this->respond(['status' => 'success', 'data' => $review]);
    }

    public function create()
    {
        $this->authenticate();
        $data = $this->request->getJSON(true);
        $data['user_id'] = $data['user_id'] ?? $this->currentUser['user_id'];

        // Check if user already reviewed
        $existing = $this->reviewModel->where('user_id', $data['user_id'])
            ->where('course_id', $data['course_id'])->first();
        if ($existing) {
            return $this->fail('You have already reviewed this course', 400);
        }

        if ($this->reviewModel->insert($data)) {
            // Update course rating
            $this->updateCourseRating($data['course_id']);

            return $this->respondCreated(['status' => 'success', 'message' => 'Review created', 'data' => $this->reviewModel->find($this->reviewModel->getInsertID())]);
        }
        return $this->failValidationErrors($this->reviewModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        $review = $this->reviewModel->find($id);
        if (!$review) return $this->failNotFound('Review not found');

        if ($review['user_id'] !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        $data = $this->request->getJSON(true);
        if ($this->reviewModel->update($id, $data)) {
            $this->updateCourseRating($review['course_id']);
            return $this->respond(['status' => 'success', 'message' => 'Review updated', 'data' => $this->reviewModel->find($id)]);
        }
        return $this->failValidationErrors($this->reviewModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        $review = $this->reviewModel->find($id);
        if (!$review) return $this->failNotFound('Review not found');

        if ($review['user_id'] !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        $course_id = $review['course_id'];
        if ($this->reviewModel->delete($id)) {
            $this->updateCourseRating($course_id);
            return $this->respondDeleted(['status' => 'success', 'message' => 'Review deleted']);
        }
        return $this->fail('Failed to delete review');
    }

    private function updateCourseRating($course_id)
    {
        $reviews = $this->reviewModel->where('course_id', $course_id)
            ->where('is_approved', true)
            ->findAll();

        $total = count($reviews);
        $avg = $total > 0 ? array_sum(array_column($reviews, 'rating')) / $total : 0;

        $this->courseModel->update($course_id, [
            'avg_rating' => round($avg, 2),
            'total_reviews' => $total
        ]);
    }
}

