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
            margin: 10% 0% 5% 0%;
        }
    </style>
</head>

<body class="antialiased welcome-page" style="background-image:url('{{ asset('images/brand/landing_bg2.webp') }}');">

    <div class="container-fluid main-content with-scroll py-4 px-0">
        <div class="top-container">
            <!-- Branding (top area) -->
            <div class="row flex-grow-1">
                <div class="col-12 animate-entry mb-4">
                    <div>
                        <div class="branding">
                            <img onclick="window.location.href='{{ route('dashboard') }}'" class="logo" src="{{ asset('images/brand/logo_white.webp') }}" alt="Brand Logo" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="banner" class="col-11 col-md-10 mx-auto d-flex flex-column justify-content-center animate-entry text-center text-white">
            <div class="top">
                <h2 class="fw-normal mb-1" style="font-size: clamp(1.4rem, 5vw, 2.2rem);">Congratulations!</h2>
                <p class="fs-4 mb-3 fw-normal" style="font-size: clamp(1.2rem, 4.5vw, 1.6rem);">You’ve completed the journey of</p>
                <h1 class="fw-bold mb-3" style="font-size: clamp(1.8rem, 6.5vw, 2.8rem); font-weight: 800; line-height: 1.2;">PropertyGuru New Homes Expo</h1>
                <p class="fs-5 mb-2" style="font-size: clamp(1rem, 3.8vw, 1.35rem);">26-28 September 2026 | 10AM - 10PM</p>
                <h3 class="fw-bold fs-4 mb-0" style="font-size: clamp(1.25rem, 4.8vw, 1.7rem); font-weight: 700;">KSL City Johor Bahru</h3>
            </div>
            <!-- Bottom CTA -->
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <div class="d-block mb-2">
                        <div class="colanimate-entry delay-2 btn-wrapperx px-5 mt-4">
                            <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary" style="font-weight: 800; color: #F00b0b; background: #ffffff;">
                                COMPLETE
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-container">
            <!-- Bottom CTA -->
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <div class="d-block  mb-2">
                        <div class="colanimate-entry delay-2 btn-wrapperx px-5 mt-4 d-none">
                            <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-seconday">
                                COMPLETE
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
