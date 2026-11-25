<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ config('app.name', 'Congratulations – Resorts World Genting') }}</title>

    <x-appCdnPackages />
    @vite(['resources/sass/app.scss'])

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Brown', 'Mulish', sans-serif;
        }

        .congrats-page {
            width: 100vw;
            height: 100vh;
            height: 100dvh;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .congrats-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(20, 5, 35, 0.45) 0%, rgba(35, 10, 60, 0.85) 100%);
            z-index: 1;
        }

        .main-content {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: calc(env(safe-area-inset-top) + 2rem) 1.5rem calc(env(safe-area-inset-bottom) + 2rem);
        }

        .congrats-logo {
            max-height: 55px;
            width: auto;
            object-fit: contain;
        }

        .btn-gold {
            background: linear-gradient(135deg, #F3C353 0%, #D4952B 100%);
            color: #2D1438;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 0.85rem 2rem;
            border-radius: 30px;
            border: none;
            box-shadow: 0 8px 20px rgba(212, 149, 43, 0.4);
            transition: transform 0.2s ease;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            max-width: 320px;
        }

        .btn-gold:hover, .btn-gold:focus {
            transform: translateY(-2px);
            color: #2D1438;
        }
    </style>
</head>

<body class="antialiased congrats-page" style="background-image: url('{{ asset('images/rwg/01 RW Genting Winter_background_2x.webp') }}');">
    <div class="congrats-overlay"></div>

    <div class="container main-content text-center">
        <!-- Top Logo -->
        <div class="animate-entry">
            <img class="congrats-logo mx-auto d-block" src="{{ asset('images/rwg/RWG WHITE_LOGO_2x.webp') }}" alt="Resorts World Genting" />
        </div>

        <!-- Center Celebration -->
        <div class="animate-entry delay-2 my-auto">
            <div class="mb-3">
                <i class="fa-solid fa-trophy fa-3x text-warning"></i>
            </div>
            <span class="text-uppercase tracking-wider text-warning font-weight-bold" style="font-size: 0.95rem; letter-spacing: 2px;">
                Treasure Hunt Completed
            </span>
            <h1 class="text-white fw-bold mt-2 mb-3" style="font-size: 2rem; line-height: 1.2;">
                CONGRATULATIONS!
            </h1>
            <p class="text-white-50 mx-auto" style="max-width: 340px; font-size: 0.95rem; line-height: 1.5;">
                You have visited all 6 stations across Resorts World Genting and unlocked your Winter Wonderland 2026 reward.
            </p>
            <div class="p-3 my-3 rounded mx-auto" style="max-width: 340px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(212, 149, 43, 0.4);">
                <p class="small text-white mb-0">
                    <i class="fa-solid fa-circle-check text-success me-1"></i>
                    Please present this screen to our redemption counter staff to claim your physical reward.
                </p>
            </div>
        </div>

        <!-- Bottom Actions -->
        <div class="animate-entry delay-3 mb-2">
            <a href="{{ route('dashboard') }}" class="btn-gold shadow-lg mb-3">
                VIEW MAP PROGRESS
            </a>
            <div>
                <a href="https://www.rwgenting.com" target="_blank" rel="noopener" class="text-white-50 small text-decoration-underline">
                    Visit Resorts World Genting Official Website <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.75rem;"></i>
                </a>
            </div>
        </div>
    </div>

    <x-scriptPackages />
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof confetti === 'function') {
                confetti({
                    particleCount: 80,
                    spread: 70,
                    origin: { y: 0.6 }
                });
            }
        });
    </script>
</body>
</html>
