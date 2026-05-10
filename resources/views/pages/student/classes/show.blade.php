<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.classes.index') }}" class="text-text-muted hover:text-text-main transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-text-main leading-tight">
                    {{ $schoolClass->subject->name ?? 'Class Details' }}
                </h2>
                <p class="text-sm text-text-muted mt-1">{{ $schoolClass->teacher->name ?? 'Teacher' }} &bull; {{ $schoolClass->day_of_week }}s, {{ \Carbon\Carbon::parse($schoolClass->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schoolClass->end_time)->format('h:i A') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-text-main">Class Assignments</h3>
        </div>

        @if($schoolClass->tasks->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($schoolClass->tasks as $task)
                    @php
                        $submission = $submissions->get($task->id);
                        $isSubmitted = $submission !== null;
                        $isChecked = $isSubmitted && $submission->status === 'checked';
                        $isLate = !$isSubmitted && $task->due_date && $task->due_date->isPast();
                    @endphp
                    
                    <x-ui.card class="hover:border-primary transition-colors flex flex-col h-full relative overflow-hidden">
                        @if($isChecked)
                            <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden">
                                <div class="absolute top-0 right-0 bg-success text-white text-xs font-bold py-1 px-8 transform rotate-45 translate-x-4 -translate-y-2 translate-y-3 origin-top-right whitespace-nowrap shadow-sm">
                                    Graded
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="flex items-start justify-between pr-8">
                                <h4 class="text-lg font-bold text-text-main">{{ $task->title }}</h4>
                            </div>
                            
                            @if($task->description)
                                <p class="text-sm text-text-muted mt-3 line-clamp-3">{{ $task->description }}</p>
                            @endif
                            
                            <div class="mt-4 flex flex-col space-y-2">
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="{{ $isLate ? 'text-error font-semibold' : 'text-text-muted' }}">
                                        @if($task->due_date)
                                            Due: {{ $task->due_date->format('M d, Y \a\t h:i A') }}
                                            @if($isLate) (Overdue) @endif
                                        @else
                                            No Due Date
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @if($isSubmitted)
                                        <span class="text-success font-semibold">Submitted on {{ $submission->created_at->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-secondary font-semibold">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <x-slot name="footer">
                            <div class="pt-2 flex justify-end items-center">
                                <a href="{{ route('student.assignments.show', $task->id) }}" class="text-sm font-bold {{ $isSubmitted ? 'text-text-muted hover:text-text-main' : 'text-primary hover:underline' }}">
                                    {{ $isSubmitted ? 'View Submission' : 'Start Assignment' }}
                                </a>
                            </div>
                        </x-slot>
                    </x-ui.card>
                @endforeach
            </div>
        @else
            <x-ui.card class="text-center py-12">
                <div class="flex flex-col items-center">
                    <div class="p-4 bg-background rounded-full mb-4">
                        <svg class="w-12 h-12 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-text-main">No assignments yet</h4>
                    <p class="text-text-muted text-sm mt-2 max-w-sm">Your teacher hasn't posted any assignments for this class.</p>
                </div>
            </x-ui.card>
        @endif
    </div>
</x-app-layout>
