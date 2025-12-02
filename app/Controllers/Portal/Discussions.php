<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\DiscussionModel;
use App\Models\DiscussionReplyModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\OrderModel;

class Discussions extends BaseController
{
    protected $discussionModel;
    protected $replyModel;
    protected $courseModel;
    protected $enrollmentModel;
    protected $orderModel;

    public function __construct()
    {
        $this->discussionModel = new DiscussionModel();
        $this->replyModel = new DiscussionReplyModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->orderModel = new OrderModel();
    }

    public function index($courseId)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login')->with('error', 'Please login to access discussions.');
        }

        // Check enrollment
        $enrollment = $this->enrollmentModel->where('user_id', $user['id'])
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return redirect()->to('courses/' . $courseId)->with('error', 'You must enroll in this course first.');
        }

        // Check order status if enrollment has order_id
        if (!empty($enrollment['order_id'])) {
            $order = $this->orderModel->find($enrollment['order_id']);
            if ($order && $order['status'] === 'pending') {
                return redirect()->to('portal/dashboard')->with('error', 'Your enrollment is pending payment approval.');
            }
            if ($order && $order['status'] !== 'completed') {
                return redirect()->to('portal/dashboard')->with('error', 'Your enrollment payment status is: ' . ucfirst($order['status']) . '. Please contact support.');
            }
        }

        $course = $this->courseModel->getCourseById($courseId);
        
        if (!$course || $course['status'] !== 'published') {
            return redirect()->to('courses')->with('error', 'Course not found.');
        }

        // Get discussions for this course
        $db = \Config\Database::connect();
        $tables = $db->listTables();
        
        if (in_array('discussion_replies', $tables)) {
            $discussions = $this->discussionModel->select('discussions.*, users.first_name, users.last_name, users.profile_picture, users.role')
                ->join('users', 'users.id = discussions.user_id', 'left')
                ->where('discussions.course_id', $courseId)
                ->where('discussions.parent_id IS NULL')
                ->orderBy('discussions.is_pinned', 'DESC')
                ->orderBy('discussions.created_at', 'DESC')
                ->findAll();
        } else {
            $discussions = $this->discussionModel->select('discussions.*, users.first_name, users.last_name, users.profile_picture, users.role')
                ->join('users', 'users.id = discussions.user_id', 'left')
                ->where('discussions.course_id', $courseId)
                ->where('discussions.parent_id IS NULL')
                ->orderBy('discussions.is_pinned', 'DESC')
                ->orderBy('discussions.created_at', 'DESC')
                ->findAll();
        }

        // Get reply counts for each discussion
        foreach ($discussions as &$discussion) {
            if (in_array('discussion_replies', $tables)) {
                $discussion['reply_count'] = $this->replyModel->where('discussion_id', $discussion['id'])->countAllResults();
            } else {
                $discussion['reply_count'] = $this->discussionModel->where('parent_id', $discussion['id'])->countAllResults();
            }
        }

        $data = [
            'title' => 'Discussions - ' . $course['title'],
            'course' => $course,
            'discussions' => $discussions,
            'enrollment' => $enrollment,
        ];

        return view('portal/discussions/index', $data);
    }

    public function view($courseId, $discussionId)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login')->with('error', 'Please login to access discussions.');
        }

        // Check enrollment
        $enrollment = $this->enrollmentModel->where('user_id', $user['id'])
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return redirect()->to('courses/' . $courseId)->with('error', 'You must enroll in this course first.');
        }

        $course = $this->courseModel->getCourseById($courseId);
        $discussion = $this->discussionModel->select('discussions.*, users.first_name, users.last_name, users.profile_picture, users.role')
            ->join('users', 'users.id = discussions.user_id', 'left')
            ->find($discussionId);

        if (!$discussion || $discussion['course_id'] != $courseId) {
            return redirect()->to('portal/discussions/' . $courseId)->with('error', 'Discussion not found.');
        }

        // Get replies
        $db = \Config\Database::connect();
        $tables = $db->listTables();
        
        if (in_array('discussion_replies', $tables)) {
            $replies = $this->replyModel->select('discussion_replies.*, users.first_name, users.last_name, users.profile_picture, users.role')
                ->join('users', 'users.id = discussion_replies.user_id', 'left')
                ->where('discussion_replies.discussion_id', $discussionId)
                ->orderBy('discussion_replies.created_at', 'ASC')
                ->findAll();
        } else {
            $replies = $this->discussionModel->select('discussions.*, users.first_name, users.last_name, users.profile_picture, users.role')
                ->join('users', 'users.id = discussions.user_id', 'left')
                ->where('discussions.parent_id', $discussionId)
                ->orderBy('discussions.created_at', 'ASC')
                ->findAll();
        }

        $data = [
            'title' => $discussion['title'] . ' - Discussions',
            'course' => $course,
            'discussion' => $discussion,
            'replies' => $replies,
            'enrollment' => $enrollment,
        ];

        return view('portal/discussions/view', $data);
    }

    public function create($courseId)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login')->with('error', 'Please login to create discussions.');
        }

        // Check enrollment
        $enrollment = $this->enrollmentModel->where('user_id', $user['id'])
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return redirect()->to('courses/' . $courseId)->with('error', 'You must enroll in this course first.');
        }

        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        $isQuestion = $this->request->getPost('is_question') ? 1 : 0;

        if (empty($title) || empty($content)) {
            return redirect()->back()->with('error', 'Title and content are required.');
        }

        $data = [
            'course_id' => $courseId,
            'user_id' => $user['id'],
            'title' => $title,
            'content' => $content,
            'is_question' => $isQuestion,
            'is_pinned' => 0,
            'is_resolved' => 0,
        ];

        if ($this->discussionModel->insert($data)) {
            return redirect()->to('portal/discussions/' . $courseId)->with('success', 'Discussion created successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to create discussion. Please try again.');
        }
    }

    public function reply($courseId, $discussionId)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'student') {
            return redirect()->to('portal/login')->with('error', 'Please login to reply.');
        }

        // Check enrollment
        $enrollment = $this->enrollmentModel->where('user_id', $user['id'])
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return redirect()->to('courses/' . $courseId)->with('error', 'You must enroll in this course first.');
        }

        $content = $this->request->getPost('content');

        if (empty($content)) {
            return redirect()->back()->with('error', 'Reply content is required.');
        }

        $db = \Config\Database::connect();
        $tables = $db->listTables();

        if (in_array('discussion_replies', $tables)) {
            $data = [
                'discussion_id' => $discussionId,
                'user_id' => $user['id'],
                'content' => $content,
            ];
            
            if ($this->replyModel->insert($data)) {
                return redirect()->to('portal/discussions/' . $courseId . '/view/' . $discussionId)->with('success', 'Reply posted successfully!');
            }
        } else {
            // Use discussions table with parent_id
            $data = [
                'course_id' => $courseId,
                'user_id' => $user['id'],
                'parent_id' => $discussionId,
                'title' => 'Re: ' . $this->request->getPost('discussion_title', FILTER_SANITIZE_STRING),
                'content' => $content,
            ];
            
            if ($this->discussionModel->insert($data)) {
                return redirect()->to('portal/discussions/' . $courseId . '/view/' . $discussionId)->with('success', 'Reply posted successfully!');
            }
        }

        return redirect()->back()->with('error', 'Failed to post reply. Please try again.');
    }
}

