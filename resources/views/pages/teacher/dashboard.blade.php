<x-app-layout>
    <x-slot name="header">
        {{ __('Welcome back,') }} {{ Auth::user()->name }}
    </x-slot>

    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-ui.card class="border-l-4 border-l-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-muted uppercase tracking-wider">Active Classes</p>
                        <p class="text-3xl font-bold text-text-main mt-1">{{ $classesCount }}</p>
                    </div>
                    <div class="p-3 bg-primary/10 rounded-lg text-primary">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="border-l-4 border-l-success">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-muted uppercase tracking-wider">Total Submissions</p>
                        <p class="text-3xl font-bold text-text-main mt-1">{{ $totalSubmissions }}</p>
                    </div>
                    <div class="p-3 bg-success/10 rounded-lg text-success">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="border-l-4 border-l-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-muted uppercase tracking-wider">Pending Tasks</p>
                        <p class="text-3xl font-bold text-text-main mt-1">{{ $pendingTasksCount }}</p>
                    </div>
                    <div class="p-3 bg-secondary/10 rounded-lg text-secondary">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Active Tasks Section -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-text-main">Active Tasks</h3>
            </div>

            @if($activeTasks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activeTasks as $task)
                        @php
                            $totalStudents = $task->schoolClass->students->count();
                            $totalSubmissions = $task->submissions->count();
                            $progress = $totalStudents > 0 ? min(100, round(($totalSubmissions / $totalStudents) * 100)) : 0;
                        @endphp
                        <x-ui.card class="hover:border-primary transition-colors group flex flex-col h-full">
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-text-main group-hover:text-primary transition-colors">{{ $task->title }}</h4>
                                <p class="text-xs text-text-muted mt-1 uppercase tracking-wider font-semibold">{{ $task->schoolClass->subject->name ?? 'Class' }}</p>
                                @if($task->description)
                                    <p class="text-sm text-text-muted mt-3 line-clamp-2">{{ $task->description }}</p>
                                @endif
                            </div>
                            
                            <x-slot name="footer">
                                <div class="flex flex-col space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-text-muted font-medium">Submissions</span>
                                        <span class="font-bold text-text-main">{{ $totalSubmissions }} / {{ $totalStudents }}</span>
                                    </div>
                                    <div class="w-full bg-border rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <div class="pt-2 flex justify-between items-center">
                                        <span class="text-xs text-text-muted">
                                            @if($task->due_date)
                                                Due: {{ $task->due_date->format('M d, Y') }}
                                            @else
                                                No Due Date
                                            @endif
                                        </span>
                                        <a href="{{ route('teacher.tasks.submissions.index', $task->id) }}" class="text-sm font-bold text-primary hover:underline">View</a>
                                    </div>
                                </div>
                            </x-slot>
                        </x-ui.card>
                    @endforeach
                </div>
            @else
                <x-ui.card class="text-center py-12">
                    <div class="flex flex-col items-center">
                        <div class="p-4 bg-background rounded-full mb-4">
                            <svg class="w-12 h-12 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-text-main">No active tasks</h4>
                        <p class="text-text-muted text-sm mt-2 max-w-sm">You haven't assigned any tasks yet. Go to your classes to create one.</p>
                        <a href="{{ route('teacher.classes.index') }}" class="mt-6 px-6 py-2 bg-primary text-surface rounded-lg font-bold hover:bg-primary-hover transition-colors shadow-lg shadow-primary/20">
                            Go to Classes
                        </a>
                    </div>
                </x-ui.card>
            @endif
        </section>
    </div>
</x-app-layout>
