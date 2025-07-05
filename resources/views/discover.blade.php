<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Dutch Lady</title>

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    </head>

    <body class="antialiased home">
        <div class="container-fluid py-5">
            <div class="content-wrapper">
                <div class="branding-container">
                    @include('components.branding')
                </div>
                <div class="text-content text-center px-3">
                    <h1 class="heading">Welcome!</h1>
                    <p class="mb-5">
                        Join us today for an adventure packed with exciting and
                        fun activities at every station. Don’t miss out on the
                        fun!
                    </p>
                    <a
                        href="{{ route('welcome') }}"
                        class="btn discover-btn rounded-pill"
                        >DISCOVER NOW</a
                    >
                </div>
            </div>
        </div>
        <footer>
            <a href="https://wowsome.com.my/">Powered by WOWSOME®2024</a>
        </footer>
    </body>
</html>
