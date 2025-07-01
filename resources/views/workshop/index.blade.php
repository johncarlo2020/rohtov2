<x-guest-layout>
    <div class="workshop-main with-scroll">
           <div class="back-btn">
            <a href="{{ route('dashboard') }}" class="">
                <img src="{{ asset('images/dutchlady/back-btn.webp') }}" alt="Back" />
            </a>
        </div>
        <div class="justify-content-center">
            <div class="col-12 d-flex justify-content-center mt-5">
                @include('components.branding')
            </div>
            <div class="col-12 mt-5 px-4">
                <h1 class="heading-dutch text-center">Workshop</h1>
                <p class="p-2 m-0 text-blue bg-yellow text-blue text-center"><strong>5th July (Sat) & 6th July (Sun)</strong></p>
                <div class="col mt-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="img-contaner-1">
                                <img src="{{ asset('images/dutchlady/diyBentoWorkshopImg.png') }}"
                                    class="station-img img-fluid" alt="Route Workshop">
                            </div>
                        </div>
                        <div class="col">
                            <p class="p-2 m-0 text-white workshop-time-text"><strong>2:00 pm - 3:00pm</strong></p>
                            <br>
                            <p class="p-2 m-0 text-white workshop-time-text"><strong>7:00 pm - 8:00pm</strong></p>
                        </div>
                    </div>
                </div>
                <div class="workshop-hr-container">
                    <hr class="workshop-hr">
                </div>
                 <div class="col mb-5">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="img-contaner-1">
                                <img src="{{ asset('images/dutchlady/sipAndPaintImg.png') }}"
                                    class="station-img img-fluid" alt="Route Workshop">
                            </div>
                        </div>
                        <div class="col ">
                            <p class="p-2 m-0 text-white workshop-time-text"><strong>4:00 pm - 5:00pm</strong></p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center text-center">
                <x-primary-button onclick="window.location='{{ route('workshop.register') }}'" class="main-btn button-dutch button-dutch-primary fit-content">
                        Continue
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
