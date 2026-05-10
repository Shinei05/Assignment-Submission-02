<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitAssignmentRequest;
use App\Models\Submission;
use App\Models\Task;
use App\Models\TaskReferenceFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        return view('pages.student.assignments.index');
    }

    public function showTask($class, $task)
    {
        return view('pages.student.assignments.show');
    }

    public function show(Request $request, $taskId)
    {
        $student = $request->user();
        $task = Task::with(['schoolClass.subject', 'schoolClass.teacher', 'referenceFiles'])
            ->findOrFail($taskId);

        if (!$student->studentClasses()->where('class_id', $task->class_id)->exists()) {
            abort(403, 'Unauthorized access to this assignment.');
        }

        $submission = Submission::where('task_id', $taskId)
            ->where('student_id', $student->id)
            ->first();

        return view('pages.student.assignments.show', compact('task', 'submission'));
    }

    public function submit(SubmitAssignmentRequest $request, $taskId)
    {
        $student = $request->user();
        $task = Task::findOrFail($taskId);

        if (!$student->studentClasses()->where('class_id', $task->class_id)->exists()) {
            abort(403);
        }

        $isLate = $task->due_date && now()->greaterThan($task->due_date);

        $submission = Submission::where('task_id', $taskId)
            ->where('student_id', $student->id)
            ->first();

        // Resubmission is allowed only until the due date.
        if ($submission && $task->due_date && now()->greaterThan($task->due_date)) {
            return back()->withErrors([
                'submission_file' => 'Resubmission is closed because the due date has passed.',
            ]);
        }

        if ($submission && $submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $uploadedFile = $request->file('submission_file');
        $path = $uploadedFile->store(
            "submissions/class_{$task->class_id}/task_{$task->id}/student_{$student->id}",
            'public'
        );

        $payload = [
            'status' => $isLate ? 'late' : 'pending',
            'file_path' => $path,
            'original_file_name' => $uploadedFile->getClientOriginalName(),
            'submitted_at' => now(),
        ];

        if ($submission) {
            $submission->update($payload);
        } else {
            Submission::create([
                'task_id' => $taskId,
                'student_id' => $student->id,
                ...$payload,
            ]);
        }

        return redirect()->back()->with('success', 'Assignment submitted successfully!');
    }

    public function downloadReferenceFile(Request $request, int $referenceFileId)
    {
        $student = $request->user();
        $referenceFile = TaskReferenceFile::with('task')->findOrFail($referenceFileId);

        if (!$student->studentClasses()->where('class_id', $referenceFile->task->class_id)->exists()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($referenceFile->file_path)) {
            abort(404, 'Reference file not found.');
        }

        return Storage::disk('public')->download(
            $referenceFile->file_path,
            $referenceFile->original_file_name ?: basename($referenceFile->file_path)
        );
    }

    public function downloadSubmission(Request $request, int $submissionId)
    {
        $submission = Submission::findOrFail($submissionId);

        if ((int) $submission->student_id !== (int) $request->user()->id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($submission->file_path)) {
            abort(404, 'Submission file not found.');
        }

        return Storage::disk('public')->download(
            $submission->file_path,
            $submission->original_file_name ?: basename($submission->file_path)
        );
    }
}
