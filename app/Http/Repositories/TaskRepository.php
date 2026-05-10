<?php

namespace App\Http\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function getByClassId(int $classId): Collection
    {
        return Task::where('class_id', $classId)->get();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function find(int $taskId): ?Task
    {
        return Task::findOrFail($taskId);
    }

    public function update(Task $task, array $data): bool
    {
        return $task->update($data);
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
