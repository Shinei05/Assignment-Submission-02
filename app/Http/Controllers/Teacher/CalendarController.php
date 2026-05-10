<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class CalendarController extends Controller
{
    public function index()
    {
        return view('pages.teacher.calendar');
    }
}
