<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\OrderModel;
use App\Models\CategoryModel;
use App\Models\NotificationModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $courseModel;
    protected $enrollmentModel;
    protected $orderModel;
    protected $categoryModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->orderModel = new OrderModel();
        $this->categoryModel = new CategoryModel();
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        // Get statistics
        $data = [
            'title' => 'Admin Dashboard',
            'stats' => $this->getDashboardStats(),
            'recentCourses' => $this->getRecentCourses(),
            'recentEnrollments' => $this->getRecentEnrollments(),
            'recentOrders' => $this->getRecentOrders(),
            'topCourses' => $this->getTopCourses(),
            'recentNotifications' => $this->getRecentNotifications(),
            'unreadNotificationCount' => $this->getUnreadNotificationCount(),
        ];
        
        return view('pages/dashboard', $data);
    }

    private function getDashboardStats()
    {
        $db = \Config\Database::connect();
        
        return [
            'total_students' => $this->userModel->where('role', 'student')->countAllResults(),
            'total_instructors' => $this->userModel->where('role', 'instructor')->countAllResults(),
            'total_courses' => $this->courseModel->countAllResults(),
            'published_courses' => $this->courseModel->where('status', 'published')->countAllResults(),
            'total_enrollments' => $this->enrollmentModel->countAllResults(),
            'total_orders' => $this->orderModel->countAllResults(),
            'total_revenue' => $this->orderModel->selectSum('final_amount')
                ->where('status', 'completed')
                ->get()
                ->getRowArray()['final_amount'] ?? 0,
            'pending_orders' => $this->orderModel->where('status', 'pending')->countAllResults(),
            'total_categories' => $this->categoryModel->countAllResults(),
        ];
    }

    private function getRecentCourses($limit = 5)
    {
        return $this->courseModel->select('courses.*, users.first_name, users.last_name, categories.name as category_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->orderBy('courses.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    private function getRecentEnrollments($limit = 5)
    {
        return $this->enrollmentModel->select('enrollments.*, users.first_name, users.last_name, courses.title as course_title')
            ->join('users', 'users.id = enrollments.user_id', 'left')
            ->join('courses', 'courses.id = enrollments.course_id', 'left')
            ->orderBy('enrollments.enrolled_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    private function getRecentOrders($limit = 5)
    {
        return $this->orderModel->select('orders.*, users.first_name, users.last_name')
            ->join('users', 'users.id = orders.user_id', 'left')
            ->orderBy('orders.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    private function getTopCourses($limit = 5)
    {
        return $this->courseModel->select('courses.*, users.first_name, users.last_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->where('courses.status', 'published')
            ->orderBy('courses.total_students', 'DESC')
            ->orderBy('courses.avg_rating', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    private function getRecentNotifications($limit = 5)
    {
        // Get all admin users
        $adminUsers = $this->userModel->where('role', 'admin')->findAll();
        $adminIds = array_column($adminUsers, 'id');

        if (empty($adminIds)) {
            return [];
        }

        return $this->notificationModel->select('notifications.*, users.first_name, users.last_name')
            ->join('users', 'users.id = notifications.user_id', 'left')
            ->whereIn('notifications.user_id', $adminIds)
            ->orderBy('notifications.sent_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    private function getUnreadNotificationCount()
    {
        // Get all admin users
        $adminUsers = $this->userModel->where('role', 'admin')->findAll();
        $adminIds = array_column($adminUsers, 'id');

        if (empty($adminIds)) {
            return 0;
        }

        return $this->notificationModel->whereIn('user_id', $adminIds)
            ->where('is_read', false)
            ->countAllResults();
    }
}

