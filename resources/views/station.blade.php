<x-guest-layout>
    <div class="mm-mobile-container justify-content-between p-0" style="max-width: 440px; margin: 0 auto; background-color: #F4F0EA; min-height: 100vh;">
        
        {{-- TOP HEADER BAR WITH BACK ARROW --}}
        <div class="w-100 px-3 pt-3 pb-1 d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="btn p-0 border-0" style="font-size: 1.3rem; font-weight: 700; color: #111; text-decoration: none;">
                ‹
            </a>
            <div class="flex-grow-1 text-center pe-4">
                @include('components.branding')
            </div>
        </div>

        {{-- SCREEN 1 & 5: PRE-SCAN OR MEMORY UNLOCKED VIEW --}}
        <div id="stationDetailView" class="w-100 px-3 pb-4 flex-grow-1 d-flex flex-column justify-content-between">
            <div>
                {{-- Station Hero Image --}}
                <div class="my-3 text-center" style="border-radius: 6px; overflow: hidden; border: 1px solid #E0DDD7; background-color: #FAF8F5;">
                    <img src="{{ asset('images/station/ST' . $station->id . '.webp') }}" 
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=600&q=80';"
                         alt="{{ $station->name }}" 
                         style="width: 100%; max-height: 240px; object-fit: cover; display: block;">
                </div>

                {{-- Station Title --}}
                <div class="mm-page-title mt-3 mb-3" style="font-size: 1.15rem; letter-spacing: 2px;">
                    {{ strtoupper($station->name) }}
                </div>

                {{-- IF USER HAS NOT CHECKED IN YET (SCREEN 1) --}}
                @if(!$user)
                    <div class="text-center my-4 px-2">
                        <p style="font-family: 'Courier Prime', monospace; font-size: 0.85rem; color: #555555; margin-bottom: 4px;">
                            Proceed to
                        </p>
                        <p style="font-family: 'Courier Prime', monospace; font-size: 0.95rem; font-weight: 700; color: #111111; margin-bottom: 4px; text-transform: uppercase;">
                            {{ $station->name }}
                        </p>
                        <p style="font-family: 'Courier Prime', monospace; font-size: 0.85rem; color: #555555;">
                            to begin your journey.
                        </p>
                    </div>

                    {{-- Camera Scan Button --}}
                    <div class="text-center mt-4">
                        <button type="button" onclick="startQRScanner()" class="mx-auto border-0 d-flex align-items-center justify-content-center" style="width: 72px; height: 38px; background-color: #000000; border-radius: 20px; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#FFFFFF" viewBox="0 0 16 16">
                                <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/>
                            </svg>
                        </button>
                        <div style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; color: #666666; margin-top: 10px;">
                            Scan the QR code to proceed
                        </div>
                    </div>
                @else
                    {{-- SCREEN 5: MEMORY UNLOCKED CARD (POST CHECK-IN STATE) --}}
                    <div class="my-4 p-4 text-center" style="background-color: #FAF8F5; border: 1px solid #DED8CE; border-radius: 8px;">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 50%; border: 1.5px solid #111; font-family: 'Courier Prime', monospace; font-size: 1.3rem; font-weight: bold; color: #111;">
                            ✓
                        </div>
                        <div style="font-family: 'Courier Prime', monospace; font-size: 0.9rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #111; margin-bottom: 0.6rem;">
                            MEMORY UNLOCKED
                        </div>
                        <p style="font-family: 'Courier Prime', monospace; font-size: 0.775rem; line-height: 1.55; color: #555555; margin: 0; padding: 0 0.5rem;">
                            A new memory has been added to your passport.
                        </p>
                    </div>

                    <div class="text-center my-3" style="font-family: 'Courier Prime', monospace; font-size: 0.725rem; color: #666666;">
                        Checked-In Successful
                    </div>

                    <div class="mt-4 px-2">
                        <a href="{{ route('dashboard') }}" class="mm-btn-black">
                            BACK
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- SCREEN 2: ACTIVE QR SCANNER VIEW --}}
        <div id="scannerSection" class="w-100 px-3 pb-4 flex-grow-1 d-flex flex-column justify-content-between d-none">
            <div>
                <div class="text-center my-2" style="font-family: 'Courier Prime', monospace; font-size: 0.85rem; letter-spacing: 1px; font-weight: 700; color: #111;">
                    SCANNING AREA
                </div>

                {{-- Scanner Frame --}}
                <div class="mx-auto my-3" style="width: 280px; height: 280px; background-color: #FFFFFF; border: 1px solid #DED8CE; border-radius: 12px; overflow: hidden; position: relative;">
                    <div id="reader" style="width: 100%; height: 100%;"></div>
                </div>

                <div class="text-center mt-3" style="font-family: 'Courier Prime', monospace; font-size: 0.75rem; color: #555555;">
                    Find the QR code & scan to proceed
                </div>
            </div>

            <div class="mt-4 px-2">
                <button type="button" onclick="cancelQRScanner()" class="mm-btn-black" style="background-color: #444444;">
                    CANCEL
                </button>
            </div>
        </div>

    </div>

    {{-- SCREEN 3: INVALID QR CODE ERROR MODAL --}}
    <div class="modal fade" id="invalidQrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3" style="max-width: 340px;">
            <div class="modal-content text-center p-4" style="background-color: #FFFFFF; border-radius: 6px; border: 1px solid #111;">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 50%; border: 2px solid #D93838; color: #D93838; font-family: 'Courier Prime', monospace; font-size: 1.4rem; font-weight: bold;">
                    i
                </div>
                <div style="font-family: 'Courier Prime', monospace; font-size: 0.9rem; font-weight: 700; letter-spacing: 1px; color: #111; margin-bottom: 1.5rem;">
                    Invalid QR Code
                </div>
                <button type="button" class="mm-btn-black py-2" data-bs-dismiss="modal">
                    DONE
                </button>
            </div>
        </div>
    </div>

    {{-- SCREEN 4: CHECK-IN SUCCESSFUL MODAL --}}
    <div class="modal fade" id="successQrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3" style="max-width: 340px;">
            <div class="modal-content text-center p-4" style="background-color: #FFFFFF; border-radius: 6px; border: 1px solid #111;">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 50%; border: 2px solid #2E7D32; color: #2E7D32; font-family: 'Courier Prime', monospace; font-size: 1.4rem; font-weight: bold;">
                    ✓
                </div>
                <div style="font-family: 'Courier Prime', monospace; font-size: 0.9rem; font-weight: 700; letter-spacing: 1px; color: #111; margin-bottom: 1.5rem;">
                    Check-in Successful
                </div>
                <button type="button" class="mm-btn-black py-2" onclick="window.location.reload();">
                    DONE
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrCode = null;

        function startQRScanner() {
            document.getElementById('stationDetailView').classList.add('d-none');
            document.getElementById('scannerSection').classList.remove('d-none');

            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 220 },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                // If camera unavailable in desktop test environment, simulate QR scan on tap
                console.log("Camera start error, using fallback reader", err);
                document.getElementById('reader').innerHTML = `
                    <div onclick="simulateScanSuccess()" class="d-flex flex-column align-items-center justify-content-center h-100" style="cursor: pointer; padding: 1rem; background-color: #FAF8F5;">
                        <span style="font-family: 'Courier Prime', monospace; font-size: 0.8rem; color: #111; text-align: center;">[ Camera Viewfinder ]<br><br><small style="color: #666;">Tap here to simulate scanning station QR code</small></span>
                    </div>
                `;
            });
        }

        function cancelQRScanner() {
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
            }
            document.getElementById('scannerSection').classList.add('d-none');
            document.getElementById('stationDetailView').classList.remove('d-none');
        }

        function onScanSuccess(decodedText, decodedResult) {
            processQrCheckin(decodedText);
        }

        function onScanFailure(error) {
            // Ignore scan attempt failures until valid code detected
        }

        function simulateScanSuccess() {
            processQrCheckin("STATION_{{ $station->id }}");
        }

        function processQrCheckin(qrMessage) {
            if (html5QrCode) {
                html5QrCode.stop().catch(() => {});
            }

            fetch("{{ route('process_qr_code') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    station: {{ $station->id }},
                    qrCodeMessage: qrMessage
                })
            })
            .then(res => {
                if (!res.ok) throw new Error("Invalid");
                return res.json();
            })
            .then(data => {
                const modal = new bootstrap.Modal(document.getElementById('successQrModal'));
                modal.show();
            })
            .catch(err => {
                const modal = new bootstrap.Modal(document.getElementById('invalidQrModal'));
                modal.show();
            });
        }
    </script>
    @endpush
</x-guest-layout>