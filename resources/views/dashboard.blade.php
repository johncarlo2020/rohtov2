<x-guest-layout>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .brand-orange-text { color: #e86034 !important; }
        .brand-orange-bg { background-color: #e86034 !important; color: #ffffff !important; border: none; }
        .brand-orange-bg:hover, .brand-orange-bg:focus { background-color: #d44f25 !important; color: #ffffff !important; }

        .ticket-box {
            border: 1px solid #cbd5e1;
            padding: 1.25rem 1rem;
            background: #ffffff;
        }

        .cursor-pointer { cursor: pointer; }
    </style>

    <div class="register-main with-scroll row">
        <!-- Desktop Left Branding Image -->
        <div class="col-lg-8 desktop-image-main">
            <img src="{{ asset('images/brand/main_img.webp') }}" alt="Login Image" srcset="">
        </div>

        <!-- Right Content Parent -->
        <div class="flex-parent col-lg-4 d-flex flex-column justify-content-between">
            <div class="top">
                <div class="d-flex justify-content-center col-12">
                    @include('components.branding')
                </div>
            </div>

            <!-- Main Content Container -->
            <div class="mid">
                <div class="px-2 w-100 m-auto">
                    <main>
                        <!-- BOOKING CONFIRMED SUCCESS DISPLAY -->
                        <div id="confirmation-success-screen" class="text-center py-2 step-fade">
                            
                            <!-- Title -->
                            <h2 class="h4 fw-semi-bold brand-orange-text text-uppercase mb-3" style="letter-spacing: 0.05em;">
                                BOOKING CONFIRMED!
                            </h2>

                            @php
                                $firstName = 'CUSTOMER';
                                $fullName = 'CUSTOMER';
                                $formattedDateStr = '';
                                $formattedTimeStr = '';
                                $refNo = null;
                                $canModify = true;
                                $resCount = 0;

                                if (isset($userBooking) && $userBooking) {
                                    $refNo = $userBooking->reference_no;
                                    $resCount = (int) $userBooking->reschedule_count;
                                    $slotDateStr = $userBooking->bookingDate ? \Carbon\Carbon::parse($userBooking->bookingDate->date)->format('Y-m-d') : null;
                                    $slotTimeStr = $userBooking->bookingSlot ? $userBooking->bookingSlot->start_time : '00:00:00';
                                    
                                    if ($resCount >= 1 || !$slotDateStr) {
                                        $canModify = false;
                                    } else {
                                        $slotDateTime = \Carbon\Carbon::parse($slotDateStr . ' ' . $slotTimeStr);
                                        $canModify = now()->lessThan($slotDateTime->copy()->subDays(7));
                                    }

                                    if ($userBooking->customer_name) {
                                        $fullName = strtoupper($userBooking->customer_name);
                                        $firstName = strtoupper(explode(' ', trim($userBooking->customer_name))[0]);
                                    }

                                    if ($userBooking->bookingDate) {
                                        $d = \Carbon\Carbon::parse($userBooking->bookingDate->date);
                                        $dayNum = $d->day;
                                        $sfx = 'TH';
                                        if (!in_array($dayNum, [11, 12, 13])) {
                                            switch ($dayNum % 10) {
                                                case 1: $sfx = 'ST'; break;
                                                case 2: $sfx = 'ND'; break;
                                                case 3: $sfx = 'RD'; break;
                                            }
                                        }
                                        $formattedDateStr = strtoupper($d->format('l')) . ', ' . $dayNum . $sfx . ' ' . strtoupper($d->format('F'));
                                    }

                                    if ($userBooking->bookingSlot) {
                                        $start = \Carbon\Carbon::parse($userBooking->bookingSlot->start_time)->format('g:iA');
                                        $end = \Carbon\Carbon::parse($userBooking->bookingSlot->end_time)->format('g:iA');
                                        $formattedTimeStr = strtoupper($start . ' - ' . $end);
                                    }
                                } elseif (auth()->check()) {
                                    $fullName = strtoupper(trim((auth()->user()->fname ?? '') . ' ' . (auth()->user()->lname ?? '')));
                                    if (empty($fullName)) {
                                        $fullName = strtoupper(auth()->user()->name ?? 'CUSTOMER');
                                    }
                                    $firstName = strtoupper(auth()->user()->fname ?? explode(' ', $fullName)[0]);
                                }
                            @endphp

                            <!-- Subtitle 1 -->
                            <p class="small fw-semi-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.03em; font-size: 0.85rem;">
                                HI <span id="confirmed-greeting-name" class="text-dark">{{ $firstName }}</span>, YOUR RESERVATION IS CONFIRMED
                            </p>

                            <!-- Subtitle 2 -->
                            <p class="small text-dark text-uppercase mb-4 mx-auto" style="letter-spacing: 0.02em; font-size: 0.8rem; line-height: 1.45; max-width: 320px;">
                                PLEASE CHECK YOUR EMAIL FOR YOUR CONFIRMATION DETAILS AND PRESENT THIS QR CODE UPON ARRIVAL.
                            </p>

                            <!-- Ticket Container (Boxed QR Code + Details) -->
                            <div id="ticket-container" class="ticket-box d-inline-block w-100 mb-4 text-center" style="max-width: 290px; border: 2px dashed red; padding: 1.25rem 1rem; background: #ffffff;">
                                
                                <!-- Dynamic QR Code Image -->
                                <img id="qr-code-img" src="{{ $refNo ? 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($refNo) : '' }}" alt="Booking QR Code" class="img-fluid mb-2" style="width: 170px; height: 170px; margin: auto; object-fit: contain; display: block;" crossorigin="anonymous">

                                <!-- Customer Name -->
                                <div id="confirmed-ticket-name" class="fw-bold text-dark text-uppercase my-2" style="font-size: 0.95rem; letter-spacing: 0.05em;">
                                    {{ $fullName }}
                                </div>

                                <!-- Details List -->
                                <div class="small fw-bold text-dark text-uppercase" style="font-size: 0.725rem; line-height: 1.5; letter-spacing: 0.03em;">
                                    <div class="mb-1"><span class="fw-bold text-dark">DATE:</span> <span id="confirmed-ticket-date" class="text-dark">{{ $formattedDateStr }}</span></div>
                                    <div class="mb-1"><span class="fw-bold text-dark">TIME:</span> <span id="confirmed-ticket-time" class="text-dark">{{ $formattedTimeStr }}</span></div>
                                    <div class="text-dark">
                                        <span class="fw-bold text-dark">VENUE:</span> LONGCHAMP POP UP STORE<br>THE GARDENS MALL
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons (MODIFY / DOWNLOAD) -->
                            <div class="d-flex flex-column gap-2 mx-auto mb-4">
                                @if($canModify)
                                    <a id="modify-btn" href="{{ url('/reservation-create?modify=1') }}" class="custom-btn custom-btn-primary w-50 m-auto text-decoration-none d-block text-center" style="border-radius: 0; padding: 0.7rem 1rem; font-weight: bold; letter-spacing: 0.05em;">
                                        MODIFY
                                    </a>
                                @else
                                    <div id="no-modify-notice" class="small fw-bold text-muted text-uppercase my-1 text-center" style="font-size: 0.7rem;">
                                        @if(isset($resCount) && $resCount >= 1)
                                            * YOU HAVE ALREADY RESCHEDULED YOUR BOOKING ONCE.
                                        @else
                                            * RESCHEDULING NOT AVAILABLE (NO DATES AVAILABLE AT LEAST 1 WEEK PRIOR TO YOUR SLOT)
                                        @endif
                                    </div>
                                @endif
                                <button id="download-btn" type="button" class="custom-btn custom-btn-primary w-50 m-auto" style="border-radius: 0; padding: 0.7rem 1rem; font-weight: bold; letter-spacing: 0.05em;">
                                    DOWNLOAD
                                </button>
                            </div>

                        </div>
                    </main>
                </div>
            </div>

            <!-- Bottom Brand Logo -->
            <div class="col-12 bot">
                <div class="logo-bot d-flex justify-content-center mt-4">
                    <img src="{{ asset('images/brand/bot_logo.webp') }}" class="img-fluid w-25" alt="Login Image" srcset="">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modifyBtn = document.getElementById('modify-btn');
            const downloadBtn = document.getElementById('download-btn');

            if (modifyBtn) {
                modifyBtn.addEventListener('click', () => {
                    const refNo = @json($refNo);
                    if (refNo) {
                        localStorage.setItem('latest_booking_ref', refNo);
                    }
                });
            }

            if (downloadBtn) {
                downloadBtn.addEventListener('click', () => {
                    const refNo = @json($refNo) || localStorage.getItem('latest_booking_ref') || 'ticket';
                    
                    const customerName = document.getElementById('confirmed-ticket-name')?.textContent.trim() || 'CUSTOMER';
                    const dateText = document.getElementById('confirmed-ticket-date')?.textContent.trim() || '';
                    const timeText = document.getElementById('confirmed-ticket-time')?.textContent.trim() || '';
                    const qrImgElem = document.getElementById('qr-code-img');

                    downloadBtn.disabled = true;
                    downloadBtn.textContent = 'GENERATING JPEG...';

                    // Create offscreen canvas (450x620)
                    const canvas = document.createElement('canvas');
                    canvas.width = 450;
                    canvas.height = 620;
                    const ctx = canvas.getContext('2d');

                    // Fill white background
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, 450, 620);

                    const renderCanvasContent = (loadedLogoImage, loadedQrImage) => {
                        let yCursor = 35;

                        // 1. Logo at Top (Centered)
                        if (loadedLogoImage) {
                            const logoWidth = 200;
                            const aspect = loadedLogoImage.height / loadedLogoImage.width;
                            const logoHeight = logoWidth * aspect;
                            const logoX = (450 - logoWidth) / 2;
                            ctx.drawImage(loadedLogoImage, logoX, yCursor, logoWidth, logoHeight);
                            yCursor += logoHeight + 35;
                        } else {
                            yCursor += 120;
                        }

                        // 2. QR Code (Centered 210x210)
                        if (loadedQrImage) {
                            const qrSize = 210;
                            const qrX = (450 - qrSize) / 2;
                            ctx.drawImage(loadedQrImage, qrX, yCursor, qrSize, qrSize);
                            yCursor += qrSize + 35;
                        } else {
                            yCursor += 230;
                        }

                        // 3. Customer Name (Bold, Centered)
                        ctx.fillStyle = '#000000';
                        ctx.font = 'bold 22px "Helvetica Neue", Helvetica, Arial, sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText(customerName.toUpperCase(), 225, yCursor);
                        yCursor += 40;

                        // 4. DATE Line (Centered)
                        ctx.font = 'bold 14px "Helvetica Neue", Helvetica, Arial, sans-serif';
                        ctx.fillStyle = '#000000';
                        ctx.fillText(`DATE: ${dateText.toUpperCase()}`, 225, yCursor);
                        yCursor += 25;

                        // 5. TIME Line (Centered)
                        ctx.fillText(`TIME: ${timeText.toUpperCase()}`, 225, yCursor);
                        yCursor += 25;

                        // 6. VENUE Lines (Centered)
                        ctx.fillText('VENUE: LONGCHAMP POP UP STORE', 225, yCursor);
                        yCursor += 22;
                        ctx.fillText('THE GARDENS MALL', 225, yCursor);

                        // Convert Canvas to Data URL & Trigger Download
                        const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
                        const link = document.createElement('a');
                        link.download = `Reservation_${refNo}.jpg`;
                        link.href = dataUrl;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        downloadBtn.disabled = false;
                        downloadBtn.textContent = 'DOWNLOAD';
                    };

                    let logoLoaded = undefined;
                    let qrLoaded = undefined;

                    const checkComplete = () => {
                        if (logoLoaded !== undefined && qrLoaded !== undefined) {
                            renderCanvasContent(logoLoaded, qrLoaded);
                        }
                    };

                    // Load Logo Image
                    const logoImg = new Image();
                    logoImg.crossOrigin = 'anonymous';
                    logoImg.onload = () => { logoLoaded = logoImg; checkComplete(); };
                    logoImg.onerror = () => { logoLoaded = null; checkComplete(); };
                    logoImg.src = "{{ asset('images/brand/logo.webp') }}";

                    // Load QR Image
                    if (qrImgElem && qrImgElem.src) {
                        const qrImg = new Image();
                        qrImg.crossOrigin = 'anonymous';
                        qrImg.onload = () => { qrLoaded = qrImg; checkComplete(); };
                        qrImg.onerror = () => { qrLoaded = null; checkComplete(); };
                        qrImg.src = qrImgElem.src;
                    } else {
                        qrLoaded = null;
                        checkComplete();
                    }
                });
            }
        });
    </script>
    @endpush
</x-guest-layout>
