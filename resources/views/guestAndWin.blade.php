<x-app-layout>
    <div class="content-box main-background d-flex flex-column min-vh-100 px-3">
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>

        <div id="form" class="guest-container rounded-3 bg-white p-3 mb-4 fade-in">
            <div class="d-flex justify-content-center align-items-center gap-3 px-4 mb-4">
                <img src="{{ asset('files/main/turtle.webp') }}" alt="Sea Turtle" class="mb-3 turtle" />
                <div>
                <h2 class="heading-text mb-0">Guess & Win!</h2>
                <p class="sub-heading-text-small mb-0 fw-bold">Guess: How many plastic empties were used to create this sea turtle?</p>
                <p class="sub-heading-text-small mb-0 fw-bold">Win: 3x RM500 L'Occitane gift set</p>
                </div>
            </div>

            <div class="how-to-win mb-2">
                <h4 class="pharagraph-text text-center">How to Win?</h4>
                <ol class="">
                    <li class="mb-1 pharagraph-text">Spot our Sea Turtle made from upcycled plastic waste at IOI City Mall – psst, it's at the West Entrance.</li>
                    <li class="mb-1 pharagraph-text">Guess how many plastic empties were used to create the Sea Turtle.</li>
                    <li class="mb-1 pharagraph-text">Submit your answer below.</li>
                </ol>
                <p class="small text-center">Note: Only one submission per registrant.</p>
            </div>

            <div class="reward-highlight text-center fw-bold mb-4">
                The first 3 correct entries will be rewarded with a RM500 L'Occitane gift set each!
            </div>

            <div class="submission-area mb-4 text-center">
                {{-- Placeholder for input field --}}
                <input id="trashNumber" name="number" type="number" class="form-control w-100 mx-auto guest-input" value="{{ $user->guess }}" placeholder="000" max="999"
                    oninput="this.value = this.value.slice(0, 3)">

            </div>

            <div class="terms-conditions small">
                <h5 class="sub-heading-text-small fw-bold mb-1">Terms & Conditions</h5>
                <div class="mb-2">
                    <strong class="d-block sub-heading-text-small fw-bold">Who Can Join?</strong>
                    <p class="mb-0 sub-heading-text-small">Open to Ocean or Plastic Roadshow registrants with a valid local phone number, and who submit their answer via the official channel: <a href="http://oceanorplastic.experienceloccitane.com" target="_blank">oceanorplastic.experienceloccitane.com</a></p>
                </div>
                <div class="mb-2">
                    <strong class="d-block sub-heading-text-small fw-bold">How to Win?</strong>
                    <p class="mb-0 sub-heading-text-small">The first 3 correct entries will each win a RM500 L'Occitane gift set, based on submission time.</p>
                </div>
                <div class="mb-2">
                    <strong class="d-block sub-heading-text-small fw-bold">The Prize?</strong>
                    <ul class="list-unstyled ps-0 mb-0">
                        <li class="sub-heading-text-small">• Curated L'Occitane gift set worth RM500</li>
                        <li class="sub-heading-text-small">• Not customizable, transferable, or exchangeable</li>
                    </ul>
                </div>
                <div class="mb-2">
                    <strong class="d-block sub-heading-text-small fw-bold">Winner Notification</strong>
                    <p class="mb-0 sub-heading-text-small">Winners will be contacted via SMS or WhatsApp by <strong>15 June 2025</strong>. Ensure SMS/WhatsApp permissions were enabled during registration to qualify.</p>
                </div>
                <div class="mb-3">
                    <strong class="d-block sub-heading-text-small fw-bold">Other Info</strong>
                    <p class="mb-0 sub-heading-text-small">L'Occitane may amend or cancel the contest at any time. By entering, you agree to these terms and the organiser's decisions.</p>
                </div>
            </div>

            <div class="text-center">
                <button id="guess" type="button" class="button button-primary w-100">Confirm</button>
            </div>
        </div>

            <div class="confirm-container congrats-container mt-5  d-none fade-in">
                <div class="congrats-icon mb-5">
                    <img src="{{ asset('files/main/congratulations.webp') }}" alt="Congratulations" />
                </div>
                <p class="fw-bold heading-text text-center px-5">You answer have <br> been submitted</p>
            </div>

            <div class="confirm-container text-center mt-auto px-4 flex-self-end d-none fade-in">
                <a id="homeButton" href="{{ route('preRegEvent') }}" class="button button-primary w-100 mb-2">
                    Home
                </a>
            </div>

        <div class="footer-container p-4">
            @include('components.footer')
        </div>
    </div>
    <script>
        @if($user->guess != null)
                document.querySelectorAll('.confirm-container').forEach(function (el) {
                    el.classList.remove('d-none');
                });
            document.getElementById('form').classList.add('d-none');
        @endif
          document.getElementById('guess').addEventListener('click', function () {
                const selectedInput = document.querySelector('.guest-input');

                const number = selectedInput.value;

                fetch("{{ route('guess.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        number: number
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        document.querySelectorAll('.confirm-container').forEach(function (el) {
                            el.classList.remove('d-none');
                        });
                        document.getElementById('form').classList.add('d-none');


                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Handle error here, e.g., show an error message to the user
                    });
            });
    </script>
</x-app-layout>
