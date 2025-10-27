<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Rytbank</title>

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    </head>

    <body>
        <div class="py-5 px-0 map-page main-content main-background">
            <div class="content-wrapper">
                <div class="d-flex justify-content-center animate-entry mb-4">
                    @include('components.branding')
                </div>
                <div class="col-12 d-flex justify-content-center align-items-center p-0 animate-entry">
                    <img class="welcome_img_store w-100" src="{{ asset('images/brand/discover.webp') }}"
                        alt="" />
                </div>
                <div class="text-content text-center px-3 mt-5">
                    <a
                        href="{{ route('dashboard') }}"
                        class="custom-btn custom-btn-primary animate-entry delay-3"
                        >DISCOVER NOW</a
                    >
                </div>
            </div>
        </div>
    </body>
</html>
