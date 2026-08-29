<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config("app.name", "NORA WORLD") WORLD') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#faf9f7] text-stone-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4">
            <div class="mb-8">
                <a href="/" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/nora/logo.jpeg') }}" alt="NORA WORLD" class="h-10 w-auto object-contain">
                </a>
            </div>

            <div class="w-full sm:max-w-md bg-white rounded-2xl shadow-xl shadow-stone-200/50 p-8">
                {{ $slot }}
            </div>

            <p class="mt-8 text-xs text-stone-400">
                &copy; {{ date('Y') }} NORA WORLD. All rights reserved.
            </p>
        </div>
    </body>
</html>
