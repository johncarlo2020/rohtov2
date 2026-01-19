<x-app-layout>
    <div class="container-fluid congrats start completed-screen main-content main-background with-scroll pt-4">
        <div class="congrats-container">
            <div class="product-image text-center">
                <div class="text-center animate-entry mt-3" style="margin-bottom:20vh;">
                    <div class="animate-entry">
                        @include('components.branding')
                    </div>
                    <h2 class="text-center fw-bold my-5">YSL BEAUTY LIGHT CLUB</h2>
                </div>
                <div class="row text-center">
                    <span>Visit</span>
                    <a href="https://www.yslbeauty.com.my/en_MY/fragrance/" class="my-1 animate-entry">
                            <img class="logo w-50 m-auto"
                                src="{{ asset('images/brand/logo.webp') }}" alt="Dutchlady Omega Logo" />
                        </a>
                    <span>for more information</span>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
