<?php
namespace App\Controllers;

class ForgotPassword extends BaseController
{
    public function index()
    {
        $data['title'] = 'Forgot Password';   
        return view('pages/forgot-password', $data);
    }
}

