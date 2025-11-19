<?php
namespace App\Controllers;

class Course extends BaseController
{
    public function index()
    {
        helper('auth');
        if (!is_logged_in()) {
            return redirect()->to('login');
        }
        
        $data['title'] = 'Courses';   
        return view('pages/course-management', $data);
    }
}

