<x-app-layout>

    <div id="stationPage" class="station-page home content-box main-background">
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>
        <div id="mainContent" class="mt-1 mb-2 text-center col-12 text-content">
            <div id="{{ $user ? '' : 'forceQr' }}" class="mt-4 icon-container">
            </div>

            <h1 class=" station-heading mt-5">
                {{ $station->name }}
            </h1>

            <div id="start" class="mb-5 d-none">
                <div class="instruction-text">
                    <h2>How to Participate</h2>
                    <ol>
                        <li>
                            <strong>Smell & Vote</strong> - Explore all 6 fragrances and vote for the one that best
                            matches your current mood.
                        </li>
                        <li>
                            <strong>Snap & Share</strong> - Take a stylish photo of yourself at the experience area.
                            Upload your photo to Instagram or Facebook. Don’t forget to tag us and #AdidasVibes
                        </li>
                        <li>
                            <strong>Redeem Your Gift</strong> - Show your social media post at the Gift Redemption
                            Counter to claim your exclusive reward.
                        </li>
                    </ol>
                </div>

            </div>

            <div id="picker" class="mb-3">
                <p class="pharagraph-text">Please pick one to vote</p>
                <div class="vote-container">
                    @for ($index = 1; $index <= 6; $index++)
                        <div class="vote-item">
                            <input class="form-check-input" type="radio" name="fragrance"
                                id="vote_option_{{ $index }}" value="{{ $index }}">
                            <label class="form-check-label" for="vote_option_{{ $index }}">
                                <img src="{{ asset('files/vote/' . $index . '.webp') }}" alt="Option {{ $index }}">
                            </label>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="button-group d-flex flex-column justify-content-center align-items-center px-5">
                <button id="nextButton" class="button button-primary w-100 text-center mb-3">NEXT</button>
                <a href="{{ route('dashboard') }}" class="button button-primary w-100 mx-auto">
                    BACK
                </a>
            </div>
        </div>

        <div class="check-in-successful mt-5 d-none">
            {{-- <div class="check-in-successful-img">
                <img src="{{ asset('files/main/successful_img.webp') }}" alt="">
            </div> --}}

            <div class="text-heading text-center">
                 <p class="pharagraph-text text-center">VOTE</p>
                <h1 class="heading-text text-center">SUCCESSFUL</h1>
            </div>
            <div class="main-img">
                <img class="station-image" src="{{ asset('files/congrats/c' . $station->id . '.webp') }}" alt="">
            </div>
            <div class="complete-progress p-3 mx-auto">
                <div class="info-progress d-flex gap-3">
                    <div class="station-progress border-right px-4">
                        <div class="circular-progress-container">
                            <div class="circular-progress"
                                style="--progress-percent: {{ ($completedStationCount / 4) * 100 }}%;">
                                <div class="progress-value-center">
                                    <span class="current-step-display">{{ $completedStationCount }}</span><span
                                        class="separator">/</span><span class="total-steps-display">4</span>
                                </div>
                            </div>
                        </div>
                        <div class="progress-label-below">
                            {{ $completedStationCount }}/4 Check-In Completed
                        </div>
                    </div>
                    <div class="info-text px-2 mt-3">
                        <h2 class="mb-0">Well Done!</h2>
                        <h1 class="mb-0">You've just checked in!</h1>
                        <p class="mb-0">Complete all checkpoints to redeem an exclusive gift.</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="button button-black w-100 uppercase">back to main journey</a>
            </div>
        </div>

        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> <!-- Ensure Bootstrap JS is included -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>

    <script>
        document.getElementById('nextButton').addEventListener('click', function() {
            const selectedFragrance = document.querySelector('input[name="fragrance"]:checked');
            if (selectedFragrance) {
                const value = selectedFragrance.value;
                addVote(value);
            } else {
                console.log('No fragrance selected');
            }
        });

        function addVote(vote) {
            fetch("{{ route('add_vote') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        vote: vote
                    })
                })
                .then(response => response.json())
                .then(data => {
                   const stationCompletedCount = data.stationCompletedCount;

                   // Update the displayed count
                   const currentStepDisplay = document.querySelector('.current-step-display');
                   if (currentStepDisplay) {
                       currentStepDisplay.textContent = stationCompletedCount;
                   }

                   const progressLabel = document.querySelector('.progress-label-below');
                   if (progressLabel) {
                       progressLabel.textContent = stationCompletedCount + '/4 Check-In Completed';
                   }

                   // Update the progress bar
                   const circularProgress = document.querySelector('.circular-progress');
                   if (circularProgress) {
                       const progressPercent = (stationCompletedCount / 4) * 100;
                       circularProgress.style.setProperty('--progress-percent', progressPercent + '%');
                   }

                   // Show the success message and hide the main content
                   document.getElementById('mainContent').classList.add('d-none');
                   document.querySelector('.check-in-successful').classList.remove('d-none');


                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle error here, e.g., show an error message to the user
                });
        }
    </script>
</x-app-layout>
