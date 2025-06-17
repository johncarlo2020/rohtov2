<!--
=========================================================
* Argon Dashboard 2 - v2.0.4
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2022 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="images/logo.svg" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Hadalabo Experience</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" />

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    {{-- Vite import for dashboard styles --}}
    @vite(['resources/sass/dashboard.scss'])
</head>

<body class="g-sidenav-show dashboard-style">
    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border rounded-0  fixed-start "
        id="sidenav-main">
        <div class="sidenav-header border-bottom rounded-0">
            <i class="fas fa-times cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 d-flex w-100 justify-content-center" href="" target="_blank">
                <img src="{{ asset('images/logo.webp') }}" class="navbar-brand-img h-100" alt="main_logo" />
            </a>
        </div>
        <hr class="horizontal dark mt-0" />
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin') ? 'active' : '' }}" href="{{ route('admin') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users') ? 'active' : '' }}" href="{{ route('users') }}">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Users</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 shadow-none bg-white mb-2 border rounded-0 sticky-top" id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-4">
                <nav aria-label="breadcrumb">
                    <div class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5 d-flex justify-content-start gap-2 align-items-center">
                        <p class="font-weight-bolder mb-0  pr-3 mr-3 border px-3 py-1 rounded ">
                            Dashboard
                        </p>
                        <p class="breadcrumb-item text-sm mb-0">
                            <a class="opacity-5 " href="javascript:;">Home</a>
                        </p>
                        <p class="breadcrumb-item text-sm first-line:active mb-0" aria-current="page">
                            Dashboard
                        </p>
                    </div>
                </nav>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid">@yield('content')</div>

        <footer class="footer pt-3">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            <a href="https://wowsome.com.my/" class="font-weight-bold" target="_blank">Wowsome</a>
                            © Copyright 2024
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <script>
        var win = navigator.platform.indexOf("Win") > -1;
        if (win && document.querySelector("#sidenav-scrollbar")) {
            var options = {
                damping: "0.5",
            };
            Scrollbar.init(
                document.querySelector("#sidenav-scrollbar"),
                options
            );
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    {{-- <script src="./assets/js/argon-dashboard.min.js?v=2.0.4"></script> --}}
     @vite(['resources/js/argon-dashboard.js'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
</body>

</html>
