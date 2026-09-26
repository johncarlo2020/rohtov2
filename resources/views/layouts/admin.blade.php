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
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Admin portal</title>
    <!--     Fonts and icons     -->
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" />

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @vite(['resources/sass/dashboard.scss'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    @include('components.fonts')
</head>

<body class="event-admin">
    @php
        $adminPages = [
            ['admin', 'Overview', 'fa-chart-simple', ['full']],
            ['users', 'Participants', 'fa-users', ['full']],
            ['admin.station-qrs', 'Station QR codes', 'fa-qrcode', ['view', 'full']],
        ];
        $pageTitle = collect($adminPages)->first(fn ($page) => request()->routeIs($page[0]))[1] ?? 'Participant details';
        if (request()->routeIs('charmConfig')) $pageTitle = 'Reward settings';
    @endphp
    <aside class="admin-sidebar" aria-label="Admin navigation">
        <div class="admin-brand" aria-hidden="true">
            <img src="{{ asset('files/main/logo-space.svg') }}" alt="" aria-hidden="true" />
        </div>
        <p class="admin-nav-label">EVENT MANAGEMENT</p>
        <nav>
            @foreach ($adminPages as [$route, $label, $icon, $permissions])
                @canany($permissions)
                    <a href="{{ route($route) }}" class="admin-nav-link {{ request()->routeIs($route) || ($route === 'users' && request()->routeIs('userData', 'userFilter')) ? 'is-active' : '' }}"
                        @if(request()->routeIs($route)) aria-current="page" @endif>
                        <i class="fa-solid {{ $icon }}" aria-hidden="true"></i><span>{{ $label }}</span>
                    </a>
                @endcanany
            @endforeach
            @if(auth()->user()->email === 'admin@loccitane.com')
                <a class="admin-nav-link {{ request()->routeIs('charmConfig') ? 'is-active' : '' }}" href="{{ route('charmConfig') }}"><i class="fa-solid fa-gear" aria-hidden="true"></i>Reward settings</a>
            @endif
        </nav>
        <div class="admin-sidebar-bottom">
            <div class="admin-discover">DISCOVER <strong>MORE</strong></div>
            <a href="{{ url('/') }}" class="admin-nav-link"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>Open event site</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="admin-nav-link" type="submit"><i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>Sign out</button></form>
        </div>
    </aside>
    <main class="admin-workspace">
        <header class="admin-topbar">
            <h1>{{ $pageTitle }}</h1>
            <div class="admin-account"><span>{{ auth()->user()->fname ?: 'Administrator' }}</span></div>
        </header>
        <div class="admin-page-content">@yield('content')</div>
        <footer class="admin-page-footer">© {{ date('Y') }} <span>Discover more. Manage with ease.</span></footer>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    @stack('scripts')
</body>
</html>
