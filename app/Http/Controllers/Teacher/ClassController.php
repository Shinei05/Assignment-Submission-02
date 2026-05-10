<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Services\ClassService;
use App\Http\Requests\Teacher\StoreClassRequest;
use App\Http\Requests\Teacher\AddStudentsRequest;
use Illuminate\Http\Request;
use App\Models\User;

class ClassController extends Controller
{
    public function __construct(private ClassService $classService)
    {
    }

    public function index(Request $request)
    {
        $schoolClasses = $this->classService->getClassesForTeacher($request->user()->id);
        $subjects = \App\Models\Subject::orderBy('name')->get();
        return view('pages.teacher.classes.index', compact('schoolClasses', 'subjects'));
    }

    public function store(StoreClassRequest $request)
    {
        $this->classService->createClass($request->user()->id, $request->validated());
        return back()->with('success', 'Class created successfully.');
    }

    public function show($classId)
    {
        $schoolClass = $this->classService->getClassDetails($classId);
        
        // Fetch all students for the selection modal
        $allStudents = User::where('role', 'student')->get();
        
        // IDs of students already enrolled to apply grayed-out logic in frontend
        $enrolledStudentIds = $schoolClass->students->pluck('id')->toArray();
        
        return view('pages.teacher.classes.show', compact('schoolClass', 'allStudents', 'enrolledStudentIds'));
    }

    public function addStudents(AddStudentsRequest $request, $classId)
    {
        $this->classService->addStudentsToClass($classId, $request->validated('student_ids'));
        return back()->with('success', 'Students added successfully.');
    }
}
