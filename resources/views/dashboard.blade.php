<x-app-layout>
    @push('styles')
        <link rel="preload" as="image" href="{{ asset('images/rwg/02 RW Genting Winter_map_2x.webp') }}" fetchpriority="high" />
    @endpush

    <style>
        /* Hide default footer on full-bleed map dashboard */
        .dashboard .footer,
        body.dashboard .footer {
            display: none !important;
        }

        .rwg-dashboard-wrapper {
            width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            background-color: #120924;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            position: relative;
            padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 2rem);
        }

        .rwg-map-container {
            position: relative;
            width: 100%;
            max-width: 480px;
            aspect-ratio: 804 / 1748;
            background-image: url('{{ asset('images/rwg/02 RW Genting Winter_map_2x.webp') }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: top center;
            overflow: hidden;
            box-shadow: 0 0 35px rgba(0, 0, 0, 0.6);
            touch-action: pan-y;
        }

        /* Top Header */
        .rwg-dash-header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 50;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: calc(env(safe-area-inset-top, 0px) + 0.85rem) 1.25rem 0.5rem 1.25rem;
        }

        .rwg-dash-logo {
            max-height: clamp(38px, 8.5vw, 48px);
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
            cursor: pointer;
        }

        .rwg-menu-dropdown-wrapper {
            position: static;
        }

        .rwg-menu-btn {
            position: absolute;
            right: 1.25rem;
            top: calc(env(safe-area-inset-top, 0px) + 0.85rem);
            z-index: 60;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1.5px solid rgba(255, 255, 255, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease, background-color 0.2s ease;
            -webkit-tap-highlight-color: transparent;
        }

        .rwg-menu-btn:hover, .rwg-menu-btn:focus {
            background: rgba(255, 255, 255, 0.75);
            color: #ffffff;
            transform: scale(1.05);
        }

        .rwg-burger-dropdown {
            position: absolute !important;
            top: calc(env(safe-area-inset-top, 0px) + 3.9rem) !important;
            left: 1rem !important;
            right: 1rem !important;
            width: auto !important;
            max-width: none !important;
            transform: none !important;
            margin: 0 !important;
            background-color: #DEB579 !important;
            border: none !important;
            border-radius: 24px !important;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5) !important;
            padding: 24px 20px !important;
            z-index: 1000 !important;
        }

        .rwg-dropdown-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-left: 20px;
            padding-right: 16px;
        }

        .rwg-dropdown-title {
            font-family: 'Brown', 'Mulish', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            color: #1E120A;
            line-height: 1;
        }

        .rwg-dropdown-close {
            background: transparent;
            border: none;
            font-size: 1.45rem;
            line-height: 1;
            color: #1E120A;
            cursor: pointer;
            padding: 4px;
            transition: opacity 0.2s ease;
        }

        .rwg-dropdown-close:hover {
            opacity: 0.7;
        }

        .rwg-dropdown-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-radius: 16px;
            text-decoration: none !important;
            font-family: 'Brown', 'Mulish', sans-serif;
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            color: #1E120A !important;
            transition: background-color 0.2s ease;
        }

        .rwg-dropdown-item.active {
            background-color: #C89B50;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .rwg-dropdown-item:hover:not(.active) {
            background-color: rgba(200, 155, 80, 0.3);
        }

        .rwg-dropdown-item i {
            font-size: 1.35rem;
            color: #1E120A;
            font-weight: 900;
        }

        /* Interactive Stations on the Map */
        .rwg-checkpoint-node {
            position: absolute;
            text-decoration: none;
            display: block;
            cursor: pointer;
            transition: transform 0.2s ease;
            z-index: 25;
            filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.35));
            -webkit-tap-highlight-color: transparent;
        }

        .rwg-checkpoint-node:hover,
        .rwg-checkpoint-node:active {
            transform: scale(1.04);
            z-index: 35;
        }

        .rwg-checkpoint-img {
            width: 100%;
            height: auto;
            display: block;
            pointer-events: none;
            user-select: none;
            -webkit-user-select: none;
        }

        .rwg-complete-overlay {
            position: absolute;
            top: 0;
            left: 1.5%;
            width: 97%;
            height: 57%;
            border-radius: clamp(6px, 1.8vw, 9px) clamp(6px, 1.8vw, 9px) 0 0;
            background: rgba(0, 0, 0, 0.68);
            color: #ffffff;
            font-family: 'Brown', 'Mulish', sans-serif;
            font-weight: 800;
            font-size: clamp(0.7rem, 2.5vw, 0.85rem);
            letter-spacing: clamp(0.4px, 0.6vw, 0.8px);
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            pointer-events: none;
        }

        .rwg-pin-check-left,
        .rwg-pin-check-right {
            position: absolute;
            bottom: 2%;
            height: 34%;
            aspect-ratio: 1;
            background: #C82333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: clamp(0.8rem, 2.8vw, 1rem);
            font-weight: 900;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
            border: clamp(1.5px, 0.5vw, 2px) solid #ffffff;
            pointer-events: none;
        }

        .rwg-pin-check-left {
            left: 0;
        }

        .rwg-pin-check-right {
            right: 0;
        }

        /* Bottom Action Button */
        .rwg-dash-footer {
            position: absolute;
            top: 80.5%;
            left: 50%;
            transform: translateX(-50%);
            width: 84%;
            max-width: 330px;
            z-index: 40;
            text-align: center;
        }

        .rwg-dash-footer .rwg-btn-gold {
            font-size: clamp(0.85rem, 3.4vw, 1rem);
            letter-spacing: clamp(0.5px, 0.8vw, 1px);
            padding: clamp(0.75rem, 2.6vw, 0.9rem) clamp(1rem, 3.5vw, 2rem);
            white-space: nowrap;
        }

        @media (max-width: 380px) {
            .rwg-burger-dropdown {
                left: 0.75rem !important;
                right: 0.75rem !important;
                padding: 18px 14px !important;
            }

            .rwg-dropdown-header {
                padding-left: 16px;
                padding-right: 12px;
            }

            .rwg-dropdown-title {
                font-size: 1.2rem;
            }

            .rwg-dropdown-item {
                font-size: 1.1rem;
                padding: 14px 16px;
            }

            .rwg-dash-footer {
                width: 88%;
            }
        }

        /* Drawer / Offcanvas Customization */
        .offcanvas-rwg {
            background: rgba(22, 10, 36, 0.96) !important;
            backdrop-filter: blur(12px);
            color: #ffffff;
            border-left: 1px solid rgba(212, 149, 43, 0.3) !important;
        }

        .station-item {
            background: rgba(45, 20, 65, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: #ffffff;
            display: flex;
            align-items: center;
            padding: 12px 14px;
            gap: 12px;
        }

        .station-item:hover, .station-item:focus {
            transform: translateY(-2px);
            border-color: #D4952B;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
            color: #ffffff;
        }

        .station-item.completed {
            border-color: rgba(64, 163, 85, 0.6);
            background: rgba(30, 45, 35, 0.9);
        }

        .badge-status {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        .badge-completed {
            background: #2E7D32;
            color: #ffffff;
        }

        .badge-pending {
            background: rgba(212, 149, 43, 0.25);
            color: #F3C353;
            border: 1px solid rgba(212, 149, 43, 0.5);
        }
    </style>

    <div class="rwg-dashboard-wrapper">
        <div class="rwg-map-container" id="mapContainer">
            <!-- Top Header: RWG Red Logo & Menu Button -->
            <header class="rwg-dash-header">
                <img src="{{ asset('images/rwg/RWG RED_LOGO_2x.webp') }}"
                     alt="Resorts World Genting"
                     class="rwg-dash-logo"
                     width="200"
                     height="40"
                     fetchpriority="high"
                     decoding="async"
                     onclick="window.location.reload();" />
                
                <div class="dropdown rwg-menu-dropdown-wrapper">
                    <button type="button"
                            class="rwg-menu-btn"
                            id="rwgBurgerMenuBtn"
                            data-bs-toggle="dropdown"
                            data-bs-display="static"
                            aria-expanded="false"
                            aria-label="Toggle Menu">
                        <i class="fa-solid fa-bars text-white fs-5"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end rwg-burger-dropdown" aria-labelledby="rwgBurgerMenuBtn">
                        <div class="rwg-dropdown-header d-flex justify-content-between align-items-center mb-3">
                            <span class="rwg-dropdown-title">Menu</span>
                            <button type="button" class="rwg-dropdown-close" id="rwgCloseMenuBtn" aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('dashboard') }}" class="rwg-dropdown-item active">
                                <span>MY MAP</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="rwg-dropdown-item">
                                <span>MY PROFILE</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('faq') }}" class="rwg-dropdown-item">
                                <span>FAQ</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            @php
                $s1Done = in_array(1, $completedStationIds);
                $s2Done = in_array(2, $completedStationIds);
                $s3Done = in_array(3, $completedStationIds);
                $s4Done = in_array(4, $completedStationIds);
                $s5Done = in_array(5, $completedStationIds);
                $s6Done = in_array(6, $completedStationIds);
            @endphp

            <!-- ============================================== -->
            <!-- STATION 7 (GIFT REDEMPTION CHECKPOINT)         -->
            <!-- ============================================== -->
            @if($allCompleted)
                @if($isRedeemed)
                    <a href="{{ route('congrats') }}" class="rwg-checkpoint-node" style="top: 29.5%; left: 54.0%; width: 36.0%; z-index: 28;" title="Reward Claimed">
                        <img src="{{ asset('images/rwg/RWG_Checkpoint-Station 7_2x.webp') }}" class="rwg-checkpoint-img" alt="Gift Redemption">
                    </a>
                @else
                    <a href="#" data-bs-toggle="modal" data-bs-target="#redeemModal" class="rwg-checkpoint-node" style="top: 29.5%; left: 54.0%; width: 36.0%; z-index: 28;" title="Claim Gift">
                        <img src="{{ asset('images/rwg/RWG_Checkpoint-Station 7_2x.webp') }}" class="rwg-checkpoint-img" alt="Gift Redemption">
                    </a>
                @endif
            @else
                <div class="rwg-checkpoint-node"
                     style="top: 29.5%; left: 54.0%; width: 36.0%; z-index: 28; cursor: pointer;"
                     data-bs-toggle="modal"
                     data-bs-target="#giftLockedModal"
                     title="Gift Redemption (Locked)">
                    <img src="{{ asset('images/rwg/RWG_Checkpoint-Station 7_2x.webp') }}" class="rwg-checkpoint-img" alt="Gift Redemption">
                </div>
            @endif

            <!-- ============================================== -->
            <!-- STATION 6: SKYAVENUE                           -->
            <!-- ============================================== -->
            <a href="{{ route('station', 6) }}" class="rwg-checkpoint-node" style="top: 33.5%; left: 16.0%; width: 42.0%;" title="SkyAvenue (Near Level 1, Swensen's)">
                <img src="{{ asset('images/rwg/RWG_Checkpoint-Station 6_2x.webp') }}" class="rwg-checkpoint-img" alt="SkyAvenue">
                @if($s6Done)
                    <div class="rwg-complete-overlay">COMPLETE</div>
                    <div class="rwg-pin-check-right"><i class="fa-solid fa-check"></i></div>
                @endif
            </a>

            <!-- ============================================== -->
            <!-- STATION 5: PAVILION HALL                       -->
            <!-- ============================================== -->
            <a href="{{ route('station', 5) }}" class="rwg-checkpoint-node" style="top: 38.5%; left: 51.0%; width: 42.0%;" title="Pavilion Hall">
                <img src="{{ asset('images/rwg/RWG_Checkpoint-Station 5_2x.webp') }}" class="rwg-checkpoint-img" alt="Pavilion Hall">
                @if($s5Done)
                    <div class="rwg-complete-overlay">COMPLETE</div>
                    <div class="rwg-pin-check-left"><i class="fa-solid fa-check"></i></div>
                @endif
            </a>

            <!-- ============================================== -->
            <!-- STATION 4: KIMHUA ATRIUM                       -->
            <!-- ============================================== -->
            <a href="{{ route('station', 4) }}" class="rwg-checkpoint-node" style="top: 47.5%; left: 8.0%; width: 43.0%;" title="Kimhua Atrium">
                <img src="{{ asset('images/rwg/RWG_Checkpoint-Station 4_2x.webp') }}" class="rwg-checkpoint-img" alt="Kimhua Atrium">
                @if($s4Done)
                    <div class="rwg-complete-overlay">COMPLETE</div>
                    <div class="rwg-pin-check-right"><i class="fa-solid fa-check"></i></div>
                @endif
            </a>

            <!-- ============================================== -->
            <!-- STATION 3: GENTING SKYWORLDS THEME PARK        -->
            <!-- ============================================== -->
            <a href="{{ route('station', 3) }}" class="rwg-checkpoint-node" style="top: 52.5%; left: 51.0%; width: 42.0%;" title="Genting SkyWorlds Studio Plaza">
                <img src="{{ asset('images/rwg/RWG_Checkpoint-Station 3_2x.webp') }}" class="rwg-checkpoint-img" alt="Genting SkyWorlds">
                @if($s3Done)
                    <div class="rwg-complete-overlay">COMPLETE</div>
                    <div class="rwg-pin-check-left"><i class="fa-solid fa-check"></i></div>
                @endif
            </a>

            <!-- ============================================== -->
            <!-- STATION 2: ARENA OF STARS                      -->
            <!-- ============================================== -->
            <a href="{{ route('station', 2) }}" class="rwg-checkpoint-node" style="top: 61.5%; left: 8.0%; width: 38.0%;" title="Arena of Stars">
                <img src="{{ asset('images/rwg/RWG_Checkpoint-Station 2_2x.webp') }}" class="rwg-checkpoint-img" alt="Arena of Stars">
                @if($s2Done)
                    <div class="rwg-complete-overlay">COMPLETE</div>
                    <div class="rwg-pin-check-right"><i class="fa-solid fa-check"></i></div>
                @endif
            </a>

            <!-- ============================================== -->
            <!-- STATION 1: MADISON SQUARE                      -->
            <!-- ============================================== -->
            <a href="{{ route('station', 1) }}" class="rwg-checkpoint-node" style="top: 66.5%; left: 50.0%; width: 42.0%;" title="Madison Square">
                <img src="{{ asset('images/rwg/RWG_Checkpoint-Station 1_2x.webp') }}" class="rwg-checkpoint-img" alt="Madison Square">
                @if($s1Done)
                    <div class="rwg-complete-overlay">COMPLETE</div>
                    <div class="rwg-pin-check-left"><i class="fa-solid fa-check"></i></div>
                @endif
            </a>

            <!-- ============================================== -->
            <!-- BOTTOM CTA BUTTON: EXPLORE THE EXCITEMENT      -->
            <!-- ============================================== -->
            <div class="rwg-dash-footer">
                @if($allCompleted && !$isRedeemed)
                    <button type="button" class="rwg-btn-gold" data-bs-toggle="modal" data-bs-target="#redeemModal">
                        EXPLORE THE EXCITEMENT
                    </button>
                @elseif($allCompleted && $isRedeemed)
                    <a href="{{ route('congrats') }}" class="rwg-btn-gold text-decoration-none">
                        EXPLORE THE EXCITEMENT
                    </a>
                @else
                    <button type="button" class="rwg-btn-gold" data-bs-toggle="offcanvas" data-bs-target="#stationDrawer">
                        EXPLORE THE EXCITEMENT
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- STATION CHECKPOINTS DRAWER (BOTTOM / SIDE)                     -->
    <!-- ============================================================== -->
    <div class="offcanvas offcanvas-bottom offcanvas-rwg h-auto" tabindex="-1" id="stationDrawer" aria-labelledby="stationDrawerLabel" style="max-height: 85vh; border-top-left-radius: 24px; border-top-right-radius: 24px; border-top: 2px solid rgba(212, 149, 43, 0.4) !important;">
        <div class="offcanvas-header border-bottom border-white-50 py-3 px-4">
            <div>
                <h5 class="offcanvas-title text-white fw-bold mb-0">Checkpoints</h5>
                <span class="small text-warning fw-semibold">Treasure Hunt Progress: {{ $completedCount }} / {{ $totalStations }} Stations</span>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4 overflow-y-auto">
            <!-- Progress Bar -->
            <div class="progress mb-4" style="height: 10px; background-color: rgba(255, 255, 255, 0.15); border-radius: 8px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                     style="width: {{ round(($completedCount / max(1, $totalStations)) * 100) }}%; background-color: #D4952B;" 
                     aria-valuenow="{{ $completedCount }}" aria-valuemin="0" aria-valuemax="{{ $totalStations }}">
                </div>
            </div>

            <!-- Redemption CTA if eligible -->
            @if($allCompleted)
                <div class="p-3 mb-4 rounded-4 text-center" style="background: rgba(212, 149, 43, 0.2); border: 1.5px solid #D4952B;">
                    <h6 class="text-warning fw-bold mb-2">🎉 All 6 Stations Completed!</h6>
                    @if($isRedeemed)
                        <a href="{{ route('congrats') }}" class="rwg-btn-gold py-2 text-decoration-none">
                            VIEW REWARD CONFIRMATION
                        </a>
                    @else
                        <button type="button" class="rwg-btn-gold py-2" data-bs-toggle="modal" data-bs-target="#redeemModal">
                            CLAIM YOUR GIFT NOW
                        </button>
                    @endif
                </div>
            @endif

            <!-- 6 Stations List -->
            <div class="d-flex flex-column gap-3 mb-3">
                @foreach($stations as $station)
                    @php
                        $isDone = in_array($station->id, $completedStationIds);
                        $checkpointIcon = asset('images/rwg/RWG_Checkpoint-Station ' . $station->id . '_2x.webp');
                    @endphp
                    <a href="{{ route('station', $station->id) }}" class="station-item {{ $isDone ? 'completed' : '' }}">
                        <img src="{{ $checkpointIcon }}" alt="{{ $station->name }}" width="50" height="50" loading="lazy" decoding="async" style="width: 50px; height: 50px; object-fit: contain;" />
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold" style="font-size: 0.95rem;">
                                    Station {{ $station->id }}: {{ $station->name }}
                                </span>
                                @if($isDone)
                                    <span class="badge-status badge-completed">
                                        <i class="fa-solid fa-check me-1"></i> STAMPED
                                    </span>
                                @else
                                    <span class="badge-status badge-pending">
                                        CHECK IN
                                    </span>
                                @endif
                            </div>
                            <div class="small text-white-50" style="font-size: 0.82rem; line-height: 1.3;">
                                {!! $station->description !!}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- GIFT REDEMPTION LOCKED MODAL                                   -->
    <!-- ============================================================== -->
    <div class="modal fade" id="giftLockedModal" tabindex="-1" aria-labelledby="giftLockedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 340px; width: 90%; margin-left: auto !important; margin-right: auto !important;">
            <div class="modal-content rounded-4 border-0 p-4 shadow-lg text-center" style="background: #ffffff !important; color: #1E0B2B !important; margin: 0 auto !important; border-radius: 24px !important;">
                <div class="modal-body p-0">
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px; border: 2.5px solid #d4952b; background: rgba(212, 149, 43, 0.08); color: #d4952b; margin: 0 auto;">
                            <i class="fa-solid fa-gift" style="font-size: 26px;"></i>
                        </div>
                    </div>

                    <h4 class="fw-bold mb-3" style="font-size: 1.25rem; color: #1E0B2B !important;">
                        Gift Redemption Locked
                    </h4>

                    <p class="mb-4 text-center" style="color: #4A5568 !important; font-size: 0.95rem; line-height: 1.5; font-weight: 500; display: block !important;">
                        Complete all 6 stations to unlock Gift Redemption!
                    </p>

                    <button type="button" class="rwg-btn-gold border-0 w-100 py-2" data-bs-dismiss="modal">
                        GOT IT
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- REDEMPTION MODAL                                               -->
    <!-- ============================================================== -->
    <div class="modal fade" id="redeemModal" tabindex="-1" aria-labelledby="redeemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-dark">
            <div class="modal-content text-center p-3" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="mb-3">
                        <i class="fa-solid fa-gift fa-3x" style="color: #D4952B;"></i>
                    </div>
                    <h4 class="modal-title font-weight-bold mb-2">Claim Your Gift</h4>
                    <p class="text-muted small mb-4">
                        Congratulations on completing all 6 stations across Resorts World Genting! Confirm below to claim your exclusive Winter Wonderland gift.
                    </p>

                    <div id="redeemMsg" class="mb-3"></div>

                    @if($isDailyQuotaFull)
                        <div class="alert alert-warning py-2 px-3 small">
                            Today's daily quota of 300 gifts has been reached. Please check back tomorrow!
                        </div>
                    @else
                        <button type="button" id="confirmRedeemBtn" class="rwg-btn-gold py-2 w-100">
                            CONFIRM & REDEEM GIFT
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- FAQ MODAL                                                      -->
    <!-- ============================================================== -->
    <div class="modal fade" id="faqModal" tabindex="-1" aria-labelledby="faqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable text-dark">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="faqModalLabel">Campaign FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body small">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading1">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1">
                                    1. Campaign Overview
                                </button>
                            </h2>
                            <div id="faqCollapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    The Resorts World Genting Winter Wonderland 2026 Treasure Hunt is an interactive digital trail across 6 locations. Open to all guests. No mobile app download required—participate directly in your phone browser.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading2">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2">
                                    2. Game Mechanics
                                </button>
                            </h2>
                            <div id="faqCollapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    Visit all 6 physical stations across Resorts World Genting in any order:
                                    <ol class="mt-2 mb-0">
                                        <li>Madison Square</li>
                                        <li>Arena of Stars</li>
                                        <li>Genting SkyWorlds Theme Park – Studio Plaza</li>
                                        <li>Kimhua Atrium</li>
                                        <li>Pavilion Hall – Near Skyropolis Indoor Theme Park</li>
                                        <li>SkyAvenue – Near Level 1, Swensen's</li>
                                    </ol>
                                    Scan each QR code using this website to collect stamps.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading3">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3">
                                    3. Camera & QR Code Scanning
                                </button>
                            </h2>
                            <div id="faqCollapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    Select any station and tap "SCAN QR CODE". When prompted, allow camera permissions in Chrome (Android) or Safari (iOS). If camera access is blocked, check your browser site settings to enable camera permissions.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading4">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4">
                                    4. Registration, Login & Entry Limits
                                </button>
                            </h2>
                            <div id="faqCollapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    Register with your Full Name, Email, Date of Birth, and optional Genting Rewards (GRC) Member ID. One redemption per participant. Your progress is saved automatically and restored whenever you log in with your email.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading5">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5">
                                    5. Prize Redemption
                                </button>
                            </h2>
                            <div id="faqCollapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    Once all 6 stations are completed, gift redemption is unlocked. Redemptions are capped at 300 gifts per day on a first-come, first-served basis. If the daily limit is exhausted, participants may return the following day while campaign supplies last.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- TERMS MODAL                                                    -->
    <!-- ============================================================== -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable text-dark">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body small">
                    <p>The Resorts World Genting Winter Wonderland 2026 Treasure Hunt is organized by Resorts World Genting. Open to all registered guests. Gifts are subject to a daily limit of 300 redemptions. Non-transferable and cannot be exchanged for cash.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown menu close button
            const closeMenuBtn = document.getElementById('rwgCloseMenuBtn');
            const burgerBtn = document.getElementById('rwgBurgerMenuBtn');
            if (closeMenuBtn && burgerBtn) {
                closeMenuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const dd = bootstrap.Dropdown.getInstance(burgerBtn) || new bootstrap.Dropdown(burgerBtn);
                    dd.hide();
                });
            }

            const confirmBtn = document.getElementById('confirmRedeemBtn');
            const msgBox = document.getElementById('redeemMsg');

            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    confirmBtn.disabled = true;
                    confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
                    msgBox.innerHTML = '';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('{{ route("giftselection.redeem") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            msgBox.innerHTML = '<span class="text-success fw-bold">' + data.message + '</span>';
                            setTimeout(() => {
                                window.location.href = data.redirect || '{{ route("congrats") }}';
                            }, 1000);
                        } else {
                            confirmBtn.disabled = false;
                            confirmBtn.innerHTML = 'CONFIRM & REDEEM GIFT';
                            msgBox.innerHTML = '<span class="text-danger fw-bold">' + (data.message || 'Redemption failed.') + '</span>';
                        }
                    })
                    .catch(err => {
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = 'CONFIRM & REDEEM GIFT';
                        msgBox.innerHTML = '<span class="text-danger">Network error. Please try again.</span>';
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
