<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Dashboard --}}
        @stack('chart-script')
        @stack('histories-style')

        {{-- Projects --}}
        @stack('projects-style')
        @stack('label-style')

        {{-- Project --}}
        @stack('projectDetail-script')
        @stack('taskDetail-script')
        @stack('editor-script')

        {{-- Development --}}
        @stack('development-script')
        @stack('breakTime-script')

        {{-- Development/Timer --}}
        @stack('buttons-style')

        {{-- Development/taskSelector --}}
        @stack('incompleteTasks-style')
        @stack('taskSelector-script')

        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-sky-950">
        <div class="min-h-screen">
            @include('layouts.navigation')
            <livewire:utils.notification.notification />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="shadow">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @livewireScripts
    </body>
</html>
