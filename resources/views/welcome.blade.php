<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Dutch Lady</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet" />
</head>
<style>

     @font-face {
            font-family: 'HvDTrial Brevia';
            src: url('/images/font/HvDTrial_Brevia-ExtraBlack.otf') format('opentype');
            font-weight: 900;
            font-style: normal;
            }

    body,
    html {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
        font-family: "Nunito", sans-serif;
        font-size: 16px;
    }

    .container-fluid {
        flex: 1;
    }

    footer {
        font-size: 12px;
        text-align: center;
        padding: 10px 0;
    }

    footer a {
        text-decoration: none;
        color: inherit;
    }

    .welcome_img {
        width: 80vw;
        height: auto;
        object-fit: contain
    }

    .welcome_img_store {
        width: 100%;
        height: auto;
        object-fit: contain
    }

    .welcome-sign-btn {
        width: 40vw !important;
        min-width: unset !important;
    }

    .sign-up-btn-txt {
  font-family: 'HvDTrial Brevia', sans-serif;
  font-weight: 900; /* optional, reinforces the ExtraBlack weight */

}
</style>

<body class="antialiased welcome-page main-background hadalabo">
    <div class="py-3 container-fluid main-content">
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center">
                @include('components.branding')
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center">
                <img class="welcome_img" src="{{ asset('images/dutchlady/dutchLadyLoginText1.webp') }}" alt="" />
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center p-0">
                <img class="welcome_img_store" src="{{ asset('images/dutchlady/dutchLadyLoginImg.webp') }}" alt="" />
            </div>
            <div class="text-center bottom-text-welcome col-12 mt-4">
                <div class="d-block">
                    <div class="col mb-3">
                        <a href="{{ route('register') }}" class="button-dutch button-dutch button-dutch-primary"><span class="sign-up-btn-txt">Sign Up</span></a>
                    </div>
                    <div class="col">
                        <a href="{{ route('login') }}" class="button-dutch button-dutch button-dutch-outlined"><span class="text-primary">Login</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="register-main mb-3">
        <a class="footer-text" href="https://wowsome.com.my/">Powered by WOWSOME®2025</a>
    </div>
</body>

</html>
