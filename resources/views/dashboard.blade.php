<x-app-layout>
    <style>
        /* Floating idle animation */
        @keyframes floatIdle {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-4px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .station-card {
            background: #3b5080;
            border-radius: 18px;
            width: 90px;
            height: 90px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            animation: floatIdle 3.5s ease-in-out infinite;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .station-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .station-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            cursor: pointer;
        }

        .tile-image-wrapper {
            position: relative;
            width: 95px;
            height: 95px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .tile-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Overlay */
        .developer-card .overlay,
        .station-card .overlay {
            position: absolute;
            inset: 0;
            border-radius: 18px;
            background: rgba(0, 0, 0, 0.65);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            pointer-events: none;
            z-index: 2;
        }

        .tile-image-wrapper .overlay span {
            color: #fff;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 1px;
            line-height: 1.4;
            text-transform: uppercase;
        }

        .station-text {
            color: #fff;
            text-align: left;
        }

        .station-number {
            font-weight: 700;
            margin-right: 6px;
            font-size: 14px;
        }

        .station-title {
            font-size: 10px;
            text-align: center;
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .station-card img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }

        h2 {
            font-weight: 700 !important;
            letter-spacing: 2px;
        }

        /* developers — glassmorphism */
        .developer-card {
            background: rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(12px) saturate(130%);
            -webkit-backdrop-filter: blur(12px) saturate(130%);
            border-radius: 16px;
            padding: 28px 22px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 10px 30px rgba(9, 30, 66, 0.10);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .developer-card+.developer-card {
            margin-top: 18px;
        }

        .developer-logo {
            height: 86px;
            width: 80vw;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.08));
        }

        /* top-left highlight sheen */
        .developer-card::after {
            content: '';
            position: absolute;
            top: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.25), rgba(255, 255, 255, 0) 45%);
            transform: rotate(-15deg);
            pointer-events: none;
            mix-blend-mode: screen;
        }

        .developer-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(9, 30, 66, 0.13);
        }

        .voucher-trigger {
            position: fixed;
            right: -25px;
            bottom: -15px;
            width: 130px;
            height: 55px;
            background: #2d67c8;
            color: #fff;
            border-radius: 30px 0 0 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,.2);
            z-index: 1000;
        }

        .voucher-trigger img {
            height: 40px;
            object-fit: contain;
        }

        .voucher-text {
            font-size: 11px;
            text-align: center;
            line-height: 1.2;
        }

        .voucher-overlay {
            position: fixed;
            right: -25px;
            bottom: -15px;
            width: 130px;
            height: 55px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 30px 0 0 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            pointer-events: none;
        }

        .voucher-overlay small {
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
        }
    </style>

    <div class="py-4 map-page main-content main-background with-scroll">

        <!-- Branding -->
        <div class="animate-entry">
            @include('components.branding')
        </div>

        <!-- CONFIRMED BOOKING TICKET CARD - MATCHING USER SCREENSHOT DESIGN -->
        <div id="dashboard-booking-card-wrapper" class="px-4 my-6 animate-entry">
            <div class="border-4 border-[#e86034] rounded-3xl bg-white shadow-2xl p-6 sm:p-8 max-w-sm mx-auto text-center relative font-sans">
                
                <!-- Branding Header -->
                <div class="mb-4 text-center">
                    <div class="text-xl font-serif italic text-slate-900 tracking-wide">Caroline Hélain</div>
                    <div class="text-xs font-bold text-slate-500 my-0.5">x</div>
                    <div class="text-sm font-extrabold tracking-widest text-slate-900 uppercase">LONGCHAMP</div>
                </div>

                <!-- Title -->
                <h2 class="text-2xl font-black text-[#e86034] uppercase tracking-wider mb-2">
                    BOOKING CONFIRMED!
                </h2>

                <!-- Subtitle -->
                <p class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wide leading-relaxed max-w-xs mx-auto mb-5">
                    HI <span id="dash-greeting-name">{{ auth()->check() && isset(auth()->user()->fname) ? strtoupper(auth()->user()->fname) : 'CUSTOMER' }}</span>,<br>
                    YOUR SLOT IS OFFICIALLY LOCKED IN. SEE YOU THERE!
                </p>

                <!-- Red Dashed Ticket Container -->
                <div id="dash-ticket-container" class="border-2 border-dashed border-red-500 rounded-2xl p-6 bg-white inline-block max-w-xs w-full shadow-sm mb-6 text-center">
                    
                    <!-- Dynamic QR Code Image -->
                    <img id="dash-qr-code-img" src="{{ isset($userBooking) && $userBooking ? 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($userBooking->reference_no) : '' }}" alt="Booking QR Code" class="w-44 h-44 mx-auto mb-3 object-contain" crossorigin="anonymous">

                    <!-- Customer Name -->
                    <div id="dash-ticket-name" class="text-sm font-black text-slate-900 uppercase mb-2 tracking-wide">
                        {{ isset($userBooking) && $userBooking ? strtoupper($userBooking->customer_name) : (auth()->check() ? strtoupper(auth()->user()->name) : 'JOSHUA') }}
                    </div>

                    <!-- Details List -->
                    <div class="space-y-1 text-[10px] font-extrabold text-slate-900 uppercase tracking-wider">
                        <div><span class="text-slate-400">DATE:</span> <span id="dash-ticket-date">{{ isset($userBooking) && $userBooking && $userBooking->bookingDate ? strtoupper(\Carbon\Carbon::parse($userBooking->bookingDate->date)->format('jS F')) : '7TH OCTOBER' }}</span></div>
                        <div><span class="text-slate-400">TIME:</span> <span id="dash-ticket-time">{{ isset($userBooking) && $userBooking && $userBooking->bookingSlot ? strtoupper(\Carbon\Carbon::parse($userBooking->bookingSlot->start_time)->format('g:iA')) : '6:00PM' }}</span></div>
                        <div class="mt-1 leading-snug px-2">
                            <span class="text-slate-400">VENUE:</span> LONGCHAMP POP UP STORE THE GARDENS MALL
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (CHANGE YOUR SLOT / DOWNLOAD) -->
                <div class="space-y-3 max-w-xs mx-auto">
                    <a href="{{ url('/reservation-create?modify=1') }}" id="dash-change-slot-btn" class="block w-full py-3.5 px-6 rounded-lg bg-[#e86034] hover:bg-[#d44f25] text-white font-black text-sm tracking-widest uppercase transition shadow-md shadow-orange-500/20 text-center text-decoration-none">
                        CHANGE YOUR SLOT
                    </a>
                    <button id="dash-download-btn" type="button" class="w-full py-3.5 px-6 rounded-lg bg-[#e86034] hover:bg-[#d44f25] text-white font-black text-sm tracking-widest uppercase transition shadow-md shadow-orange-500/20">
                        DOWNLOAD
                    </button>
                </div>

                <!-- Longchamp Emblem Icon at Bottom -->
                <div class="mt-6 flex justify-center items-center">
                    <svg class="w-12 h-6 text-slate-800" viewBox="0 0 100 40" fill="currentColor">
                        <path d="M12 25 C 20 10, 35 10, 45 20 C 50 15, 65 15, 75 25 C 65 23, 50 28, 45 35 C 35 28, 20 28, 12 25 Z M 40 18 C 42 12, 48 10, 52 14 C 48 18, 44 20, 40 18 Z" />
                    </svg>
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
                    const refNo = @json(isset($userBooking) && $userBooking ? $userBooking->reference_no : null);
                    if (refNo) {
                        localStorage.setItem('latest_booking_ref', refNo);
                    }
                });
            }

            if (dashDownloadBtn) {
                dashDownloadBtn.addEventListener('click', () => {
                    const refNo = @json(isset($userBooking) && $userBooking ? $userBooking->reference_no : null) || localStorage.getItem('latest_booking_ref') || 'ticket';
                    
                    const customerName = document.getElementById('dash-ticket-name').textContent.trim();
                    const dateText = document.getElementById('dash-ticket-date').textContent.trim();
                    const timeText = document.getElementById('dash-ticket-time').textContent.trim();
                    const qrImgElem = document.getElementById('dash-qr-code-img');

                    dashDownloadBtn.disabled = true;
                    dashDownloadBtn.textContent = 'GENERATING JPEG...';

                    const canvas = document.createElement('canvas');
                    canvas.width = 600;
                    canvas.height = 700;
                    const ctx = canvas.getContext('2d');

                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, 600, 700);

                    ctx.save();
                    ctx.strokeStyle = '#ef4444';
                    ctx.lineWidth = 4;
                    ctx.setLineDash([10, 8]);
                    
                    const r = 24;
                    const x = 30, y = 30, w = 540, h = 640;
                    ctx.beginPath();
                    ctx.moveTo(x + r, y);
                    ctx.lineTo(x + w - r, y);
                    ctx.quadraticCurveTo(x + w, y, x + w, y + r);
                    ctx.lineTo(x + w, y + h - r);
                    ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
                    ctx.lineTo(x + r, y + h);
                    ctx.quadraticCurveTo(x, y + h, x, y + h - r);
                    ctx.lineTo(x, y + r);
                    ctx.quadraticCurveTo(x, y, x + r, y);
                    ctx.closePath();
                    ctx.stroke();
                    ctx.restore();

                    const triggerDownload = (imgSource) => {
                        if (imgSource) {
                            ctx.drawImage(imgSource, 160, 60, 280, 280);
                        }

                        ctx.fillStyle = '#0f172a';
                        ctx.font = '900 28px "Plus Jakarta Sans", sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText(customerName.toUpperCase(), 300, 390);

                        ctx.font = '800 18px "Plus Jakarta Sans", sans-serif';
                        
                        ctx.fillStyle = '#94a3b8';
                        ctx.fillText('DATE: ', 240, 440);
                        ctx.fillStyle = '#0f172a';
                        ctx.fillText(dateText, 320, 440);

                        ctx.fillStyle = '#94a3b8';
                        ctx.fillText('TIME: ', 240, 480);
                        ctx.fillStyle = '#0f172a';
                        ctx.fillText(timeText, 320, 480);

                        ctx.fillStyle = '#94a3b8';
                        ctx.fillText('VENUE: ', 220, 520);
                        ctx.fillStyle = '#0f172a';
                        ctx.fillText('LONGCHAMP POP UP STORE', 340, 520);
                        ctx.fillText('THE GARDENS MALL', 300, 555);

                        const imgData = canvas.toDataURL('image/jpeg', 0.95);
                        const link = document.createElement('a');
                        link.download = `booking-ticket-${refNo}.jpeg`;
                        link.href = imgData;
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
</x-app-layout>
