<x-app-layout>
    <x-slot name="header">
        {{ __('Welcome,') }} {{ Auth::user()->name }}
    </x-slot>

    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-ui.card class="border-l-4 border-l-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-muted uppercase tracking-wider">Enrolled Classes</p>
                        <p class="text-3xl font-bold text-text-main mt-1">{{ $classesCount }}</p>
                    </div>
                    <div class="p-3 bg-primary/10 rounded-lg text-primary">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="border-l-4 border-l-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-muted uppercase tracking-wider">Pending Assignments</p>
                        <p class="text-3xl font-bold text-text-main mt-1">{{ $pendingTasksCount }}</p>
                    </div>
                    <div class="p-3 bg-secondary/10 rounded-lg text-secondary">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card class="border-l-4 border-l-success">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-muted uppercase tracking-wider">Completed Assignments</p>
                        <p class="text-3xl font-bold text-text-main mt-1">{{ $completedTasksCount }}</p>
                    </div>
                    <div class="p-3 bg-success/10 rounded-lg text-success">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Enrolled Classes Section -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-text-main">Your Classes</h3>
            </div>

            @if($enrolledClasses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrolledClasses as $schoolClass)
                        <x-ui.card class="hover:border-primary transition-colors group flex flex-col h-full">
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-text-main group-hover:text-primary transition-colors">{{ $schoolClass->subject->name ?? 'Class' }}</h4>
                                <p class="text-sm text-text-muted mt-1">{{ $schoolClass->teacher->name ?? 'Teacher' }}</p>
                                
                                <div class="mt-4 flex items-center gap-2 text-sm text-text-muted">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>{{ $schoolClass->day_of_week }}s, {{ \Carbon\Carbon::parse($schoolClass->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schoolClass->end_time)->format('h:i A') }}</span>
                                </div>
                            </div>
                            
                            <x-slot name="footer">
                                <div class="pt-2 flex justify-end">
                                    <a href="{{ route('student.classes.show', $schoolClass->id) }}" class="text-sm font-bold text-primary hover:underline">View Class</a>
                                </div>
                            </x-slot>
                        </x-ui.card>
                    @endforeach
                </div>
            @else
                <x-ui.card class="text-center py-12">
                    <div class="flex flex-col items-center">
                        <div class="p-4 bg-background rounded-full mb-4">
                            <svg class="w-12 h-12 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-text-main">No enrolled classes</h4>
                        <p class="text-text-muted text-sm mt-2 max-w-sm">You haven't been added to any classes yet.</p>
                    </div>
                </x-ui.card>
            @endif
        </section>
    </div>
</x-app-layout>
