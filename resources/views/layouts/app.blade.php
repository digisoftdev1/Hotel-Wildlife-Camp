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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Global Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex flex-col">
        @include('layouts.navigation')
        <x-sidebar />
        <!-- Page Content -->
        <main class="flex-1 flex flex-col lg:ml-64">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="py-6 border-t border-gray-200 text-center bg-white/50 backdrop-blur-sm mt-auto lg:ml-64 z-10">
            <div class="text-xs text-gray-500 flex justify-center items-center gap-1.5">
                Developed by
                <a href="https://www.digisoft.com.np" target="_blank"
                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition-colors font-medium">
                    <img src="{{ asset('assets/logo/company-logo.png') }}" alt="Digisoft Logo"
                        class="w-5 h-5 rounded-full object-cover shadow-sm bg-white">
                    Digisoft Developers Pvt. Ltd.
                </a>
            </div>
            <div class="mt-2 text-xs text-gray-400">
                © {{ date('Y') }} Hotel Wildlife Camp Admin Panel. All rights reserved.
            </div>
        </footer>
    </div>
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('scripts')
</body>

</html>
