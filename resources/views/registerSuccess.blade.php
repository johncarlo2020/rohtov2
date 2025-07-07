<x-guest-layout>
    <body class="antialiased welcome-page">
    <div class="justify-content-center w-100 px-3">
        <div class="my-5 col-12 d-flex justify-content-center">
            @include('components.branding')
        </div>
        <div class="otp-form-container bg-white px-3 py-4 rounded">
           
              
                <div class="text-center mb-4 px-1">
                    <h2 class="heading-text text-dark text-center mb-2">Hi, {{ auth()->check() && isset(auth()->user()->fname) ? auth()->user()->fname : 'Guest' }}!</h2>
                    <p class="sub-heading-text text-dark text-center">Thank you for your time. Your <br> Registration is now complete.</p>
                </div>
                <button type="button" class="w-100 custom-btn custom-btn-primary animate-entry delay-3" onclick="window.location='{{ route('login') }}'">
    Start Your Journey Now
</button>   
           
        </div>
        <x-footer />
    </div>
</x-guest-layout>
