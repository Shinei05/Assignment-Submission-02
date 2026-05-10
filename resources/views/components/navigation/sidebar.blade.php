<aside x-data="{ isOpen: true }" :class="{ 'translate-x-0': isOpen, '-translate-x-full': !isOpen }"
    class="fixed inset-y-0 left-0 z-50 transition-transform duration-300 ease-in-out border-r border-border bg-surface"
    style="width: var(--sidebar-width);">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-border">
        <a href="{{ route(Auth::user()->role === 'teacher' ? 'teacher.dashboard' : 'student.dashboard') }}"
            class="flex items-center space-x-3">
            <x-application-logo class="w-8 h-8 fill-current text-primary" />
            <span class="text-lg font-bold tracking-tight text-text-main">Assignly</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="p-4 flex flex-col overflow-y-auto" style="height: calc(100vh - 64px);">
        @php
            $role = Auth::user()->role;
            $links = [
                [
                    'name' => 'Dashboard',
                    'route' => $role . '.dashboard',
                    'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>'
                ],
                [
                    'name' => 'My Classes',
                    'route' => $role . '.classes.index',
                    'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'
                ],
            ];
        @endphp

        <div class="space-y-2 flex-1">
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}" @class([
                    'flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg group',
                    'bg-primary text-surface shadow-md' => request()->routeIs($link['route'] . '*'),
                    'text-text-muted hover:bg-surface-hover hover:text-text-main' => !request()->routeIs($link['route'] . '*'),
                ])>
                    <span @class([
                        'transition-colors duration-200',
                        'text-surface' => request()->routeIs($link['route'] . '*'),
                        'text-text-muted group-hover:text-primary' => !request()->routeIs($link['route'] . '*'),
                    ])>
                        {!! $link['icon'] !!}
                    </span>
                    <span class="ms-3">{{ $link['name'] }}</span>
                </a>
            @endforeach
        </div>

        <div class="pt-4 mt-auto border-t border-border">
            <h4 class="px-4 text-xs font-semibold tracking-wider uppercase text-text-muted">Account</h4>
            <div class="mt-4 space-y-2">
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium transition-all duration-200 rounded-lg text-text-muted hover:bg-surface-hover hover:text-text-main group">
                    <span class="text-text-muted group-hover:text-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </span>
                    <span class="ms-3">Profile</span>
                </a>

                <!-- Logout Trigger -->
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-logout')"
                    class="flex items-center w-full px-4 py-3 text-sm font-medium text-left transition-all duration-200 rounded-lg text-danger hover:bg-danger hover:text-surface group">
                    <span class="text-danger group-hover:text-surface">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </span>
                    <span class="ms-3">Logout</span>
                </button>
            </div>
        </div>
    </nav>
</aside>