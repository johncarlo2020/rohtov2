<x-app-layout>
    <style>
        .message {
            letter-spacing: 3px;
        }

        /* Header & Branding consistency matching dashboard */
        .branding-wrapper {
            margin-bottom: clamp(49px, 8vh, 106px) !important;
            text-align: center;
        }

        .page-main-title {
            font-weight: 700 !important;
            letter-spacing: 2px;
            line-height: 1.25;
            font-size: clamp(1.15rem, 4.5vw, 1.5rem);
            margin: 0 !important;
            text-align: center;
            color: #fff;
        }

        .discover-page {
            min-height: 100svh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-top: max(16px, env(safe-area-inset-top, 0px)) !important;
            padding-bottom: max(20px, calc(env(safe-area-inset-bottom, 0px) + 16px)) !important;
        }

        .discover-content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            width: 100%;
            max-width: min(94vw, 460px);
            margin: clamp(42px, 5vh, 98px) auto 0 auto;
        }

        .discover_img {
            max-width: 100%;
            height: auto;
            display: block;
        }
    </style>

    <div class="map-page main-content main-background with-scroll discover-page">

        <!-- Header Section (Branding + Title) matching dashboard exactly -->
        <div class="dashboard-header animate-entry">
            <div class="branding-wrapper">
                @include('components.branding')
            </div>

            <div class="text-center">
                <h2 class="page-main-title">LET THE YSL SHOW BEGIN</h2>
            </div>
        </div>

        <!-- Content Section with matching margin-top -->
        <div class="discover-content-wrapper animate-entry">
            <div id="banner" class="col-12 d-flex justify-content-center p-0">
                <img class="discover_img w-100" src="{{ asset('images/brand/discover.webp') }}"
                    alt="Discover" />
            </div>

            <div class="discover-text">
                <p class="text-center px-3 py-4" style="font-size:14px; color:#fff;">
                    Step into the wildest party of the season.<br><br>
                    Discover liberate boundless freedom and unapologetic self-expression to be the part of the YSL Holiday Show.
                </p>
            </div>

            <div class="text-content text-center px-3 w-100">
                <a
                    href="{{ route('dashboard') }}"
                    class="custom-btn custom-btn-secondary animate-entry delay-3 px-4"
                    ><strong>BEGIN YOUR JOURNEY</strong></a
                >
            </div>
        </div>

    </div>

    <!-- Modal outside flex flow -->
    <div class="modal fade custom-modal animate-entry" id="welcomeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered w-75 m-auto">
            <div class="modal-content card modal-parent">
                <div class="modal-body">
                    <div class="text-center content">
                        <div class="text-content">
                            <h5 class="text-dark mb-3" style="font-size:16px;">Your Member ID</h5>
                            <p class="message text-dark text-center mb-3">
                                {{ preg_replace('/^\+?\d{1,2}/', '', auth()->user()->number) }}
                            </p>
                        </div>
                        <button type="button" class="w-100 custom-btn custom-btn-primary" data-bs-dismiss="modal"
                            aria-label="Close" style="font-weight:300;">DONE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const banner = document.getElementById('banner');
    const modalEl = document.getElementById('welcomeModal');
    const modal = new bootstrap.Modal(modalEl);

    banner.addEventListener('click', function () {
        modal.show();
    });
});
</script>
</x-app-layout>
