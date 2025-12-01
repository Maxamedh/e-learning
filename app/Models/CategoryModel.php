<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'description', 'icon', 'parent_id', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
    ];

    public function getActiveCategories()
    {
        return $this->where('is_active', true)->findAll();
    }

    public function getCategoriesWithParent()
    {
        return $this->select('categories.*, parent.name as parent_name')
            ->join('categories as parent', 'parent.id = categories.parent_id', 'left')
            ->findAll();
    }

    public function getParentCategories()
    {
        return $this->where('parent_id', null)->where('is_active', true)->findAll();
    }

    public function getSubCategories($parentId)
    {
        return $this->where('parent_id', $parentId)->where('is_active', true)->findAll();
    }
}
