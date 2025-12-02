<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CategoryModel;
use App\Models\EnrollmentModel;
use App\Models\SectionModel;
use App\Models\LectureModel;
use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\NotificationModel;

class Courses extends BaseController
{
    protected $courseModel;
    protected $categoryModel;
    protected $enrollmentModel;
    protected $sectionModel;
    protected $lectureModel;
    protected $userModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->sectionModel = new SectionModel();
        $this->lectureModel = new LectureModel();
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $level = $this->request->getGet('level');
        $sort = $this->request->getGet('sort') ?? 'recent';

        $builder = $this->courseModel->select('courses.*, users.first_name, users.last_name, categories.name as category_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->where('courses.status', 'published');

        if ($search) {
            $builder->groupStart()
                ->like('courses.title', $search)
                ->orLike('courses.description', $search)
                ->orLike('users.first_name', $search)
                ->orLike('users.last_name', $search)
                ->groupEnd();
        }

        if ($category && $category !== 'all') {
            $builder->where('courses.category_id', $category);
        }

        if ($level && $level !== 'all') {
            $builder->where('courses.level', $level);
        }

        // Sorting
        switch ($sort) {
            case 'popular':
                $builder->orderBy('courses.total_students', 'DESC');
                break;
            case 'rating':
                $builder->orderBy('courses.avg_rating', 'DESC');
                break;
            case 'price-low':
                $builder->orderBy('courses.price', 'ASC');
                break;
            case 'price-high':
                $builder->orderBy('courses.price', 'DESC');
                break;
            default:
                $builder->orderBy('courses.created_at', 'DESC');
        }

        $courses = $builder->findAll();
        $categories = $this->categoryModel->where('is_active', 1)->findAll();

        $data = [
            'title' => 'Browse Courses - E-LOOX Academy',
            'courses' => $courses,
            'categories' => $categories,
            'search' => $search,
            'category' => $category,
            'level' => $level,
            'sort' => $sort,
        ];

        return view('portal/courses/index', $data);
    }

    public function view($id)
    {
        $course = $this->courseModel->getCourseById($id);
        
        if (!$course || $course['status'] !== 'published') {
            return redirect()->to('courses')->with('error', 'Course not found.');
        }

        // Get instructor
        $instructor = $this->userModel->find($course['instructor_id']);
        
        // Get category
        $category = $this->categoryModel->find($course['category_id']);
        
        // Get sections and lectures
        $sections = $this->sectionModel->getAllSectionsByCourse($id);
        foreach ($sections as &$section) {
            $section['lectures'] = $this->lectureModel->getAllLecturesBySection($section['id']);
        }

        // Check if user is enrolled
        $isEnrolled = false;
        $session = \Config\Services::session();
        $user = $session->get('user');
        if ($user && $user['role'] === 'student') {
            $enrollment = $this->enrollmentModel->where('user_id', $user['id'])
                ->where('course_id', $id)
                ->first();
            $isEnrolled = !empty($enrollment);
        }

        $data = [
            'title' => $course['title'] . ' - E-LOOX Academy',
            'course' => $course,
            'instructor' => $instructor,
            'category' => $category,
            'sections' => $sections,
            'isEnrolled' => $isEnrolled,
        ];

        return view('portal/courses/view', $data);
    }

    public function category($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category || !$category['is_active']) {
            return redirect()->to('courses')->with('error', 'Category not found.');
        }

        $courses = $this->courseModel->select('courses.*, users.first_name, users.last_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->where('courses.category_id', $id)
            ->where('courses.status', 'published')
            ->orderBy('courses.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => $category['name'] . ' Courses - E-LOOX Academy',
            'courses' => $courses,
            'category' => $category,
        ];

        return view('portal/courses/category', $data);
    }

    public function enroll($id)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login')->with('error', 'Please login to enroll in courses.');
        }

        $course = $this->courseModel->find($id);
        
        if (!$course || $course['status'] !== 'published') {
            return redirect()->back()->with('error', 'Course not found.');
        }

        // Check if already enrolled
        $existingEnrollment = $this->enrollmentModel->where('user_id', $user['id'])
            ->where('course_id', $id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->to('portal/learn/' . $id)->with('info', 'You are already enrolled in this course.');
        }

        // Calculate course price
        $coursePrice = $course['is_free'] ? 0 : ($course['discount_price'] ?? $course['price'] ?? 0);
        $finalAmount = $coursePrice;

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Generate order number
            $orderNumber = 'ORD-' . strtoupper(uniqid());
            
            // Create order with pending status
            $orderData = [
                'order_number' => $orderNumber,
                'user_id' => $user['id'],
                'total_amount' => $coursePrice,
                'discount_amount' => 0,
                'final_amount' => $finalAmount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_method' => 'manual',
            ];

            // Validate order data
            if (!$this->orderModel->insert($orderData)) {
                $errors = $this->orderModel->errors();
                $errorMsg = 'Failed to create order. ';
                if (!empty($errors)) {
                    $errorMsg .= implode(', ', array_values($errors));
                } else {
                    $errorMsg .= 'Please check your input and try again.';
                }
                throw new \Exception($errorMsg);
            }

            $orderId = $this->orderModel->insertID();
            
            if (empty($orderId)) {
                throw new \Exception('Failed to get order ID after creation.');
            }

            // Create order item
            $orderItemData = [
                'order_id' => $orderId,
                'course_id' => $id,
                'unit_price' => $course['price'] ?? 0,
                'discount_price' => ($course['price'] ?? 0) - $coursePrice,
                'final_price' => $coursePrice,
            ];

            if (!$this->orderItemModel->insert($orderItemData)) {
                $errors = $this->orderItemModel->errors();
                $errorMsg = 'Failed to create order item. ';
                if (!empty($errors)) {
                    $errorMsg .= implode(', ', array_values($errors));
                } else {
                    $errorMsg .= 'Please try again.';
                }
                throw new \Exception($errorMsg);
            }

            // Create enrollment linked to order
            $enrollmentData = [
                'user_id' => $user['id'],
                'course_id' => $id,
                'order_id' => $orderId,
                'enrolled_at' => date('Y-m-d H:i:s'),
                'progress_percentage' => 0,
            ];

            if (!$this->enrollmentModel->insert($enrollmentData)) {
                $errors = $this->enrollmentModel->errors();
                $errorMsg = 'Failed to create enrollment. ';
                if (!empty($errors)) {
                    $errorMsg .= implode(', ', array_values($errors));
                } else {
                    $errorMsg .= 'Please try again.';
                }
                throw new \Exception($errorMsg);
            }

            // Update course student count
            $this->courseModel->update($id, [
                'total_students' => ($course['total_students'] ?? 0) + 1
            ]);

            // Create notifications for all admin users
            $adminUsers = $this->userModel->where('role', 'admin')->findAll();
            foreach ($adminUsers as $admin) {
                $notificationData = [
                    'user_id' => $admin['id'],
                    'title' => 'New Order Pending Approval',
                    'message' => "A new order (#{$orderNumber}) has been created by {$user['first_name']} {$user['last_name']} for course: {$course['title']}. Amount: $" . number_format($finalAmount, 2) . ". Please review and approve.",
                    'type' => 'payment',
                    'related_entity_type' => 'order',
                    'related_entity_id' => $orderId,
                    'is_read' => false,
                ];
                $this->notificationModel->insert($notificationData);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to enroll in course. Please try again.');
            }

            return redirect()->to('portal/dashboard')->with('success', 'Enrollment request submitted! Your payment is pending approval. You will be able to access the course once payment is approved.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Failed to enroll in course: ' . $e->getMessage());
        }
    }
}

