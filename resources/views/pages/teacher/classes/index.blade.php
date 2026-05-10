<x-app-layout>
    <x-slot name="header">
        {{ __('Classes') }}
    </x-slot>

    <x-slot name="actions">
        <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-class')" class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create Class
        </x-primary-button>
    </x-slot>

    <div class="space-y-6">


            <div class="bg-surface border border-border overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($schoolClasses->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm whitespace-nowrap">
                                <thead class="uppercase tracking-wider border-b border-border bg-background text-text-muted">
                                    <tr>
                                        <th scope="col" class="px-6 py-4">Class Subject</th>
                                        <th scope="col" class="px-6 py-4">Schedule</th>
                                        <th scope="col" class="px-6 py-4 text-center">Students</th>
                                        <th scope="col" class="px-6 py-4 text-center">Tasks</th>
                                        <th scope="col" class="px-6 py-4"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schoolClasses as $class)
                                        <tr class="border-b border-border hover:bg-background transition-colors group">
                                            <td class="px-6 py-4 text-text-main font-semibold">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded bg-primary/10 text-primary flex items-center justify-center font-bold mr-3">
                                                        {{ substr($class->subject->name, 0, 1) }}
                                                    </div>
                                                    {{ $class->subject->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-text-muted">
                                                {{ $class->day_of_week }}<br>
                                                <span class="text-xs">{{ \Carbon\Carbon::parse($class->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($class->end_time)->format('g:i A') }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary/10 text-secondary">
                                                    {{ $class->students_count ?? 0 }} Students
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                                    {{ $class->tasks_count ?? 0 }} Tasks
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('teacher.classes.show', $class->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-primary text-primary hover:bg-primary hover:text-surface rounded transition-colors text-sm font-medium">
                                                    Manage
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-text-muted mb-4">No classes found.</p>
                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-class')" class="px-4 py-2 bg-primary hover:bg-primary-hover text-surface rounded text-sm font-medium transition-colors">
                                Create Your First Class
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-modal name="create-class" focusable>
        <form method="post" action="{{ route('teacher.classes.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-text-main">
                {{ __('Create New Class') }}
            </h2>

            @if ($errors->has('base'))
                <div class="mt-4 p-3 bg-danger/10 text-danger rounded text-sm">
                    {{ $errors->first('base') }}
                </div>
            @endif

            <div class="mt-6">
                <x-input-label for="subject_id" value="{{ __('Class Name (Subject)') }}" />
                <select id="subject_id" name="subject_id" class="mt-1 block w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary text-text-main bg-surface" required autofocus>
                    <option value="" disabled selected>Select a subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('subject_id')" class="mt-2 text-danger" />
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="day_of_week" value="{{ __('Day of the Week') }}" />
                    <select id="day_of_week" name="day_of_week" class="mt-1 block w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary text-text-main bg-surface" required>
                        <option value="" disabled selected>Select day</option>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('day_of_week')" class="mt-2 text-danger" />
                </div>
                <div>
                    <x-input-label for="start_time" value="{{ __('Start Time') }}" />
                    <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('start_time')" class="mt-2 text-danger" />
                </div>
                <div>
                    <x-input-label for="end_time" value="{{ __('End Time') }}" />
                    <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('end_time')" class="mt-2 text-danger" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <button type="submit" class="ml-3 px-4 py-2 bg-primary hover:bg-primary-hover text-surface rounded text-sm font-medium transition-colors">
                    {{ __('Create Class') }}
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
