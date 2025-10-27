<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ config('app.name', 'Loading ...') }}</title>

    <x-appCdnPackages />
    @vite(['resources/sass/app.scss'])
</head>

<body class="antialiased main-background welcome-page">
    <div class="container-fluid main-content with-scroll">
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center animate-entry">
                @include('components.branding')
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center p-0 animate-entry">
                <img class="welcome_img_store w-100" src="{{ asset('images/brand/ryt_front.webp') }}"
                    alt="" />
            </div>
            <div class="text-center bottom-text-welcome col-12 ">
                <div class="d-block">
                    <div class="col mb-3 animate-entry delay-2">
                        <a href="{{ route('register') }}" class="custom-btn custom-btn-primary">Sign Up</a>
                    </div>
                      <div class="col mb-3 animate-entry delay-2">
                        <a href="{{ route('login') }}" class="custom-btn custom-btn-secondary">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-scriptPackages />
</body>
</html>
