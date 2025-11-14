<x-app-layout>
    <div class="container-fluid congrats start completed-screen main-content main-background with-scroll pt-4">
        <div class="congrats-container">
                <div class="product-image px-5 text-center">
                    <div class="mb-2">
                        <span class="sub-heading-text">Click on the logo</span>
                    </div>
                    <div class="row">
                        <a href="https://www.behnmeyer.com/" class="mt-5">
                            <img class="logo "
                                src="{{ asset('images/brand/logo_congrats.webp') }}" alt="bm Logo" />
                        </a>
                    </div>
                    
                    <div class="mt-2">
                        <span class="sub-heading-text">for more information</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer/>
</x-app-layout>
