<x-app-layout>
    <div class="container-fluid home start completed-screen pt-4">
        <img
            class="bubble bubble__1"
            src="{{ asset('images/bubble-1.png') }}"
            alt=""
        />
        <img
            class="bubble bubble__2"
            src="{{ asset('images/bubble-2.png') }}"
            alt=""
        />
        <img
            class="bubble bubble__3"
            src="{{ asset('images/bubble-3.png') }}"
            alt=""
        />
        <img
            class="bubble bubble__4"
            src="{{ asset('images/bubble-4.png') }}"
            alt=""
        />

        <div class="title-container">
            <img
                class="bubble bubble__5"
                src="{{ asset('images/bubble-5.png') }}"
                alt=""
            />
            <img
                class="bubble bubble__6"
                src="{{ asset('images/bubble-6.png') }}"
                alt=""
            />
        </div>
        <div class="col-12 d-flex justify-content-center">
            @include('components.branding')
        </div>
        <p class="yellow-text mt-4">
            Congratulations, {{ auth()->user()->fname }}!<br />
            <span>You have completed</span><br />the Hydration Journey!
        </p>

        <div class="product-image">
            <img class="" src="{{ asset('images/badge2.png') }}" alt="" />
        </div>
        <div class="title-container">
            <p class="title small">
                Please proceed to r<br />redemption counte
                <img
                    class="arrow arrow__2"
                    src="{{ asset('images/arrow-2.png') }}"
                    alt=""
                />
                <img
                    class="arrow arrow__1"
                    src="{{ asset('images/arrow-1.png') }}"
                    alt=""
                />
            </p>
        </div>
        <div class="bottom-text mt-3">
            <br />
            <p class="text-success text-center">Visit our official website</p>
            <br />
            <img src="{{ asset('images/logo-large.png') }}" alt="" />
            <a class="mt-3" href="https://hadalabo.com.my/">
                Click Here for more Information
            </a>
        </div>
    </div>
</x-app-layout>
