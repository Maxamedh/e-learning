<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryApiController extends BaseApiController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $this->authenticate();
        $categories = $this->categoryModel->getCategoriesWithParent();
        return $this->respond(['status' => 'success', 'data' => $categories]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return $this->failNotFound('Category not found');
        }
        return $this->respond(['status' => 'success', 'data' => $category]);
    }

    public function create()
    {
        $this->authenticate();
        if (!$this->requirePermission(['admin'])) return;

        $data = $this->request->getJSON(true);
        if ($this->categoryModel->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Category created', 'data' => $this->categoryModel->find($this->categoryModel->getInsertID())]);
        }
        return $this->failValidationErrors($this->categoryModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        if (!$this->requirePermission(['admin'])) return;

        $data = $this->request->getJSON(true);
        if ($this->categoryModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Category updated', 'data' => $this->categoryModel->find($id)]);
        }
        return $this->failValidationErrors($this->categoryModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        if (!$this->requirePermission(['admin'])) return;

        if ($this->categoryModel->delete($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Category deleted']);
        }
        return $this->fail('Failed to delete category');
    }
}

