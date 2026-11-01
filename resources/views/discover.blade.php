<x-app-layout>
    <style>
        .message 
        {
            letter-spacing: 3px;
        }
    </style>
        <div class="pb-5 px-0 main-background main-content with-scroll">
            <div class="content-wrapper d-flex flex-column justify-content-between align-items-center" style="min-height: 100%; flex: 1;">
                <div class="animate-entry">
                    @include('components.branding')
                </div>
                
                <div class="animate-entry">
                <h2 class="text-title text-center fw-bold py-3" >LET THE YSL SHOW BEGIN</h2>
                </div>
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

                <div class="discover-text">
                    <p class="text-center px-3 py-4" style="font-size:14px;">
                        Step into the wildest party of the season.<br><br>
                        Discover liberate boundless freedom and unaplogetic self-expression to be the part of the YSL Holiday Show.
                    </p>
                </div>

                <div class="text-content text-center px-3">

                    <a
                        href="{{ route('dashboard') }}"
                        class="custom-btn custom-btn-secondary animate-entry delay-3 px-4"
                        ><strong>BEGIN YOUR JOURNEY</strong></a
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
