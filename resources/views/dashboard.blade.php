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
        /* Voucher */
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
                right: -25px;      /* same as voucher */
                bottom: -15px;     /* same as voucher */
                width: 130px;      /* same as voucher */
                height: 55px;      /* same as voucher */

                background: rgba(0, 0, 0, 0.7);
                border-radius: 30px 0 0 30px;

                display: flex;
                align-items: center;
                justify-content: center;

                z-index: 1001;     /* higher than voucher */
                pointer-events: none;
            }

            .voucher-overlay small {
                color: #fff;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 1px;
            }

            .voucher-trigger.disabled {
                pointer-events: none;
                cursor: not-allowed;
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
            @if($canSeeVoucher)
                <div class="voucher-wrapper">

                    <div
                        class="voucher-trigger {{ $voucherRedemeed ? 'disabled' : '' }}"
                        @if(!$voucherRedemeed)
                            id="voucherModal"
                            data-can-access="{{ $canAccessStation ? 1 : 0 }}"
                        @endif
                    >
                        <div class="voucher-text">
                            CHAGEE<br>
                            Drink <br>Voucher
                        </div>

                        <img src="{{ asset('images/brand/chagee.webp') }}" alt="Voucher">
                    </div>

                    @if($voucherRedemeed)
                        <div class="voucher-overlay">
                            <small>REDEEMED</small>
                        </div>
                    @endif

                </div>
            @endif
        </div>

        <!-- Modal -->
        <div class="animate-entry modal fade custom-modal" id="lockedStationModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content card modal-parent">
                    <div class="modal-body">
                        <div class="text-center content">

                            <div class="mt-4 mb-4 text-content">
                                <p>
                                    Unlock this by completing<br>
                                    your visits to 3 developer stations,<br>
                                    voting and lucky draw station.<br>
                                </p>
                                <br>
                                <p>
                                    Free Chagee Drink.<br>
                                    Limited to 50 people.
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

        <!-- Modal2 -->
        <div class="animate-entry modal fade custom-modal" id="unlockedStationModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content card modal-parent">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="row">
                                <div class="touchBox-container col-11 m-auto d-flex justify-content-center align-items-center p-0 animate-entry">
                                    <div id="touchBox">
                                        <img class="stamping-image {{ $voucherRedemeed ? 'active' : '' }}"
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
                                <h3>CHAGEE Happy Hour!</h3>
                                <p>
                                   Complete 3 Developers' Booth Quizzes + 
                                    Vote + Lucky Draw Check-in to CLAIM FREE
                                    CHAGEE! 
                                </p>
                                <p>50 cups at 12pm | 50 cups at 6pm <br>*First-come-first-served</p>
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
                            {{-- <img class="mx-auto mb-4 check" id="badge" src="{{ asset('images/error.png') }}"
                                style="filter:brightness(0);"> --}}

                            <div class="mt-4 mb-4 text-content">
                                <p>
                                    Unlock this by completing<br>
                                    your visits to 3 developer stations.
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

                        $isLocked = ($station->id == 1 && !$canAccessStation) || ($station->id == 2 && !$canAccessStation);
                    @endphp

                    {{-- 🚫 HIDE station 3 if NOT early bird --}}
                    @if ($station->id == 3 && !auth()->user()->is_early_bird)
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

                        {{-- ✅ Redeemed (priority) --}}
                        @if ($station->status)
                            <div class="overlay">
                                <span class="text-white" style="font-size:10px;">REDEEMED</span>
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
            $completedStation4 = auth()->user()->stationUser()->where('station_id', 3)->exists();
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
            document.addEventListener('DOMContentLoaded', function() {
                let canAccessStation5 = @json($canAccessStation5);
                let canAccessStation = @json($canAccessStation);

            
                

                window.gotoStamping = function(id, ) {
                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id", id
                    );
                    window.location.href = url;
                }

                window.gotoQuiz = function(id, ) {
                    var url = "{{ route('developer', ['developer' => ':id']) }}".replace(
                        ":id", id
                    );
                    // Redirect to the generated URL
                    window.location.href = url;
                }

                window.gotoStation = function(id, ) {
                    var url = "{{ route('station', ['station' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    if (id == 1 && !canAccessStation) {
                        // Show the not allowed modal if trying to access station 3 without permission
                        var notAllowedModal = new bootstrap.Modal(document.getElementById('notAllowedModal'));
                        notAllowedModal.show();
                        return;
                    }

                    if (id == 2 && !canAccessStation) {
                        // Show the not allowed modal if trying to access station 3 without permission
                        var notAllowedModal = new bootstrap.Modal(document.getElementById('notAllowedModal'));
                        notAllowedModal.show();
                        return;
                    }

                    // Redirect to the generated URL
                    window.location.href = url;
                }
            });
        </script>
        <script>
            document.getElementById('voucherModal').addEventListener('click', function () {

                let canAccessStation = @json($canAccessStation);

                if (!canAccessStation) {
                    bootstrap.Modal
                        .getOrCreateInstance(document.getElementById('lockedStationModal'))
                        .show();

                    return;
                }
                else{
                    bootstrap.Modal
                        .getOrCreateInstance(document.getElementById('unlockedStationModal'))
                        .show();
                }

                // User can access voucher
               
            });
            </script>
            <script>
        document.addEventListener("DOMContentLoaded", () => {
            const box = document.getElementById("touchBox");
            const countDisplay = document.getElementById("countNum");
            const stampingImage = document.querySelector(".stamping-image");
            const stationid = stampingImage ? parseInt(stampingImage.dataset.id) : null;
            const activeInside = new Map();
            let hasStamped = false;
         
            // isStamped();  

            // function isStamped()
            // {
            //     hasStamped = false;
            // }

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
                countDisplay.textContent = activeInside.size;
                // Toggle grayscale removal
                // Only trigger once

                let requiredCount;

                requiredCount = 3;
                
                console.log(requiredCount);
                console.log('activeInside.size:', activeInside.size, 'requiredCount:', requiredCount, 'hasStamped:', hasStamped);

                    if (!hasStamped && activeInside.size === requiredCount ) {
                      console.log('activeInside.size:', activeInside.size, 'requiredCount:', requiredCount, 'hasStamped:', hasStamped);

                        hasStamped = true;
                        stampingImage.classList.add("active");

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const stampImage = document.querySelector('.stamping-image');
                        const stampId = stampingImage ? parseInt(stampingImage.dataset.id) : null;
                        
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
                                    console.log(response);
                                    location.reload();
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error sending QR Code message:', error);
                                }
                            });
                    }
                    else 
                    {
                        console.log('invalid touchpoint');
                    }
                    
            }

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
        });
    </script>
    @endpush
</x-app-layout>
