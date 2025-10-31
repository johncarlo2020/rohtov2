<x-guest-layout>
       <div class="otp-success">
        <div class="justify-content-center w-100 px-3 main-content with-scroll">
            <div class="my-5 col-12 d-flex justify-content-center animate-entry ">
                @include('components.branding')
            </div>
            <div class="card px-4 py-4 rounded animate-entry delay-3">
                <div class="text-center mb-4 px-1">
                    <h2 class="heading text-center mb-2">Hi,
                      <span class="fw-bold">{{ auth()->check() && isset(auth()->user()->name) ? auth()->user()->name : 'Guest' }}!</span></h2>
                    <p class="text-center">Thank you for your time. Your <br> Registration is
                        now complete.</p>
                </div>
                <button type="button" class="w-100 custom-btn custom-btn-primary text-transform-normal fw-normal"
                    onclick="window.location='{{ route('dashboard') }}'">
                    Start Your Journey Now
                </button>
            </div>
        </div>
       </div>
</x-guest-layout>
