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

        /* #banner .top{
            margin: 15% 0%;
        } */

        .instructions-box
        {
            background-size: contain;
            margin-bottom: -4vw;
            background-repeat: no-repeat;
        }

        .welcome-text
        {
            font-size:50px;
            margin-bottom: -8vw;
        }

        .instructions-text
        {
            font-size:45px;
        }

    </style>
</head>

<body class="antialiased welcome-page" style="background-image:url('{{ asset('images/brand/landing_bg.webp') }}');">

    <div class="container-fluid main-content with-scroll pt-4 px-0">
        <div class="top-container">
            <!-- Branding (top area) -->
            <div class="row flex-grow-1">
                <div class="col-12 animate-entry mb-4">
                    <div>
                        <div class="branding pulse-slow">
                            <img onclick="window.location.href='{{ route('dashboard') }}'" class="logo" src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div id="banner" class="col-10 mx-auto d-flex flex-column justify-content-center animate-entry">
                <div class="top">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="text-center text-white welcome-text">Gandingan Mantap,<br>Berkhasiat dan Sedap!</h3>
                        </div>
                    </div>
                    <div class="row">
                        <img class="w-100 p-0" src="{{ asset('images/brand/masthead.webp') }}"
                        alt="" />
                    </div>
                </div>

                {{-- instructions --}}

                <div id="instructionsParent" class="instructions-parent animate-entry d-none w-100">
                    <p class="text-center instructions-text text-white text-center">INSTRUCTIONS</p>
                    <div class="instructions-box">
                        <img src="{{ asset('images/brand/instructions.webp') }}" alt="" srcset="">
                    </div>
                    <a href="{{ route('quiz') }}" class="custom-btn custom-btn-primary pulse-slow" style="background-image:url('{{ asset('images/brand/btn.png') }}');">
                        Let's Go
                    </a>
                </div>

                    
                    

                <!-- Bottom CTA -->
                <div id="startWrapper" class="row mb-5">
                    <div class="col-12 text-center">
                        <div class="d-block  mb-2">
                            <div class="colanimate-entry delay-2 btn-wrapperx px-5 mt-4">
                                <a href="javascript:void(0)" id="startBtn" class="custom-btn custom-btn-primary pulse-slow" style="background-image:url('{{ asset('images/brand/btn.png') }}');">
                                    Start
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="bottom-container">
            <!-- Bottom CTA -->
            <img class="p-0" src="{{ asset('images/brand/masthead2.webp') }}"alt="" />
        </div>
    </div>

    <x-scriptPackages />
    <script>
        document.getElementById('startBtn').addEventListener('click', function () {

            // show instructions
            document.getElementById('instructionsParent')
                    .classList.remove('d-none');

            // hide start button
            document.getElementById('startWrapper')
                    .style.display = 'none';
        });
    </script>

</body>
</html>
