<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-text-main bg-background">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <x-navigation.sidebar />

            <!-- Main Content -->
            <div class="flex-1 main-content flex flex-col min-h-screen">
                @include('layouts.navigation', [
                    'breadcrumbs' => $breadcrumbs ?? null
                ])

                <!-- Page Content -->
                <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-screen-2xl mx-auto w-full space-y-8">
                    <!-- Page Title / Header -->
                    @if(isset($header) || isset($actions))
                        <div class="flex items-center justify-between w-full">
                            @isset($header)
                                <h2 class="text-2xl font-bold text-text-main tracking-tight">
                                    {{ $header }}
                                </h2>
                            @endisset

                            @isset($actions)
                                <div class="flex items-center space-x-3">
                                    {{ $actions }}
                                </div>
                            @endisset
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Global Modals -->
        <x-ui.confirm-modal 
            name="confirm-logout" 
            title="Log Out" 
            message="Are you sure you want to log out of your session?" 
            type="danger" 
            confirmText="Log Out"
            :action="route('logout')"
        />

        <!-- Global Toast Notification -->
        <x-ui.toast />
    </body>
</html>
