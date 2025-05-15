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
    <div class="content-box main-background fade-in">
        <div class="container">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="ocean-img mb-4">
            <img src="{{ asset('files/main/ocean_photo.webp') }}" />
        </div>
        <div class="info-box px-5 mb-3">
            <p class="pharagraph-text text-center">Discover our very first <strong>Ocean or Plastic</strong> Roadshow—.
                an immersive exploration of where your plastic ends
                up
                and how small choices
                lead to lasting impact
            </p>
            <p class="pharagraph-text text-center">
                As part of the journey, uncover beauty that cares: enjoy personalised services for hair, skin, and body,
                and discover
                thoughtful ways to make choices that are gentler on the planet.
            </p>

        </div>
        <img class="info-img mb-3" src="{{ asset('files/main/welcome_info_img.webp') }}" />
        <div class="info-box px-5 mb-3">
            <p class="pharagraph-text text-center mb-0">Date: 27 May – 2 June 2025</p>
            <p class="pharagraph-text text-center mb-0">
                Time: 10am – 10pm</p>
            <p class="pharagraph-text text-center mb-0">
                Venue: IOI City Mall, Putrajaya –</p>
            <p class="pharagraph-text text-center mb-0">
                West Court on Ground Floor</p>
        </div>
        <div class="button-container px-4">
            <a href="{{ route('register') }}" id="routeBtn" class="button-primary button mb-3">
               Sign Up
            </a>
              <a href="{{ route('login') }}" id="routeBtn" class="button-secondary button">
                Sign In
            </a>
        </div>
        <div class="footer-container p-4">
             @include('components.footer')
        </div>
    </div>
</body>

</html>
