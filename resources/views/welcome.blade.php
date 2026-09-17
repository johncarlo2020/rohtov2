<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Ocean or Plastic</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>        .content-box {
            position: relative;
        }
    </style>
    @include('components.fonts')
</head>

<body class="antialiased welcome-page">
    <div class="pt-0 content-box main-background fade-in">
        <img class="mb-4 w-100" src="{{ asset('files/main/welcomBG.webp') }}" />
        <div class="px-4 button-container">
            <a href="{{ route('register') }}" id="routeBtn" class="mb-3 button-primary button">
               Sign Up
            </a>
             <a href="{{ route('login') }}" id="routeBtn" class="mb-3 button-secondary button">
               Log In
            </a>
        </div>
    </div>
</body>

</html>
