<x-app-layout>
    <div class="container-fluid congrats start completed-screen main-content main-background with-scroll pt-4">
        <div class="congrats-container">
            <div class="product-image text-center">
                <div class="text-center animate-entry mt-3">
                    <!-- TOP TEXT -->
                    <svg viewBox="0 0 600 90" width="100%" height="60">
                        <path
                        id="archTop"
                        d="M 60 90 Q 300 20 540 90"
                        fill="transparent"
                        />
                        <text
                        font-size="32"
                        font-weight="950"
                        fill="#ff7a00"
                        stroke="#ffffff"
                        stroke-width="7"
                        paint-order="stroke"
                        text-anchor="middle"
                        >
                        <textPath href="#archTop" startOffset="50%">
                            Terima Kasih
                        </textPath>
                        </text>
                    </svg>

                    <!-- MIDDLE TEXT -->
                    <svg viewBox="0 0 600 90" width="100%" height="60" style="margin-top:-35px;">
                        <path
                        id="archBottom"
                        d="M 60 90 Q 300 20 540 90"
                        fill="transparent"
                        />
                        <text
                        font-size="32"
                        font-weight="950"
                        fill="#ff7a00"
                        stroke="#ffffff"
                        stroke-width="7"
                        paint-order="stroke"
                        text-anchor="middle"
                        >
                        <textPath href="#archBottom" startOffset="50%">
                            Anda telah menyelasaikan
                        </textPath>
                        </text>
                    </svg>

                    <!-- BOTTOM TEXT -->
                    <svg viewBox="0 0 600 90" width="100%" height="50" style="margin-top:-30px;">
                        <path
                        id="archBottom"
                        d="M 60 90 Q 300 20 540 90"
                        fill="transparent"
                        />
                        <text
                        font-size="32"
                        font-weight="950"
                        fill="#ff7a00"
                        stroke="#ffffff"
                        stroke-width="7"
                        paint-order="stroke"
                        text-anchor="middle"
                        >
                        <textPath href="#archBottom" startOffset="50%">
                            perjalanan anda
                        </textPath>
                        </text>
                    </svg>
                </div>
                <div class="row">
                    <a href="https://www.dutchladyomega.com/" class="my-5 animate-entry">
                            <img class="logo w-50 m-auto"
                                src="{{ asset('images/brand/logo.webp') }}" alt="Dutchlady Omega Logo" />
                        </a>
                </div>

                <div class="mt-2">
                    <a href="{{ route('dashboard') }}" class="custom-btn custom-btn-primary w-50 mx-auto animate-entry">
                        SELESAI
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <x-footer />
</x-app-layout>
