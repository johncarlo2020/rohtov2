<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ config('app.name', 'Resorts World Genting – Christmas Trail Challenge') }}</title>

    <link rel="preload" as="image" href="{{ asset('images/rwg/01 RW Genting Winter_background_2x.webp') }}" fetchpriority="high" />
    <link rel="preload" as="image" href="{{ asset('images/rwg/RWG WHITE_LOGO_2x.webp') }}" fetchpriority="high" />

    <x-appCdnPackages />
    @vite(['resources/sass/app.scss'])

    <style>
        html, body {
            min-height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            font-family: 'Brown', 'Mulish', sans-serif;
            background-color: #120924;
        }

        .welcome-page {
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            background-image: url('{{ asset('images/rwg/01 RW Genting Winter_background_2x.webp') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center top;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding-top: calc(env(safe-area-inset-top) + 2rem);
            padding-bottom: calc(env(safe-area-inset-bottom) + 3.5rem);
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            z-index: 2;
        }

        .welcome-logo {
            max-height: 54px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.4));
        }

        .welcome-headline {
            color: #ffffff;
            font-size: clamp(1.6rem, 5vw, 1.95rem);
            font-weight: 700;
            line-height: 1.25;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
            margin-bottom: 0.25rem;
        }

        .welcome-subhead {
            color: #ffffff;
            font-size: clamp(1.05rem, 3.5vw, 1.2rem);
            font-weight: 700;
            margin-top: 1.25rem;
            margin-bottom: 0.6rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
        }

        .welcome-body-text {
            color: rgba(255, 255, 255, 0.95);
            font-size: clamp(0.85rem, 2.8vw, 0.95rem);
            line-height: 1.45;
            max-width: 330px;
            margin: 0 auto;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.6);
            font-weight: 400;
        }

        .btn-begin-journey {
            background-color: #DFB773;
            background: linear-gradient(180deg, #E8C88B 0%, #D8A95C 100%);
            color: #251610;
            font-family: 'Brown', 'Mulish', sans-serif;
            font-weight: 800;
            font-size: 0.95rem;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 0.95rem 2rem;
            border-radius: 40px;
            border: none;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            display: inline-block;
            width: 85%;
            max-width: 320px;
            text-align: center;
        }

        .btn-begin-journey:hover, .btn-begin-journey:focus {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.45);
            color: #1A0D08;
        }
    </style>
</head>

<body class="antialiased welcome-page">
    <div class="container main-content text-center">
        <!-- Top Section: Logo & Text Header -->
        <div class="w-100 animate-entry">
            <!-- Resorts World Genting Logo -->
            <div class="mb-4">
                <img class="welcome-logo mx-auto d-block" 
                     src="{{ asset('images/rwg/RWG WHITE_LOGO_2x.webp') }}" 
                     alt="Resorts World Genting"
                     width="200" height="40"
                     fetchpriority="high"
                     decoding="async" />
            </div>

            <!-- Title & Subtitle -->
            <div class="px-2">
                <h1 class="welcome-headline mb-0">
                    Welcome to the
                </h1>
                <h2 class="welcome-headline mb-0">
                    Christmas Trail Challenge
                </h2>

                <p class="welcome-subhead">
                    The ultimate festive quest begins now.
                </p>

                <p class="welcome-body-text">
                    We are thrilled to have you join us for this year's magic. A world of frosty adventure awaits, and some incredible rewards are waiting just for you!
                </p>
            </div>
        </div>

        <!-- Bottom Section: Call To Action -->
        <div class="w-100 animate-entry delay-2 mb-2">
            @auth
                <a href="{{ route('instructions') }}" class="btn-begin-journey rwg-btn-gold">
                    BEGIN THE JOURNEY
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-begin-journey rwg-btn-gold">
                    BEGIN THE JOURNEY
                </a>
            @endauth
        </div>
    </div>

    @if(auth()->check() && auth()->user()->isUnder18())
        <x-age-verification-modal :show="true" />
    @endif

    <x-scriptPackages />
</body>
</html>
