@props([
    'name',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed?',
    'type' => 'info', // info, danger, success, warning
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'action' => '',
    'confirmAction' => '',
    'method' => 'POST',
    'maxWidth' => 'sm'
])

<x-modal :name="$name" focusable :maxWidth="$maxWidth">
    <div class="p-8 flex flex-col items-center text-center">
        @php
            $iconClass = match($type) {
                'danger' => 'bg-red-100 text-danger',
                'success' => 'bg-green-100 text-success',
                'warning' => 'bg-yellow-100 text-yellow-600',
                default => 'bg-blue-100 text-primary',
            };
        @endphp

        <div class="flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center {{ $iconClass }} mb-5">
            @if($type === 'danger')
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            @elseif($type === 'success')
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            @else
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @endif
        </div>

        <h3 class="text-xl font-bold text-text-main mb-2">{{ $title }}</h3>
        @if($message)
            <p class="text-sm text-text-muted mb-8">{{ $message }}</p>
        @else
            <div class="mb-8"></div>
        @endif

        <div class="w-full flex flex-col-reverse sm:flex-row sm:justify-center sm:space-x-3">
            <x-secondary-button x-on:click="$dispatch('close')" class="w-full sm:w-auto justify-center mt-3 sm:mt-0">
                {{ $cancelText }}
            </x-secondary-button>

            @if($action)
                <form method="POST" action="{{ $action }}" class="w-full sm:w-auto">
                    @csrf
                    @if(in_array($method, ['PUT', 'PATCH', 'DELETE']))
                        @method($method)
                    @endif
                    <button 
                        type="submit" 
                        @class([
                            'w-full justify-center px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-200 shadow-sm flex items-center',
                            'bg-danger hover:bg-red-700' => $type === 'danger',
                            'bg-success hover:bg-green-700' => $type === 'success',
                            'bg-primary hover:bg-primary-hover' => $type === 'info' || $type === 'warning',
                        ])
                    >
                        {{ $confirmText }}
                    </button>
                </form>
            @else
                <button 
                    @if($confirmAction)
                        x-on:click="{{ $confirmAction }}"
                    @else
                        x-on:click="$dispatch('confirm')"
                    @endif
                    @class([
                        'w-full sm:w-auto justify-center px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-200 shadow-sm flex items-center',
                        'bg-danger hover:bg-red-700' => $type === 'danger',
                        'bg-success hover:bg-green-700' => $type === 'success',
                        'bg-primary hover:bg-primary-hover' => $type === 'info' || $type === 'warning',
                    ])
                >
                    {{ $confirmText }}
                </button>
            @endif
        </div>
    </div>
</x-modal>
