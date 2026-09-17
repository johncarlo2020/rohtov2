<x-app-layout>
    <div class="d-flex flex-column px-3 content-box main-background min-vh-100">
        <div class="mb-5 container">
            <div>
                @include('components.branding')
            </div>
        </div>



        <div class="mt-auto p-4 footer-container">
            @include('components.footer')
        </div>
    </div>
</x-app-layout>
