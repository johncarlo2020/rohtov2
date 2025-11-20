<x-app-layout>
    <style>
    .referral-card {
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }

    .referral-card.active {
        border-color: #28a745; /* highlight color */
        background-color: #d4edda;
    }

    .tier {
        margin-top: 10px;
        padding: 10px;
        border-radius: 8px;
        color: white;
    }

    .tier1 {
        background-color: #28a745;
    }

    .tier2 {
        background-color: #6c757d;
    }

    .tier span {
        font-weight: bold;
        font-size: 1.2rem;
    }

    .tap-copy-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .tap-copy-btn:hover {
        background-color: #f8f9fa;
    }

    img.tiers-ico {
        width: auto;
        height: 45px;
        width: 40%;
        object-fit: contain;
        margin: auto;
    }

    </style>

    <div class="with-scroll py-4 map-page {{ request()->segment(2) == 1 ? 'weekday-background' : (request()->segment(2) == 2 ? 'weekend-background' : 'main-background') }}" data-id="{{ request()->segment(2) }}">
        <div class="animate-entry">
            @include('components.branding')
        </div>
            @php
                $type = request()->segment(2) == 1
                    ? 'weekday'
                    : (request()->segment(2) == 2 ? 'weekend' : 'referral');

                $image = "images/brand/{$type}_hero.png";

                $alt   = request()->segment(2) == 1
                    ? 'Weekday Img'
                    : (request()->segment(2) == 2 ? 'Weekend Img' : '');
            @endphp
        <div class="hero mt-4 animate-entry">
            <img class="w-100" src="{{ asset($image) }}" alt="{{ $alt }}">
        </div>
        <div class="main-content">
        <!-- login Modal -->
                <div class="mb-2 ">
                    <!-- Center image (middle area) -->
                    <div class="row">
                        <div class="col-12  text-center my-4 p-0 animate-entry">
                            <h2>Refer a friend and get a gift</h2>
                            <p>Share your code with your friends , You will receive an exclusive gift</p>
                        </div>
                    </div>

                    <!-- Referral Code -->
                       <div class="row animate-entry">
                            <div class="col-8 pe-1">
                                <a href="#">
                                    <div class="card p-2 text-center">
                                        4SEASONSSHOPPES <br>
                                        <small> Tap to copy</small>
                                    </div>
                                </a>
                            </div>
                            <div class="col-4 ps-1">
                                <div class="card p-2 text-center">
                                    <span>0</span>
                                    <small>Total Referral</small>
                                </div>
                            </div>
                       </div>


                    <!-- Tiers -->
                     <div class="row mb-3 animate-entry delay-2">
                        <div class="col-6 pe-1">
                            <div class="tier tier1 referral-card" id="tier1">
                                <a href="{{ route('reward.index', ['reward' => 3]) }}">
                                    <img class="tiers-ico mb-2" src="{{ asset('images/brand/tier1.webp');}}" alt="">
                                    <div>Tier 1</div>
                                    <div><span>0/1</span></div>
                                </a>
                            </div>
                        </div>
                            <div class="col-6 ps-1">
                                <div class="tier tier2 referral-card" id="tier1">
                                    <a href="{{ route('reward.index', ['reward' => 3]) }}">
                                        <img class="tiers-ico mb-2" src="{{ asset('images/brand/tier2.webp');}}" alt="">
                                        <div>Tier 2</div>
                                        <div><span>0/5</span></div>
                                    </a>
                                </div>
                        </div>
                     </div>
    
                    <!-- Bottom CTA -->
                    <div class="row animate-entry">
                        <div class="col-12 text-center">
                            <div class="d-block">
                                <div class="col mb-3 animate-entry delay-2">
                                    <button type="button" class="custom-btn custom-btn-primary"
                                        onclick="window.location.href='{{ route('dashboard') }}'">
                                        Back
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-footer/>
        </div>
    </div>
    <script>
    document.getElementById('openModalBtn').addEventListener('click', function () {
        // Initialize modal
        var modalEl = document.getElementById('qrModal');
        var myModal = new bootstrap.Modal(modalEl);
        myModal.show();

        // Close button in footer
        modalEl.querySelector('.close').addEventListener('click', function () {
            myModal.hide(); // hides modal
            removeBackdrop();
        });

        // Also remove backdrop if somehow stuck
        modalEl.addEventListener('hidden.bs.modal', function () {
            removeBackdrop();
        });

        function removeBackdrop() {
            document.querySelectorAll('.modal-backdrop').forEach(function (el) {
                el.remove();
            });
        }
    });
    </script>

</x-app-layout>
