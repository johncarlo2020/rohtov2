<x-guest-layout>
       <div class="otp-success">
        <div class="justify-content-center w-100 main-content with-scroll">
            <div class="col-12 d-flex justify-content-center animate-entry ">
                @include('components.branding')
            </div>
             <div class="col-12 px-0 text-center py-4">
                <h1 class="welcome-title mb-0">
                    <span>FACE</span>
                    <span>EVERYTHING</span>
                </h1>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center p-0">
                <img class="welcome_img_store w-90" src="{{ asset('images/brand/nars_landing_page.webp') }}"
                    alt="" />
            </div>
             <div class="col-12 px-0 text-center py-4">
                <p class="text-white px-4">Put it on. Don’t look back.
                Skin that’s free to face it all.​
</p>
            </div>
            <div class="text-center col-12 ">
                <div class="d-block">
                    <div class="col text-center animate-entry delay-3">
                        <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary">DISCOVER NOW</a>
                    </div>
                </div>
            </div>
        </div>
       </div>
</x-guest-layout>
