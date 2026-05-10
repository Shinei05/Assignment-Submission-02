<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class CalendarController extends Controller
{
    public function index()
    {
        return view('pages.student.calendar');
    }
}
