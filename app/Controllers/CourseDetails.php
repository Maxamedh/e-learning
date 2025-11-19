<?php
namespace App\Controllers;

class CourseDetails extends BaseController
{
    public function index()
    {
        $data['title'] = 'Course Details';   
        return view('pages/course-details', $data);
    }
}

