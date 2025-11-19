<?php
namespace App\Controllers;

class Students extends BaseController
{
    public function index()
    {
        $data['title'] = 'Students';   
        return view('pages/students', $data);
    }
}

