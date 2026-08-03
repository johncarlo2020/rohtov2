<x-app-layout>
    <div class="container-fluid congrats start completed-screen main-content main-background with-scroll pt-4">
        <div class="congrats-container">
                    {{-- branding --}}
                    <div class="animate-entry">
                        @include('components.branding')
                    </div>
                     {{-- success container (TOP) --}}
                    <div class="success-content">
                        <div class="mb-5 d-flex justify-content-center">
                            <img src="{{ asset('images/check.png') }}" class="check-img">
                        </div>
                        <h2 class="text-center5">Redeemed successfully!</h2>
                    </div>
                    {{-- home button --}}
                    <div class="col-8 colanimate-entry delay-2 mt-4 mb-5">
                        <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary pulse-slow">
                            HOME
                        </a>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
