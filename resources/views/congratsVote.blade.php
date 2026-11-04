<x-app-layout>
    <div class="container-fluid home completed-screen pt-4">


        <p class="yellow-text mt-4">
            Thank You<br />
            <span>For Voting</span><br />
        </p>

        <div class="product-image">
            <img class="" src="{{ asset('images/congrats.png') }}" alt="" />
        </div>
        <div class="title-container">
            <p class="title small">
                Join our <br /> Journey Now

            </p>
            <a href="{{ route('welcome') }}" class="join-btn btn rounded-pill"><span>Join Now</span></a>

        </div>
      <div class="bottom-text mt-5">
            <p class="text-success text-center">Visit our official website</p>
            <a href="http://www.mentholatum.com.my/">  <img src="{{ asset('images/logo-large.png') }}" alt="" /></a>

        </div>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</x-app-layout>
