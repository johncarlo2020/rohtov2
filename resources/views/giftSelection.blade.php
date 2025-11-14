<x-app-layout>
    <style>

    .gift-card {
      background-color: #a5e5e7;
      border-radius: 20px;
      border: none;
      padding: 10px 60px;
      cursor: pointer;
      transition: transform 0.2s, background-color 0.2s;
    }

    .gift-card:hover {
      transform: scale(1.05);
      background-color: #92dadc;
    }

    .gift-card img {
      width: 100px;
      height: 100px;
      object-fit:contain;
    }

    .btn-proceed {
      background-color: #a89efc;
      color: white;
      font-weight: 600;
      border-radius: 30px;
      padding: 10px;
      width: 100%;
      border: none;
      transition: background-color 0.3s;
    }

    .btn-proceed:hover {
      background-color: #0000e6;
    }

    .gift-card.active {
      outline: 2px solid #0000e6;
      background-color: #90d8da;
    }
  </style>

    <div id="giftPage" class="main-background main-content">
        <div class="modal fade custom-modal animate-entry" id="staffVerificationModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card card-parent">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="check mx-auto mb-4" id="badge" src="">
                            <div class="text-content mt-0">
                                <p class="sub-heading fw-bold mb-2 station-text "><span class="station_name text-dark"></span></p>
                                <p class="mb-4 message text-main">
                                    Are you sure you want to redeem the gift?
                                </p>
                            </div>
                            <div class="text-content mt-3">
                                <div class="d-flex d-flex justify-content-around">
                                    <a href="#" id="redeemBtn"
                                        class="col-3  custom-btn w-auto px-4 fw-regular custom-btn-primary text-white">
                                        Yes
                                    </a>
                                    <a href="{{ route('dashboard'); }}" id="returnBtn"
                                        class="col-3  custom-btn w-auto px-4 fw-regular custom-btn-primary text-white">
                                        No
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="back-btn animate-entry">
            <a href="{{ route('dashboard') }}" class="">
                <i class="fas fa-chevron-left"></i>
            </a>
        </div>
        <div class="animate-entry">
            @include('components.branding')
        </div>
        <div id="mainContent"
            class="mt-1 mb-2 d-flex flex-column align-items-center justify-content-center animate-entry delay-3">
            <h1 class="heading mb-3 mt-3 sub-heading-text">Gift Redemption</h1>
                <p class="sub-heading mb-1 fw-thin text-dark">
            </p>

  <div class="d-flex flex-column gap-4 mb-5">
      <div class="card card-parent p-4">
            <div class="text-content mt-0">
                <p class="sub-heading fw-bold mb-2 station-text "><span class="station_name text-dark"></span></p>
                <p class="mb-4 text-main text-center ">
                    You've collected<br>
                    ALL the stamps!
                </p>
                <p class="mb-4 text-center">
                    Please head over to the reception counter at B06 &
                    show this page to the staff to claim your reward!
                </p>
                <div class="text-content mt-3">
                    <button type="button"
                        style="width:100%;"
                        class="custom-btn px-5 fw-regular custom-btn-primary text-white"
                        onclick="staffVerificationAction()">
                        Staff Verification
                    </button>
                </div>
            </div>
      </div>
  </div>
        </div>
        <div id="scannerContainer" class="scanner-container d-none mt-4">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center text-dark">Find the QR code & Scan to check in</p>
            <div class="text-center my-3">
                <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary">
                    BACK
                </a>
            </div>
        </div>
    </div>
<x-footer/>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
        <script>
            const cards = document.querySelectorAll('.gift-card');
            const proceedBtn = document.getElementById('start-scanner');
            let selectedGiftId = null;

            cards.forEach(card => {
                card.addEventListener('click', () => {
                cards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                selectedGiftId = card.dataset.id;
                proceedBtn.disabled = false;
                });
            });
        </script>
        <script>
            // Pass data from Blade to JavaScript
            window.stationConfig = {
                urls: {
                    process_qr_code: '{{ route('process_qr_code') }}',
                    congrats: '{{ route('congrats') }}'
                },
                assets: {
                    check_image: '{{ asset('images/check.png') }}',
                    error_image: '{{ asset('images/error.png') }}'
                },
            };

            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('staffVerificationModal');

                if (!modalEl) {
                    console.error('staffVerificationModal element not found!');
                    return;
                }

                // Initialize modal once
                const staffVerificationModal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: false
                });

                // Function to show modal
                window.staffVerificationAction = function () {
                    staffVerificationModal.show();
                };

                // Example: Yes button AJAX inside modal
                document.getElementById('staffVerificationYesBtn').addEventListener('click', function () {
                    console.log('Perform AJAX here...');
                    // Close modal after success
                    staffVerificationModal.hide();
                });
            });

            document.addEventListener('DOMContentLoaded', () => {
                const redeemBtn = document.getElementById('redeemBtn');

                redeemBtn.addEventListener('click', async () => {
                    redeemBtn.disabled = true;
                    redeemBtn.textContent = 'Processing...';

                    try {
                        const response = await fetch("{{ route('giftselection.redeem') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                        });

                        const result = await response.json();
                            if (result.success) {
                                window.location.href = result.redirect;
                            } 

                    } catch (error) {
                        console.error(error);
                        alert('Something went wrong while redeeming your gift.');
                    } finally {
                        redeemBtn.disabled = false;
                        redeemBtn.textContent = 'Yes';
                    }
                });
            });


        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>
