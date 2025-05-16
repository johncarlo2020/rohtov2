<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Ocean or Plastic</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet" />
</head>

<body class="antialiased welcome-page">
    <div class="content-box main-background fade-in pt-0">
        <img class="w-100 mb-4" src="{{ asset('files/main/ocean or plastic_microsite_v4-02.webp') }}" />
        <div class="button-container px-4">
            <a href="{{ route('register') }}" id="routeBtn" class="button-primary button mb-3">
               Sign Up
            </a>
              <a href="{{ route('login') }}" id="routeBtn" class="button-secondary button">
                Sign In
            </a>
        </div>
    </div>
</body>

</html>
