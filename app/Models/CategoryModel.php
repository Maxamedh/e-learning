<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'category_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'description', 'parent_category_id', 'slug', 
        'thumbnail_url', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|is_unique[categories.slug,category_id,{category_id}]',
    ];

    public function getActiveCategories()
    {
        return $this->where('is_active', true)->findAll();
    }

    public function getCategoriesWithParent()
    {
        return $this->select('categories.*, parent.name as parent_name')
            ->join('categories as parent', 'parent.category_id = categories.parent_category_id', 'left')
            ->findAll();
    }
}
