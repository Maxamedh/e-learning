<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Models\OrderModel;

class Notifications extends BaseController
{
    protected $notificationModel;
    protected $userModel;
    protected $orderModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $session = \Config\Services::session();
        $admin = $session->get('admin');
        
        if (!$admin) {
            return redirect()->to('admin/login');
        }

        $filter = $this->request->getGet('filter') ?? 'all'; // all, unread, read
        $type = $this->request->getGet('type') ?? 'all'; // all, order, enrollment, discussion

        // Get all admin users to show notifications for all admins
        $adminUsers = $this->userModel->where('role', 'admin')->findAll();
        $adminIds = array_column($adminUsers, 'id');

        $builder = $this->notificationModel->select('notifications.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.id = notifications.user_id', 'left')
            ->whereIn('notifications.user_id', $adminIds);

        // Apply filters
        if ($filter === 'unread') {
            $builder->where('notifications.is_read', false);
        } elseif ($filter === 'read') {
            $builder->where('notifications.is_read', true);
        }

        if ($type !== 'all') {
            $builder->where('notifications.type', $type);
        }

        $notifications = $builder->orderBy('notifications.sent_at', 'DESC')->findAll();

        // Get unread count
        $unreadCount = $this->notificationModel
            ->whereIn('user_id', $adminIds)
            ->where('is_read', false)
            ->countAllResults();

        $data = [
            'title' => 'Notifications',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'filter' => $filter,
            'type' => $type,
        ];

        return view('admin/notifications/index', $data);
    }

    public function markAsRead($id)
    {
        $notification = $this->notificationModel->find($id);
        
        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }

        $this->notificationModel->update($id, ['is_read' => true]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $session = \Config\Services::session();
        $admin = $session->get('admin');
        
        if (!$admin) {
            return redirect()->to('admin/login');
        }

        // Get all admin users
        $adminUsers = $this->userModel->where('role', 'admin')->findAll();
        $adminIds = array_column($adminUsers, 'id');

        $this->notificationModel->whereIn('user_id', $adminIds)
            ->where('is_read', false)
            ->set(['is_read' => true])
            ->update();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function delete($id)
    {
        $notification = $this->notificationModel->find($id);
        
        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }

        $this->notificationModel->delete($id);

        return redirect()->back()->with('success', 'Notification deleted.');
    }

    public function view($id)
    {
        $notification = $this->notificationModel->select('notifications.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.id = notifications.user_id', 'left')
            ->find($id);

        if (!$notification) {
            return redirect()->to('admin/notifications')->with('error', 'Notification not found.');
        }

        // Mark as read when viewing
        if (!$notification['is_read']) {
            $this->notificationModel->update($id, ['is_read' => true]);
            $notification['is_read'] = true;
        }

        // Get related entity details if available
        $relatedData = null;
        if ($notification['related_entity_type'] === 'order' && $notification['related_entity_id']) {
            $relatedData = $this->orderModel->getOrderWithItems($notification['related_entity_id']);
        }

        $data = [
            'title' => 'Notification Details',
            'notification' => $notification,
            'relatedData' => $relatedData,
        ];

        return view('admin/notifications/view', $data);
    }
}

