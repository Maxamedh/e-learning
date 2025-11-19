<?php
namespace App\Controllers;

class Signup extends BaseController
{
    public function index()
    {
        $data['title'] = 'Sign Up';   
        return view('pages/signup', $data);
    }
}

