<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Hadalabo Experience</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet" />
</head>
<style>
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

    h1 {
        font-family: "Montserrat", sans-serif;
        font-weight: 700;
        /* Bold */
    }
</style>

<body class="antialiased welcome-page">
    {{-- <img class="cover-image" src="{{ asset('images/girl_bg.png') }}" alt="" /> --}}
    <img class="cover-image" src="{{ asset('images/rohto.webp') }}" alt="" />
    <div class="py-5 container-fluid main-content">
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center">
                @include('components.branding')
            </div>
            <div class="text-center col-12 text-content">
                <h3 class="sub-heading mb-4">
                    Mentholatum 135th Anniversary Roadshow
                </h3>

                <h1>Vote For Your Favourite Brand</h1>
            </div>
        </div>
    </div>
    <div class="register-main">
        <a href="{{ route('vote') }}" class="home-btn btn rounded-pill"><span>Vote</span></a>
        <a href="{{ route('register') }}" class="sign-btn btn rounded-pill"><span>Sign Up</span></a>

        <a class="footer-text" href="https://wowsome.com.my/">Powered by WOWSOME®2024</a>
    </div>
</body>

</html>
