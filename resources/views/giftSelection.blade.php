<x-app-layout>
    <style>

    .gift-card {
      background-color: #a5e5e7;
      border-radius: 20px;
      border: none;
      padding: 10px 60px;
      cursor: pointer;
      transition: transform 0.2s, background-color 0.2s;
    }

    .gift-card:hover {
      transform: scale(1.05);
      background-color: #92dadc;
    }

    .gift-card img {
      width: 100px;
      height: 100px;
      object-fit:contain;
    }

    .btn-proceed {
      background-color: #a89efc;
      color: white;
      font-weight: 600;
      border-radius: 30px;
      padding: 10px;
      width: 100%;
      border: none;
      transition: background-color 0.3s;
    }

    .btn-proceed:hover {
      background-color: #0000e6;
    }

    .gift-card.active {
      outline: 2px solid #0000e6;
      background-color: #90d8da;
    }
  </style>

    <div id="stationPage" class="station-page main-content main-background with-scroll">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="check mx-auto mb-4" id="badge" src="">
                            <div class="text-content mt-0">
                                <p class="sub-heading fw-bold mb-2 station-text "><span class="station_name text-dark"></span></p>
                                <p class="mb-4 message text-grey">
                                    Check-In Successful
                                </p>
                            </div>
                            <div class="text-content mt-3">
                                <a href="{{ route('dashboard') }}" id="routeBtn"
                                    class="custom-btn w-auto px-5 fw-regular custom-btn-primary text-white">
                                    Back
                                </a>
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
            <h1 class="heading mb-3 mt-3 text-dark">Select your gift </h1>
                <p class="sub-heading mb-1 fw-thin text-dark">
                    
                </p>@php
      $gifts = [
          ['id' => 1, 'image' => 'images/brand/gift_1.webp'],
          ['id' => 2, 'image' => 'images/brand/gift_2.webp'],
          ['id' => 3, 'image' => 'images/brand/gift_3.webp'],
      ];
  @endphp

  <div class="d-flex flex-column gap-4 mb-5">
      @foreach ($gifts as $gift)
          <div class="card gift-card mx-auto" data-id="{{ $gift['id'] }}">
              <div class="card-body d-flex justify-content-center align-items-center">
                  <img src="{{ asset($gift['image']) }}" class="img-fluid gifts" alt="Gift {{ $gift['id'] }}">
              </div>
          </div>
      @endforeach
  </div>
                <button id="start-scanner" 
                    class="custom-btn custom-btn-primary mx-auto"
                    title="Start Scanner"
                    disabled>
                    PROCEED
                </button>

            <div id="" class="icon-container">
            </div>
        </div>
        <div id="scannerContainer" class="scanner-container d-none mt-4">
            <!-- <button id="close" class="mx-auto mt-4 camera-btn">x</button> -->
            <div id="reader"></div>
            <p class="mt-4 scanner-text text-center text-dark">Find the QR code & Scan to check in</p>
            <div class="text-center my-3">
                <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary">
                    BACK
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
        <script>
            const cards = document.querySelectorAll('.gift-card');
            const proceedBtn = document.getElementById('start-scanner');
            let selectedGiftId = null;

            cards.forEach(card => {
                card.addEventListener('click', () => {
                cards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                selectedGiftId = card.dataset.id;
                proceedBtn.disabled = false;
                });
            });
        </script>
        <script>
            // Pass data from Blade to JavaScript
            window.stationConfig = {
                urls: {
                    process_qr_code: '{{ route('process_qr_code') }}',
                    congrats: '{{ route('congrats') }}'
                },
                assets: {
                    check_image: '{{ asset('images/check.png') }}',
                    error_image: '{{ asset('images/error.png') }}'
                },
            };
        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>
