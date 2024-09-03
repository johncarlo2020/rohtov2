<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Rohto</title>

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet"
        />
    </head>

    <body class="welcome main main-bg">
        <style>
            .main-bg {
                background-image: url("{{ asset("/images/main-bg.png") }}");
            }
        </style>
        <div class="branding-container">@include('components.branding')</div>

        <div class="container">
            <h1>
                Please rescan and access the QR from the digital journey. To
                start the journey
                <a href="{{ route('rohto') }}">Click here</a> !
            </h1>
        </div>
    </body>
</html>
