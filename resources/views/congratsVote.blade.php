<x-app-layout>
    <div class="container-fluid home completed-screen pt-4">

        <div class="col-12 d-flex justify-content-center">
            @include('components.branding')
        </div>
        <p class="yellow-text mt-4">
            Thank You<br />
            <span>For Voting</span><br />
        </p>

        <div class="product-image">
            <img class="" src="{{ asset('images/badge2.png') }}" alt="" />
        </div>
        <div class="title-container">
            <p class="title small">
                Join our <br /> Journey Now

            </p>
        </div>
        <div class="bottom-text mt-3">
            <br />
            <p class="text-success text-center">Visit our official website</p>
            <br />
            <img src="{{ asset('images/logo-large.png') }}" alt="" />
            <a class="mt-3" href=""> Click Here for more Information </a>
        </div>
    </div>
    </div>
</x-app-layout>
