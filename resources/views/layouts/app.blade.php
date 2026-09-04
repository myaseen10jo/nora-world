<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="NORA WORLD — From Our Home to Yours. Vintage, Collectibles, Art, and Pre-Loved Treasures.">

        <title>@yield('title', 'عالم نورا للكنوز — من بيوتنا لبيتك')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="bg-[#faf5ef] text-gray-900 antialiased font-sans" data-theme="{{ $designTheme ?? 'pro' }}" data-animation="{{ $designAnimation ?? 'reveal' }}">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.footer')
        </div>

        {{-- Theme Switcher --}}
        @include('components.theme-switcher')

        @stack('scripts')
    </body>
</html>
