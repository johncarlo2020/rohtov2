<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Rohto</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
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
</style>

<body class="antialiased welcome-page">
    <img class="cover-image" src="{{ asset('images/girl_bg.png') }}" alt="" />
    <div class="py-5 container-fluid main-content">
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center">
                @include('components.branding')
            </div>
            <div class="text-center col-12 text-content">
                <h1>Welcome to</h1>
                <h2 class="heading">
                    Mentholatum 135th Anniversary Roadshow
                </h2>
                <h3 class="sub-heading">Queensbay Mall, Penang</h3>
                <p class="date with-border">24-29 Sept 2024</p>
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
