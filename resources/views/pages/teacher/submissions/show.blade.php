<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('teacher.tasks.submissions.index', $submission->task_id) }}" class="text-text-muted hover:text-text-main transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <span>Submission Details</span>
        </div>
    </x-slot>

    <x-ui.card>
        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
            <div>
                <p class="text-sm font-semibold text-text-muted uppercase tracking-wider">{{ $submission->task->schoolClass->subject->name ?? 'Class' }}</p>
                <h3 class="mt-1 text-2xl font-bold text-text-main">{{ $submission->task->title }}</h3>
                <p class="mt-4 font-semibold text-text-main">{{ $submission->student->name }}</p>
                <p class="text-sm text-text-muted">{{ $submission->student->email }}</p>
                <p class="mt-4 text-sm text-text-muted">
                    Submitted {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'time not recorded' }}
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('teacher.submissions.download', $submission->id) }}" class="px-4 py-2 border border-border rounded text-sm font-semibold text-text-main hover:bg-background transition-colors">Download File</a>
                @if($submission->status !== 'checked')
                    <form method="POST" action="{{ route('teacher.submissions.mark-as-checked', $submission->id) }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-primary text-surface rounded text-sm font-semibold hover:bg-primary-hover transition-colors">Mark Checked</button>
                    </form>
                @endif
            </div>
        </div>
    </x-ui.card>
</x-app-layout>
