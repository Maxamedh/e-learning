<?php
namespace App\Controllers;

class TableBootstrap extends BaseController
{
    public function index()
    {
        $data['title'] = 'Bootstrap Table';   
        return view('pages/table-bootstrap', $data);
    }
}

