<x-app-layout>
    <style>
        .check_img
        {
            Width: 70px;
            Height: auto;
        }
    </style>
    <div class="container-fluid congrats start completed-screen main-content main-background with-scroll pt-4">
        <div class="congrats-container">
                <div class="brand-container d-flex justify-content-center animate-entry">
                    @include('components.branding')
                </div>            
    
                <div class="row d-flex justify-content-center align-center gap-3">
                    <img class="check_img animate-entry" src="{{ asset('images/brand/check_img.png') }}" alt="">
                    <h5 class="sub-heading-text animate-entry text-center">REDEMPTION DONE ! <br> THANK YOU</h5>
                </div>

                <div class="mt-2">
                    <a href="{{ route('congrats') }}" class="custom-btn custom-btn-primary w-100 px-5 mx-auto animate-entry">
                        FINISH
                    </a>
                </div>
        </div>
    </div>
    </div>
</x-app-layout>
