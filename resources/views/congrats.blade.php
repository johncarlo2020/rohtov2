<x-app-layout>
    <style>
    .congrats-container {
        row-gap: clamp(16px, 5vh, 20px);
    }
    </style>
    <div class="container-fluid congrats start completed-screen main-content main-background with-scroll pt-4">
        <div class="congrats-container d-flex justify-content-center align-center">         
                <div class="row">
                    <h5 class="sub-heading-text animate-entry text-center">Thank You! <br> Visit our website</h5>
                </div>

                <div class="brand-container d-flex justify-content-center animate-entry">
                    @include('components.branding')
                </div>   
        </div>
    </div>
    </div>
</x-app-layout>
