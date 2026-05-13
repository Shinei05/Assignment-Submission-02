<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('teacher.classes.index') }}" class="text-text-muted hover:text-text-main transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <span>{{ $schoolClass->subject->name }} - {{ $schoolClass->day_of_week }} ({{ \Carbon\Carbon::parse($schoolClass->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schoolClass->end_time)->format('g:i A') }})</span>
        </div>
    </x-slot>

    <x-slot name="actions">
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-student')" class="px-3 py-1.5 bg-secondary hover:bg-text-main text-surface rounded text-xs font-medium transition-colors">
            Add Students
        </button>
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-task')" class="px-3 py-1.5 bg-primary hover:bg-primary-hover text-surface rounded text-xs font-medium transition-colors">
            Create Task
        </button>
    </x-slot>

    <div class="py-12 bg-background">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 space-y-6">


            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Students Column -->
                <div class="lg:col-span-1">
                    <div class="bg-surface border border-border overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-text-main mb-4 border-b border-border pb-2">Enrolled Students ({{ $schoolClass->students->count() }})</h3>
                            @if($schoolClass->students->count() > 0)
                                <ul class="divide-y divide-border">
                                    @foreach($schoolClass->students as $student)
                                        <li class="py-3 flex flex-col space-y-1">
                                            <span class="text-text-main font-medium">{{ $student->name }}</span>
                                            <span class="text-xs text-text-muted">{{ $student->email }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-text-muted text-sm text-center py-4">No students enrolled yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tasks Column -->
                <div class="lg:col-span-2">
                    <div class="bg-surface border border-border overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-text-main mb-4 border-b border-border pb-2">Tasks</h3>
                            @if($schoolClass->tasks->count() > 0)
                                <div class="space-y-4">
                                    @foreach($schoolClass->tasks as $task)
                                        <div class="p-4 border border-border rounded-lg bg-background flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <h4 class="text-md font-bold text-text-main">{{ $task->title }}</h4>
                                                <p class="text-sm text-text-muted mt-1">{{ Str::limit($task->description, 60) }}</p>
                                                <div class="mt-2 flex space-x-4 text-xs text-text-muted">
                                                    @if($task->due_date)
                                                        <span>Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y g:i A') }}</span>
                                                    @endif
                                                    @if($task->max_attempts)
                                                        <span>Attempts: {{ $task->max_attempts }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mt-4 sm:mt-0 flex space-x-2">
                                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-task-{{ $task->id }}')" class="px-3 py-1 text-sm bg-secondary text-surface rounded hover:bg-text-main transition-colors">Edit</button>
                                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-{{ $task->id }}')" class="px-3 py-1 text-sm bg-danger text-surface rounded hover:bg-red-700 transition-colors">Delete</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-text-muted mb-4">No tasks assigned yet.</p>
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-task')" class="text-primary hover:underline font-medium text-sm">
                                        Assign a task now
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Students Modal -->
    <x-modal name="add-student" focusable>
        <form method="post" action="{{ route('teacher.classes.students.add', $schoolClass->id) }}" class="flex flex-col max-h-[85vh]" x-data="{ selectAll: false }">
            @csrf
            
            <div class="p-6 pb-4 border-b border-border shrink-0 flex items-center justify-between gap-4">
                <h2 class="text-lg font-medium text-text-main">
                    {{ __('Add Students to Class') }}
                </h2>

                <button type="button"
                    class="px-3 py-1.5 border border-border text-text-main rounded text-xs font-semibold tracking-wider uppercase hover:bg-background transition-colors"
                    x-on:click="
                        selectAll = !selectAll;
                        $root.querySelectorAll('input[name=\'student_ids[]\']:not(:disabled)').forEach((checkbox) => checkbox.checked = selectAll);
                    "
                    x-text="selectAll ? '{{ __('Clear All') }}' : '{{ __('Select All') }}'">
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 space-y-2 bg-background/30">
                @php
                    // Sort students: not enrolled first, enrolled last
                    $sortedStudents = $allStudents->sortBy(function($student) use ($enrolledStudentIds) {
                        return in_array($student->id, $enrolledStudentIds) ? 1 : 0;
                    });
                @endphp

                @foreach($sortedStudents as $student)
                    @php
                        $isEnrolled = in_array($student->id, $enrolledStudentIds);
                    @endphp
                    <div class="flex items-center justify-between p-3 border border-border rounded {{ $isEnrolled ? 'bg-background opacity-60' : 'bg-surface hover:bg-background' }}">
                        <label class="flex items-center space-x-3 cursor-pointer w-full">
                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="rounded border-border text-primary shadow-sm focus:ring-primary" x-on:change="selectAll = false" {{ $isEnrolled ? 'disabled checked' : '' }}>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-text-main {{ $isEnrolled ? 'line-through' : '' }}">{{ $student->name }}</span>
                                <span class="text-xs text-text-muted">{{ $student->email }}</span>
                            </div>
                        </label>
                        @if($isEnrolled)
                            <span class="text-xs font-semibold text-text-muted bg-border px-2 py-1 rounded">Enrolled</span>
                        @endif
                    </div>
                @endforeach
                
                <x-input-error :messages="$errors->get('student_ids')" class="mt-2 text-danger" />
            </div>

            <div class="p-6 bg-surface border-t border-border flex justify-end shrink-0">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <button type="submit" class="ml-3 px-4 py-2 bg-primary hover:bg-primary-hover text-surface rounded text-sm font-medium transition-colors">
                    {{ __('Add Selected') }}
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Create Task Modal -->
    <x-modal name="create-task" focusable>
        <form method="post" action="{{ route('teacher.classes.tasks.store', $schoolClass->id) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-text-main mb-4">
                {{ __('Create New Task') }}
            </h2>

            <div class="space-y-4">
                <div>
                    <x-input-label for="title" value="{{ __('Task Title') }}" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-1 text-danger" />
                </div>

                <div>
                    <x-input-label for="task_description" value="{{ __('Instructions / Description') }}" />
                    <textarea id="task_description" name="description" class="mt-1 block w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary text-text-main bg-surface" rows="4"></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1 text-danger" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" x-data="{ dueDate: @js(old('due_date', '')), today: @js(now()->format('Y-m-d')), currentTime: @js(now()->format('H:i')) }">
                    <div>
                        <x-input-label for="due_date" value="{{ __('Due Date') }}" />
                        <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" min="{{ now()->format('Y-m-d') }}" x-model="dueDate" :value="old('due_date')" required />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-1 text-danger" />
                    </div>
                    <div>
                        <x-input-label for="due_time" value="{{ __('Submission Time') }}" />
                        <x-text-input id="due_time" name="due_time" type="time" class="mt-1 block w-full" x-bind:min="dueDate === today ? currentTime : null" :value="old('due_time')" required />
                        <x-input-error :messages="$errors->get('due_time')" class="mt-1 text-danger" />
                    </div>
                    <div>
                        <x-input-label for="max_attempts" value="{{ __('Max Attempts (Optional)') }}" />
                        <x-text-input id="max_attempts" name="max_attempts" type="number" min="1" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('max_attempts')" class="mt-1 text-danger" />
                    </div>
                </div>

                <div>
                    <x-input-label for="reference_files" value="{{ __('Reference Files (Optional)') }}" />
                    <input type="file" id="reference_files" name="reference_files[]" multiple accept=".png,.jpeg,.jpg,.pdf,.xls,.xlsx,.csv,.doc,.docx" class="mt-1 block w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-surface hover:file:bg-primary-hover">
                    <p class="text-xs text-text-muted mt-1">Accepted formats: PNG, JPEG, PDF, Excel, Word (Max 10MB each)</p>
                    <x-input-error :messages="$errors->get('reference_files')" class="mt-1 text-danger" />
                    <x-input-error :messages="$errors->get('reference_files.*')" class="mt-1 text-danger" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <button type="submit" class="ml-3 px-4 py-2 bg-primary hover:bg-primary-hover text-surface rounded text-sm font-medium transition-colors">
                    {{ __('Create Task') }}
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit and Delete Task Modals -->
    @foreach($schoolClass->tasks as $task)
        <!-- Edit Task Modal -->
        <x-modal name="edit-task-{{ $task->id }}" focusable>
            <form method="post" action="{{ route('teacher.classes.tasks.update', [$schoolClass->id, $task->id]) }}" enctype="multipart/form-data" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-text-main mb-4">
                    {{ __('Edit Task') }}
                </h2>

                <div class="space-y-4">
                    <div>
                        <x-input-label for="title_{{ $task->id }}" value="{{ __('Task Title') }}" />
                        <x-text-input id="title_{{ $task->id }}" name="title" type="text" class="mt-1 block w-full" :value="old('title', $task->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-1 text-danger" />
                    </div>

                    <div>
                        <x-input-label for="task_description_{{ $task->id }}" value="{{ __('Instructions / Description') }}" />
                        <textarea id="task_description_{{ $task->id }}" name="description" class="mt-1 block w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary text-text-main bg-surface" rows="4">{{ old('description', $task->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1 text-danger" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" x-data="{ dueDate: @js(old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '')), today: @js(now()->format('Y-m-d')), currentTime: @js(now()->format('H:i')) }">
                        <div>
                            <x-input-label for="due_date_{{ $task->id }}" value="{{ __('Due Date') }}" />
                            <x-text-input id="due_date_{{ $task->id }}" name="due_date" type="date" class="mt-1 block w-full" min="{{ now()->format('Y-m-d') }}" x-model="dueDate" :value="old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '')" required />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-1 text-danger" />
                        </div>
                        <div>
                            <x-input-label for="due_time_{{ $task->id }}" value="{{ __('Submission Time') }}" />
                            <x-text-input id="due_time_{{ $task->id }}" name="due_time" type="time" class="mt-1 block w-full" x-bind:min="dueDate === today ? currentTime : null" :value="old('due_time', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('H:i') : '')" required />
                            <x-input-error :messages="$errors->get('due_time')" class="mt-1 text-danger" />
                        </div>
                        <div>
                            <x-input-label for="max_attempts_{{ $task->id }}" value="{{ __('Max Attempts (Optional)') }}" />
                            <x-text-input id="max_attempts_{{ $task->id }}" name="max_attempts" type="number" min="1" class="mt-1 block w-full" :value="old('max_attempts', $task->max_attempts)" />
                            <x-input-error :messages="$errors->get('max_attempts')" class="mt-1 text-danger" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="reference_files_{{ $task->id }}" value="{{ __('Add More Reference Files (Optional)') }}" />
                        <input type="file" id="reference_files_{{ $task->id }}" name="reference_files[]" multiple accept=".png,.jpeg,.jpg,.pdf,.xls,.xlsx,.csv,.doc,.docx" class="mt-1 block w-full text-sm text-text-muted file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-surface hover:file:bg-primary-hover">
                        <x-input-error :messages="$errors->get('reference_files')" class="mt-1 text-danger" />
                        <x-input-error :messages="$errors->get('reference_files.*')" class="mt-1 text-danger" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <button type="submit" class="ml-3 px-4 py-2 bg-primary hover:bg-primary-hover text-surface rounded text-sm font-medium transition-colors">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Delete Confirmation Modal -->
        <x-ui.confirm-modal 
            name="confirm-delete-{{ $task->id }}"
            title="Delete Task"
            message="Are you sure you want to delete '{{ $task->title }}'? This action cannot be undone."
            type="danger"
            confirmText="Delete"
            :action="route('teacher.classes.tasks.destroy', [$schoolClass->id, $task->id])"
            method="DELETE"
        />
    @endforeach
</x-app-layout>
