<x-app-layout>
<style>
    .option-btn
    {   
        width: 100%;
        border-color: #ffffff;
        border-radius: 5px;
        background-color: #ffffff;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        cursor: pointer;

        /* Subtle elevation */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

        /* Animations */
        animation: scannerIdle 2.8s ease-in-out infinite;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    #start-scanner,#start-quiz,#perfume-next-btn {
        width: 50%;
        border-color: #ffffff;
        border-radius: 5px;
        background-color: #ffffff;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        cursor: pointer;

        /* Subtle elevation */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

        /* Animations */
        animation: scannerIdle 2.8s ease-in-out infinite;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Hover / Active */
    #start-scanner:hover {
        animation-play-state: paused;
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
    }

    #start-scanner:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }

    .main-content
    {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

   /* Whole screen */
#mainContainer {
  min-height: 100vh;
  min-height: 100svh;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: clamp(12px, 3vh, 24px);
  padding-block: clamp(12px, 3vh, 24px);
}

/* Main content */
#mainContent {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: clamp(12px, 3vh, 24px);
}

/* Title */
/* #mainContent h2 {
  font-size: clamp(1rem, 4.5vw, 1.4rem);
  letter-spacing: clamp(1px, 0.4vw, 2px);
} */
/* Text */
/* #mainContent p {
  font-size: 16px;
  max-width: 38ch;
  line-height: 1.5;
} */

/* Buttons */
.custom-btn-secondary {
  font-size: 16px;
  padding: clamp(12px, 3vh, 16px) clamp(16px, 6vw, 24px);
  width: min(90%, 360px);
}

/* Scanner */
.scanner-wrapper {
  width: 100%;
}

.scanner-container {
  padding: clamp(16px, 4vh, 32px);
}

#reader {
  width: min(90vw, 360px);
  aspect-ratio: 1 / 1;
}

.tile-title , .station_name  {
    text-transform: uppercase;
}

.answer-tile {
    height: 120px;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,0.5);
    background: linear-gradient(145deg, #e9eef5, #dfe6ee);

    color: #2f5ea8;
    font-weight: 600;
    font-size: 14px;

    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 10px;

    transition: all 0.25s ease;
}

/* Hover */
.answer-tile:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}

.answer-tile.wrong {
    color: #ff4d4f;
    box-shadow: 0 0 10px rgba(255, 77, 79, 0.8);
}

.answer-tile.correct {
    color: #28a745;
    box-shadow: 0 0 12px rgba(40, 167, 69, 0.8);
}


    /* Idle animation */
    @keyframes scannerIdle {
        0%   { transform: translateY(0); }
        50%  { transform: translateY(-2px); }
        100% { transform: translateY(0); }
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
        #start-scanner {
            animation: none;
            transition: none;
        }
    }
</style>
    <div id="stationPage" class="station-page main-content main-background with-scroll px-0">
        <div class="modal fade custom-modal" id="scanCompleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-parent rounded-1">
                    <div class="modal-body">
                        <div class="text-center">
                            <img class="check mx-auto mb-4" id="badge" src="">
                            <div class="text-content mt-0">
                                <p class="mb-2 message station_name text-dark"></p>
                                <p class="status-text my-4 text-dark">
                                </p>
                            </div>
                            <div class="text-content mt-3">
                                <a href="{{ route('dashboard') }}" id="routeBtn"
                                    class="custom-btn px-5 fw-regular custom-btn-primary w-50">
                                    BACK
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button
            class="back-btn animate-entry"
            onclick="window.location.href='{{ route('dashboard') }}'"
            aria-label="Go back"
        ></button>
        
        <div id="mainContainer">

            <!-- Branding -->
            <div class="branding-container animate-entry px-4">
                @include('components.branding')
            </div>
            <!-- Main content -->
            <div id="mainContent"
                class="d-flex flex-column align-items-center animate-entry delay-3">

                <div class="img-container text-center">
                    <!-- station image -->
                    <img class="station-image w-25 mx-auto my-4"
                       src="{{ asset('images/developer/DEV' . $developer->id . '.webp') }}"   
                        alt="Station Image">
                </div>

                <div class="container quiz-card mb-4">

                    <!-- 🧠 Question -->
                    <h5 class="fw-bold mb-3 text-center">
                        {{ $question->question }}
                    </h5>

                    <div class="container py-4">
                        <div class="row g-3">

                            @foreach($question->answers as $answer)
                                <div class="col-6">
                                    <button 
                                        class="answer-tile w-100"
                                        data-id="{{ $answer->id }}"
                                        data-correct="{{ $answer->is_correct }}"
                                        data-question="{{ $question->id }}"
                                    >
                                        {{ $answer->answer }}
                                    </button>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                
                <!-- actions -->
                @if ($developer->pivot->isCompleted)
                    <!-- ✅ Already checked in -->
                    <div class="checkedInContainer w-50 mx-auto">
                        <p class="text-center mb-2">Checked In</p>
                        <a href="{{ route('dashboard') }}"
                        class="custom-btn custom-btn-secondary w-100">
                            BACK
                        </a>
                    </div>
                @else

                    <!-- ✅ Other stations → Scanner -->
                    {{-- <button id="start-scanner"
                            class="text-dark custom-btn-secondary px-3 py-2">
                        SCAN QR CODE TO PROCEED
                    </button> --}}

                      {{-- <div class="text-content mt-3">
                                <a href="{{ route('station.stamping', $station->id);}}" id="routeBtn"
                                    class="custom-btn w-auto px-5 fw-regular custom-btn-primary text-white">
                                    I'M THERE
                                </a>
                            </div> --}}

                @endif
            </div>

            <div id="quizContainer" class="d-none">
                <!-- Quiz content will be injected here by JavaScript -->
                <div id="quiz-container" style="display:none; width:100%; max-width:360px;">
                    <h3 id="question-text" class="text-center mb-3"></h3>
                    <div id="options"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center p-4 rounded-4">

                <h5 class="fw-bold text-primary">Excellent!</h5>

                <div class="my-3">
                    <div style="width:60px;height:60px;border-radius:50%;border:3px solid #28a745;
                                display:flex;align-items:center;justify-content:center;margin:auto;">
                        <span style="font-size:28px;color:#28a745;">✓</span>
                    </div>
                </div>

                <p class="text-primary fw-bold">
                    Your knowledge is <br> shining through!
                </p>

                <button class="custom-btn custom-btn-primary w-100 mt-3" onclick="goNext()">
                    NEXT
                </button>

            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        let answeredCorrectly = false;

        document.querySelectorAll('.answer-tile').forEach(btn => {

    btn.addEventListener('click', function () {

        // 🚫 stop after correct
        if (answeredCorrectly) return;

        let isCorrect = this.dataset.correct == "1";

        if (isCorrect) {

            // ✅ mark correct
            this.classList.add('correct');

            // highlight correct + lock all
            document.querySelectorAll('.answer-tile').forEach(b => {
                if (b.dataset.correct == "1") {
                    b.classList.add('correct');
                }
                b.disabled = true;
            });

            answeredCorrectly = true;

            // 🚀 send to backend
            submitAnswer(this.dataset.question, this.dataset.id, true);

            // 🎉 show modal
            let modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();

        } else {

            // ❌ mark wrong (only clicked one)
            this.classList.add('wrong');

            // prevent clicking same wrong again
            this.disabled = true;

            // 🚀 send attempt
            submitAnswer(this.dataset.question, this.dataset.id, false);
        }

    });

});

        function goNext() {
            window.location.href = "{{ route('dashboard') }}";
        }

        function submitAnswer(questionId, answerId, isCorrect) {

                fetch("{{ route('submit.answer') }}",{
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        answer_id: answerId,
                        is_correct: isCorrect
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log('Saved:', data);
                })
                .catch(err => {
                    console.error('Error:', err);
                });
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
        <script>
            // Pass data from Blade to JavaScript
            window.stationConfig = {
                urls: {
                    process_qr_code: '{{ route('process_qr_code') }}',
                    submit_quiz: '{{ route('submit.answer') }}',
                    congrats: '{{ route('congrats') }}'
                },
                assets: {
                    check_image: '{{ asset('images/check.png') }}',
                    error_image: '{{ asset('images/error.png') }}'
                },
                station_id: {{ $developer->id }},
                station_name: `{!! strtoupper($developer->name) !!}`,
                asset_base: "{{ asset('') }}"
            };

            window.gotoStation = function(id,) {
                    var url = "{{ route('developer', ['developer' => ':id']) }}".replace(
                        ":id",
                        id
                    );

                    // Redirect to the generated URL
                    window.location.href = url;
                }
        </script>
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>