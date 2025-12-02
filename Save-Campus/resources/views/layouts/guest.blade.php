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
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-outfit text-gray-900 antialiased bg-gradient-to-br from-green-50 to-emerald-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/">
                    <div class="flex items-center space-x-3">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.67c.67-2.08 1.59-4.65 3.29-7.01C10 13 11 11 13 10c2-1 4-2 6-2v-1h-2z"/>
                                <path d="M16.07 3.05l-1.41 1.41C14.9 4.24 15 4.12 15 4s-.1-.24-.24-.46l1.41-1.41c1.95 1.95 2.2 4.99.58 7.24l1.45 1.45c2.2-3.29 1.81-7.75-1.13-10.68z"/>
                            </svg>
                        </div>
                        <span class="text-3xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Save Campus</span>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-3xl border border-gray-100">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
