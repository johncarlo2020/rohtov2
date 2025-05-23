<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Adidas vibes</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet" />
</head>

<body class="antialiased welcome-page">
    <div class="content-box welcome-background fade-in">
        <div class="container">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="welcome-logo mt-auto">
            <img src="{{ asset('files/main/welcome_img.webp') }}" alt="" />
        </div>
        <div class="button-container px-4 mt-auto">
            <a href="{{ route('register') }}" id="routeBtn" class="button-primary button mb-3 w-100">
                Sign Up
            </a>
            <a href="{{ route('login') }}" id="routeBtn" class="button-secondary button w-100">
                Continue Journey
            </a>
        </div>
        <div class="footer-container  mt-auto">
            @include('components.footer')
        </div>
    </div>
</body>

</html>
