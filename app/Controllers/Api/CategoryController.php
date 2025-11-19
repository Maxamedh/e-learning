<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        $categories = $this->categoryModel->getActiveCategories();
        return ajax_response(true, 'Categories retrieved', ['categories' => $categories]);
    }

    public function show($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Category ID required', null, 400);
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return ajax_response(false, 'Category not found', null, 404);
        }

        return ajax_response(true, 'Category retrieved', ['category' => $category]);
    }

    public function create()
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        $user = get_current_user();
        if ($user['user_type'] !== 'admin') {
            return ajax_response(false, 'Only admins can create categories', null, 403);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'parent_category_id' => $this->request->getPost('parent_category_id'),
            'slug' => $this->request->getPost('slug') ?: url_title($this->request->getPost('name'), '-', true),
            'thumbnail_url' => $this->request->getPost('thumbnail_url'),
            'is_active' => $this->request->getPost('is_active') !== null ? (bool)$this->request->getPost('is_active') : true,
        ];

        if (!$this->categoryModel->insert($data)) {
            return ajax_response(false, 'Failed to create category: ' . implode(', ', $this->categoryModel->errors()), null, 400);
        }

        $category = $this->categoryModel->find($this->categoryModel->getInsertID());
        return ajax_response(true, 'Category created successfully', ['category' => $category]);
    }

    public function update($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Category ID required', null, 400);
        }

        $user = get_current_user();
        if ($user['user_type'] !== 'admin') {
            return ajax_response(false, 'Only admins can update categories', null, 403);
        }

        $data = [];
        $fields = ['name', 'description', 'parent_category_id', 'slug', 'thumbnail_url', 'is_active'];
        
        foreach ($fields as $field) {
            if ($this->request->getPost($field) !== null) {
                $data[$field] = $this->request->getPost($field);
            }
        }

        if (empty($data)) {
            return ajax_response(false, 'No data to update', null, 400);
        }

        if (!$this->categoryModel->update($id, $data)) {
            return ajax_response(false, 'Failed to update category: ' . implode(', ', $this->categoryModel->errors()), null, 400);
        }

        $category = $this->categoryModel->find($id);
        return ajax_response(true, 'Category updated successfully', ['category' => $category]);
    }

    public function delete($id = null)
    {
        helper('auth');
        if (!validate_ajax_request()) {
            return ajax_response(false, 'Unauthorized', null, 401);
        }

        if (!$id) {
            return ajax_response(false, 'Category ID required', null, 400);
        }

        $user = get_current_user();
        if ($user['user_type'] !== 'admin') {
            return ajax_response(false, 'Only admins can delete categories', null, 403);
        }

        if (!$this->categoryModel->delete($id)) {
            return ajax_response(false, 'Failed to delete category', null, 400);
        }

        return ajax_response(true, 'Category deleted successfully');
    }
}

