<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function taskSubmissions($task)
    {
        return view('pages.teacher.submissions.index');
    }

    public function index()
    {
        return view('pages.teacher.submissions.index');
    }

    public function show($submission)
    {
        return view('pages.teacher.submissions.show');
    }

    public function markAsChecked($submission)
    {
        return back();
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
