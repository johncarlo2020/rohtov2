<x-guest-layout>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .brand-orange-text { color: #e86034 !important; }
        .brand-orange-bg { background-color: #e86034 !important; color: #ffffff !important; border: none; }
        .brand-orange-bg:hover, .brand-orange-bg:focus { background-color: #d44f25 !important; color: #ffffff !important; }

        .ticket-box {
            border: 2px dashed #ef4444;
            padding: 1.5rem;
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
                            <h2 class="h4 fw-bold brand-orange-text text-uppercase mb-2">
                                BOOKING CONFIRMED!
                            </h2>

                            @php
                                $firstName = 'CUSTOMER';
                                $fullName = 'JOSHUA';
                                $formattedDateStr = '1ST OCTOBER';
                                $formattedTimeStr = '2:00PM';
                                $refNo = null;
                                $canModify = true;

                                if (isset($userBooking) && $userBooking) {
                                    $refNo = $userBooking->reference_no;
                                    $canModify = ((int) $userBooking->reschedule_count) < 1;

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
                                        $formattedDateStr = $dayNum . $sfx . ' ' . strtoupper($d->format('F'));
                                    }

                                    if ($userBooking->bookingSlot) {
                                        $formattedTimeStr = strtoupper(\Carbon\Carbon::parse($userBooking->bookingSlot->start_time)->format('g:iA'));
                                    }
                                } elseif (auth()->check()) {
                                    $fullName = strtoupper(trim((auth()->user()->fname ?? '') . ' ' . (auth()->user()->lname ?? '')));
                                    if (empty($fullName)) {
                                        $fullName = strtoupper(auth()->user()->name ?? 'CUSTOMER');
                                    }
                                    $firstName = strtoupper(auth()->user()->fname ?? explode(' ', $fullName)[0]);
                                }
                            @endphp

                            <!-- Subtitle -->
                            <p class="small fw-bold text-dark text-uppercase mb-2">
                                HI <span id="dash-greeting-name">{{ $firstName }}</span>,
                                YOUR IS CONFIRMED
                            </p>
                            <p class="small text-dark mb-4">
                                PLEASE CHECK YOUR EMAIL FOR YOUR<br>CONFIRMATION DETAILS AND PRESENT THIS <br> QR CODE UPON ARRIVAL  
                            </p>

                            <!-- Red Dashed Ticket Container -->
                            <div id="dash-ticket-container" class="ticket-box d-inline-block w-100 mb-4 text-center" style="max-width: 320px;">
                                
                                <!-- Dynamic QR Code Image -->
                                <img id="dash-qr-code-img" src="{{ $refNo ? 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($refNo) : '' }}" alt="Booking QR Code" class="img-fluid mb-3" style="width: 170px; height: 170px; margin:auto; object-fit: contain;" crossorigin="anonymous">

                                <!-- Customer Name -->
                                <div id="dash-ticket-name" class="fw-bold text-dark text-uppercase mb-2">
                                    {{ $fullName }}
                                </div>

                                <!-- Details List -->
                                <div class="small fw-bold text-dark text-uppercase">
                                    <div><span class="text-muted">DATE:</span> <span id="dash-ticket-date" class="brand-orange-text">{{ $formattedDateStr }}</span></div>
                                    <div class="my-1"><span class="text-muted">TIME:</span> <span id="dash-ticket-time">{{ $formattedTimeStr }}</span></div>
                                    <div class="mt-2 text-muted">
                                        <span class="text-muted">VENUE:</span> LONGCHAMP POP UP STORE THE GARDENS MALL
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons (CHANGE YOUR SLOT / DOWNLOAD) -->
                            <div class="d-flex flex-column gap-2 mx-auto" style="max-width: 320px;">
                                @if($canModify)
                                    <a href="{{ url('/reservation-create?modify=1') }}" id="dash-change-slot-btn" class="custom-btn custom-btn-primary pulse-slow w-50 m-auto text-decoration-none">
                                        CHANGE YOUR SLOT
                                    </a>
                                @endif

                                <button id="dash-download-btn" type="button" class="custom-btn custom-btn-primary pulse-slow w-50 m-auto">
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
            const dashChangeSlotBtn = document.getElementById('dash-change-slot-btn');
            const dashDownloadBtn = document.getElementById('dash-download-btn');

            if (dashChangeSlotBtn) {
                dashChangeSlotBtn.addEventListener('click', () => {
                    const refNo = @json($refNo);
                    if (refNo) {
                        localStorage.setItem('latest_booking_ref', refNo);
                    }
                });
            }

            if (dashDownloadBtn) {
                dashDownloadBtn.addEventListener('click', () => {
                    const refNo = @json($refNo) || localStorage.getItem('latest_booking_ref') || 'ticket';
                    
                    const customerName = document.getElementById('dash-ticket-name').textContent.trim();
                    const dateText = document.getElementById('dash-ticket-date').textContent.trim();
                    const timeText = document.getElementById('dash-ticket-time').textContent.trim();
                    const qrImgElem = document.getElementById('dash-qr-code-img');

                    dashDownloadBtn.disabled = true;
                    dashDownloadBtn.textContent = 'GENERATING...';

                    // Create offscreen canvas matching red dashed ticket box aspect (450x540)
                    const canvas = document.createElement('canvas');
                    canvas.width = 450;
                    canvas.height = 540;
                    const ctx = canvas.getContext('2d');

                    // Fill white background
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, 450, 540);

                    // Outer Red Dashed Border Box (inset 15px)
                    ctx.strokeStyle = '#ef4444';
                    ctx.lineWidth = 3;
                    ctx.setLineDash([8, 6]);
                    ctx.strokeRect(15, 15, 420, 510);
                    ctx.setLineDash([]); // reset dash

                    // Helper to trigger image download
                    const triggerDownload = (loadedQrImage) => {
                        // 1. QR Code (Centered 200x200)
                        if (loadedQrImage) {
                            ctx.drawImage(loadedQrImage, 125, 40, 200, 200);
                        } else {
                            ctx.fillStyle = '#f1f5f9';
                            ctx.fillRect(125, 40, 200, 200);
                            ctx.fillStyle = '#64748b';
                            ctx.font = 'bold 16px "Helvetica Neue", Helvetica, Arial, sans-serif';
                            ctx.textAlign = 'center';
                            ctx.fillText('QR CODE', 225, 145);
                        }

                        // 2. Customer Name (Bold, Centered)
                        ctx.fillStyle = '#0f172a';
                        ctx.font = 'bold 22px "Helvetica Neue", Helvetica, Arial, sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText(customerName.toUpperCase(), 225, 280);

                        // 3. DATE Line (DATE: dark, value: brand orange)
                        ctx.font = 'bold 14px "Helvetica Neue", Helvetica, Arial, sans-serif';
                        const dateLabel = 'DATE: ';
                        const dateVal = dateText.toUpperCase();

                        ctx.fillStyle = '#0f172a';
                        const labelW = ctx.measureText(dateLabel).width;
                        ctx.fillStyle = '#e86034';
                        const valW = ctx.measureText(dateVal).width;
                        const dateStartX = (450 - (labelW + valW)) / 2;

                        ctx.textAlign = 'left';
                        ctx.fillStyle = '#0f172a';
                        ctx.fillText(dateLabel, dateStartX, 330);
                        ctx.fillStyle = '#e86034';
                        ctx.fillText(dateVal, dateStartX + labelW, 330);

                        // 4. TIME Line (Centered)
                        ctx.textAlign = 'center';
                        ctx.fillStyle = '#0f172a';
                        ctx.font = 'bold 14px "Helvetica Neue", Helvetica, Arial, sans-serif';
                        ctx.fillText(`TIME: ${timeText.toUpperCase()}`, 225, 360);

                        // 5. VENUE Lines (Centered)
                        ctx.fillText('VENUE: LONGCHAMP POP UP STORE', 225, 410);
                        ctx.fillText('THE GARDENS MALL', 225, 435);

                        // Convert Canvas to Data URL & Trigger Download
                        const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
                        const link = document.createElement('a');
                        link.download = `Reservation_${refNo}.jpg`;
                        link.href = dataUrl;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        dashDownloadBtn.disabled = false;
                        dashDownloadBtn.textContent = 'DOWNLOAD';
                    };

                    if (qrImgElem && qrImgElem.src) {
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.onload = () => triggerDownload(img);
                        img.onerror = () => triggerDownload(null);
                        img.src = qrImgElem.src;
                    } else {
                        triggerDownload(null);
                    }
                });
            }
        });
    </script>
    @endpush
</x-guest-layout>
