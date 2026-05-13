<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function taskSubmissions(Request $request, int $taskId)
    {
        $task = Task::with([
            'schoolClass.subject',
            'schoolClass.students',
            'submissions.student',
        ])->findOrFail($taskId);

        if ((int) $task->schoolClass->teacher_id !== (int) $request->user()->id) {
            abort(403);
        }

        $submissions = $task->submissions->sortByDesc('submitted_at');
        $submittedStudentIds = $submissions->pluck('student_id')->all();
        $missingStudents = $task->schoolClass->students
            ->whereNotIn('id', $submittedStudentIds)
            ->sortBy('name');

        return view('pages.teacher.submissions.index', compact('task', 'submissions', 'missingStudents'));
    }

    public function index(Request $request)
    {
        $submissions = Submission::with(['student', 'task.schoolClass.subject'])
            ->whereHas('task.schoolClass', function ($query) use ($request) {
                $query->where('teacher_id', $request->user()->id);
            })
            ->latest('submitted_at')
            ->paginate(15);

        return view('pages.teacher.submissions.index', [
            'task' => null,
            'submissions' => $submissions,
            'missingStudents' => collect(),
        ]);
    }

    public function show(Request $request, int $submissionId)
    {
        $submission = Submission::with(['student', 'task.schoolClass.subject'])->findOrFail($submissionId);

        if ((int) $submission->task->schoolClass->teacher_id !== (int) $request->user()->id) {
            abort(403);
        }

        return view('pages.teacher.submissions.show', compact('submission'));
    }

    public function markAsChecked(Request $request, int $submissionId)
    {
        $submission = Submission::with('task.schoolClass')->findOrFail($submissionId);

        if ((int) $submission->task->schoolClass->teacher_id !== (int) $request->user()->id) {
            abort(403);
        }

        $submission->update(['status' => 'checked']);

        return back()->with('success', 'Submission marked as checked.');
    }

    public function download(Request $request, int $submissionId)
    {
        $submission = Submission::with('task.schoolClass')->findOrFail($submissionId);

        if ((int) $submission->task->schoolClass->teacher_id !== (int) $request->user()->id) {
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
