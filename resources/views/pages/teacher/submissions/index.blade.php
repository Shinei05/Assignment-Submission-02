<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            @if($task)
                <a href="{{ route('teacher.dashboard') }}" class="text-text-muted hover:text-text-main transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
            @endif
            <span>{{ $task ? 'Task Submissions' : 'All Submissions' }}</span>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if($task)
            <x-ui.card>
                <div class="flex flex-col gap-6">
                    <div>
                        <p class="text-sm font-semibold text-text-muted uppercase tracking-wider">{{ $task->schoolClass->subject->name ?? 'Class' }}</p>
                        <h3 class="mt-1 text-2xl font-bold text-text-main">{{ $task->title }}</h3>
                        @if($task->due_date)
                            <p class="mt-2 text-sm text-text-muted">Due {{ $task->due_date->format('M d, Y h:i A') }}</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        <div class="flex items-center gap-4 rounded-xl border border-border px-6 py-4 bg-surface transition-shadow hover:shadow-sm">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-text-muted font-bold uppercase tracking-wider">Students</p>
                                <p class="text-2xl font-bold text-text-main leading-none mt-1">{{ $task->schoolClass->students->count() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 rounded-xl border border-success/20 px-6 py-4 bg-success/5 transition-shadow hover:shadow-sm">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-success/20 flex items-center justify-center text-success">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-success font-bold uppercase tracking-wider">Submitted</p>
                                <p class="text-2xl font-bold text-success leading-none mt-1">{{ $submissions->count() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 rounded-xl border border-danger/20 px-6 py-4 bg-danger/5 transition-shadow hover:shadow-sm">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-danger/20 flex items-center justify-center text-danger">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-danger font-bold uppercase tracking-wider">Missing</p>
                                <p class="text-2xl font-bold text-danger leading-none mt-1">{{ $missingStudents->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        @endif

        <div class="{{ $task ? 'grid grid-cols-1 xl:grid-cols-3 gap-6 items-start' : 'space-y-6' }}">
            <div class="{{ $task ? 'xl:col-span-2 flex flex-col space-y-6' : 'space-y-6' }}">
                <x-ui.card title="Submitted Work">
                    @if($submissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-border">
                                <thead>
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-text-muted">
                                        <th class="pb-3 pr-4">Student</th>
                                        @unless($task)
                                            <th class="pb-3 pr-4">Task</th>
                                        @endunless
                                        <th class="pb-3 pr-4">Submitted</th>
                                        <th class="pb-3 pr-4">Status</th>
                                        <th class="pb-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    @foreach($submissions as $submission)
                                        <tr>
                                            <td class="py-4 pr-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-sm">
                                                        {{ strtoupper(substr($submission->student->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-text-main text-sm">{{ $submission->student->name }}</p>
                                                        <p class="text-xs text-text-muted">{{ $submission->student->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            @unless($task)
                                                <td class="py-4 pr-4">
                                                    <p class="font-semibold text-text-main text-sm">{{ $submission->task->title }}</p>
                                                    <p class="text-xs text-text-muted">{{ $submission->task->schoolClass->subject->name ?? 'Class' }}</p>
                                                </td>
                                            @endunless
                                            <td class="py-4 pr-4 text-sm text-text-muted">
                                                {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y h:i A') : 'Not recorded' }}
                                            </td>
                                            <td class="py-4 pr-4">
                                                <span @class([
                                                    'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize',
                                                    'bg-success/10 text-success' => $submission->status === 'checked',
                                                    'bg-danger/10 text-danger' => $submission->status === 'late',
                                                    'bg-secondary/10 text-secondary' => $submission->status === 'pending',
                                                ])>{{ $submission->status }}</span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('teacher.submissions.download', $submission->id) }}" class="px-3 py-1.5 border border-border rounded text-sm font-semibold text-text-main hover:bg-background transition-colors">Download</a>
                                                    @if($submission->status !== 'checked')
                                                        <form method="POST" action="{{ route('teacher.submissions.mark-as-checked', $submission->id) }}">
                                                            @csrf
                                                            <button type="submit" class="px-3 py-1.5 bg-primary text-surface rounded text-sm font-semibold hover:bg-primary-hover transition-colors whitespace-nowrap">Mark Checked</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($submissions, 'links'))
                            <div class="mt-6">
                                {{ $submissions->links() }}
                            </div>
                        @endif
                    @else
                        <div class="py-10 text-center">
                            <h3 class="text-lg font-bold text-text-main">No submissions yet</h3>
                            <p class="mt-2 text-sm text-text-muted">Submitted files will appear here once students turn in their work.</p>
                        </div>
                    @endif
                </x-ui.card>
            </div>

            @if($task && $missingStudents->count() > 0)
                <div class="xl:col-span-1">
                    <x-ui.card title="Missing Submissions">
                        <div class="flex flex-col gap-3">
                            @foreach($missingStudents as $student)
                                <div class="flex items-center gap-3 p-3 rounded-lg border border-border bg-surface transition-colors hover:bg-background">
                                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-danger/10 text-danger font-bold">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-text-main truncate">{{ $student->name }}</p>
                                        <p class="text-xs text-text-muted truncate">{{ $student->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-ui.card>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
