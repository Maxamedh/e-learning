<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DiscussionModel;
use App\Models\DiscussionReplyModel;
use App\Models\CourseModel;
use App\Models\UserModel;

class Discussions extends BaseController
{
    protected $discussionModel;
    protected $replyModel;
    protected $courseModel;
    protected $userModel;

    public function __construct()
    {
        $this->discussionModel = new DiscussionModel();
        $this->replyModel = new DiscussionReplyModel();
        $this->courseModel = new CourseModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $courseId = $this->request->getGet('course_id');
        $postType = $this->request->getGet('post_type') ?? 'all';
        $search = $this->request->getGet('search');

        $builder = $this->discussionModel->select('discussions.*, courses.title as course_title, users.first_name, users.last_name, users.email')
            ->join('courses', 'courses.id = discussions.course_id', 'left')
            ->join('users', 'users.id = discussions.user_id', 'left');

        if ($courseId) {
            $builder->where('discussions.course_id', $courseId);
        }

        if ($postType !== 'all') {
            if ($postType === 'question') {
                $builder->where('discussions.is_question', true);
            } else {
                $builder->where('discussions.is_question', false);
            }
        }

        if ($search) {
            $builder->groupStart()
                ->like('discussions.title', $search)
                ->orLike('discussions.content', $search)
                ->orLike('courses.title', $search)
                ->groupEnd();
        }

        $discussions = $builder->orderBy('discussions.is_pinned', 'DESC')
            ->orderBy('discussions.created_at', 'DESC')
            ->findAll();

        // Get courses for filter
        $courses = $this->courseModel->select('id, title')->orderBy('title', 'ASC')->findAll();

        $data = [
            'title' => 'Discussions Management',
            'discussions' => $discussions,
            'courses' => $courses,
            'courseId' => $courseId,
            'postType' => $postType,
            'search' => $search,
        ];

        return view('admin/discussions/index', $data);
    }

    public function view($id)
    {
        $discussion = $this->discussionModel->select('discussions.*, courses.title as course_title, courses.id as course_id, users.first_name, users.last_name, users.email, users.profile_picture')
            ->join('courses', 'courses.id = discussions.course_id', 'left')
            ->join('users', 'users.id = discussions.user_id', 'left')
            ->find($id);

        if (!$discussion) {
            return redirect()->to('admin/discussions')->with('error', 'Discussion not found.');
        }

        // Get replies - check if discussion_replies table exists, otherwise use discussions table with parent_id
        $db = \Config\Database::connect();
        $tables = $db->listTables();
        
        if (in_array('discussion_replies', $tables)) {
            $replies = $this->replyModel->select('discussion_replies.*, users.first_name, users.last_name, users.email, users.profile_picture, users.role')
                ->join('users', 'users.id = discussion_replies.user_id', 'left')
                ->where('discussion_replies.discussion_id', $id)
                ->orderBy('discussion_replies.created_at', 'ASC')
                ->findAll();
        } else {
            // Use discussions table with parent_id for replies
            $replies = $this->discussionModel->select('discussions.*, users.first_name, users.last_name, users.email, users.profile_picture, users.role')
                ->join('users', 'users.id = discussions.user_id', 'left')
                ->where('discussions.parent_id', $id)
                ->orderBy('discussions.created_at', 'ASC')
                ->findAll();
        }

        // Note: view_count is not in the schema, so we skip this

        $data = [
            'title' => 'Discussion Details',
            'discussion' => $discussion,
            'replies' => $replies,
        ];

        return view('admin/discussions/view', $data);
    }

    public function togglePin($id)
    {
        $discussion = $this->discussionModel->find($id);
        
        if (!$discussion) {
            return redirect()->back()->with('error', 'Discussion not found.');
        }

        $this->discussionModel->update($id, [
            'is_pinned' => !$discussion['is_pinned']
        ]);

        return redirect()->back()->with('success', 'Discussion pin status updated.');
    }

    public function toggleResolve($id)
    {
        $discussion = $this->discussionModel->find($id);
        
        if (!$discussion) {
            return redirect()->back()->with('error', 'Discussion not found.');
        }

        $this->discussionModel->update($id, [
            'is_resolved' => !$discussion['is_resolved']
        ]);

        return redirect()->back()->with('success', 'Discussion resolve status updated.');
    }

    public function delete($id)
    {
        $discussion = $this->discussionModel->find($id);
        
        if (!$discussion) {
            return redirect()->back()->with('error', 'Discussion not found.');
        }

        // Delete all replies first - check if discussion_replies table exists
        $db = \Config\Database::connect();
        $tables = $db->listTables();
        
        if (in_array('discussion_replies', $tables)) {
            $this->replyModel->where('discussion_id', $id)->delete();
        } else {
            // Use discussions table with parent_id
            $this->discussionModel->where('parent_id', $id)->delete();
        }

        // Delete discussion
        $this->discussionModel->delete($id);

        return redirect()->to('admin/discussions')->with('success', 'Discussion deleted successfully.');
    }

    public function deleteReply($id)
    {
        $db = \Config\Database::connect();
        $tables = $db->listTables();
        
        if (in_array('discussion_replies', $tables)) {
            $reply = $this->replyModel->find($id);
            if (!$reply) {
                return redirect()->back()->with('error', 'Reply not found.');
            }
            $discussionId = $reply['discussion_id'];
            $this->replyModel->delete($id);
        } else {
            // Use discussions table
            $reply = $this->discussionModel->find($id);
            if (!$reply) {
                return redirect()->back()->with('error', 'Reply not found.');
            }
            $discussionId = $reply['parent_id'];
            $this->discussionModel->delete($id);
        }

        return redirect()->to('admin/discussions/view/' . $discussionId)->with('success', 'Reply deleted successfully.');
    }
}

