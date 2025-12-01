<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LectureModel;
use App\Models\SectionModel;
use App\Models\CourseModel;

class Lectures extends BaseController
{
    protected $lectureModel;
    protected $sectionModel;
    protected $courseModel;

    public function __construct()
    {
        $this->lectureModel = new LectureModel();
        $this->sectionModel = new SectionModel();
        $this->courseModel = new CourseModel();
    }

    public function index($courseId, $sectionId = null)
    {
        $course = $this->courseModel->getCourseById($courseId);
        if (!$course) {
            return redirect()->to('admin/courses')->with('error', 'Course not found.');
        }

        $sections = $this->sectionModel->getAllSectionsByCourse($courseId);
        
        if ($sectionId) {
            $lectures = $this->lectureModel->getAllLecturesBySection($sectionId);
            $currentSection = $this->sectionModel->find($sectionId);
        } else {
            $lectures = [];
            $currentSection = null;
        }
        
        $data = [
            'title' => 'Course Lectures - ' . $course['title'],
            'course' => $course,
            'sections' => $sections,
            'currentSection' => $currentSection,
            'lectures' => $lectures,
            'sectionId' => $sectionId,
        ];
        
        return view('admin/lectures/index', $data);
    }

    public function store($courseId, $sectionId)
    {
        $section = $this->sectionModel->find($sectionId);
        if (!$section || $section['course_id'] != $courseId) {
            return redirect()->to('admin/lectures/' . $courseId)->with('error', 'Section not found.');
        }

        $rules = [
            'title' => 'required|min_length[2]|max_length[255]',
            'content_type' => 'required|in_list[video,article,quiz,assignment,live]',
        ];
        
        // Add file validation only if video is uploaded
        $videoFile = $this->request->getFile('video');
        if ($videoFile && $videoFile->isValid() && !$videoFile->hasMoved()) {
            $rules['video'] = 'uploaded[video]|max_size[video,512000]|ext_in[video,mp4,webm,mov,avi]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get max order_index
        $maxOrder = $this->lectureModel->where('section_id', $sectionId)
            ->selectMax('order_index')
            ->first();
        $nextOrder = ($maxOrder['order_index'] ?? 0) + 1;

        // Handle video upload
        $videoUrl = $this->request->getPost('video_url');
        
        // If a file is uploaded, it takes priority over URL
        if ($videoFile && $videoFile->isValid() && !$videoFile->hasMoved()) {
            // Ensure directory exists
            $uploadPath = ROOTPATH . 'public/uploads/courses/lectures/';
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $uploadPath);
                    return redirect()->back()->withInput()->with('error', 'Failed to create upload directory. Please check permissions.');
                }
            }
            
            // Generate unique filename
            $newName = $videoFile->getRandomName();
            $fullPath = $uploadPath . $newName;
            
            // Move file and verify
            try {
                if ($videoFile->move($uploadPath, $newName)) {
                    // Verify file was actually moved and exists
                    if (file_exists($fullPath) && filesize($fullPath) > 0) {
                        // Construct URL without /public/ - base_url() handles the base path
                        $videoUrl = rtrim(base_url(), '/') . '/uploads/courses/lectures/' . $newName;
                        log_message('info', 'Lecture video uploaded successfully: ' . $videoUrl . ' (Size: ' . filesize($fullPath) . ' bytes)');
                    } else {
                        log_message('error', 'Video file not found or empty after move: ' . $fullPath);
                        return redirect()->back()->withInput()->with('error', 'Video file upload failed. File was not saved correctly.');
                    }
                } else {
                    $error = $videoFile->getErrorString() ?: 'Unknown error';
                    log_message('error', 'Failed to move video file: ' . $error . ' (Path: ' . $uploadPath . ')');
                    return redirect()->back()->withInput()->with('error', 'Failed to upload video: ' . $error);
                }
            } catch (\Exception $e) {
                log_message('error', 'Exception during video upload: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Error uploading video: ' . $e->getMessage());
            }
        } elseif (empty($videoUrl) && $this->request->getPost('content_type') === 'video') {
            // If content type is video but no file or URL provided
            return redirect()->back()->withInput()->with('error', 'Please upload a video file or provide a video URL.');
        }

        $data = [
            'section_id' => $sectionId,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'content_type' => $this->request->getPost('content_type'),
            'video_url' => $videoUrl,
            'video_duration' => $this->request->getPost('video_duration') ?: null,
            'article_content' => $this->request->getPost('article_content'),
            'order_index' => $nextOrder,
            'is_preview' => $this->request->getPost('is_preview') ? 1 : 0,
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
        ];
        
        if ($this->lectureModel->insert($data)) {
            return redirect()->to('admin/lectures/' . $courseId . '/' . $sectionId)->with('success', 'Lecture created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create lecture.');
        }
    }

    public function update($courseId, $sectionId, $id)
    {
        $lecture = $this->lectureModel->find($id);
        if (!$lecture || $lecture['section_id'] != $sectionId) {
            return redirect()->to('admin/lectures/' . $courseId . '/' . $sectionId)->with('error', 'Lecture not found.');
        }

        $rules = [
            'title' => 'required|min_length[2]|max_length[255]',
            'content_type' => 'required|in_list[video,article,quiz,assignment,live]',
        ];
        
        // Add file validation only if video is uploaded
        $videoFile = $this->request->getFile('video');
        if ($videoFile && $videoFile->isValid() && !$videoFile->hasMoved()) {
            $rules['video'] = 'uploaded[video]|max_size[video,512000]|ext_in[video,mp4,webm,mov,avi]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle video upload
        $videoUrl = $this->request->getPost('video_url') ?: $lecture['video_url'];
        
        // If a file is uploaded, it takes priority over URL
        if ($videoFile && $videoFile->isValid() && !$videoFile->hasMoved()) {
            // Ensure directory exists
            $uploadPath = ROOTPATH . 'public/uploads/courses/lectures/';
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $uploadPath);
                    return redirect()->back()->withInput()->with('error', 'Failed to create upload directory. Please check permissions.');
                }
            }
            
            // Generate unique filename
            $newName = $videoFile->getRandomName();
            $fullPath = $uploadPath . $newName;
            
            // Move file and verify
            try {
                if ($videoFile->move($uploadPath, $newName)) {
                    // Verify file was actually moved and exists
                    if (file_exists($fullPath) && filesize($fullPath) > 0) {
                        // Construct URL without /public/ - base_url() handles the base path
                        $videoUrl = rtrim(base_url(), '/') . '/uploads/courses/lectures/' . $newName;
                        log_message('info', 'Lecture video updated successfully: ' . $videoUrl . ' (Size: ' . filesize($fullPath) . ' bytes)');
                    } else {
                        log_message('error', 'Video file not found or empty after move: ' . $fullPath);
                        return redirect()->back()->withInput()->with('error', 'Video file upload failed. File was not saved correctly.');
                    }
                } else {
                    $error = $videoFile->getErrorString() ?: 'Unknown error';
                    log_message('error', 'Failed to move video file: ' . $error . ' (Path: ' . $uploadPath . ')');
                    return redirect()->back()->withInput()->with('error', 'Failed to upload video: ' . $error);
                }
            } catch (\Exception $e) {
                log_message('error', 'Exception during video upload: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Error uploading video: ' . $e->getMessage());
            }
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'content_type' => $this->request->getPost('content_type'),
            'video_url' => $videoUrl,
            'video_duration' => $this->request->getPost('video_duration') ?: null,
            'article_content' => $this->request->getPost('article_content'),
            'order_index' => $this->request->getPost('order_index'),
            'is_preview' => $this->request->getPost('is_preview') ? 1 : 0,
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
        ];
        
        if ($this->lectureModel->update($id, $data)) {
            return redirect()->to('admin/lectures/' . $courseId . '/' . $sectionId)->with('success', 'Lecture updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update lecture.');
        }
    }

    public function delete($courseId, $sectionId, $id)
    {
        $lecture = $this->lectureModel->find($id);
        if (!$lecture || $lecture['section_id'] != $sectionId) {
            return redirect()->to('admin/lectures/' . $courseId . '/' . $sectionId)->with('error', 'Lecture not found.');
        }

        if ($this->lectureModel->delete($id)) {
            return redirect()->to('admin/lectures/' . $courseId . '/' . $sectionId)->with('success', 'Lecture deleted successfully!');
        } else {
            return redirect()->to('admin/lectures/' . $courseId . '/' . $sectionId)->with('error', 'Failed to delete lecture.');
        }
    }
}

