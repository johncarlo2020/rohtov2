<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ config('app.name', 'Loading ...') }}</title>

    <x-appCdnPackages />
    @vite(['resources/sass/app.scss'])
</head>

<body class="antialiased welcome-page main-background hadalabo">
    <div class="py-3 container-fluid main-content">
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center animate-entry">
                @include('components.branding')
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center animate-entry delay-1">
                <img class="welcome_img" src="{{ asset('images/dutchlady/dutchLadyLoginText1.webp') }}"
                    alt="" />
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center p-0 animate-entry delay-2">
                <img class="welcome_img_store" src="{{ asset('images/dutchlady/dutchLadyLoginImg.webp') }}"
                    alt="" />
            </div>
            <div class="text-center bottom-text-welcome col-12 mt-4 animate-entry delay-3">
                <div class="d-block">
                    <div class="col mb-3">
                        <a href="{{ route('register') }}" class="button-dutch button-dutch button-dutch-primary"><span
                                class="sign-up-btn-txt">Sign Up</span></a>
                    </div>
                    <div class="col">
                        <a href="{{ route('login') }}" class="button-dutch button-dutch button-dutch-outlined"><span
                                class="text-primary">Login</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-scriptPackages />
</body>

</html>
