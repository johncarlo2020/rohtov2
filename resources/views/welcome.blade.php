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
            justify-content: space-between;
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
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
    </style>
</head>

<body class="antialiased main-background welcome-page">

    <div class="container-fluid main-content">
        <div class="top-container">
            <!-- Branding (top area) -->
            <div class="row flex-grow-1">
                <div class="col-12 pt-5 animate-entry">
                    <div>
                        <img onclick="window.location.href='{{ route('dashboard') }}'" class="logo logo-welcome pulse-slow" src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" />
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom-container">
            <!-- Center image (middle area) -->
            <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center p-0 animate-entry">
                    <img class="welcome_img_store w-100" 
                        src="{{ asset('images/brand/bm_front.webp') }}" 
                        alt="">
                </div>
            </div>

            <!-- Bottom CTA -->
            <div class="row">
                <div class="col-12 text-center">
                    <div class="d-block">
                        <div class="colanimate-entry delay-2 btn-wrapperx">
                            <a href="{{ route('register') }}" class="continue-btn pulse-slow">
                                TAP TO CONTINUE
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer-welcome mb-4 text-center w-100">
                <x-footer/>
            </div>
        </div>
    </div>

    <x-scriptPackages />

</body>
</html>
