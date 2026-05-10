<nav x-data="{ open: false }" class="bg-surface border-b border-border sticky top-0 z-40 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center flex-1">
                <!-- Mobile toggle -->
                <button @click="$dispatch('toggle-sidebar')" class="inline-flex items-center justify-center p-2 rounded-md text-text-muted hover:text-text-main hover:bg-surface-hover lg:hidden focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                <!-- Page Indicator (Breadcrumbs) -->
                <div class="ms-4 lg:ms-0 flex-1">
                    @isset($breadcrumbs)
                        {{ $breadcrumbs }}
                    @else
                        <x-ui.breadcrumb />
                    @endisset
                </div>
            </div>

            <div class="flex items-center space-x-4 ms-4">
                <!-- User Info -->
                <div class="flex items-center px-3 py-2 text-sm font-medium text-text-muted">
                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold me-2">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="hidden md:block">{{ Auth::user()->name }}</div>
                </div>
            </div>
        </div>
    </div>
</nav>
