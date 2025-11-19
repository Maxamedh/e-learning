<?php
namespace App\Controllers;

class Library extends BaseController
{
    public function index()
    {
        $data['title'] = 'Library';   
        return view('pages/library', $data);
    }
}

