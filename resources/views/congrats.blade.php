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
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet"
        />
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
            crossorigin="anonymous"
        />
    </head>

    <body class="welcome home">
        <style>
            .main-bg {
                background-image: url("{{ asset("/images/main-bg.png") }}");
            }
            .main-logo {
                width: 34%;
                height: auto;
            }

            .tag-line {
                font-size: 16px;
                line-height: 20px;
                font-weight: 400;
                color: #3c727a;
                letter-spacing: 1px;
            }

            .congrats {
                height: 100%;
                display: flex;
                flex-direction: column;
                gap: 20px;
                text-align: center;
                justify-content: center;
                align-items: center;
            }
            .visit {
                align-self: flex-end;
            }
        </style>
        <div class="congrats">
            <div class="branding">
                <img
                    class="logo"
                    src="{{ asset('images/logo-large.png') }}"
                    alt=""
                />
            </div>
            <p class="mt-5 tag-line">
                CONGRATULATIONS <br />
                YOU HAVE COMPLETED <br />
                YOUR JOURNEY
            </p>
            <div class="visit">
                <p class="mt-5 mb-4">Visit our official website</p>
                <div class="mb-3 logo-footer">
                    <img
                        class="main-logo"
                        src="{{ asset('images/logo-large.png') }}"
                        alt=""
                    />
                </div>
                <a href="">CLick Here for more information</a>
            </div>
        </div>
    </body>
</html>
