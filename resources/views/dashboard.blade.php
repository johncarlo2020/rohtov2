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

        /* Floating voucher button */
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

        /* Overlay */
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

        #touchBox {
            width: 200px;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            touch-action: none;
        }

        .stamping-image {
            width: 150px;
            height: 150px;
            filter: grayscale(100%);
            transition: filter 0.3s ease;
        }

        .stamping-image.active {
            filter: grayscale(0%);
        }

    </style>

    <div class="py-4 map-page main-content main-background with-scroll">

        <!-- Branding -->
        <div class="animate-entry">
            @include('components.branding')
        </div>

        <!-- Title -->
        <div class="text-center animate-entry">
            <h2 class="my-5 text-center">Explore</h2>

            <div class="voucher-wrapper">
                <div class="voucher-trigger" id="voucherModal" data-can-access="{{ $canSeeVoucher ? 1 : 0 }}">
                    <div class="voucher-text">
                        CHAGEE<br>
                        Drink <br>Voucher
                    </div>
                    <img src="{{ asset('images/brand/chagee.webp') }}" alt="Voucher">
                </div>

                @if($voucherRedeemed)
                    <div class="voucher-overlay">
                        <small>REDEEMED</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Locked CHAGEE Modal -->
        <div class="animate-entry modal fade custom-modal" id="lockedStationModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content card modal-parent">
                    <div class="modal-body">
                        <div class="text-center content">
                            <h3 class="mb-3" style="font-size: 1.15rem; font-weight: 700; color: #205493;">CHAGEE Happy Hour!</h3>

                            @if(!$voucherRedeemed)
                                <p style="font-size: 0.9rem; line-height: 1.4; color: #205493;">
                                    Complete 3 Developers' booth quizzes<br>
                                    + Game + Lucky Draw check-in to<br>
                                    CLAIM FREE CHAGEE!
                                </p>
                                <div class="mt-3 mb-4 text-content">
                                    <p style="font-weight: 600; color: #205493; margin-bottom: 4px;">22 November 2026, Sunday</p>
                                    <p style="font-weight: 500; color: #205493; margin-bottom: 8px;">50 cups, 11 am</p>
                                    <p style="font-size: 0.8rem; color: #205493; margin-bottom: 0;">*First-come-first-served</p>
                                </div>
                            @else
                                <img class="stamping-image m-auto active"
                                    src="{{ asset('images/brand/chagee_stamp.webp') }}"
                                    alt="Stamp Image"
                                    data-id="5">
                                <br>
                                <p class="mt-2 text-success fw-bold">Voucher Fully Redeemed</p>
                            @endif

                            <button type="button" class="w-75 custom-btn custom-btn-primary" data-bs-dismiss="modal">
                                CLOSE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unlocked CHAGEE Modal (Stamping) -->
        <div class="animate-entry modal fade custom-modal" id="unlockedStationModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content card modal-parent">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="row">
                                <div class="touchBox-container col-11 m-auto d-flex justify-content-center align-items-center p-0 animate-entry">
                                    <div id="touchBox">
                                        <img class="stamping-image {{ $voucherRedeemed ? 'active' : '' }}"
                                            src="{{ asset('images/brand/chagee_stamp.webp') }}"
                                            alt="Stamp Image"
                                            data-id="5">
                                    </div>
                                </div>
                            </div>
                            <div id="countDisplay" class="text-center mt-3 d-none">
                                Touches inside count: <span id="countNum">0</span>
                            </div>

                            <div class="mb-4 text-content">
                                <p style="color: #205493; font-size: 0.9rem;">
                                    Congratulations! You've completed the journey. Here is our complimentary
                                    drink for you. Head to the counter to get stamped.
                                </p>
                            </div>

                            <button type="button" class="w-75 custom-btn custom-btn-primary" data-bs-dismiss="modal">
                                CLOSE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- Modal -->
        <div class="animate-entry modal fade custom-modal" id="notAllowedModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content card modal-parent">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="mt-4 mb-4 text-content">
                                <p style="color: #205493; font-weight: 500; font-size: 0.95rem; line-height: 1.45;">
                                    Complete 3 developers'<br>
                                    booth quizzes to unlock<br>
                                    the lucky draw.
                                </p>
                            </div>

                            <button type="button" class="w-75 custom-btn custom-btn-primary" data-bs-dismiss="modal">
                                CLOSE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- not allowed video -->
        <div class="animate-entry modal fade custom-modal" id="notAllowedModalVideo" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content card modal-parent">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="mt-4 mb-4 text-content">
                                <p>
                                    This booth is only open on May 1st for Labour Day, and May 9th - 10th for Mother's Day.<br>
                                </p>
                                <br>
                                <p>If you're visiting on these dates, <br> please drop by!</p>
                            </div>

                            <button type="button" class="w-75 custom-btn custom-btn-primary" data-bs-dismiss="modal">
                                CLOSE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-0 py-3 container">



            @foreach (auth()->user()->developers as $developer)
                @php
                    $imageDev = asset("images/developer/DEV{$developer->id}.webp");
                @endphp

                <div class="d-flex align-items-center justify-content-center mb-3 animate-entry developer-card"
                    onclick="gotoQuiz({{ $developer->id }})">
                    <img src="{{ $imageDev }}" alt="Developer {{ $developer->id }}" class="developer-logo">

                    @if ($developer->pivot->isCompleted)
                        <div class="overlay">
                            <h2 class="text-white">COMPLETED</h2>
                        </div>
                    @endif
                </div>
            @endforeach

        </div>

        <!-- Stations -->
        <div class="px-0 container" style="max-width: 420px;">

            <div class="d-flex justify-content-center animate-entry station-row">
                @foreach ($stations as $station)
                    @php
                        $image = asset("images/station/ST{$station->id}.webp");

                        $isLocked = ($station->id == 3 && !$canAccessStation);
                    @endphp

                    {{-- 🚫 HIDE station 1 if NOT early bird --}}
                    @if ($station->id == 1 && !auth()->user()->is_early_bird)
                        @continue
                    @endif

                    <div class="station-card"
                        @if ($isLocked)
                            onclick="gotoStation({{ $station->id }})"
                        @else
                            onclick="gotoStation({{ $station->id }})"
                        @endif
                    >

                        <!-- Image -->
                        <img src="{{ $image }}" alt="Station {{ $station->id }}">

                        <!-- Title -->
                        <div class="station-title">
                            {{ $station->name }}
                        </div>

                        {{-- ✅ Status Overlay --}}
                        @if ($station->status)
                            <div class="overlay">
                                <span class="text-white" style="font-size:10px;">{{ !in_array($station->id, [1, 3]) ? 'COMPLETED' : 'REDEEMED' }}</span>
                            </div>

                        {{-- 🔒 LOCK (Station 3 or Video Booth) --}}
                        @elseif ($isLocked)
                            <div class="overlay">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                    viewBox="0 0 24 24" fill="white">
                                    <path
                                        d="M12 1C9.243 1 7 3.243 7 6v2H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2h-2V6c0-2.757-2.243-5-5-5zm0 2c1.654 0 3 1.346 3 3v2H9V6c0-1.654 1.346-3 3-3zm0 9a2 2 0 1 1 0 4 2 2 0 0 1 0-4z" />
                                </svg>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        </div>

        @php
            $earlyBird = auth()->user()->is_early_bird;
            $completedStation4 = auth()->user()->stationUser()->where('station_id', 1)->exists();
            $showEarlyBirdModal = $earlyBird;
        @endphp

        <div class="modal fade" id="earlyBirdModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="p-4 text-center modal-content">

                    <h5>Hello!</h5>

                    <p class="mb-4">
                        We see you've pre-registered!<br>
                        <br>
                        Tap the <b>'Early Bird'</b> button below<br>
                        and grab your special reward!
                    </p>

                    <button class="m-auto w-75 custom-btn custom-btn-primary" data-bs-dismiss="modal">
                        CLOSE
                    </button>

                </div>
            </div>
        </div>

    </div>
    @push('scripts')
        @if ($showEarlyBirdModal && !$completedStation4)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let modal = new bootstrap.Modal(document.getElementById('earlyBirdModal'));
                    modal.show();
                });
            </script>
        @endif

        <script>
            let canAccessStation5 = @json($canAccessStation5 ?? false);
            let canAccessStation = @json($canAccessStation ?? false);

            window.gotoStamping = function(id) {
                var url = "{{ route('station', ['station' => ':id']) }}".replace(":id", id);
                window.location.href = url;
            };

            window.gotoQuiz = function(id) {
                var url = "{{ route('developer', ['developer' => ':id']) }}".replace(":id", id);
                window.location.href = url;
            };

            window.gotoStation = function(id) {
                if (id == 3 && !canAccessStation) {
                    var modalEl = document.getElementById('notAllowedModal');
                    if (modalEl) {
                        var notAllowedModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        notAllowedModal.show();
                    }
                    return;
                }

                var url = "{{ route('station', ['station' => ':id']) }}".replace(":id", id);
                window.location.href = url;
            };
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var voucherBtn = document.getElementById('voucherModal');
                if (voucherBtn) {
                    voucherBtn.addEventListener('click', function () {
                        let canAccessStation = @json($canSeeVoucher);

                        if (!canAccessStation) {
                            bootstrap.Modal
                                .getOrCreateInstance(document.getElementById('lockedStationModal'))
                                .show();
                            return;
                        } else {
                            bootstrap.Modal
                                .getOrCreateInstance(document.getElementById('unlockedStationModal'))
                                .show();
                        }
                    });
                }

                const box = document.getElementById("touchBox");
                const countDisplay = document.getElementById("countNum");
                const stampingImage = document.querySelector(".stamping-image");
                const activeInside = new Map();
                let hasStamped = false;

                function isInside(event, element) {
                    const rect = element.getBoundingClientRect();
                    return (
                        event.clientX >= rect.left &&
                        event.clientX <= rect.right &&
                        event.clientY >= rect.top &&
                        event.clientY <= rect.bottom
                    );
                }

                function updateDisplay() {
                    if (countDisplay) {
                        countDisplay.textContent = activeInside.size;
                    }

                    let requiredCount = 3;

                    if (!hasStamped && activeInside.size === requiredCount && box) {
                        hasStamped = true;
                        if (stampingImage) {
                            stampingImage.classList.add("active");
                        }

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const stampId = stampingImage ? parseInt(stampingImage.dataset.id) : 5;

                        $.ajax({
                            url: '{{ route('process_stamp') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            data: {
                                station: stampId,
                            },
                            success: function (response) {
                                location.reload();
                            },
                            error: function (xhr, status, error) {
                                console.error('Error sending stamp message:', error);
                            }
                        });
                    }
                }

                if (box) {
                    document.addEventListener("pointerdown", (event) => {
                        if (event.pointerType === "mouse") return;
                        if (isInside(event, box)) {
                            activeInside.set(event.pointerId, event);
                        }
                        updateDisplay();
                    });

                    document.addEventListener("pointermove", (event) => {
                        if (event.pointerType === "mouse") return;

                        if (activeInside.has(event.pointerId) && !isInside(event, box)) {
                            activeInside.delete(event.pointerId);
                        } else if (!activeInside.has(event.pointerId) && isInside(event, box)) {
                            activeInside.set(event.pointerId, event);
                        }

                        updateDisplay();
                    });

                    document.addEventListener("pointerup", (event) => {
                        activeInside.delete(event.pointerId);
                        updateDisplay();
                    });

                    document.addEventListener("pointercancel", (event) => {
                        activeInside.delete(event.pointerId);
                        updateDisplay();
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
