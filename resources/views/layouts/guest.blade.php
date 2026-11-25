<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <!-- CSRF Token for security -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Page Title -->
    <title>{{ config('app.name', 'Loading ...') }}</title>

    <!-- Preload critical background image -->
    <link rel="preload" as="image" href="{{ asset('images/rwg/01 RW Genting Winter_background_2x.webp') }}" fetchpriority="high" />

    <!-- Common CDN packages (CSS, etc.) -->
    <x-appCdnPackages />

    {{-- @include('components.fonts') --}}

    <!-- Vite assets (compiled CSS and JS) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="main-background d-flex flex-column min-vh-100">
    <!-- Main content area where page-specific content will be injected -->
    <main class="flex-grow-1 d-flex flex-column">
        {{ $slot }}
    </main>

    @include('components.footer')

    <!-- Common JavaScript packages -->
    <x-scriptPackages />

    <!-- Stack for page-specific JavaScript -->
    @stack('scripts')
</body>

</html>
