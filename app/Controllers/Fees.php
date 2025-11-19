<?php
namespace App\Controllers;

class Fees extends BaseController
{
    public function index()
    {
        $data['title'] = 'Fees';   
        return view('pages/fees', $data);
    }
}

