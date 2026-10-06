<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <!-- CSRF Token for security -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Page Title -->
    <title>{{ config('app.name', 'Loading ...') }}</title>

    <!-- Google Fonts for Maison Margiela Paris aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Common CDN packages (CSS, etc.) -->
    <x-appCdnPackages />

    {{-- @include('components.fonts') --}}

    <!-- Vite assets (compiled CSS and JS) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="main-background">
    <!-- Main content area where page-specific content will be injected -->
    <main class="container-fluid p-0 m-0 w-100 min-vh-100">
        {{ $slot }}
    </main>

    <!-- Common JavaScript packages -->
    <x-scriptPackages />

    <!-- Stack for page-specific JavaScript -->
    @stack('scripts')
</body>

</html>
