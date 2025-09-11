<x-guest-layout>
       <div class="otp-success">
        <div class="justify-content-center w-100 main-content with-scroll">
            <div class="col-12 d-flex justify-content-center animate-entry ">
                @include('components.branding')
            </div>
             <div class="col-12 px-0 text-center py-4">
                <p class="text-black"><span class="text-black">MONOCHROME</span> . <span class="text-black">MINIMALIST</span> . <span class="text-black">THE MULTIPLE</span></p>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center p-0">
                <img class="welcome_img_store w-100" src="{{ asset('images/brand/nars_landing_page.webp') }}"
                    alt="" />
            </div>
             <div class="col-12 px-0 text-center py-4">
                <p class="text-black px-4">NARS iconic Multiple is reimagined in a new, next-level formula. 
Discover 12 vibrant, versatile shades designed to be used across cheeks, lips and eyes.
</p>
            </div>
            <div class="text-center pt-4 col-12 ">
                <div class="d-block">
                    <div class="col text-center animate-entry delay-3">
                        <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-secondary">DISCOVER NOW</a>
                    </div>
                </div>
            </div>
        </div>
       </div>
</x-guest-layout>
