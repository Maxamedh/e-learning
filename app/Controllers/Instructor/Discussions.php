<?php

namespace App\Controllers\Instructor;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\DiscussionModel;
use App\Models\UserModel;

class Discussions extends BaseController
{
    protected $courseModel;
    protected $discussionModel;
    protected $userModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->discussionModel = new DiscussionModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'instructor') {
            return redirect()->to('admin/login')->with('error', 'Access denied.');
        }

        // Get instructor's courses
        $courses = $this->courseModel->where('instructor_id', $user['id'])->findAll();
        $courseIds = array_column($courses, 'id');

        // Get discussions for instructor's courses
        $discussions = [];
        if (!empty($courseIds)) {
            $db = \Config\Database::connect();
            $tables = $db->listTables();
            
            $discussions = $this->discussionModel->select('discussions.*, users.first_name, users.last_name, courses.title as course_title')
                ->join('users', 'users.id = discussions.user_id', 'left')
                ->join('courses', 'courses.id = discussions.course_id', 'left')
                ->whereIn('discussions.course_id', $courseIds)
                ->where('discussions.parent_id IS NULL')
                ->orderBy('discussions.is_pinned', 'DESC')
                ->orderBy('discussions.created_at', 'DESC')
                ->findAll();

            // Get reply counts
            $db = \Config\Database::connect();
            foreach ($discussions as &$discussion) {
                if (in_array('discussion_replies', $tables)) {
                    $discussion['reply_count'] = $db->table('discussion_replies')
                        ->where('discussion_id', $discussion['id'])
                        ->countAllResults();
                } else {
                    $discussion['reply_count'] = $this->discussionModel->where('parent_id', $discussion['id'])->countAllResults();
                }
            }
        }

        $data = [
            'title' => 'Course Discussions',
            'discussions' => $discussions,
            'courses' => $courses,
        ];

        return view('instructor/discussions/index', $data);
    }

    public function view($id)
    {
        $session = \Config\Services::session();
        $user = $session->get('user');
        
        if (!$user || $user['role'] !== 'instructor') {
            return redirect()->to('admin/login')->with('error', 'Access denied.');
        }

        $discussion = $this->discussionModel->select('discussions.*, users.first_name, users.last_name, courses.title as course_title, courses.instructor_id')
            ->join('users', 'users.id = discussions.user_id', 'left')
            ->join('courses', 'courses.id = discussions.course_id', 'left')
            ->where('discussions.id', $id)
            ->first();

        if (!$discussion || $discussion['instructor_id'] != $user['id']) {
            return redirect()->to('instructor/discussions')->with('error', 'Discussion not found.');
        }

        // Get replies
        $db = \Config\Database::connect();
        $tables = $db->listTables();
        
        if (in_array('discussion_replies', $tables)) {
            $replies = $db->table('discussion_replies dr')
                ->select('dr.*, u.first_name, u.last_name, u.profile_picture, u.role')
                ->join('users u', 'u.id = dr.user_id', 'left')
                ->where('dr.discussion_id', $id)
                ->orderBy('dr.created_at', 'ASC')
                ->get()
                ->getResultArray();
        } else {
            $replies = $this->discussionModel->select('discussions.*, users.first_name, users.last_name, users.profile_picture, users.role')
                ->join('users', 'users.id = discussions.user_id', 'left')
                ->where('discussions.parent_id', $id)
                ->orderBy('discussions.created_at', 'ASC')
                ->findAll();
        }

        $data = [
            'title' => 'Discussion: ' . ($discussion['title'] ?? 'No Title'),
            'discussion' => $discussion,
            'replies' => $replies,
        ];

        return view('instructor/discussions/view', $data);
    }
}

