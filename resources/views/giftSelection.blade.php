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
                <p class="mb-4 text-main text-center " style="font-size:28px">
                    You've collected<br>
                    ALL the stamps!
                </p>
                <p class="mb-4 text-center">
                    Please head over to the reception counter at <strong class="text-main">B06</strong> &
                    show this page to the staff to <strong class="text-main">claim your reward!</strong>
                </p>
                <div class="text-content mt-3">
                    <button type="button"
                        style="width:100%;"
                        class="custom-btn px-5 fw-regular custom-btn-primary text-white gift-redemption-btn"
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
            });

            document.addEventListener('DOMContentLoaded', () => {
                // Trigger confetti on page load
                const duration = 3 * 1000;
                const animationEnd = Date.now() + duration;
                const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 9999 };

                function randomInRange(min, max) {
                    return Math.random() * (max - min) + min;
                }

                const interval = setInterval(function() {
                    const timeLeft = animationEnd - Date.now();

                    if (timeLeft <= 0) {
                        clearInterval(interval);
                        return;
                    }

                    const particleCount = 50 * (timeLeft / duration);
                    confetti(Object.assign({}, defaults, { 
                        particleCount, 
                        origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } 
                    }));
                    confetti(Object.assign({}, defaults, { 
                        particleCount, 
                        origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } 
                    }));
                }, 250);

                const redeemBtn = document.getElementById('redeemBtn');

                if (redeemBtn) {
                    redeemBtn.addEventListener('click', async (e) => {
                        e.preventDefault();
                        
                        redeemBtn.style.pointerEvents = 'none';
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
                            } else {
                                alert(result.message || 'Failed to redeem gift.');
                                redeemBtn.style.pointerEvents = 'auto';
                                redeemBtn.textContent = 'Yes';
                            }

                        } catch (error) {
                            console.error(error);
                            alert('Something went wrong while redeeming your gift.');
                            redeemBtn.style.pointerEvents = 'auto';
                            redeemBtn.textContent = 'Yes';
                        }
                    });
                }
            });


        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>
