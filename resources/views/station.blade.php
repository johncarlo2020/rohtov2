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
            $isRedemption = ($station->id == 8 || $station->slug === 'redemption-counter' || !empty($station->is_redemption));
        @endphp
        <div id="stationDetailView" class="w-100 flex-grow-1 d-flex flex-column justify-content-between">
            <div class="d-flex flex-column flex-grow-1">
                {{-- Station Hero Image --}}
                <div class="mt-5 text-center" style="border-radius: 8px; overflow: hidden; border: 1px solid #E0DDD7; background-color: #FAF8F5;">
                    <img src="{{ asset('images/station/ST' . $station->id . '.webp') }}" 
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=600&q=80';"
                         alt="{{ $station->name }}" 
                         style="width: 100%; max-height: 240px; object-fit: cover; display: block;">
                </div>

                {{-- Station Title --}}
                <div class="mm-page-title text-center">
                    {{ strtoupper($station->name) }}
                </div>

                {{-- IF USER HAS NOT CHECKED IN YET (SCREEN 1) --}}
                @if(!$user)
                    <div class="text-center my-2 py-1 px-2 d-flex flex-column justify-content-center">
                        @if($isReplicaCafe || $isRedemption)
                            <p style="font-size: 1rem; color: #555555; margin-bottom: 6px; letter-spacing: 0.5px;">
                                Proceed to
                            </p>
                            <p style="font-size: 1.5rem; font-weight: 700; color: #111111; margin-bottom: 6px; letter-spacing: 1px; text-transform: uppercase;">
                                {{ $station->name }}
                            </p>
                            <p style="font-size: 1rem; color: #555555; margin: 0; letter-spacing: 0.5px;">
                                to begin your journey.
                            </p>
                        @elseif($station->description)
                            <div style="font-size: 1rem; color: #333333; max-width: 340px; margin: 0 auto; text-align: center;">
                                {!! nl2br(e($station->description)) !!}
                            </div>
                        @else
                            <p style="font-size: 1rem; color: #555555; margin-bottom: 6px;">
                                Proceed to
                            </p>
                            <p style="font-size: 1.5rem; font-weight: 700; color: #111111; margin-bottom: 6px; text-transform: uppercase;">
                                {{ $station->name }}
                            </p>
                            <p style="font-size: 1rem; color: #555555; margin: 0;">
                                to begin your journey.
                            </p>
                        @endif
                    </div>

                    {{-- Camera Scan Button (Directly below text with matching top spacing) --}}
                    <div class="text-center mt-4 pt-1">
                        <button type="button" onclick="startQRScanner()" class="mx-auto border-0 d-flex align-items-center justify-content-center" style="width: 76px; height: 38px; background-color: #000000; border-radius: 20px; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#FFFFFF" viewBox="0 0 16 16">
                                <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/>
                            </svg>
                        </button>
                        <div style="font-size: 0.725rem; color: #666666; margin-top: 10px;">
                            Scan the QR code to proceed
                        </div>
                    </div>
                @else
                    {{-- SCREEN 5: MEMORY UNLOCKED CARD (POST CHECK-IN STATE) --}}
                    <div class="my-3 p-4 text-center" style="background-color: #FAF8F5; border: 1px solid #DED8CE; border-radius: 8px;">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 50%; border: 1.5px solid #111; font-size: 1.3rem; font-weight: bold; color: #111;">
                            ✓
                        </div>
                        <div style="font-size: 0.9rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #111; margin-bottom: 0.6rem;">
                            MEMORY UNLOCKED
                        </div>
                        <p style="font-size: 0.775rem; color: #555555; margin: 0; padding: 0 0.5rem;">
                            A new memory has been added to your passport.
                        </p>
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
    <div class="modal fade" id="invalidQrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3" style="max-width: 340px;margin:auto;">
            <div class="modal-content text-center p-4" style="background-color: #FFFFFF; border-radius: 6px; border: 1px solid #111;">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 50%; border: 2px solid #D93838; color: #D93838; font-size: 1.4rem; font-weight: bold;">
                    i
                </div>
                <div style="font-size: 0.9rem; font-weight: 700; letter-spacing: 1px; color: #111; margin-bottom: 1.5rem;">
                    Invalid QR Code
                </div>
                <button type="button" class="mm-btn-black" data-bs-dismiss="modal" onclick="bootstrap.Modal.getInstance(document.getElementById('invalidQrModal'))?.hide();">
                    DONE
                </button>
            </div>
        </div>
    </div>

    {{-- SCREEN 4: CHECK-IN SUCCESSFUL MODAL --}}
    <div class="modal fade" id="successQrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3" style="max-width: 340px;margin:auto">
            <div class="modal-content text-center p-4" style="background-color: #FFFFFF; border-radius: 6px; border: 1px solid #111;">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 50%; border: 2px solid #2E7D32; color: #2E7D32; font-size: 1.4rem; font-weight: bold;">
                    ✓
                </div>
                <div style="font-size: 0.9rem; font-weight: 700; letter-spacing: 1px; color: #111; margin-bottom: 1.5rem;">
                    Check-in Successful
                </div>
                <button id="successDoneBtn" type="button" class="mm-btn-black" onclick="window.location.reload();">
                    DONE
                </button>
            </div>
        </div>
    </div>

    <style>
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

                // Stop camera ONLY on valid check-in success
                if (html5QrCode) {
                    html5QrCode.stop().catch(() => {});
                    html5QrCode = null;
                }

                const modal = new bootstrap.Modal(document.getElementById('successQrModal'));
                modal.show();

                const targetUrl = data.redirect_url || "{{ route('station', ['station' => $station->id]) }}";

                const doneBtn = document.getElementById('successDoneBtn');
                if (doneBtn) {
                    doneBtn.onclick = function() {
                        window.location.href = targetUrl;
                    };
                }
            })
            .catch(err => {
                showInvalidModal();
            });
        }

        function showInvalidModal() {
            const modalEl = document.getElementById('invalidQrModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

            const onModalHidden = function () {
                modalEl.removeEventListener('hidden.bs.modal', onModalHidden);
                resumeScanning();
            };
            modalEl.addEventListener('hidden.bs.modal', onModalHidden);

            modal.show();
        }
    </script>
    @endpush
</x-guest-layout>