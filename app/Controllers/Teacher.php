<?php
namespace App\Controllers;

class Teacher extends BaseController
{
    public function index()
    {
        $data['title'] = 'Teachers';   
        return view('pages/teacher', $data);
    }
}

