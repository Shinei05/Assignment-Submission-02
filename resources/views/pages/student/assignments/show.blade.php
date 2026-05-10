<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.classes.show', $task->class_id) }}" class="text-text-muted hover:text-text-main transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-text-main leading-tight">
                    {{ $task->schoolClass->subject->name ?? 'Class' }} - Task Details
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content: Task Details -->
            <div class="lg:col-span-2 space-y-6">
                <x-ui.card>
                    <div class="flex items-start justify-between border-b border-border pb-4 mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-text-main">{{ $task->title }}</h3>
                            <p class="text-sm text-text-muted mt-1">{{ $task->schoolClass->teacher->name ?? 'Teacher' }} &bull; Posted on {{ $task->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-text-muted uppercase tracking-wider">Due Date</p>
                            <p class="text-lg font-bold {{ (!$submission && $task->due_date && $task->due_date->isPast()) ? 'text-error' : 'text-text-main' }}">
                                {{ $task->due_date ? $task->due_date->format('M d, Y \a\t h:i A') : 'No Due Date' }}
                            </p>
                        </div>
                    </div>

                    <div class="prose max-w-none text-text-main">
                        {!! nl2br(e($task->description)) !!}
                    </div>

                    @if($task->referenceFiles && $task->referenceFiles->count() > 0)
                        <div class="mt-8 border-t border-border pt-4">
                            <h4 class="text-sm font-bold text-text-main mb-3 uppercase tracking-wider">Reference Files</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($task->referenceFiles as $file)
                                    <a href="{{ route('student.reference-files.download', $file->id) }}" class="flex items-center gap-3 p-3 border border-border rounded-lg hover:border-primary hover:bg-background transition-all group">
                                        <div class="p-2 bg-primary/10 text-primary rounded-md group-hover:bg-primary group-hover:text-surface transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        </div>
                                        <span class="text-sm font-medium text-text-main truncate">{{ $file->original_file_name ?: basename($file->file_path) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </x-ui.card>
            </div>

            <!-- Sidebar: Submission Status -->
            <div class="space-y-6">
                @php
                    $isPastDue = $task->due_date && now()->greaterThan($task->due_date);
                    $canResubmit = !$task->due_date || now()->lessThanOrEqualTo($task->due_date);
                    $showUploadForm = !$submission || $canResubmit;
                @endphp

                <x-ui.card class="border-t-4 {{ $submission ? ($submission->status === 'checked' ? 'border-t-success' : ($submission->status === 'late' ? 'border-t-error' : 'border-t-secondary')) : 'border-t-primary' }}">
                    <h3 class="text-lg font-bold text-text-main mb-4">Your Work</h3>

                    @if($errors->has('submission_file'))
                        <div class="mb-4 bg-danger/10 border border-danger/20 text-danger px-3 py-2 rounded-lg text-sm">
                            {{ $errors->first('submission_file') }}
                        </div>
                    @endif
                    
                    @if($submission)
                        <div class="flex items-center gap-3 mb-6 p-4 rounded-lg {{ $submission->status === 'checked' ? 'bg-success/10 text-success' : ($submission->status === 'late' ? 'bg-danger/10 text-danger' : 'bg-secondary/10 text-secondary') }}">
                            @if($submission->status === 'checked')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="font-bold">Graded</p>
                                    <p class="text-xs opacity-80">Your teacher has reviewed this.</p>
                                </div>
                            @elseif($submission->status === 'late')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="font-bold">Submitted Late</p>
                                    <p class="text-xs opacity-80">Submitted after the due date.</p>
                                </div>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="font-bold">Turned In</p>
                                    <p class="text-xs opacity-80">Waiting for review.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="space-y-2 text-sm text-text-muted mb-6">
                            <div class="flex justify-between">
                                <span>Submitted At:</span>
                                <span class="font-medium text-text-main">{{ $submission->submitted_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span>File:</span>
                                <span class="font-medium text-text-main truncate">{{ $submission->original_file_name ?: basename($submission->file_path) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('student.submissions.download', $submission->id) }}" class="mb-4 w-full flex items-center justify-center gap-2 py-2.5 px-4 border border-border text-text-main rounded-lg font-semibold hover:bg-background transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download My Submission
                        </a>

                        @if($showUploadForm)
                            <p class="text-xs text-text-muted mb-3">You can replace this file until the due date.</p>
                        @endif
                    @else
                        <div class="mb-6">
                            <p class="text-sm text-text-muted">You haven't submitted this assignment yet. Upload your file to submit.</p>
                        </div>
                    @endif

                    @if($showUploadForm)
                        <form id="submission-upload-form" action="{{ route('student.assignments.submit', $task->id) }}" method="POST" enctype="multipart/form-data" x-data="{ fileName: '', isDragging: false }">
                            @csrf

                            <input
                                id="submission_file"
                                name="submission_file"
                                type="file"
                                class="hidden"
                                accept=".pdf,.xls,.xlsx,.docx,.json"
                                x-ref="submissionFile"
                                @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''"
                                required
                            >

                            <div
                                class="border-2 border-dashed rounded-xl p-5 text-center transition-colors cursor-pointer"
                                :class="isDragging ? 'border-primary bg-primary/5' : 'border-border hover:border-primary/60 hover:bg-background'"
                                @click="$refs.submissionFile.click()"
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                @drop.prevent="
                                    isDragging = false;
                                    if ($event.dataTransfer.files.length) {
                                        $refs.submissionFile.files = $event.dataTransfer.files;
                                        fileName = $event.dataTransfer.files[0].name;
                                    }
                                "
                            >
                                <svg class="w-8 h-8 mx-auto text-text-muted mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="text-sm font-semibold text-text-main">Drag and drop your file here</p>
                                <p class="text-xs text-text-muted mt-1">or click to choose a file (PDF, EXCEL, DOCX, JSON up to 25 MB)</p>
                                <p x-show="fileName" class="text-xs text-primary mt-3 font-medium truncate" x-text="`Selected: ${fileName}`"></p>
                            </div>

                            @if($isPastDue && !$submission)
                                <div class="mt-3 text-xs bg-yellow-100 border border-yellow-300 text-yellow-800 px-3 py-2 rounded-lg">
                                    This assignment is past due. Submitting now will mark your work as late.
                                </div>
                            @endif

                            <button type="button" onclick="if (!document.getElementById('submission_file').files.length) { return; } window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-submit-assignment' }));" class="mt-4 w-full flex items-center justify-center gap-2 py-3 px-4 bg-primary text-surface rounded-lg font-bold hover:bg-primary-hover transition-colors shadow-lg shadow-primary/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ $submission ? 'Resubmit Assignment' : 'Submit Assignment' }}
                            </button>
                        </form>
                    @endif
                </x-ui.card>
            </div>
        </div>
    </div>

    <x-modal name="confirm-submit-assignment" focusable>
        <div class="p-6">
            <h3 class="text-lg font-bold text-text-main mb-2">Confirm Submission</h3>
            <p class="text-sm text-text-muted mb-4">
                @if($isPastDue && !$submission)
                    This file will be submitted and marked as late. Continue?
                @else
                    Are you sure you want to submit this file now?
                @endif
            </p>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>
                <button
                    type="button"
                    class="px-4 py-2 bg-primary hover:bg-primary-hover text-surface rounded text-sm font-medium transition-colors"
                    onclick="document.getElementById('submission-upload-form').submit();"
                >
                    Confirm Submit
                </button>
            </div>
        </div>
    </x-modal>
</x-app-layout>
