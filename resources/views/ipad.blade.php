<x-app-layout>
<body class="antialiased main-background">
    <div class="py-3 container-fluid main-content with-scroll">
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center animate-entry">
                @include('components.branding')
            </div>
            <div class="text-center col-12 mt-5">
                <div class="d-block">
                    <h2 class="text-center animate-entry"><strong>Total Pledge</strong></h2>
                    <!-- <div class="col mb-3 animate-entry delay-2">
                        <a href="{{ route('register') }}" class="custom-btn custom-btn-secondary">Sign Up</a>
                    </div>
                    <div class="col text-center login-text animate-entry delay-3">
                        <p class="d-block">Already Registered</p>
                        <p>Please Login <a href="{{ route('login') }}">here</a></p>
                    </div> -->
                    <x-counter />
                    <h2 class="text-center animate-entry mb-2"><strong>Start Pledge</strong></h2>
                    <p class="mb-4">Pledge for the Blue</p>
                    <div class="col mb-3 animate-entry delay-2">
                        <a href="{{ route('ipad.info') }}" class="custom-btn custom-btn-secondary">START</a>
                    </div>
                </div>
            </div>
        </div>
        <x-footer />
    </div>
    <x-scriptPackages />
</x-app-layout>
