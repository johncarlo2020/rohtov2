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
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="mobile-web-app-capable" content="yes">
    <title>{{ config('app.name', 'Loading ...') }}</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <!-- Font Awesome Icons -->
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
    <style>
        @media (max-width: 1199.98px) {
            body.g-sidenav-show:not(.rtl) .sidenav,
            .sidenav {
                transform: translateX(-105%) !important;
            }
            body.g-sidenav-show.g-sidenav-pinned .sidenav {
                transform: translateX(0) !important;
            }
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
</head>

<body class="g-sidenav-show dashboard-style">
    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border rounded-0  fixed-start "
        id="sidenav-main">
        <div class="sidenav-header border-bottom rounded-0 bg-gradient-dark d-flex justify-content-center position-relative">
            <i class="fas fa-times cursor-pointer text-white opacity-8 position-absolute end-0 top-0 p-3 d-xl-none"
                aria-hidden="true" id="iconSidenav" style="z-index: 10;"></i>
            <a class="navbar-brand m-auto d-flex w-75 justify-content-center align-items-center" href="{{ route('admin.overview') }}">
                <img src="{{ asset('porsche/images/PORSCHE_logo_2x.webp') }}" class="navbar-brand-img" style="max-height: 28px; width: auto; object-fit: contain;" alt="Porsche" />
            </a>
        </div>
        <hr class="horizontal dark mt-0" />
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin*overview') || request()->routeIs('admin') ? 'active' : '' }}" href="{{ route('admin.overview') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-gauge text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.participants*') || request()->routeIs('users*') ? 'active' : '' }}" href="{{ route('admin.participants') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-users text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.entries*') ? 'active' : '' }}" href="{{ route('admin.entries') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-list-check text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Quiz Entries</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-chart-pie text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-sliders text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Campaign Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative border-radius-lg">
        @php
            $routeName = Route::currentRouteName() ?? '';
            if (str_contains($routeName, 'participant') || str_contains($routeName, 'user')) {
                $cleanTitle = 'Users';
            } elseif (str_contains($routeName, 'entri') || str_contains($routeName, 'entry')) {
                $cleanTitle = 'Quiz Entries';
            } elseif (str_contains($routeName, 'report')) {
                $cleanTitle = 'Reports';
            } elseif (str_contains($routeName, 'setting')) {
                $cleanTitle = 'Settings';
            } elseif (str_contains($routeName, 'overview') || $routeName === 'admin') {
                $cleanTitle = 'Dashboard';
            } else {
                $cleanTitle = ucfirst(last(explode('.', $routeName)));
            }
        @endphp

        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand px-0 shadow-none bg-white mb-2 border rounded-0 sticky-top" id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-2 px-3 px-md-4 d-flex justify-content-between align-items-center flex-nowrap">
                <nav aria-label="breadcrumb">
                    <div class="breadcrumb bg-transparent mb-0 pb-0 pt-0 px-0 d-flex justify-content-start gap-2 align-items-center flex-nowrap text-nowrap">
                        <p class="font-weight-bolder mb-0 border px-3 py-1 rounded text-primary" style="font-size: 0.95rem; line-height: 1.4;">
                             {{ $cleanTitle }}
                        </p>
                        <p class="breadcrumb-item text-sm mb-0">
                            <a class="opacity-5 text-secondary" href="{{ route('admin.overview') }}">Home</a>
                        </p>
                        <p class="breadcrumb-item text-sm active mb-0 text-secondary" aria-current="page">
                              {{ $cleanTitle }}
                        </p>
                    </div>
                </nav>

                <div class="d-flex align-items-center ms-auto">
                    <ul class="navbar-nav d-flex flex-row align-items-center gap-3 mb-0 ps-2">
                        <li class="nav-item d-flex align-items-center">
                            <form role="form" method="post" action="{{route('admin.logout')}}" id="logout-form" class="m-0">
                               @csrf
                                <a href="{{route('admin.logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="nav-link font-weight-bold px-0 text-dark d-flex align-items-center" title="Log out">
                                    <i class="fa fa-user fs-6"></i>
                                    <span class="d-sm-inline d-none ms-1">Log out</span>
                                </a>
                            </form>
                        </li>
                        <li class="nav-item d-xl-none ps-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link p-0 text-dark" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-dark"></i>
                                    <i class="sidenav-toggler-line bg-dark"></i>
                                    <i class="sidenav-toggler-line bg-dark"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid">@yield('content')</div>

        <footer class="footer py-3">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            <a href="https://wowsome.com.my/" class="font-weight-bold" target="_blank">Wowsome</a>
                            © Copyright 2025
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
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    @vite(['resources/js/argon-dashboard.js'])
</body>

</html>
