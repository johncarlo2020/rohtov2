<x-app-layout>
<style>
    .answer-text
    {
        font-family: Grifa-Regular, sans-serif !important;
        width: 65%;
        text-align: center;
        font-size: 50px;
        margin-left: -5vw;
    }
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
  /* align-items: center; */
  gap: clamp(12px, 3vh, 24px);
  padding-block: clamp(12px, 3vh, 24px);
  padding: 20px;
}

/* Main content */
#mainContent {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: clamp(12px, 3vh, 24px);
}

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

    .q-box
    {
        background-size: contain;
        height: 70vw;
        background-repeat: no-repeat;
        display: flex;
        justify-content: center;
        align-items: center;
        transform: scale(1);
        cursor: pointer;
        transition: all 0.35s ease;
    }

    /* BEFORE selection happens */
    .q-box.default-state{
        filter: brightness(1);
        opacity: 1;
    }

    /* selected box */
    .q-box.selected{
        filter: brightness(1);
        opacity: 1;

        transform: scale(1.08);

        /* box-shadow:
            0 0 15px #fff7a8,
            0 0 30px #ffee58,
            0 0 45px rgba(255, 235, 59, 0.8);

        border-radius: 12px; */
        z-index: 2;
    }

    /* non-selected AFTER click */
    .q-box.dimmed{
        filter: brightness(0.4);
        opacity: 0.7;
        transform: scale(0.95);
    }

    .number-image
    {
        width:20%;
        margin:auto;
    }

    .question-image
    {
        width:80%;
        margin:auto;
    }

    .cat{
            position: absolute;
            bottom: 0px;
            width: 90% !important;
            right: 0;
            left: 0;
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

        {{-- <button
            class="back-btn animate-entry"
            onclick="window.location.href='{{ route('dashboard') }}'"
            aria-label="Go back"
        ></button> --}}
        
        <div id="mainContainer">
            <audio id="bgMusic" loop>
                <source src="{{ asset('audio/bg_music.mp3') }}" type="audio/mpeg">
            </audio>
            <!-- Branding -->
            <div class="branding-container animate-entry px-4">
                @include('components.branding')
            </div>
            <!-- Main content -->
            <div id="mainContent" class="mt-5">

                <div class="number-container">
                    <img class="number-image" src="" alt="">
                </div>

                <div class="question-container">
                    <img class="question-image" src="" alt="">
                </div>

                <div class="row">
                    <audio id="clickSound">
                    <source src="{{ asset('audio/clicksoundeffect.mp3') }}" type="audio/mpeg">
                </audio>
                    <div class="col-6">
                        <div class="q-box blue-box default-state"
                            data-answer="A"
                            style="background-image:url('{{ asset('images/brand/blue.webp') }}');">

                            <p class="text-white answer-text answer-a">
                                I'm a GIRL
                            </p>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="q-box purple-box default-state"
                            data-answer="B"
                            style="background-image:url('{{ asset('images/brand/purple.webp') }}');">

                            <p class="text-white answer-text answer-b">
                                I'm a BOY
                            </p>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <img class="w-75 m-auto p-0 cat" src="{{ asset('images/brand/cat_quiz.webp') }}" alt="" />
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
        document.addEventListener('click', function initMusic() {
            const music = document.getElementById('bgMusic');

            music.volume = 0.2;
            music.play();

            document.removeEventListener('click', initMusic);
        });

        document.querySelectorAll('.q-box').forEach(box => {
            box.addEventListener('click', () => {
                const sound = document.getElementById('clickSound');

                sound.currentTime = 0;
                sound.volume = 0.5;

                sound.play().catch(err => {
                    console.log(err);
                });
            });
        });
        </script>
    <script>

    const questions = [

        {
            number: "01",
            question: "It’s Saturday afternoon. Where are you?",
            answerA: "Chilling at the mall, shopping, catching up with friends",
            answerB: "At a cute cafe, journaling, reading, or people-watching"
        },

        {
            number: "02",
            question: "Your vibe in the group chat is?",
            answerA: "Loud, funny, always sending memes, planner of last-minute hangouts",
            answerB: "Calm, witty, selectyive replies, secretly the wise one"
        },

        {
            number: "03",
            question: "What is your ideal snack combo",
            answerA: "Fries, fried chicken, sweet treats, indulgent combo",
            answerB: "Croissant, fruit toast, light desserts, aesthetic bites"
        }

        ,

        {
            number: "04",
            question: "Choose a weekend fit",
            answerA: "Trendy, comfy, effortless cool, sneakers ready",
            answerB: "Clean, minimalist neutral tones, stylish but subtle"
        }

    ];

    let currentQuestion = 0;

    let scoreA = 0;
    let scoreB = 0;

    // const numberText = document.querySelector('.number-text');
    // const questionText = document.querySelector('.question-text');
    const numberImage = document.querySelector('.number-image');
    const questionImage = document.querySelector('.question-image');

    const answerA = document.querySelector('.answer-a');
    const answerB = document.querySelector('.answer-b');

    const qBoxes = document.querySelectorAll('.q-box');


    // load question
    function loadQuestion(index){

        const current = questions[index];

        // Default image paths
        numberImage.src = `images/brand/number-${current.number}.webp`;

        // Example:
        // images/brand/question-01.png
        questionImage.src = `images/brand/question-${current.number}.webp`;

        answerA.innerText = questions[index].answerA;
        answerB.innerText = questions[index].answerB;

        // reset styles
        qBoxes.forEach(box => {
            box.classList.remove('selected','dimmed');
            box.classList.add('default-state');
        });

    }


    // click event
    qBoxes.forEach(box => {

        box.addEventListener('click', function(){

            const selectedAnswer = this.dataset.answer;

            // score
            if(selectedAnswer === 'A'){
                scoreA++;
            }else{
                scoreB++;
            }

            // remove old
            qBoxes.forEach(item => {
                item.classList.remove(
                    'selected',
                    'dimmed',
                    'default-state'
                );
            });

            // selected style
            this.classList.add('selected');

            // darken other
            qBoxes.forEach(item => {
                if(item !== this){
                    item.classList.add('dimmed');
                }
            });

            // next question
            setTimeout(() => {

                currentQuestion++;
                const reviewRoute = "{{ route('review') }}";

                if(currentQuestion < questions.length){

                    loadQuestion(currentQuestion);

                }else{
                    // final result
                    if (scoreA > scoreB) {

                        window.location.href = `${reviewRoute}?review=milktea`;

                    } else if (scoreB > scoreA) {

                        window.location.href = `${reviewRoute}?review=mootea`;

                    } else {

                        // Randomize if tied
                        const randomReview = Math.random() < 0.5 ? 'milktea' : 'mootea';
                        window.location.href = `${reviewRoute}?review=${randomReview}`;

                    }

                }

            }, 600);

        });

    });


    // first load
    loadQuestion(currentQuestion);

    </script>
    <script>
    const boxes = document.querySelectorAll('.q-box');

    boxes.forEach(box => {

        box.addEventListener('click', function () {

            // remove all states first
            boxes.forEach(item => {
                item.classList.remove(
                    'selected',
                    'dimmed',
                    'default-state'
                );
            });

            // selected item
            this.classList.add('selected');

            // darken others
            boxes.forEach(item => {
                if(item !== this){
                    item.classList.add('dimmed');
                }
            });

        });

    });
</script>
    
        
        @vite(['resources/js/station.js'])
    @endpush
</x-app-layout>