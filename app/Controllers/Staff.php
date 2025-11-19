<?php
namespace App\Controllers;

class Staff extends BaseController
{
    public function index()
    {
        $data['title'] = 'Staff';   
        return view('pages/staff', $data);
    }
}

