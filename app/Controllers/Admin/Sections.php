<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SectionModel;
use App\Models\CourseModel;

class Sections extends BaseController
{
    protected $sectionModel;
    protected $courseModel;

    public function __construct()
    {
        $this->sectionModel = new SectionModel();
        $this->courseModel = new CourseModel();
    }

    public function index($courseId = null)
    {
        if (!$courseId) {
            return redirect()->to('admin/courses')->with('error', 'Please select a course first.');
        }

        $course = $this->courseModel->getCourseById($courseId);
        if (!$course) {
            return redirect()->to('admin/courses')->with('error', 'Course not found.');
        }

        $sections = $this->sectionModel->getAllSectionsByCourse($courseId);
        
        $data = [
            'title' => 'Course Sections - ' . $course['title'],
            'course' => $course,
            'sections' => $sections,
        ];
        
        return view('admin/sections/index', $data);
    }

    public function store($courseId)
    {
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->to('admin/courses')->with('error', 'Course not found.');
        }

        $rules = [
            'title' => 'required|min_length[2]|max_length[255]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get max order_index
        $maxOrder = $this->sectionModel->where('course_id', $courseId)
            ->selectMax('order_index')
            ->first();
        $nextOrder = ($maxOrder['order_index'] ?? 0) + 1;

        $data = [
            'course_id' => (int)$courseId,
            'title' => trim($this->request->getPost('title')),
            'description' => trim($this->request->getPost('description') ?? ''),
            'order_index' => (int)$nextOrder,
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
        ];
        
        // Remove empty description
        if (empty($data['description'])) {
            unset($data['description']);
        }
        
        if ($this->sectionModel->insert($data)) {
            return redirect()->to('admin/sections/' . $courseId)->with('success', 'Section created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create section.');
        }
    }

    public function update($courseId, $id)
    {
        $section = $this->sectionModel->find($id);
        if (!$section || $section['course_id'] != $courseId) {
            return redirect()->to('admin/sections/' . $courseId)->with('error', 'Section not found.');
        }

        $rules = [
            'title' => 'required|min_length[2]|max_length[255]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => trim($this->request->getPost('title')),
            'description' => trim($this->request->getPost('description') ?? ''),
            'order_index' => (int)$this->request->getPost('order_index'),
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
        ];
        
        // Remove empty description
        if (empty($data['description'])) {
            $data['description'] = null;
        }
        
        if ($this->sectionModel->update($id, $data)) {
            return redirect()->to('admin/sections/' . $courseId)->with('success', 'Section updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update section.');
        }
    }

    public function delete($courseId, $id)
    {
        $section = $this->sectionModel->find($id);
        if (!$section || $section['course_id'] != $courseId) {
            return redirect()->to('admin/sections/' . $courseId)->with('error', 'Section not found.');
        }

        if ($this->sectionModel->delete($id)) {
            return redirect()->to('admin/sections/' . $courseId)->with('success', 'Section deleted successfully!');
        } else {
            return redirect()->to('admin/sections/' . $courseId)->with('error', 'Failed to delete section.');
        }
    }
}

