<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hadalabo Experience</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


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
        <div class="info-container bg-white p-3 rounded">
            {{-- Todo: Change with parameter --}}
            <div id="withQr" class="d-none mb-3">
                @include('components.containerWithQr')
            </div>
              <div id="withoutQr" class="mb-4">
                @include('components.containerWithoutQr')
            </div>
              <div class="button-container">
            <button id="homeButton" type="button" class="button button-primary w-100 mb-2" data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                Home
            </button>
            <button id="rescheduleButton" type="button" class="button button-secondary w-100" data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                Reschedule
            </button>
        </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-xmark"></i></a>
                        <div class="info-icon mb-3">
                            <img src="{{ asset('files/main/info.png') }}" alt="" />
                        </div>
                        <p class="modal-main-text mb-1">Do you want to reschedule your visit ?</p>
                        <p class="warning-text text-center px-5">Note: You may reschedule your selected date <strong>only once</strong>.</p>
                        <div class="">
                            <button id="confirmVisitButton" type="submit" class="button button-primary w-100 mb-2">
                                YES
                            </button>
                            <button id="cancelModalButton" type="button" class="button button-secondary w-100 mb-2" data-bs-dismiss="modal">
                                NO
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
        </div>
    </div>
</body>

</html>
