<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1, viewport-fit=cover" />

    <title>{{ config('app.name', 'Loading ...') }}</title>

    <x-appCdnPackages />
    @vite(['resources/sass/app.scss'])

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Removes scrolling */
        }

        .welcome-page {
            width: 100vw;
            height: 100vh;
            height: 100dvh; /* Correct for iOS Safari dynamic viewport */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
            height:100svh;
        }

        .welcome_img_store {
            object-fit: contain;
            max-height: 40vh; /* Responsive height */
        }

        .bottom-text-welcome {
            position: relative;
            z-index: 10;
        }

        .btn-wrapper 
        {
            margin-top: -5%;
        }

        .continue-btn
        {
            -webkit-text-stroke: 1px #733412;
            font-size: 24px;
            font-weight: 900;
            text-decoration: none;
            margin-top: -5%;
            text-shadow: 0 3px 0 #f7a239;
        }

        /* #banner .top{
            margin: 15% 0%;
        } */

        .instructions-box
        {
            background-size: contain;
            margin-bottom: -4vw;
            background-repeat: no-repeat;
        }

        .welcome-text
        {
            font-size:50px;
            margin-bottom: -8vw;
        }

        .instructions-text
        {
            font-size:45px;
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
        background-position: center;
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
        max-width:20%;
        margin:auto;
    }

    .question-image
    {
        width:70%;
        margin:auto;
    }

    .cat{
            position: absolute;
            bottom: 0px;
            width: 90% !important;
            right: 0;
            left: 0;
        }

        .answer-text
    {
        font-family: Grifa-Regular, sans-serif !important;
        width: 65%;
        text-align: center;
        font-size: 50px;
    }

    </style>
</head>

<body class="antialiased welcome-page" style="background-image:url('{{ asset('images/brand/landing_bg.webp') }}');">

    <div id="welcomeParent" class="container-fluid main-content with-scroll pt-4 px-0">
            <div class="top-container">
            <!-- Branding (top area) -->
            <div class="row flex-grow-1">
                <div class="col-12 animate-entry mb-4">
                    <div>
                        <div class="branding pulse-slow">
                            <img onclick="window.location.href='{{ route('dashboard') }}'" class="logo" src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div id="banner" class="col-10 mx-auto d-flex flex-column justify-content-center animate-entry">
                <div class="top">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="text-center text-white welcome-text">Gandingan Mantap,<br>Berkhasiat dan Sedap!</h3>
                        </div>
                    </div>
                    <div class="row">
                        <img class="w-100 p-0 pulse-slow" src="{{ asset('images/brand/masthead.webp') }}"
                        alt="" />
                    </div>
                </div>

                {{-- instructions --}}

                <div id="instructionsParent" class="instructions-parent animate-entry d-none w-100">
                    <p class="text-center instructions-text text-white text-center">INSTRUCTIONS</p>
                    <div class="instructions-box">
                        <img src="{{ asset('images/brand/instructions.webp') }}" alt="" srcset="">
                    </div>
                    <a href="javascript:void(0);" class="custom-btn custom-btn-primary pulse-slow animate-entry delay-2" id="letsGoBtn" style="background-image:url('{{ asset('images/brand/btn.png') }}');">
                        Let's Go
                    </a>
                </div>

                <!-- Bottom CTA -->
                <div id="startWrapper" class="row mb-5">
                    <div class="col-12 text-center">
                        <div class="d-block  mb-2">
                            <div class="colanimate-entry delay-2 btn-wrapperx px-5 mt-4">
                                <a href="javascript:void(0)" id="startBtn" class="custom-btn custom-btn-primary pulse-slow" style="background-image:url('{{ asset('images/brand/btn.png') }}');">
                                    Start
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-container">
                <!-- Bottom CTA -->
                <img class="p-0" src="{{ asset('images/brand/cat_quiz.webp') }}"alt="" />
            </div>
        
    </div>

    <div id="quizParent" class="container-fluid main-content with-scroll pt-4 px-0 d-none">
        <div id="mainContainerx">
            <div class="branding-container animate-entry px-4">
                @include('components.branding')
            </div>
            <audio id="bgMusic" loop>
                <source src="{{ asset('audio/bg_music.mp3') }}" type="audio/mpeg">
            </audio>
            <!-- Main content -->
            <div id="mainContent" class="mt-5">

                <div class="number-container animate-entry">
                    <img class="number-image pulse-slow" src="" alt="">
                </div>

                <div class="question-container animate-entry">
                    <img class="question-image pulse-slow" src="" alt="">
                </div>

                <div class="row">
                    <audio id="clickSound">
                    <source src="{{ asset('audio/clicksoundeffect.mp3') }}" type="audio/mpeg">
                </audio>
                    <div class="col-6 animate-entry delay-2">
                        <div class="q-box blue-box default-state"
                            data-answer="A"
                            style="background-image:url('{{ asset('images/brand/blue.webp') }}');">

                            <p class="text-white answer-text answer-a">
                                I'm a GIRL
                            </p>
                        </div>
                    </div>

                    <div class="col-6 animate-entry delay-2">
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

    <x-scriptPackages />
    <script>
        document.getElementById('startBtn').addEventListener('click', function () {

            // show instructions
            document.getElementById('instructionsParent')
                    .classList.remove('d-none');

            // hide start button
            document.getElementById('startWrapper')
                    .style.display = 'none';
        });

        document.getElementById('letsGoBtn').addEventListener('click', function () {

                // hide welcome section
                document.getElementById('welcomeParent').classList.add('d-none');

                // show quiz section
                document.getElementById('quizParent').classList.remove('d-none');

            });


    </script>
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
<script>
    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();
    });

    document.addEventListener('dblclick', function (e) {
        e.preventDefault();
    });
    </script>

</body>
</html>
