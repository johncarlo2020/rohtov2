<x-app-layout>
    <div class="antialiased pledge-wall-background h-100 ipad-page">
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
                    </div>
                </div>
            </div>
            <x-footer />
        </div>
    </div>
</x-app-layout>
