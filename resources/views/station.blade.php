<x-app-layout>
    <style>
        .answer-btn {
            background-image: url(http://localhost/sekkisei/rohtov2/public/images/brand/Bubble.webp);
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-color: transparent;
            box-shadow: none;
            width:40vw;
            height:40vw;
            border-radius:50%;
        }

        .station-card.col-6.choice-6 {
            margin-top: 50vw;
        }
    </style>
    <div id="stationPage" class="station-page main-content main-background with-scroll">
        <div class="modal fade custom-modal" id="answerCorrectModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="check mx-auto mb-4" id="badge" src="">
                            <div class="text-content mt-0">
                                <p class="sub-heading fw-bold mb-2 station-text "><span class="station_name text-dark"></span></p>
                                <p class="mb-4 message text-grey">
                                    Excellent
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
        <div class="d-flex justify-content-center animate-entry">
            @include('components.branding')
        </div>
        <div id="mainContent"
            class="mt-1 mb-2 d-flex flex-column align-items-center justify-content-center animate-entry delay-3">
                <p class="sub-heading mb-1 text-white mb-5 mt-4 text-center">
                    {{ isset($station->question) ? $station->question : '' }}
                </p>

               <div class="answers-wrapper row">
                 @foreach($choices->answers as $choice)
                        <div class="station-card col-6 choice-{{$choice->id}}">
                                <button 
                                    class="custom-btn answer-btn"
                                    data-id="{{ $choice->id }}"
                                    data-idc="{{ $choice->id === $station->answer_id ? 'true' : 'false' }}"
                                    @if($user) disabled @endif
                                    style="background-image: url('{{ asset('images/brand/Bubble.webp') }}');">
                                    {{ $choice->text }}
                                </button>
                            </div>
                    @endforeach
               </div>
            </div>
            <!-- <img class="mt-5 station-image w-75" src="{{ asset('images/station/ST') }}"
                alt="Station Image"> -->
            <!-- @if ($user)
                <div class="scanner-button">
                    <p class="my-0 mt-5 text-center mb-3 text-dark">Checked in</p>
                    <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary">
                        Back
                    </a>
                </div>
            @elseif ($station->id != 3)
                <button id="start-scanner"
                    class="camera-btn mx-auto mt-5 mb-3"
                    title="Start Scanner">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <p class="px-4 mt-3 bottom-text scanner-text text-center text-dark">Scan the QR code to check in</p>
                <div class="text-center my-3">
                    <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary">
                        BACK
                    </a>
                </div>
            @elseif ($station->id == 3)
                <div class="gift-selection mt-2">
                    <label for="giftSelect" class="form-label text-dark fw-bold">Select your gift:</label>
                    <select id="giftSelect" class="form-select " style="width: 50vw; margin: 0 auto;" required>
                        <option value="">Select a gift</option>
                        @foreach($gifts as $gift)
                            <option value="{{ $gift->id }}" {{ !$gift->enabled ? 'disabled' : '' }}>
                                Gift {{ $gift->id }}{{ !$gift->enabled ? ' (Not Available)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button id="start-scanner" class="camera-btn mx-auto mt-5 mb-3" title="Start Scanner" disabled style="opacity: 0.5; cursor: not-allowed;">
                    <i class="fa-solid fa-camera"></i>
                </button>
                <p class="px-4 mt-3 bottom-text scanner-text text-center text-dark">Scan the QR code to check in</p>
                <div class="text-center my-3">
                    <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary">
                        BACK
                    </a>
                </div>
            @endif -->
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

                buttons.forEach(button => {
                    button.addEventListener('click', function() {
                        const isCorrect = this.getAttribute('data-idc') === 'true';
                        console.log(isCorrect);
                        if (isCorrect) {
                            this.style.backgroundImage = "url('{{ asset('images/brand/Bubble_right.webp') }}')";
                            this.classList.add('btn-success');
                            
                            // disable all buttons
                            buttons.forEach(btn => btn.disabled = true);

                            const correctModal = new bootstrap.Modal(document.getElementById('answerCorrectModal'));
                            correctModal.show();
                        } else {
                            // this.classList.remove('btn-outline-primary');
                            // this.classList.add('btn-secondary');
                            this.style.backgroundImage = "url('{{ asset('images/brand/Bubble_wrong.webp') }}')";
                            this.disabled = true;
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
