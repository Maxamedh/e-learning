<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CategoryModel;
use App\Models\UserModel;

class Courses extends BaseController
{
    protected $courseModel;
    protected $categoryModel;
    protected $userModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        
        $builder = $this->courseModel->select('courses.*, users.first_name, users.last_name, categories.name as category_name')
            ->join('users', 'users.id = courses.instructor_id', 'left')
            ->join('categories', 'categories.id = courses.category_id', 'left');
        
        if ($status) {
            $builder->where('courses.status', $status);
        }
        
        if ($search) {
            $builder->groupStart()
                ->like('courses.title', $search)
                ->orLike('courses.description', $search)
                ->orLike('users.first_name', $search)
                ->orLike('users.last_name', $search)
                ->groupEnd();
        }
        
        $courses = $builder->orderBy('courses.created_at', 'DESC')->findAll();
        
        $data = [
            'title' => 'Courses Management',
            'courses' => $courses,
            'status' => $status,
            'search' => $search,
        ];
        
        return view('admin/courses/index', $data);
    }

    public function create()
    {
        $categories = $this->categoryModel->getActiveCategories();
        $instructors = $this->userModel->getInstructorUsers();
        
        $data = [
            'title' => 'Create Course',
            'categories' => $categories,
            'instructors' => $instructors,
        ];
        
        return view('admin/courses/create', $data);
    }

    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'instructor_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'level' => 'required|in_list[beginner,intermediate,advanced,all]',
            'status' => 'required|in_list[draft,published,unpublished,pending]',
        ];
        
        // Add file validation only if files are uploaded
        $thumbnailFile = $this->request->getFile('thumbnail');
        $promoVideoFile = $this->request->getFile('promo_video');
        
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            $rules['thumbnail'] = 'uploaded[thumbnail]|max_size[thumbnail,5120]|ext_in[thumbnail,jpg,jpeg,png,gif]';
        }
        if ($promoVideoFile && $promoVideoFile->isValid() && !$promoVideoFile->hasMoved()) {
            $rules['promo_video'] = 'uploaded[promo_video]|max_size[promo_video,102400]|ext_in[promo_video,mp4,webm,mov]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle thumbnail upload
        $thumbnailUrl = $this->request->getPost('thumbnail_url');
        $thumbnailFile = $this->request->getFile('thumbnail');
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            // Ensure directory exists
            $uploadPath = ROOTPATH . 'public/uploads/courses/thumbnails/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move to public directory for web access
            $newName = $thumbnailFile->getRandomName();
            if ($thumbnailFile->move($uploadPath, $newName)) {
                // Construct URL with /public/ in the path
                $thumbnailUrl = rtrim(base_url(), '/') . '/public/uploads/courses/thumbnails/' . $newName;
                
                // Verify file exists
                if (!file_exists($uploadPath . $newName)) {
                    log_message('error', 'Thumbnail file not found after upload: ' . $uploadPath . $newName);
                } else {
                    log_message('info', 'Thumbnail uploaded successfully: ' . $thumbnailUrl . ' (Size: ' . filesize($uploadPath . $newName) . ' bytes)');
                }
            } else {
                log_message('error', 'Failed to move thumbnail file: ' . $thumbnailFile->getErrorString());
                return redirect()->back()->withInput()->with('error', 'Failed to upload thumbnail: ' . $thumbnailFile->getErrorString());
            }
        }
        
        // Handle promo video upload
        $promoVideoUrl = $this->request->getPost('promo_video_url');
        $promoVideoFile = $this->request->getFile('promo_video');
        if ($promoVideoFile && $promoVideoFile->isValid() && !$promoVideoFile->hasMoved()) {
            // Ensure directory exists
            $uploadPath = ROOTPATH . 'public/uploads/courses/videos/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move to public directory for web access
            $newName = $promoVideoFile->getRandomName();
            if ($promoVideoFile->move($uploadPath, $newName)) {
                // Construct URL with /public/ in the path
                $promoVideoUrl = rtrim(base_url(), '/') . '/public/uploads/courses/videos/' . $newName;
                // Verify file exists
                if (!file_exists($uploadPath . $newName)) {
                    log_message('error', 'Video file not found after upload: ' . $uploadPath . $newName);
                } else {
                    log_message('info', 'Video uploaded successfully: ' . $promoVideoUrl);
                }
            } else {
                log_message('error', 'Failed to move video file: ' . $promoVideoFile->getErrorString());
            }
        }
        
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'instructor_id' => $this->request->getPost('instructor_id'),
            'category_id' => $this->request->getPost('category_id'),
            'price' => $this->request->getPost('is_free') ? 0 : $this->request->getPost('price'),
            'discount_price' => $this->request->getPost('is_free') ? null : ($this->request->getPost('discount_price') ?: null),
            'level' => $this->request->getPost('level'),
            'language' => $this->request->getPost('language') ?: 'English',
            'status' => $this->request->getPost('status'),
            'is_free' => $this->request->getPost('is_free') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'thumbnail_url' => $thumbnailUrl ?: null,
            'promo_video_url' => $promoVideoUrl,
        ];
        
        // Handle JSON fields
        if ($this->request->getPost('requirements')) {
            $data['requirements'] = json_encode(explode("\n", $this->request->getPost('requirements')));
        }
        if ($this->request->getPost('learning_outcomes')) {
            $data['learning_outcomes'] = json_encode(explode("\n", $this->request->getPost('learning_outcomes')));
        }
        
        if ($courseId = $this->courseModel->insert($data)) {
            // Get the inserted course ID
            $insertedId = $this->courseModel->getInsertID();
            return redirect()->to('admin/courses/edit/' . $insertedId)->with('success', 'Course created successfully! Now you can add sections and lectures.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create course.');
        }
    }

    public function edit($id)
    {
        $course = $this->courseModel->getCourseById($id);
        
        if (!$course) {
            return redirect()->to('admin/courses')->with('error', 'Course not found.');
        }
        
        // Load sections and lectures for this course
        $sectionModel = new \App\Models\SectionModel();
        $lectureModel = new \App\Models\LectureModel();
        $sections = $sectionModel->getAllSectionsByCourse($id);
        
        // Get lectures for each section
        foreach ($sections as &$section) {
            $section['lectures'] = $lectureModel->getAllLecturesBySection($section['id']);
        }
        
        $categories = $this->categoryModel->getActiveCategories();
        $instructors = $this->userModel->getInstructorUsers();
        
        $data = [
            'title' => 'Edit Course',
            'course' => $course,
            'categories' => $categories,
            'instructors' => $instructors,
            'sections' => $sections,
        ];
        
        return view('admin/courses/edit', $data);
    }

    public function update($id)
    {
        $course = $this->courseModel->find($id);
        
        if (!$course) {
            return redirect()->to('admin/courses')->with('error', 'Course not found.');
        }
        
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'instructor_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'level' => 'required|in_list[beginner,intermediate,advanced,all]',
            'status' => 'required|in_list[draft,published,unpublished,pending]',
        ];
        
        // Add file validation only if files are uploaded
        $thumbnailFile = $this->request->getFile('thumbnail');
        $promoVideoFile = $this->request->getFile('promo_video');
        
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            $rules['thumbnail'] = 'uploaded[thumbnail]|max_size[thumbnail,5120]|ext_in[thumbnail,jpg,jpeg,png,gif]';
        }
        if ($promoVideoFile && $promoVideoFile->isValid() && !$promoVideoFile->hasMoved()) {
            $rules['promo_video'] = 'uploaded[promo_video]|max_size[promo_video,102400]|ext_in[promo_video,mp4,webm,mov]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Handle thumbnail upload
        $thumbnailUrl = $this->request->getPost('thumbnail_url') ?: $course['thumbnail_url'];
        if ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved()) {
            // Ensure directory exists
            $uploadPath = ROOTPATH . 'public/uploads/courses/thumbnails/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move to public directory for web access
            $newName = $thumbnailFile->getRandomName();
            if ($thumbnailFile->move($uploadPath, $newName)) {
                // Construct URL with /public/ in the path
                $thumbnailUrl = rtrim(base_url(), '/') . '/public/uploads/courses/thumbnails/' . $newName;
                
                // Verify file exists
                if (!file_exists($uploadPath . $newName)) {
                    log_message('error', 'Thumbnail file not found after upload: ' . $uploadPath . $newName);
                } else {
                    log_message('info', 'Thumbnail uploaded successfully: ' . $thumbnailUrl . ' (Size: ' . filesize($uploadPath . $newName) . ' bytes)');
                }
            } else {
                log_message('error', 'Failed to move thumbnail file: ' . $thumbnailFile->getErrorString());
                return redirect()->back()->withInput()->with('error', 'Failed to upload thumbnail: ' . $thumbnailFile->getErrorString());
            }
        }
        
        // Handle promo video upload
        $promoVideoUrl = $this->request->getPost('promo_video_url') ?: $course['promo_video_url'];
        if ($promoVideoFile && $promoVideoFile->isValid() && !$promoVideoFile->hasMoved()) {
            // Ensure directory exists
            $uploadPath = ROOTPATH . 'public/uploads/courses/videos/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move to public directory for web access
            $newName = $promoVideoFile->getRandomName();
            if ($promoVideoFile->move($uploadPath, $newName)) {
                // Construct URL with /public/ in the path
                $promoVideoUrl = rtrim(base_url(), '/') . '/public/uploads/courses/videos/' . $newName;
                // Verify file exists
                if (!file_exists($uploadPath . $newName)) {
                    log_message('error', 'Video file not found after upload: ' . $uploadPath . $newName);
                } else {
                    log_message('info', 'Video uploaded successfully: ' . $promoVideoUrl);
                }
            } else {
                log_message('error', 'Failed to move video file: ' . $promoVideoFile->getErrorString());
            }
        }
        
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'instructor_id' => $this->request->getPost('instructor_id'),
            'category_id' => $this->request->getPost('category_id'),
            'price' => $this->request->getPost('is_free') ? 0 : $this->request->getPost('price'),
            'discount_price' => $this->request->getPost('is_free') ? null : ($this->request->getPost('discount_price') ?: null),
            'level' => $this->request->getPost('level'),
            'language' => $this->request->getPost('language') ?: 'English',
            'status' => $this->request->getPost('status'),
            'is_free' => $this->request->getPost('is_free') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'thumbnail_url' => $thumbnailUrl ?: null,
            'promo_video_url' => $promoVideoUrl,
        ];
        
        // Handle JSON fields
        if ($this->request->getPost('requirements')) {
            $data['requirements'] = json_encode(explode("\n", $this->request->getPost('requirements')));
        }
        if ($this->request->getPost('learning_outcomes')) {
            $data['learning_outcomes'] = json_encode(explode("\n", $this->request->getPost('learning_outcomes')));
        }
        
        if ($this->courseModel->update($id, $data)) {
            return redirect()->to('admin/courses')->with('success', 'Course updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update course.');
        }
    }

    public function view($id)
    {
        $course = $this->courseModel->getCourseById($id);
        
        if (!$course) {
            return redirect()->to('admin/courses')->with('error', 'Course not found.');
        }
        
        // Load sections and lectures for this course
        $sectionModel = new \App\Models\SectionModel();
        $lectureModel = new \App\Models\LectureModel();
        $sections = $sectionModel->getAllSectionsByCourse($id);
        
        // Get lectures for each section
        $allLectures = [];
        foreach ($sections as &$section) {
            $section['lectures'] = $lectureModel->getAllLecturesBySection($section['id']);
            // Flatten lectures for easy access
            foreach ($section['lectures'] as $lecture) {
                $lecture['section_title'] = $section['title'];
                $allLectures[] = $lecture;
            }
        }
        
        // Get instructor info
        $instructor = $this->userModel->find($course['instructor_id']);
        
        // Get category info
        $category = $this->categoryModel->find($course['category_id']);
        
        // Determine default video (promo video or first lecture video)
        $defaultVideo = $course['promo_video_url'] ?? null;
        $defaultLecture = null;
        if (empty($defaultVideo) && !empty($allLectures)) {
            // Find first video lecture
            foreach ($allLectures as $lecture) {
                if ($lecture['content_type'] === 'video' && !empty($lecture['video_url'])) {
                    $defaultVideo = $lecture['video_url'];
                    $defaultLecture = $lecture;
                    break;
                }
            }
        }
        
        // Normalize video URLs - ensure they're absolute URLs
        if (!empty($defaultVideo)) {
            $baseUrl = base_url();
            // If it doesn't start with http/https and doesn't already contain base_url
            if (!filter_var($defaultVideo, FILTER_VALIDATE_URL) && strpos($defaultVideo, $baseUrl) === false) {
                // If it's a relative path starting with uploads/, make it absolute
                if (strpos($defaultVideo, 'uploads/') === 0) {
                    $defaultVideo = $baseUrl . $defaultVideo;
                }
                // If it starts with /uploads/, add base_url
                elseif (strpos($defaultVideo, '/uploads/') === 0) {
                    $defaultVideo = $baseUrl . ltrim($defaultVideo, '/');
                }
                // Otherwise, assume it needs base_url
                else {
                    $defaultVideo = $baseUrl . ltrim($defaultVideo, '/');
                }
            }
        }
        
        // Also normalize lecture video URLs
        foreach ($allLectures as &$lecture) {
            if (!empty($lecture['video_url'])) {
                $videoUrl = $lecture['video_url'];
                $baseUrl = base_url();
                if (!filter_var($videoUrl, FILTER_VALIDATE_URL) && strpos($videoUrl, $baseUrl) === false) {
                    if (strpos($videoUrl, 'uploads/') === 0) {
                        $lecture['video_url'] = $baseUrl . $videoUrl;
                    } elseif (strpos($videoUrl, '/uploads/') === 0) {
                        $lecture['video_url'] = $baseUrl . ltrim($videoUrl, '/');
                    } else {
                        $lecture['video_url'] = $baseUrl . ltrim($videoUrl, '/');
                    }
                }
            }
        }
        
        $data = [
            'title' => 'View Course - ' . $course['title'],
            'course' => $course,
            'sections' => $sections,
            'allLectures' => $allLectures,
            'instructor' => $instructor,
            'category' => $category,
            'defaultVideo' => $defaultVideo,
            'defaultLecture' => $defaultLecture,
        ];
        
        return view('admin/courses/view', $data);
    }

    public function delete($id)
    {
        if ($this->courseModel->delete($id)) {
            return redirect()->to('admin/courses')->with('success', 'Course deleted successfully!');
        } else {
            return redirect()->to('admin/courses')->with('error', 'Failed to delete course.');
        }
    }
}

