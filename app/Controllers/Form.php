<?php
namespace App\Controllers;

class Form extends BaseController
{
    public function index()
    {
        $data['title'] = 'Form Elements';   
        return view('pages/form', $data);
    }
}

