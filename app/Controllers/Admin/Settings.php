<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Settings extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Settings',
        ];

        return view('admin/settings/index', $data);
    }
}

