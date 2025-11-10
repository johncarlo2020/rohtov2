<x-app-layout>
    <div class="py-5 map-page main-content stamping-page">
        <div class="d-flex justify-content-center align-item-center animate-entry">
            @include('components.branding')
        </div>

        <!-- login Modal -->
        <!-- Welcome Modal -->
        <div class="modal fade transparent-modal" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center position-relative card">

                    <!-- Close Button (top-right) -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                    <!-- Image -->
                    <img src="{{ asset('images/dutchlady/dutchLadyWelcomeModal.webp') }}" alt="Welcome"
                        class="img-fluid">

                </div>
            </div>
        </div>
        <div class="text-success text-center mt-2 d-none">
            <h2>Nicely done!!<br>
            Stamp Collected!</h2>
        </div>
        <div class="station-selection-container mb-2 animate-entry delay-2">
            <!-- Center image (middle area) -->
            <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center p-0 animate-entry">
                    <img class="stamping-image" 
                        src="{{ asset('images/brand/STMP' . request()->segment(2) . '.webp') }}"
                        alt="">
                </div>
            </div>

            <!-- Bottom CTA -->
            <div class="row">
                <div class="col-12 text-center">
                    <div class="d-block">
                        <div class="col mb-3 animate-entry delay-2">
                            <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary">
                                HOME
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-footer/>
    </div>
</x-app-layout>
