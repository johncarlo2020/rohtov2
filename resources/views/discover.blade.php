<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Rohto</title>

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"
            rel="stylesheet"
        />
    </head>

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: "Nunito", sans-serif; /* Set the font to Nunito */
            font-size: 16px; /* Set the font size to 16px */
        }
        .container-fluid {
            flex: 1;
        }
        footer {
            font-size: 10px; /* Adjust the font size for the footer as needed */
            text-align: center;
            padding: 10px 0;
            background-color: none;
        }
        footer a {
            text-decoration: none; /* Remove underline */
            color: inherit; /* Keep the link color same as the text color */
        }
    </style>

    <body class="antialiased home">
        <div class="container-fluid py-5">
            <div class="content-wrapper">
                <div class="branding-container">
                    @include('components.branding')
                </div>
                <div class="text-content text-center px-3">
                    <h1 class="heading">Welcome!</h1>
                    <p class="mb-5">
                        Join us today for an adventure packed with exciting and
                        fun activities at every station. Don’t miss out on the
                        fun!
                    </p>
                    <a
                        href="{{ route('welcome') }}"
                        class="btn discover-btn rounded-pill"
                        >DISCOVER NOW</a
                    >
                </div>
            </div>
        </div>
        <footer>
            <a href="https://wowsome.com.my/">Powered by WOWSOME®2024</a>
        </footer>
    </body>
</html>
