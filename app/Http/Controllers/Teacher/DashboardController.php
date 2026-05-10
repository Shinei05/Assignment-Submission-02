<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Services\ClassService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private ClassService $classService)
    {
    }

    public function index(Request $request)
    {
        $teacherId = $request->user()->id;
        $classes = $this->classService->getClassesForTeacher($teacherId);
        $classesCount = $classes->count();
        $classIds = $classes->pluck('id');
        
        $activeTasks = \App\Models\Task::whereIn('class_id', $classIds)
            ->with(['schoolClass.subject', 'schoolClass.students', 'submissions'])
            ->latest()
            ->take(6)
            ->get();
            
        $totalSubmissions = \App\Models\Submission::whereHas('task', function($q) use($classIds) {
            $q->whereIn('class_id', $classIds);
        })->count();

        $pendingTasksCount = \App\Models\Task::whereIn('class_id', $classIds)
            ->where(function($q) {
                $q->where('due_date', '>', now())
                  ->orWhereNull('due_date');
            })->count();
        
        return view('pages.teacher.dashboard', [
            'classesCount' => $classesCount,
            'totalSubmissions' => $totalSubmissions,
            'pendingTasksCount' => $pendingTasksCount,
            'activeTasks' => $activeTasks,
        ]);
    }
}
