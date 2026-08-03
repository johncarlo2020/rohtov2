<x-app-layout>
    <div class="antialiased main-background h-100 ipad-page">
        <div class="py-3 container-fluid main-content">
            <div class="row">
                <div class="back-btn animate-entry">
                    <a href="{{ route('ipad.index') }}" class="">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </div>
                <div class="col-12 d-flex justify-content-center align-items-center animate-entry">
                    <div class="branding">
                        <img class="logo" src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" />
                    </div>
                </div>
                <div class="text-center col-12 mt-5">
                    <div class="d-block">
                        <div class="row align-items-center w-75 mx-auto">
                            <div class="col-5">
                                <img src="{{ asset('images/brand/diverImg.png') }}" class="rounded-circle"
                                    width="150" height="150" alt="diver">
                            </div>
                            <div class="col-7 text-start mb-3">
                                <h2 class="mb-2">Mission</h2>
                                <small style="font-size:12px;">
                                    SEKKISEI has partnered with the Coral Reef Alliance since 2018 with the mission to
                                    preserve coral reefs and protect marine habitats in the United States and the
                                    Caribbean.
                                    SEKKISEI aims to raise awareness on how we can all work together to keep our oceans
                                    healthy for future generations.
                                </small>
                            </div>
                            <div class="col-7 text-start mb-3">
                                <h2 class="mb-2">Donation</h2>
                                <small style="font-size:12px;">
                                    In the United States, your purchase of any CLEAR WELLNESS product in the month of
                                    June includes a donation*
                                    to The Coral Reef Alliance. In support of the SAVE The BLUE project, SEKKISEI will
                                    donate a portion of the proceeds of each product sold.
                                </small>
                            </div>
                            <div class="col-5">
                                <div class="rounded-circle"
                                    style="background-image: url('images/brand/globeImg.jpg');
                                background-size: cover;
                                background-position: center;
                                border-radius: 15px;
                                height: 150px;
                                width: 150px;">
                                </div>
                                <!-- <img src="{{ asset('images/brand/globeImg.jpg') }}" class="rounded-circle" width="150" height="150" alt="diver"> -->
                            </div>
                            <div class="col-5">
                                <img src="{{ asset('images/brand/handImg.png') }}" class="rounded-circle" width="150"
                                    height="150" alt="hand">
                            </div>
                            <div class="col-7 text-start mb-3">
                                <h2 class="mb-2">Results</h2>
                                <small style="font-size:12px;">
                                    Our donations to The Coral Reef Alliance support coral health and resilience against
                                    climate change. Since
                                    2009, SEKKISEI has worked to save endangered coral reefs in Okinawa and support
                                    ocean conservation. Since 2011,
                                    we’ve expanded globally. This year, our "SAVE the BLUE" project will operate in
                                    eight countries,
                                    focusing on coral preservation and tree planting.
                                </small>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade custom-modal animate-entry delay-2" id="notAllowedModal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                                <div class="modal-content card">
                                    <div class="modal-body">
                                        <div class="text-center content">
                                            <div class="text-content mt-4 mb-4">
                                                <p class="message">
                                                    Do you pledge to<br>
                                                    Save the Blue ?
                                                </p>
                                            </div>
                                            <div class="d-block gap-3">
                                                <button id="modalYesBtn" type="button"
                                                    class="custom-btn custom-btn-primary w-50 mb-3">
                                                    Yes
                                                </button>
                                                <button type="button" class="custom-btn custom-btn-secondary w-50 mb-3"
                                                    data-bs-dismiss="modal" aria-label="Close">
                                                    No
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col mt-5 animate-entry delay-2">
                            <button type="button" class="custom-btn custom-btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#notAllowedModal">
                                CONTINUE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-scriptPackages />
    <script>
        document.getElementById('modalYesBtn').addEventListener('click', function() {
            window.location.href = "{{ route('ipad.message.type') }}"; // Adjust route if needed
        });
    </script>
</x-app-layout>
