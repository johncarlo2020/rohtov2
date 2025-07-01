<x-guest-layout>
    <div class="workshop-main">
        <div class="justify-content-center">
            <div class="col-12 d-flex justify-content-center mt-5">
                @include('components.branding')
            </div>
            <div class="col-12 mt-5 px-4">
                <h1 class="heading-dutch text-center">Workshop</h1>

                <div class="col mt-5">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="img-contaner-1">
                                <img src="{{ asset('images/dutchlady/diyBentoWorkshopImg.png') }}"
                                    class="station-img img-fluid" alt="Route Workshop">
                            </div>
                        </div>
                        <div class="col ">
                            <p>2:00 pm - 3:00pm</p><br>
                            <p>2:00 pm - 3:00pm</p>
                        </div>
                    </div>
                </div>
                <hr>
                 <div class="col mb-5">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="img-contaner-1">
                                <img src="{{ asset('images/dutchlady/sipAndPaintImg.png') }}"
                                    class="station-img img-fluid" alt="Route Workshop">
                            </div>
                        </div>
                        <div class="col ">
                            <p>2:00 pm - 3:00pm</p><br>
                            <p>2:00 pm - 3:00pm</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end">
                <x-primary-button onclick="window.location='{{ route('workshop.register') }}'" class="main-btn button-dutch button-dutch-primary">
                        Continue
                    </x-primary-button>
                </div>
                <div class="bottom-text">
                    <a class="footer-text" href="https://wowsome.com.my/">Powered by WOWSOME®2025</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
