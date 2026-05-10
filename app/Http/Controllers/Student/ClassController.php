<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();
        $enrolledClasses = $student->studentClasses()
            ->with(['subject', 'teacher'])
            ->latest()
            ->paginate(12);

        return view('pages.student.classes.index', compact('enrolledClasses'));
    }

    public function show(Request $request, $classId)
    {
        $student = $request->user();
        
        // Ensure the student is actually enrolled in this class
        $schoolClass = $student->studentClasses()
            ->with(['subject', 'teacher', 'tasks' => function($query) {
                $query->latest();
            }])
            ->findOrFail($classId);

        // Calculate progress or fetch submissions if needed
        $submissions = \App\Models\Submission::where('student_id', $student->id)
            ->whereIn('task_id', $schoolClass->tasks->pluck('id'))
            ->get()
            ->keyBy('task_id');

        return view('pages.student.classes.show', compact('schoolClass', 'submissions'));
    }
}
