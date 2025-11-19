<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\NotificationModel;

class NotificationApiController extends BaseApiController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new \App\Models\NotificationModel();
    }

    public function index()
    {
        $this->authenticate();
        $user_id = $this->currentUser['user_id'];
        $is_read = $this->request->getGet('is_read');

        $builder = $this->notificationModel->where('user_id', $user_id);
        if ($is_read !== null) {
            $builder->where('is_read', $is_read === 'true' ? 1 : 0);
        }

        $notifications = $builder->orderBy('created_at', 'DESC')->findAll();
        return $this->respond(['status' => 'success', 'data' => $notifications]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $notification = $this->notificationModel->find($id);
        if (!$notification) return $this->failNotFound('Notification not found');

        if ($notification['user_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('Access denied');
        }

        // Mark as read
        if (!$notification['is_read']) {
            $this->notificationModel->update($id, ['is_read' => true]);
        }

        return $this->respond(['status' => 'success', 'data' => $notification]);
    }

    public function update($id = null)
    {
        $this->authenticate();
        $notification = $this->notificationModel->find($id);
        if (!$notification) return $this->failNotFound('Notification not found');

        if ($notification['user_id'] !== $this->currentUser['user_id']) {
            return $this->failForbidden('Access denied');
        }

        $data = $this->request->getJSON(true);
        if ($this->notificationModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Notification updated', 'data' => $this->notificationModel->find($id)]);
        }
        return $this->failValidationErrors($this->notificationModel->errors());
    }
}

