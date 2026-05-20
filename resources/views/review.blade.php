<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1, viewport-fit=cover" />

    <title>{{ config('app.name', 'Loading ...') }}</title>

    <x-appCdnPackages />
    @vite(['resources/sass/app.scss'])

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Removes scrolling */
        }

        .welcome-page {
            width: 100vw;
            height: 100vh;
            height: 100dvh; /* Correct for iOS Safari dynamic viewport */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: start;
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
            height:100svh;
        }

        .welcome_img_store {
            object-fit: contain;
            max-height: 40vh; /* Responsive height */
        }

        .bottom-text-welcome {
            position: relative;
            z-index: 10;
        }

        .btn-wrapper 
        {
            margin-top: -5%;
        }

        .continue-btn
        {
            -webkit-text-stroke: 1px #733412;
            font-size: 24px;
            font-weight: 900;
            text-decoration: none;
            margin-top: -5%;
            text-shadow: 0 3px 0 #f7a239;
        }

        #banner .top{
            margin-top: 25%;
        }

        .cat {
            position: absolute;
            bottom: 0px;
            width: 200px !important;
            z-index: -2;
            right: 0;
        }

        .review-box-desc {
            margin-bottom: -8vw;
        }
    </style>
</head>

<body class="antialiased welcome-page" style="background-image:url('{{ asset('images/brand/landing_bg.webp') }}');">

    @php

        if(request('review') == 'milktea'){
            $bg = 'milktea.webp';
            $desc = 'desc_milktea.webp';
        }

        if(request('review') == 'mootea'){
            $bg = 'matcha.webp';
            $desc = 'desc_matcha.webp';
        }

    @endphp

    <div class="container-fluid main-content with-scroll py-4 px-0">
        <div class="branding-container animate-entry px-4">
            @include('components.branding')
        </div>

        <div id="banner" class="col-10 mx-auto d-flex flex-column justify-content-center animate-entry">
                <div class="top">
                    <div class="row">
                        <img class="w-100 p-0" src="{{ asset('images/brand/'.$bg) }}" alt="" />
                    </div>
                </div>

                {{-- instructions --}}

                <div id="instructionsParent" class="instructions-parent animate-entry w-100">

                    <div class="review-box-desc">
                        <img src="{{ asset('images/brand/'.$desc) }}" alt="" srcset="">
                    </div>
                    <a href="{{ route('quiz') }}" class="custom-btn custom-btn-primary pulse-slow" style="background-image:url('{{ asset('images/brand/btn.png') }}');">
                        Play Again
                    </a>
                </div>
            </div>
    </div>
    
    <div class="row">
        <img class="w-100 p-0 cat" src="{{ asset('images/brand/cat.png') }}" alt="" />
    </div>

    <x-scriptPackages />

</body>
</html>
