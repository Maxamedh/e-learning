<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\DiscussionModel;
use App\Models\DiscussionReplyModel;

class DiscussionApiController extends BaseApiController
{
    protected $discussionModel;
    protected $replyModel;

    public function __construct()
    {
        $this->discussionModel = new DiscussionModel();
        $this->replyModel = new \App\Models\DiscussionReplyModel();
    }

    public function index()
    {
        $this->authenticate();
        $course_id = $this->request->getGet('course_id');
        if (!$course_id) return $this->fail('course_id required', 400);

        $discussions = $this->discussionModel->getDiscussionsWithUser($course_id);
        return $this->respond(['status' => 'success', 'data' => $discussions]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $discussion = $this->discussionModel->select('discussions.*, users.first_name, users.last_name, users.profile_picture_url')
            ->join('users', 'users.user_id = discussions.user_id')
            ->find($id);
        if (!$discussion) return $this->failNotFound('Discussion not found');

        // Get replies
        $replies = $this->replyModel->select('discussion_replies.*, users.first_name, users.last_name, users.profile_picture_url, users.user_type')
            ->join('users', 'users.user_id = discussion_replies.user_id')
            ->where('discussion_replies.discussion_id', $id)
            ->orderBy('discussion_replies.created_at', 'ASC')
            ->findAll();

        $discussion['replies'] = $replies;

        // Increment view count
        $this->discussionModel->update($id, ['view_count' => ($discussion['view_count'] ?? 0) + 1]);

        return $this->respond(['status' => 'success', 'data' => $discussion]);
    }

    public function create()
    {
        $this->authenticate();
        $data = $this->request->getJSON(true);
        $data['user_id'] = $data['user_id'] ?? $this->currentUser['user_id'];

        helper('uuid');
        $data['discussion_id'] = generate_uuid();

        if ($this->discussionModel->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Discussion created', 'data' => $this->discussionModel->find($data['discussion_id'])]);
        }
        return $this->failValidationErrors($this->discussionModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        $discussion = $this->discussionModel->find($id);
        if (!$discussion) return $this->failNotFound('Discussion not found');

        if ($discussion['user_id'] !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        $data = $this->request->getJSON(true);
        if ($this->discussionModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Discussion updated', 'data' => $this->discussionModel->find($id)]);
        }
        return $this->failValidationErrors($this->discussionModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        $discussion = $this->discussionModel->find($id);
        if (!$discussion) return $this->failNotFound('Discussion not found');

        if ($discussion['user_id'] !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        if ($this->discussionModel->delete($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Discussion deleted']);
        }
        return $this->fail('Failed to delete discussion');
    }
}

