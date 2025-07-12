<x-app-layout>
    <div class="antialiased main-background h-100 ipad-page">
        <div class="py-3 container-fluid main-content">
            <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center animate-entry">
                    <div class="branding">
                        <img class="logo" src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" />
                    </div>
                </div>
                <div class="text-center col-12 mt-5">
                    <div class="d-block">
                        <h2 class="text-center animate-entry heading">Total Pledge</h2>
                        <x-counter />
                        <h2 class="text-center animate-entry mb-2 heading"><strong>Start Pledge</strong></h2>
                        <p class="mb-4">Pledge for the Blue</p>
                        <div class="col mb-3 animate-entry delay-2">
                            <a href="{{ route('ipad.info') }}" class="custom-btn custom-btn-secondary">START</a>
                        </div>
                    </div>
                </div>
            </div>
            <x-footer />
        </div>
    </div>
</x-app-layout>
