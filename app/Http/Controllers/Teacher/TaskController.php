<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Services\TaskService;
use App\Http\Requests\Teacher\StoreTaskRequest;
use App\Http\Requests\Teacher\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService)
    {
    }

    public function store(StoreTaskRequest $request, $classId)
    {
        $this->taskService->createTask($classId, $request->validated());
        return back()->with('success', 'Task created successfully.');
    }

    public function update(UpdateTaskRequest $request, $classId, $taskId)
    {
        $this->taskService->updateTask($taskId, $request->validated());
        return back()->with('success', 'Task updated successfully.');
    }

    public function destroy($classId, $taskId)
    {
        $this->taskService->deleteTask($taskId);
        return back()->with('success', 'Task deleted successfully.');
    }
}
