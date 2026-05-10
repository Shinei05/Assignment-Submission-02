<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();
        
        // Fetch classes the student is enrolled in
        $enrolledClasses = $student->studentClasses()->with('subject', 'teacher')->get();
        $classesCount = $enrolledClasses->count();
        
        $classIds = $enrolledClasses->pluck('id');
        
        // Count total tasks in these classes
        $totalTasksCount = \App\Models\Task::whereIn('class_id', $classIds)->count();
        
        // Count completed tasks (submissions made by this student)
        $completedTasksCount = \App\Models\Submission::where('student_id', $student->id)->count();
        
        // Calculate pending tasks
        $pendingTasksCount = max(0, $totalTasksCount - $completedTasksCount);

        return view('pages.student.dashboard', [
            'enrolledClasses' => $enrolledClasses,
            'classesCount' => $classesCount,
            'pendingTasksCount' => $pendingTasksCount,
            'completedTasksCount' => $completedTasksCount,
        ]);
    }
}
