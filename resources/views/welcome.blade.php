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
    <div class="py-3 container-fluid main-content with-scroll">
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center animate-entry">
                @include('components.branding')
            </div>
            <div class="col-12 px-0 text-center py-4">
                <p><span class="text-black">MONOCHROME</span>.<span class="text-black">MINIMALIST</span>.<span class="text-black">THE MULTIPLE</span></p>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center p-0">
                <img class="welcome_img_store w-100" src="{{ asset('images/brand/Hero.png') }}"
                    alt="" />
            </div>
            <div class="text-center pt-4 col-12 ">
                <div class="d-block">
                    <div class="col mb-1 animate-entry delay-2">
                        <a href="{{ route('register') }}" class="custom-btn custom-btn-secondary">Sign Up</a>
                    </div>
                    <div class="col text-center animate-entry delay-3">
                        <a href="{{ route('login') }}" class="custom-btn custom-btn-transparent">LOGIN</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- <x-footer /> -->
    </div>
    <x-scriptPackages />
</body>
</html>
