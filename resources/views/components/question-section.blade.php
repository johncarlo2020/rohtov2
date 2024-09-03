<style>
    .icon-bg {
        width: 150px;
        height: auto;
        margin-bottom: 25px;
    }
    .progress-container {
        width: 90%;
        background-color: #f3f3f3;
        border-radius: 5px;
        overflow: hidden;
        margin: 0 auto;
    }

    .progress-bar {
        height: 10px;
        background-color: #0c5a40; /* Change color */
        text-align: center;
        line-height: 30px;
        color: white;
        border-radius: 5px; /* Make it rounded */
        transition: width 0.5s ease; /* Add transition for animation */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow */
    }
    .heading-question {
        color: #358abf;
        font-size: 20px;
        font-weight: 700;
        text-align: center;
        margin-top: 20px;
    }
    .question-description {
        width: 90%;
        background: #fff;
        border-radius: 12px;
        position: relative;
        margin: 0 auto;
        padding: 20px;
        margin-top: 40px;
    }
    .question-img {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        background: #8bc28c;
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);

        /* Centering the content using CSS Grid */
        display: grid;
        place-items: center; /* This centers the image within the container */
    }

    .question-img img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 12px;
        object-fit: cover; /* Ensures the image covers the container while maintaining its aspect ratio */
    }
    #question {
        text-align: center;
        color: #358abf;
        font-size: 24px;
        font-weight: 700;
        margin-top: 86px;
    }
    .answers {
        width: 90%;
        margin: 0 auto;
        margin-top: 20px;
    }

    .answers .item {
        width: 100%;
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin: 0 auto;
        border: none;
        outline: none;
        margin-bottom: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease; /* Add transition for background color */
    }
    .answers .active {
        background: #0c5a40;
        color: #fff;
        border: 1px solid #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    #next {
        width: 170px;
        margin-top: 20px;
        background: #0c5a40;
        color: #fff;
        border-radius: 40px;
        padding: 10px;
        border: none;
        outline: none;
    }
    .navigation {
        width: 90%;
        margin: 0 auto;
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    @keyframes shake {
        0% {
            transform: translateX(0);
        }
        25% {
            transform: translateX(-5px);
        }
        50% {
            transform: translateX(5px);
        }
        75% {
            transform: translateX(-5px);
        }
        100% {
            transform: translateX(0);
        }
    }

    .shake {
        animation: shake 0.5s;
    }
</style>

<div class="progress-container">
    <div class="progress-bar" id="progress-bar" style="width: 0%"></div>
</div>
<h1 class="heading-question">Question <span id="question-number">1</span></h1>
<div class="question-description">
    <div class="question-img">
        <img src="" alt="" id="img" />
    </div>
    <p id="question"></p>
</div>
<div class="answers"></div>

<div class="modal fade" id="scanCompleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center content">
                    <div class="image-check">
                        <div class="text-content">
                            <img
                                class="icon-bg"
                                src="{{ asset('images/badge1.png') }}"
                                alt="Lock Image"
                            />

                            <p class="station-text">
                                Station <span class="station_id"></span>
                            </p>
                            <p class="message">Check-in Successful</p>
                        </div>
                        <div class="">
                            <a href="{{ route('dashboard') }}" class="button">
                                Okay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Ensure Bootstrap JS is included -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>

    <script>
        const questions = [
            {
                question: "In which year Mentholatum Trademark was registered?",
                choices: ["1889", "1895", "2009"],
                correctAnswer: "1895"
            },
            {
                question: "Mentholatum celebrates its ______ anniversary in year 2024",
                choices: ["135th", "133rd", "100th"],
                correctAnswer: "135th"
            },
            {
                question: "Rohto Mentholatum Malaysia (RMM) launched the first skincare product _____ in year 2009",
                choices: ["Sunplay", "OXY", "Hada Labo"],
                correctAnswer: "Hada Labo"
            }
        ];

        let currentQuestionIndex = 0;

        function shuffle(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        function sendMessage() {
            const confettiCanvas = document.createElement('canvas');
                            confettiCanvas.style.position = 'fixed';
                            confettiCanvas.style.top = 0;
                            confettiCanvas.style.left = 0;
                            confettiCanvas.style.width = '100%';
                            confettiCanvas.style.height = '100%';
                            confettiCanvas.style.pointerEvents = 'none';
                            confettiCanvas.style.zIndex = 9999;
                            document.body.appendChild(confettiCanvas);

                            // Trigger confetti using the new canvas
                            const myConfetti = confetti.create(confettiCanvas, {
                                resize: true,
                                useWorker: true
                            });

                            myConfetti({
                                particleCount: 100,
                                spread: 70,
                                origin: {
                                    y: 0.6
                                }
                            });

                            // Optional: Remove the canvas after a short delay
                            setTimeout(() => {
                                document.body.removeChild(confettiCanvas);
                            }, 5000);
                    // Fetch the CSRF token from the meta tag
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '{{ route('process_qr_code') }}', // Using Laravel's route() helper function
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                        },
                        data: {
                            qrCodeMessage: 'example.com?station=1',
                            station: 1
                        },
                        success: function(response) {
                            console.log('QR Code message sent successfully:', response);
                            // Handle success response if needed



                                $('.station_id').html('1');

                            $(scanCompleteModal).modal('show');

                        },
                        error: function(xhr, status, error) {
                            console.error('Error sending QR Code message:', error);
                            $('.station-text').html('Failed');
                            $('.message').html('Invalid QR code. Please try again.');
                            $('.check').attr('src', '{{ asset('images/error.svg') }}');
                            $(scanCompleteModal).modal('show');

                        }
                    });
                }

        function renderQuestion() {
            const questionElement = document.getElementById('question');
            const answersElement = document.querySelector('.answers');
            const progressBar = document.getElementById('progress-bar');
            const questionNumberElement = document.getElementById('question-number');
            const questionNumberImage = document.getElementById('img');
        questionNumberImage.src = "{{ asset('images/') }}" + '/Q' + (currentQuestionIndex + 1) + '.png';




            // Clear previous answers
            answersElement.innerHTML = '';

            // Set question text
            questionElement.textContent = questions[currentQuestionIndex].question;


            // Update question number
            questionNumberElement.textContent = currentQuestionIndex + 1;

            // Update progress bar
            const progressPercentage = ((currentQuestionIndex + 1) / questions.length) * 100;
            progressBar.style.width = progressPercentage + '%';

            // Shuffle and create answer buttons
            const shuffledChoices = shuffle([...questions[currentQuestionIndex].choices]);
            shuffledChoices.forEach(choice => {
                const button = document.createElement('button');
                button.classList.add('item', 'shadow-sm');
                button.textContent = choice;
                button.addEventListener('click', () => checkAnswer(button, choice));
                answersElement.appendChild(button);
            });
        }

        function checkAnswer(button, selectedAnswer) {
            const correctAnswer = questions[currentQuestionIndex].correctAnswer;
            const answerButtons = document.querySelectorAll('.answers .item');

            if (selectedAnswer === correctAnswer) {
                button.style.backgroundColor = '#0C5A40';
                button.style.border = '2px solid #fff';
                button.style.color = '#fff';
                setTimeout(() => {
                    currentQuestionIndex++;
                    if (currentQuestionIndex < questions.length) {
                        renderQuestion();
                    } else {
                        sendMessage();
                        var firstStationId = 1;
                        // var url = "{{ route('station', ['station' => ':id']) }}".replace(':id', firstStationId) + "?questionComplete=true";
                        // window.location.href = url;
                    }
                }, 500);
            } else {
                button.style.backgroundColor = '#FF0000';
                button.style.border = '2px solid #fff';
                button.style.color = '#fff';
                document.body.classList.add('shake'); // Add shake class
                navigator.vibrate(1000); // Vibrate for 500ms
                setTimeout(() => {
                    document.body.classList.remove('shake'); // Remove shake class
                }, 500); // Duration of the shake animation

                // No longer highlighting the correct answer

                setTimeout(() => {
                    answerButtons.forEach(btn => {
                        btn.style.backgroundColor = '';
                        btn.style.border = ''; // Reset border
                        btn.style.color = '';
                    });
                    // Re-render the question with shuffled choices
                    renderQuestion();
                }, 2000);
            }
        }

        // Initialize the first question
        document.addEventListener('DOMContentLoaded', renderQuestion);
    </script>
</div>
