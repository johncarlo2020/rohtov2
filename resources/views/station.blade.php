<x-guest-layout>
    <div class="mm-mobile-container">
        
        {{-- TOP HEADER BAR WITH BACK ARROW --}}
        <div class="w-100 pb-2 d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="btn p-0 border-0" style="font-size: 1.3rem; font-weight: 700; color: #111; text-decoration: none;">
                ‹
            </a>
            <div class="flex-grow-1 text-center pe-4">
                @include('components.branding')
            </div>
        </div>

        {{-- SCREEN 1 & 5: PRE-SCAN OR MEMORY UNLOCKED VIEW --}}
        @php
            $isReplicaCafe = ($station->id == 1 || $station->slug === 'replica-cafe' || strtoupper($station->name) === 'REPLICA CAFE');
            $isRedemption = ($station->slug === 'redemption-counter' || $station->slug === 'complimentary-gift' || !empty($station->is_redemption));
        @endphp
        <div id="stationDetailView" class="w-100 flex-grow-1 d-flex flex-column justify-content-between">
            <div class="d-flex flex-column flex-grow-1">
                {{-- Station Hero Image --}}
                <div class="mt-5 text-center station-hero-card">
                    <img src="{{ asset('images/station/ST' . $station->id . '.webp') }}" 
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=600&q=80';"
                         alt="{{ $station->name }}">
                </div>

                {{-- Station Title --}}
                <div class="mm-page-title station-title text-center">
                    {{ strtoupper($station->name) }}
                </div>

                {{-- IF USER HAS NOT CHECKED IN YET (SCREEN 1) --}}
                @if(!$user)
                    <div class="text-center my-2 py-1 px-2 d-flex flex-column justify-content-center">
                        @if($isReplicaCafe)
                            <div class="station-desc">
                                <p class="mb-1 text-muted">Proceed to</p>
                                <p class="h4 font-weight-bold text-dark mb-1 text-uppercase">{{ $station->name }}</p>
                                <p class="mb-0 text-muted">to begin your journey.</p>
                            </div>
                        @elseif($station->description)
                            <div class="station-desc">
                                {!! nl2br(e($station->description)) !!}
                            </div>
                            @if($station->id == 7 || $station->slug === 'scentsorium' || strtoupper($station->name) === 'SCENTSORIUM')
                                <div class="mt-3 text-center">
                                    <img src="{{ asset('images/station/7ST.webp') }}" 
                                         alt="{{ $station->name }}" 
                                         class="station-collection-img">
                                </div>
                            @endif
                        @else
                            <div class="station-desc">
                                <p class="mb-1 text-muted">Proceed to</p>
                                <p class="h4 font-weight-bold text-dark mb-1 text-uppercase">{{ $station->name }}</p>
                                <p class="mb-0 text-muted">to begin your journey.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Camera Scan Button (Directly below text with matching top spacing) --}}
                    <div class="text-center mt-4 pt-1">
                        <button type="button" onclick="startQRScanner()" class="station-camera-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#FFFFFF" viewBox="0 0 16 16">
                                <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/>
                            </svg>
                        </button>
                        <div class="station-scan-hint">
                            Scan the QR code to proceed
                        </div>
                    </div>
                @else
                    {{-- SCREEN 5: MEMORY UNLOCKED CARD (POST CHECK-IN STATE) --}}
                    <div class="station-unlocked-card">
                        <div class="station-unlocked-check">✓</div>
                        <div class="station-unlocked-title">MEMORY UNLOCKED</div>
                        <p class="station-unlocked-sub">A new memory has been added to your passport.</p>
                    </div>

                    {{-- Back to Dashboard Button --}}
                    <div class="w-100 mt-4 text-center">
                        <a href="{{ route('dashboard') }}" class="mm-btn-black mx-auto" style="width: 60%;">
                            BACK
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- SCREEN 2: ACTIVE QR SCANNER VIEW --}}
        <div id="scannerSection" class="w-100 flex-grow-1 d-flex flex-column justify-content-between align-items-center d-none" style="min-height: calc(100vh - 200px);">
            <div class="w-100 d-flex flex-column align-items-center justify-content-center my-auto py-3">
                <!-- <div class="text-center mb-3" style="font-family: 'Courier Prime', monospace; font-size: 1rem; letter-spacing: 2px; font-weight: 700; color: #111; text-transform: uppercase;">
                    SCANNING AREA
                </div> -->

                {{-- Scanner Frame: Flat square, No Border, No Radius --}}
                <div class="position-relative mx-auto my-2" style="width: 100%; max-width: 350px; aspect-ratio: 1 / 1; height: auto; min-height: 320px; background-color: #000000; border-radius: 0; overflow: hidden; border: none;">
                    <div id="reader" style="width: 100%; height: 100%;"></div>
                </div>

                <div class="text-center mt-3" style="font-family: 'Courier New', monospace; font-size: 1rem; color: #555555; letter-spacing: 0.5px;">
                    Find the QR code & scan to proceed
                </div>
            </div>

            <div class="w-100 mt-auto">
                <button type="button" onclick="cancelQRScanner()" class="mm-btn-black" style="background-color: #111111; border-radius: 8px;">
                    CANCEL
                </button>
            </div>
        </div>

    </div>

    {{-- SCREEN 3: INVALID QR CODE ERROR MODAL --}}
    <div id="invalidQrModal" class="qr-modal-overlay d-none" onclick="if(event.target === this) closeInvalidModal()">
        <div class="qr-modal-card">
            <div class="qr-modal-icon error">
                i
            </div>
            <div class="qr-modal-title">
                Invalid QR Code
            </div>
            <button type="button" class="mm-btn-black" onclick="closeInvalidModal()">
                DONE
            </button>
        </div>
    </div>

    {{-- SCREEN 4: CHECK-IN SUCCESSFUL MODAL --}}
    <div id="successQrModal" class="qr-modal-overlay d-none">
        <div class="qr-modal-card">
            <div class="qr-modal-icon success">
                ✓
            </div>
            <div class="qr-modal-title">
                Check-in Successful
            </div>
            <button id="successDoneBtn" type="button" class="mm-btn-black">
                DONE
            </button>
        </div>
    </div>

    <style>
        .qr-modal-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            background: rgba(0, 0, 0, 0.6) !important;
            -webkit-backdrop-filter: blur(3px);
            backdrop-filter: blur(3px);
            z-index: 999999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 1.25rem !important;
            box-sizing: border-box !important;
            touch-action: manipulation;
        }
        .qr-modal-overlay.d-none {
            display: none !important;
        }
        .qr-modal-card {
            background-color: #FFFFFF !important;
            border-radius: 6px !important;
            border: 1px solid #111111 !important;
            max-width: 340px !important;
            width: 100% !important;
            padding: 1.75rem 1.5rem !important;
            text-align: center !important;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.35) !important;
            position: relative !important;
            z-index: 1000000 !important;
            box-sizing: border-box !important;
            animation: qrModalPop 0.2s ease-out;
        }
        @keyframes qrModalPop {
            from {
                opacity: 0;
                transform: scale(0.92);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .qr-modal-icon {
            margin: 0 auto 0.85rem auto !important;
            width: 44px !important;
            height: 44px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 1.4rem !important;
            font-weight: bold !important;
        }
        .qr-modal-icon.success {
            border: 2px solid #2E7D32 !important;
            color: #2E7D32 !important;
        }
        .qr-modal-icon.error {
            border: 2px solid #D93838 !important;
            color: #D93838 !important;
        }
        .qr-modal-title {
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            letter-spacing: 1px !important;
            color: #111111 !important;
            margin-bottom: 1.5rem !important;
        }
        #reader {
            width: 100% !important;
            height: 100% !important;
            border: none !important;
            padding: 0 !important;
            position: relative;
            background-color: #000000;
            overflow: hidden;
            border-radius: 0 !important;
        }
        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 0 !important;
            display: block;
        }
        #reader__scan_region {
            width: 100% !important;
            height: 100% !important;
            min-height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        #reader__scan_region video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
        #reader__dashboard_section,
        #reader__dashboard_section_csr,
        #reader__header_message,
        #reader img {
            display: none !important;
        }
    </style>

    @push('scripts')
    <script>
        let html5QrCode = null;
        let isProcessingQr = false;

        function startQRScanner() {
            document.getElementById('stationDetailView').classList.add('d-none');
            document.getElementById('scannerSection').classList.remove('d-none');
            isProcessingQr = false;

            if (html5QrCode) {
                html5QrCode.stop().catch(() => {}).then(() => {
                    initScannerInstance();
                });
            } else {
                initScannerInstance();
            }
        }

        function initScannerInstance() {
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { 
                    fps: 15,
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        const edge = Math.min(viewfinderWidth, viewfinderHeight);
                        const boxSize = Math.floor(edge * 0.8);
                        return { width: boxSize, height: boxSize };
                    },
                    aspectRatio: 1.0
                },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.log("Camera start error, using fallback reader", err);
                document.getElementById('reader').innerHTML = `
                    <div onclick="simulateScanSuccess()" class="d-flex flex-column align-items-center justify-content-center h-100" style="cursor: pointer; padding: 1rem; background-color: #FAF8F5;">
                        <span style="font-family: 'Courier Prime', monospace; font-size: 0.8rem; color: #111; text-align: center;">[ Camera Viewfinder ]<br><br><small style="color: #666;">Tap here to simulate scanning station QR code</small></span>
                    </div>
                `;
            });
        }

        function cancelQRScanner() {
            isProcessingQr = false;
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
                html5QrCode = null;
            }
            document.getElementById('scannerSection').classList.add('d-none');
            document.getElementById('stationDetailView').classList.remove('d-none');
        }

        function resumeScanning() {
            // Re-enable scanning after closing invalid modal
            setTimeout(() => {
                isProcessingQr = false;
            }, 300);
        }

        function extractStationId(qrText) {
            if (!qrText) return null;
            const str = String(qrText).trim();
            if (/^\d+$/.test(str)) return parseInt(str, 10);
            const match = str.match(/(?:station[_\-\/]|st[_\-\/]?)(\d+)/i);
            if (match) return parseInt(match[1], 10);
            return null;
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessingQr) return;
            processQrCheckin(decodedText);
        }

        function onScanFailure(error) {
            // Ignore scan attempt failures until valid code detected
        }

        function simulateScanSuccess() {
            if (isProcessingQr) return;
            processQrCheckin("STATION_{{ $station->id }}");
        }

        function processQrCheckin(qrMessage) {
            if (isProcessingQr) return;
            isProcessingQr = true;

            const currentStationId = {{ $station->id }};
            const parsedScannedId = extractStationId(qrMessage);

            // If scanned QR code has an ID that does not match this station, show Invalid Modal immediately
            if (parsedScannedId !== null && parsedScannedId !== currentStationId) {
                showInvalidModal();
                return;
            }

            fetch("{{ route('process_qr_code') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    station: currentStationId,
                    qrCodeMessage: qrMessage
                })
            })
            .then(res => {
                if (!res.ok) throw new Error("Invalid");
                return res.json();
            })
            .then(data => {
                if (!data.success) {
                    throw new Error("Invalid");
                }

                const targetUrl = data.redirect_url || "{{ route('station', ['station' => $station->id]) }}";
                showSuccessModal(targetUrl);
            })
            .catch(err => {
                showInvalidModal();
            });
        }

        function showSuccessModal(targetUrl) {
            // Stop camera and hide scanner viewfinder
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
                html5QrCode = null;
            }
            document.getElementById('scannerSection').classList.add('d-none');
            document.getElementById('stationDetailView').classList.remove('d-none');

            teleportModalsToBody();

            const overlay = document.getElementById('successQrModal');
            if (overlay) {
                overlay.classList.remove('d-none');
            }

            const doneBtn = document.getElementById('successDoneBtn');
            if (doneBtn) {
                doneBtn.onclick = function() {
                    if (overlay) overlay.classList.add('d-none');
                    window.location.href = targetUrl;
                };
            }
        }

        function showInvalidModal() {
            teleportModalsToBody();
            const overlay = document.getElementById('invalidQrModal');
            if (overlay) {
                overlay.classList.remove('d-none');
            }
        }

        function closeInvalidModal() {
            const overlay = document.getElementById('invalidQrModal');
            if (overlay) {
                overlay.classList.add('d-none');
            }
            resumeScanning();
        }

        function teleportModalsToBody() {
            const s = document.getElementById('successQrModal');
            const i = document.getElementById('invalidQrModal');
            if (s && s.parentElement !== document.body) {
                document.body.appendChild(s);
            }
            if (i && i.parentElement !== document.body) {
                document.body.appendChild(i);
            }
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
        }

        document.addEventListener('DOMContentLoaded', teleportModalsToBody);
        window.addEventListener('pagehide', function() {
            const s = document.getElementById('successQrModal');
            const i = document.getElementById('invalidQrModal');
            if (s) s.classList.add('d-none');
            if (i) i.classList.add('d-none');
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
        });
        teleportModalsToBody();
    </script>
    @endpush
</x-guest-layout>