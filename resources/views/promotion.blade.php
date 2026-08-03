<x-guest-layout>
    <div class="promotion-main with-scroll">
        <div class="back-btn">
            <a href="{{ route('dashboard') }}" class="">
                <img src="{{ asset('images/dutchlady/back-btn.webp') }}" alt="Back" />
            </a>
        </div>
        <div class="justify-content-center">
            <div class="col-12 d-flex justify-content-center mt-5">
                @include('components.branding')
            </div>
            <div class="col-12 px-4">
                <h1 class="heading-dutch text-center curve">Promotion</h1>
                <div class="col">
                    <img src="{{ asset('images/dutchlady/dutchLadyWelcomeModal.webp') }}" alt="Welcome" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
