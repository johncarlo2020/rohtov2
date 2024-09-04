<x-app-layout>
    <div class="container-fluid home start completed-screen pt-4">

        </div>
        <div class="col-12 d-flex justify-content-center">
            @include('components.branding')
        </div>
        <p class="yellow-text mt-4">
            Mission Accomplished<br />
            <span>You’ve successfully</span><br />unlocked the Little Nurse
        </p>

        <div class="product-image">
            <img class="" src="{{ asset('images/badge2.png') }}" alt="" />
        </div>
        <div class="title-container">
            <p class="title small">
                Please proceed to <br />redemption counter

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
</x-app-layout>
