<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class SeedCategories extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();
        
        $categories = [
            [
                'name' => 'Web Development',
                'description' => 'Learn to build modern websites and web applications',
                'icon' => 'fa-code',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Create mobile apps for iOS and Android',
                'icon' => 'fa-mobile-alt',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Data Science',
                'description' => 'Analyze data and build machine learning models',
                'icon' => 'fa-chart-line',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Design',
                'description' => 'UI/UX design, graphic design, and digital art',
                'icon' => 'fa-palette',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'description' => 'Business skills, entrepreneurship, and management',
                'icon' => 'fa-briefcase',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'description' => 'Digital marketing, SEO, and social media',
                'icon' => 'fa-bullhorn',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Photography',
                'description' => 'Learn photography techniques and editing',
                'icon' => 'fa-camera',
                'parent_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Music',
                'description' => 'Music production, instruments, and theory',
                'icon' => 'fa-music',
                'parent_id' => null,
                'is_active' => true,
            ],
            // Sub-categories
            [
                'name' => 'Frontend Development',
                'description' => 'HTML, CSS, JavaScript, React, Vue',
                'icon' => 'fa-laptop-code',
                'parent_id' => null, // Will be set after parent is created
                'is_active' => true,
            ],
            [
                'name' => 'Backend Development',
                'description' => 'Server-side programming, APIs, databases',
                'icon' => 'fa-server',
                'parent_id' => null,
                'is_active' => true,
            ],
        ];
        
        $inserted = 0;
        $parentIds = [];
        
        foreach ($categories as $index => $category) {
            // Get parent ID if it's a sub-category
            if ($index >= 8 && isset($parentIds['Web Development'])) {
                $category['parent_id'] = $parentIds['Web Development'];
            }
            
            // Check if category already exists
            $existing = $categoryModel->where('name', $category['name'])->first();
            if (!$existing) {
                $categoryModel->insert($category);
                $inserted++;
                if ($index < 8) {
                    $parentIds[$category['name']] = $categoryModel->getInsertID();
                }
            }
        }
        
        return redirect()->to('admin/categories')->with('success', "Successfully inserted {$inserted} sample categories!");
    }
}

