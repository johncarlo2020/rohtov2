<x-app-layout>
    <div class="content-box welcome-background fade-in">
        <div class="container">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div id="goto" class="congrats-img mt-auto">
            <img src="{{ asset('files/main/congrats_img.webp') }}" alt="" />
        </div>
        <div class="footer-container  mt-auto">
            @include('components.footer')
        </div>
    </div>
</x-app-layout>

<script>
    const goto = document.getElementById('goto');
    // add event listener to the element
    goto.addEventListener('click', function() {
        // redirect to the desired URL
        window.location.href = "{{ route('dashboard') }}";
    });
</script>
