<x-app-layout>
    <style>
        .answer-btn {
            background-image: url('{{ asset('images/bubble_animation.webp') }}');
            background-position: 0 0;
            background-size: 900% 100%;
            background-repeat: no-repeat;
            background-color: transparent;
            box-shadow: none;
            width:35vw;
            height:35vw;
            border-radius:50%;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .answer-btn.animating {
            background-image: none;
        }

        .answer-btn.wrong-animating {
            background-image: none;
            animation: wrongButtonScale 1.2s ease-out forwards;
        }

        @keyframes wrongButtonScale {
            0% {
                transform: scale(1);
            }
            15% {
                transform: scale(1.1);
            }
            35% {
                transform: scale(0.98);
            }
            50% {
                transform: scale(1.05);
            }
            70% {
                transform: scale(1);
            }
            100% {
                transform: scale(1);
            }
        }

        .station-card {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .button-wrapper {
            position: relative;
            width: 35vw;
            height: 35vw;
        }

        .bubble-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('images/bubble_animation.webp') }}');
            background-repeat: no-repeat;
            background-size: 900% 100%;
            background-position: 0 0;
            pointer-events: none;
            z-index: 10;
            display: none;
            border-radius: 50%;
            overflow: hidden;
        }

        .bubble-animation.animate {
            display: block;
            animation: bubbleSprite 0.9s steps(1) forwards;
        }

        .bubble-animation.wrong {
            display: block;
            background-image: url('{{ asset('images/brand/Bubble_wrong.webp') }}');
            background-size: 100% 100%;
            background-position: center;
            z-index: 0;
            animation: wrongFadeScale 1.2s ease-out forwards;
        }

        @keyframes wrongFadeScale {
            0% {
                opacity: 0;
            }
            15% {
                opacity: 1;
            }
            70% {
                opacity: 1;
            }
            85% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }

        @keyframes bubbleSprite {
            0% { background-position: 0% 0; }
            11.11% { background-position: 12.5% 0; }
            22.22% { background-position: 25% 0; }
            33.33% { background-position: 37.5% 0; }
            44.44% { background-position: 50% 0; }
            55.55% { background-position: 62.5% 0; }
            66.66% { background-position: 75% 0; }
            77.77% { background-position: 87.5% 0; }
            88.88% { background-position: 100% 0; }
            100% { background-position: 100% 0; }
        }

        .station-card.col-6.choice-6 {
            margin-top: 15vh;
        }
        /* .station-card.col-6.choice-5
        {
            margin-bottom:50vw;
        } */


        .overlay {
            position: fixed;
            top: env(safe-area-inset-top, 0);
            left: env(safe-area-inset-left, 0);
            width: calc(100vw - env(safe-area-inset-left, 0) - env(safe-area-inset-right, 0));
            height: calc(100vh - env(safe-area-inset-top, 0) - env(safe-area-inset-bottom, 0));
            pointer-events: none;
            backdrop-filter: blur(8px);
        }

        /* Overlay for station 1 */
        .overlay.station-1,
        .overlay.station-3 {
            background: linear-gradient(
                180deg,
                rgba(233, 239, 250, 0.3) 0%,
                rgba(124, 161, 255, 0.3) 48.56%,
                rgba(9, 84, 181, 0.3) 100%
            );
        }

        /* Overlay for station 2 */
        .overlay.station-2 {
            background: linear-gradient(
                180deg,
                rgba(233, 250, 241, 0.3) 0%,   /* light green with transparency */
                rgba(124, 255, 194, 0.3) 48.56%, /* mid green with transparency */
                rgba(9, 181, 95, 0.3) 100%       /* dark green with transparency */
            );
        }

    </style>

    
    <div id="stationPage" class="station-page main-content main-background">
        <div class="overlay station-{{$station->id}}"></div>
        <div class="modal fade custom-modal animate-entry" id="answerCorrectModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="check mx-auto mb-4" id="badge" src="">
                            <div class="text-content mt-0">
                                <p class="sub-heading fw-bold mb-2 station-text "><span class="station_name text-dark"></span></p>
                                <p class="mb-4 message text-dark">
                                    Excellent!
                                </p>
                                <div class="d-flex justify-content-center mb-4">
                                    <img src="{{ asset('images/check.png') }}" alt="">
                                </div>
                                <p class="mb-4 message text-dark">
                                    Your knowledge is <br>
                                    shining through!
                                </p>
                            </div>
                            <div class="text-content mt-3">
                                <a href="{{ route('station.stamping', $station->id);}}" id="routeBtn"
                                    class="custom-btn w-auto px-5 fw-regular custom-btn-primary text-white">
                                    NEXT
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gift Confirmation Modal -->
        <div class="modal fade custom-modal" id="giftConfirmModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="text-danger mb-3">
                                <i class="fas fa-exclamation-circle fa-3x"></i>
                            </div>
                            <h5 class="text-dark mb-3">Are you sure to redeem?</h5>
                            <p class="text-dark mb-4" id="selectedGiftText">Gift 1</p>
                            <div class="d-flex flex-column gap-2">
                                <button id="confirmYes" class="custom-btn custom-btn-primary w-100">YES</button>
                                <button id="confirmNo" class="custom-btn custom-btn-secondary w-100" style="background: white; color: #0000e6; border: 2px solid #0000e6;">NO</button>
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
                <p class="sub-heading mb-1 text-white mb-3 mt-3 text-center">
                    {{ isset($station->question) ? $station->question : '' }}
                </p>

               <div class="answers-wrapper row">
                 @foreach($choices->answers as $choice)
                        <div class="station-card col-6 choice-{{$choice->id}} pulse-slow">
                            <div class="button-wrapper">
                                <div class="bubble-animation"></div>
                                <button 
                                    class="custom-btn answer-btn answer-btn-{{$station->id}}"
                                    data-id="{{ $choice->id }}"
                                    data-idc="{{ $choice->id === $station->answer_id ? 'true' : 'false' }}"
                                    @if($user) disabled @endif>
                                    {{ $choice->text }}
                                </button>
                            </div>
                        </div>
                    @endforeach
               </div>
            </div>
            <x-footer/>
        </div>
        <div id="scannerContainer" class="scanner-container d-none mt-4">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center text-dark">Find the QR code & Scan to check in</p>
            <div class="text-center my-3">
                <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary">
                    NEXT
                </a>
            </div>
        </div>
    </div>
    

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const buttons = document.querySelectorAll('.answer-btn');
                const correctSound = new Audio('{{ asset('sounds/correct_sound.mp3') }}');
                const wrongSound = new Audio('{{ asset('sounds/wrong_sound.mp3') }}');

                buttons.forEach(button => {
                    button.addEventListener('click', function() {
                        const isCorrect = this.getAttribute('data-idc') === 'true';
                        const buttonWrapper = this.closest('.button-wrapper');
                        const bubbleAnimation = buttonWrapper.querySelector('.bubble-animation');
                        
                        // Hide button background and start sprite animation
                        
                        // if (bubbleAnimation) {
                        //     bubbleAnimation.classList.add('animate');
                        // }
                        
                        console.log(isCorrect);
                        if (isCorrect) {
                            correctSound.play();
                            this.classList.add('animating');
                            if (bubbleAnimation) {
                                bubbleAnimation.classList.add('animate');
                                this.classList.add('d-none');
                            }
                            // disable all buttons
                            buttons.forEach(btn => btn.disabled = true);
                            // Show modal after animation completes (900ms)
                            setTimeout(() => {
                                const correctModal = new bootstrap.Modal(document.getElementById('answerCorrectModal'));
                                correctModal.show();
                                
                                // Reset animation
                                if (bubbleAnimation) {
                                    bubbleAnimation.classList.remove('animate');
                                }
                                this.classList.remove('animating');
                            }, 900);

                        } else {
                           wrongSound.play();
                           this.disabled = true;
                           this.classList.add('wrong-animating');
                           
                           if (bubbleAnimation) {
                               bubbleAnimation.classList.add('wrong');
                           }

                            setTimeout(() => {
                                if (bubbleAnimation) {
                                    bubbleAnimation.classList.remove('wrong');
                                }
                                this.classList.remove('wrong-animating');
                            }, 1200);
                        }
                    });
                });
            });
        </script>
        <script>
            // Pass data from Blade to JavaScript
            window.stationConfig = {
                urls: {
                    process_qr_code: '{{ route('process_qr_code') }}',
                    congrats: '{{ route('congrats') }}',
                    dashboard: '{{ route('dashboard') }}'
                },
                assets: {
                    check_image: '{{ asset('images/check.png') }}',
                    error_image: '{{ asset('images/error.png') }}'
                },
                station_id: {{ $station->id }},
                station_name: '{{ $station->name }}'
            };


            window.gotoStation = function(id,) {
                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    // Redirect to the generated URL
                    window.location.href = url;
                }

            // Gift selection functionality for station 3
            document.addEventListener('DOMContentLoaded', function() {
                const giftSelect = document.getElementById('giftSelect');
                const redeemBtn = document.getElementById('redeemGiftBtn');
                const startScanner = document.getElementById('start-scanner');
                const giftConfirmModal = new bootstrap.Modal(document.getElementById('giftConfirmModal'));
                const selectedGiftText = document.getElementById('selectedGiftText');
                const confirmYes = document.getElementById('confirmYes');
                const confirmNo = document.getElementById('confirmNo');
                let selectedGiftValue = '';
                let scannedQrMessage = '';

                // Only apply this logic for station 3
                if (giftSelect && window.stationConfig.station_id == 3) {
                    giftSelect.addEventListener('change', function() {
                        if (this.value) {
                            // Store selected gift value
                            selectedGiftValue = this.value;
                            // Enable camera button when gift is selected
                            if (startScanner) {
                                startScanner.disabled = false;
                                startScanner.style.opacity = '1';
                                startScanner.style.cursor = 'pointer';
                            }
                        } else {
                            // Reset when no gift selected
                            selectedGiftValue = '';
                            if (startScanner) {
                                startScanner.disabled = true;
                                startScanner.style.opacity = '0.5';
                                startScanner.style.cursor = 'not-allowed';
                            }
                        }
                    });

                    // Handle YES button - continue with QR processing
                    confirmYes.addEventListener('click', function() {
                        giftConfirmModal.hide();
                        // Continue with the original AJAX call
                        if (scannedQrMessage && selectedGiftValue) {
                            proceedWithQrProcessing(scannedQrMessage, selectedGiftValue);
                        }
                    });

                    // Handle NO button - reset and highlight
                    confirmNo.addEventListener('click', function() {
                        giftConfirmModal.hide();
                        
                        // Close scanner container and show main container
                        const mainContent = document.getElementById('mainContent');
                        const scannerContainer = document.getElementById('scannerContainer');
                        if (scannerContainer) {
                            scannerContainer.classList.add('d-none');
                        }
                        if (mainContent) {
                            mainContent.classList.remove('d-none');
                        }
                        
                        // Reset select to default option
                        giftSelect.value = '';
                        selectedGiftValue = '';
                        
                        // Highlight the select input
                        giftSelect.style.border = '2px solid #dc3545';
                        giftSelect.style.boxShadow = '0 0 5px rgba(220, 53, 69, 0.5)';
                        
                        // Remove highlight after 3 seconds
                        setTimeout(() => {
                            giftSelect.style.border = '';
                            giftSelect.style.boxShadow = '';
                        }, 3000);
                        
                        // Disable camera button
                        if (startScanner) {
                            startScanner.disabled = true;
                            startScanner.style.opacity = '0.5';
                            startScanner.style.cursor = 'not-allowed';
                        }
                        
                        // Reset scanned message
                        scannedQrMessage = '';
                    });

                    // Store the QR message when scanned and show confirmation modal (ONLY FOR STATION 3)
                    window.showGiftConfirmation = function(qrMessage, giftId) {
                        if (window.stationConfig.station_id == 3) {
                            scannedQrMessage = qrMessage;
                            selectedGiftValue = giftId;
                            selectedGiftText.textContent = `Gift ${giftId}`;
                            giftConfirmModal.show();
                        }
                    };

                    // Function to proceed with QR processing
                    window.proceedWithQrProcessing = function(qrMessage, giftId) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        
                        $.ajax({
                            url: window.stationConfig.urls.process_qr_code,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            data: {
                                qrCodeMessage: qrMessage,
                                station: window.stationConfig.station_id,
                                selected_gift_id: giftId
                            },
                            success: function (response) {
                                $('#badge').attr('src', window.stationConfig.assets.check_image);
                                $('#scanCompleteModal').modal('show');

                                const trimmedMessage = qrMessage.trim();
                                const lastCharacter = trimmedMessage.charAt(trimmedMessage.length - 1);

                                $('.station_id').html(lastCharacter);
                                $('.station_name').html(window.stationConfig.station_name);
                                $('#routeBtn').text('Back');

                                if (lastCharacter == 3) {
                                    document.getElementById('routeBtn').setAttribute('href', window.stationConfig.urls.congrats);
                                } else {
                                    document.getElementById('routeBtn').setAttribute('href', window.stationConfig.urls.dashboard);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Error sending QR Code message:', error);
                                $('.modal-icon').addClass('d-none');
                                $('.message').html('INVALID QR CODE');
                                $('.check').attr('src', window.stationConfig.assets.error_image);
                                $('#scanCompleteModal').modal('show');
                            }
                        });
                    };

                    if (redeemBtn) {
                        redeemBtn.addEventListener('click', function() {
                            if (selectedGiftValue) {
                                // Redirect to gift selection page with selected gift
                                const url = "{{ route('station.stamping', ['station' => $station->id]) }}" + "?gift=" + selectedGiftValue;
                                window.location.href = url;
                            }
                        });
                    }
                }
            });        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>
