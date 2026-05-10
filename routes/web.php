<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ClassController as StudentClassController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\CalendarController as StudentCalendarController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Teacher\TaskController as TeacherTaskController;
use App\Http\Controllers\Teacher\SubmissionController as TeacherSubmissionController;
use App\Http\Controllers\Teacher\CalendarController as TeacherCalendarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/classes', [StudentClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/{class}', [StudentClassController::class, 'show'])->name('classes.show');
        
        Route::get('/assignments', [StudentAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/classes/{class}/tasks/{task}', [StudentAssignmentController::class, 'showTask'])->name('classes.tasks.show');
        Route::get('/assignments/{task}', [StudentAssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{task}/submit', [StudentAssignmentController::class, 'submit'])->name('assignments.submit');
        Route::get('/reference-files/{referenceFile}/download', [StudentAssignmentController::class, 'downloadReferenceFile'])->name('reference-files.download');
        Route::get('/submissions/{submission}/download', [StudentAssignmentController::class, 'downloadSubmission'])->name('submissions.download');
        
        Route::get('/calendar', [StudentCalendarController::class, 'index'])->name('calendar');
    });

    Route::middleware('role:teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/classes', [TeacherClassController::class, 'index'])->name('classes.index');
        Route::post('/classes', [TeacherClassController::class, 'store'])->name('classes.store');
        Route::get('/classes/{class}', [TeacherClassController::class, 'show'])->name('classes.show');
        Route::post('/classes/{class}/students', [TeacherClassController::class, 'addStudents'])->name('classes.students.add');
        
        Route::post('/classes/{class}/tasks', [TeacherTaskController::class, 'store'])->name('classes.tasks.store');
        Route::post('/classes/{class}/tasks/{task}', [TeacherTaskController::class, 'update'])->name('classes.tasks.update');
        Route::delete('/classes/{class}/tasks/{task}', [TeacherTaskController::class, 'destroy'])->name('classes.tasks.destroy');
        
        Route::get('/tasks/{task}/submissions', [TeacherSubmissionController::class, 'taskSubmissions'])->name('tasks.submissions.index');
        Route::get('/submissions', [TeacherSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [TeacherSubmissionController::class, 'show'])->name('submissions.show');
        Route::post('/submissions/{submission}/mark-as-checked', [TeacherSubmissionController::class, 'markAsChecked'])->name('submissions.mark-as-checked');
        Route::get('/submissions/{submission}/download', [TeacherSubmissionController::class, 'download'])->name('submissions.download');
        
        Route::get('/calendar', [TeacherCalendarController::class, 'index'])->name('calendar');
    });
});

require __DIR__.'/auth.php';
