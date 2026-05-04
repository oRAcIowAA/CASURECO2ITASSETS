<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="{{ asset('css/figtree.css') }}" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Choices.js -->
        <link rel="stylesheet" href="{{ asset('css/choices.min.css') }}" />
        <script src="{{ asset('js/choices.min.js') }}"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 w-full min-h-screen" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">
            <!-- Content Overlay for Mobile -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition-opacity ease-linear duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-600 bg-opacity-75 z-20 md:hidden" 
                 @click="sidebarOpen = false" 
                 aria-hidden="true" style="display: none;"></div>

            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden relative w-full">
                
                <!-- Mobile Header -->
                <div class="md:hidden bg-blue-900 flex justify-between items-center p-4 shadow-md z-50 sticky top-0 w-full pointer-events-auto">
                    <button @click="sidebarOpen = true" type="button" class="text-white hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white z-50 relative pointer-events-auto cursor-pointer p-2 -ml-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-2 relative z-40 pointer-events-none">
                        <img src="{{ asset('images/casureco-logo.png') }}" alt="Casureco Logo" class="h-8 w-auto bg-white rounded-full p-1" style="max-height: 32px;">
                        <span class="text-white font-bold tracking-wider">IT ASSETS</span>
                    </div>
                </div>
                
                <!-- Main Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 relative z-0">
                    <!-- Page Header -->
                    @isset($header)
                        <header class="bg-white shadow-sm z-0 sticky top-0">
                            <div class="w-full py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                                <div class="text-gray-900">
                                    {{ $header }}
                                </div>

                            </div>
                        </header>
                    @endisset

                    <div class="w-full px-6 py-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>


