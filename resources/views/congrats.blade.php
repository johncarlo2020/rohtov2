<x-app-layout>
    <div class="container-fluid home start completed-screen pt-4">
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
        <div class="congrats-container">
            <h1 class="text-center main-color font-medium mb-2">Visit</h1>
            <div class="product-image mb-3 px-5">
                <a href="https://hadalabo.com.my">

                <img class="" src="{{ asset('images/congrats.webp') }}" alt="" />

                </a>
            </div>
           <p class="text-center main-color font-medium mb-3 ">
             for more information
           </p>
        </div>
    </div>
    </div>
</x-app-layout>
