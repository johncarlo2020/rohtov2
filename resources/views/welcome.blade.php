<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Welcome</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>        .content-box {
            position: relative;
        }
    </style>
    @include('components.fonts')
</head>

<body class="antialiased welcome-page">
    <div class="pt-0 content-box main-background fade-in">
        @include('components.branding')

        <div class="content welcome-content">
            <p class="discover-text">DISCOVER</p>
            <h1 class="more-text">MORE</h1>

            <p>Your journey to more starts here.</p>
            <p>Explore the experiences, collect digital stamps and unlock rewards along the way.</p>

            <img class="card-img" src="{{ asset('files/main/Maybank_Card.png') }}" alt="maybank card" />
        </div>

        <div class="px-5 button-container">
            <a href="{{ route('register') }}" id="routeBtn" class="mb-3 button-primary button">
               SIGN UP
            </a>
             <a href="{{ route('login') }}" id="routeBtn" class="mb-0 button-outlined button">
               LOGIN
            </a>
        </div>
    </div>
</body>

    </html>
