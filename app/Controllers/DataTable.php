<?php
namespace App\Controllers;

class DataTable extends BaseController
{
    public function index()
    {
        $data['title'] = 'DataTable';   
        return view('pages/data-table', $data);
    }
}

