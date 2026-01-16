<x-app-layout>
    <style>
        .main-content{
            padding-top: 0 !important;
        }

        .discover-page{
            background: #000;
        }

        img.discover_img{
            height: 75vh;
            object-fit: cover;
        }

        .message 
        {
            letter-spacing: 3px;
        }
    </style>
        <div class="pb-5 px-0 discover-page main-content with-scroll">
            <div class="content-wrapper d-flex flex-column justify-content-between align-items-center" style="min-height: 100%; flex: 1;">

                <div id="banner" class="col-12 d-flex justify-content-center p-0 animate-entry">
                    <img class="discover_img w-100" src="{{ asset('images/brand/discover.webp') }}"
                        alt="" />
                </div>

                <!-- Modal -->
                <div class="modal fade custom-modal animate-entry" id="welcomeModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                        <div class="modal-content card modal-parent">
                            <div class="modal-body">
                                <div class="text-center content">
                                    <div class="text-content">
                                        <h5 class="text-dark mb-3">Your Member ID</h5>
                                        <p class="message text-dark text-center mb-3">
                                            {{ preg_replace('/^\+?\d{1,2}/', '', auth()->user()->number) }}
                                        </p>
                                    </div>
                                    <button type="button" class="w-100 custom-btn custom-btn-primary" data-bs-dismiss="modal"
                                        aria-label="Close">DONE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="discover-text">
                    <p class="text-center px-2">
                        Get ready to enter the immersive YSL BEAUTY LIGHT CLUB,
                        a unique destination where beauty sounds better with music.
                    </p>
                </div>

                <div class="text-content text-center px-3">

                    <a
                        href="{{ route('dashboard') }}"
                        class="custom-btn custom-btn-secondary animate-entry delay-3 px-4"
                        ><strong>CLICK TO START JOURNEY<strong></a
                    >
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
