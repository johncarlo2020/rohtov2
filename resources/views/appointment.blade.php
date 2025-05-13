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
</head>

<body class="antialiased welcome-page">
    <div class="content-box main-background px-3 d-flex flex-column min-vh-100">
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div class="otp-form-container bg-white px-3 py-4 rounded">
            <form method="POST">
                @csrf
                <div class="text-center mb-4 px-1">
                    <h2 class="heading-text text-center mb-2">Hi Selena</h2>
                    <p class="sub-heading-text text-center">Please select your preferred date for the Ocean or Plastic Roadshow visit and redemption.</p>
                    <p class="sub-heading-text text-center">Kindly note that redemption is only valid on the selected date. Redemption on a different date will not be accommodated. You may only reschedule once, after submission.
                    </p>
                </div>
                <div class="date-picker">
                    <h2 class="heading-text text-center mb-2">Date selected: 21 May 2025</h2>
                </div>
                <div class="text-center mb-3">
                    <button type="submit" class="button button-primary w-100">Submit</button>
                </div>
            </form>
        </div>

        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

        });
    </script>
</body>

</html>
