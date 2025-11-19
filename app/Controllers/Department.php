<?php
namespace App\Controllers;

class Department extends BaseController
{
    public function index()
    {
        $data['title'] = 'Department';   
        return view('pages/department', $data);
    }
}

