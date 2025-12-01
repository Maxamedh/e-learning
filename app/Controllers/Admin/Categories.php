<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categories = $this->categoryModel->getCategoriesWithParent();
        
        $data = [
            'title' => 'Categories Management',
            'categories' => $categories,
        ];
        
        return view('admin/categories/index', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'icon' => $this->request->getPost('icon'),
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];
        
        if ($this->categoryModel->insert($data)) {
            return redirect()->to('admin/categories')->with('success', 'Category created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create category.');
        }
    }

    public function update($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('admin/categories')->with('error', 'Category not found.');
        }
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'icon' => $this->request->getPost('icon'),
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];
        
        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('admin/categories')->with('success', 'Category updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update category.');
        }
    }

    public function delete($id)
    {
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('admin/categories')->with('error', 'Category not found.');
        }
        
        if ($this->categoryModel->delete($id)) {
            return redirect()->to('admin/categories')->with('success', 'Category deleted successfully!');
        } else {
            return redirect()->to('admin/categories')->with('error', 'Failed to delete category.');
        }
    }
}

