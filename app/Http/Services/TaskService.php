<?php

namespace App\Http\Services;

use App\Http\Repositories\TaskRepository;
use App\Models\Task;
use App\Models\TaskReferenceFile;
use Illuminate\Http\UploadedFile;

class TaskService
{
    public function __construct(private TaskRepository $taskRepository)
    {
    }

    public function getTasksForClass(int $classId)
    {
        return $this->taskRepository->getByClassId($classId);
    }

    public function createTask(int $classId, array $data): Task
    {
        $referenceFiles = $data['reference_files'] ?? [];
        unset($data['reference_files']);

        $data['class_id'] = $classId;
        $task = $this->taskRepository->create($data);

        $this->storeReferenceFiles($task, $referenceFiles);

        return $task;
    }

    public function updateTask(int $taskId, array $data): bool
    {
        $referenceFiles = $data['reference_files'] ?? [];
        unset($data['reference_files']);

        $task = $this->taskRepository->find($taskId);
        $updated = $this->taskRepository->update($task, $data);

        if ($updated) {
            $this->storeReferenceFiles($task, $referenceFiles);
        }

        return $updated;
    }

    public function deleteTask(int $taskId): bool
    {
        $task = $this->taskRepository->find($taskId);
        return $this->taskRepository->delete($task);
    }

    /**
     * Persist uploaded task reference files under the public disk.
     *
     * @param  UploadedFile[]  $referenceFiles
     */
    private function storeReferenceFiles(Task $task, array $referenceFiles): void
    {
        foreach ($referenceFiles as $referenceFile) {
            if (!$referenceFile instanceof UploadedFile) {
                continue;
            }

            $path = $referenceFile->store(
                "reference-files/class_{$task->class_id}/task_{$task->id}",
                'public'
            );

            TaskReferenceFile::create([
                'task_id' => $task->id,
                'file_path' => $path,
                'original_file_name' => $referenceFile->getClientOriginalName(),
            ]);
        }
    }
}
