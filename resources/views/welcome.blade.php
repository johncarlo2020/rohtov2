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
            margin: 12% 0% 6% 0%;
        }
    </style>
</head>

<body class="antialiased welcome-page">
    <img class="welcome_img_bottom" src="{{ asset('images/brand/welcome_img.webp') }}" alt="Welcome Image" />

    <div class="px-0 py-4 container-fluid main-content with-scroll">
        <div class="top-container">
            <!-- Branding (top area) -->
            <div class="flex-grow-1 row">
                <div class="mb-4 animate-entry col-12">
                    <div>
                        <div class="branding">
                            <img onclick="window.location.href='{{ route('dashboard') }}'" class="logo" src="{{ asset('images/brand/logo_white.webp') }}" alt="Brand Logo" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="banner" class="d-flex flex-column justify-content-center mx-auto text-white text-center animate-entry col-11 col-md-10">
            <div class="top">
                <p class="mb-3 fs-4 fw-normal" style="font-size: clamp(1.2rem, 4.5vw, 1.6rem);">Welcome to the journey of</p>
                <h1 class="mb-3 fw-bold" style="font-size: clamp(1.8rem, 6.5vw, 2.8rem); font-weight: 800; line-height: 1.2;">PropertyGuru New Homes Expo</h1>
                <p class="mb-2 fs-5" style="font-size: clamp(1rem, 3.8vw, 1.35rem);">26-28 September 2026 | 10AM - 10PM</p>
                <h3 class="mb-0 fw-bold fs-4" style="font-size: clamp(1.25rem, 4.8vw, 1.7rem); font-weight: 700;">KSL City Johor Bahru</h3>
            </div>
            <!-- Bottom CTA -->
            <div class="mb-5 row">
                <div class="text-center col-12">
                    <div class="d-block mb-2">
                        <div class="mt-4 px-5 delay-2 colanimate-entry btn-wrapperx">
                            <a href="{{ route('register') }}" class="custom-btn custom-btn-secondary">
                                JOIN NOW
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-container">
            <!-- Bottom CTA -->
            <div class="mb-5 row">
                <div class="text-center col-12">
                    <div class="d-block mb-2">
                        <div class="mt-4 px-5 delay-2 colanimate-entry btn-wrapperx d-none">
                            <a href="{{ route('register') }}" class="custom-btn custom-btn-seconday">
                                JOIN NOW
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-scriptPackages />

</body>
</html>
