<x-app-layout>
    <div class="antialiased pledge-wall-backgroundx h-100 ipad-page">
        <div class="py-3 container-fluid main-content">
            <div class="row">
                <div class="col-12 d-flex justify-content-end align-items-center animate-entry">
                    <div class="branding w-50 d-flex justify-content-end">
                        <img class="logo w-25" src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" />
                    </div>
                </div>
                <div class="text-center col-12 mt-5">
                    <div class="d-block">
                        <x-counter />
                    </div>
                </div>
            </div>
            <x-footer />
        </div>
    </div>
</x-app-layout>
