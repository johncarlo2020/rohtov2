<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Ocean or Plastic</title>

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        @include('components.fonts')
    </head>

    <body class="welcome home">
        <div class="branding-container">@include('components.branding')</div>

        <div class="container">
            <h1>
                Please rescan and access the QR from the digital journey. To
                start the journey
                <a href="{{ route('welcome') }}">Click here</a> !
            </h1>
        </div>
    </body>
</html>
