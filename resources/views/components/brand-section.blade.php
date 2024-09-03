<style>

    .icon-bg{
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
        background-color: #0C5A40; /* Change color */
        text-align: center;
        line-height: 30px;
        color: white;
        border-radius: 5px; /* Make it rounded */
        transition: width 0.5s ease; /* Add transition for animation */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow */
    }
    .heading-question {
        color: #358ABF;
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
    background: #8BC28C;
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
        color: #358ABF;
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
        color:#358ABF;
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
        background: #0C5A40;
        color: #fff;
        border: 1px solid #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    #next {
        width: 170px;
        margin-top: 20px;
        background: #0C5A40;
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
        0% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
    }

    .shake {
        animation: shake 0.5s;
    }



    .brand-img {
        width:75px;
        height:auto;
        margin-left: 115px; /* Space between text and image */
    }
</style>

<h1 class="heading-question">Our Best Seller <span id="question-number"></span></h1>
<div class="question-description">
    <div class="question-img">
        <img src="" alt="" id="img">
    </div>
    <p id="question"></p>
</div>
<div class="answers">
    @foreach ($brands as $brand)
                        <button onclick="checkAnswer(this)" class="item shadow-sm" data-id="{{$brand->id}}" style="">
                            {{$brand->name}}
                        <img class="brand-img" src="{{ asset('images/brand'.$brand->id.'.png') }}" alt="Lock Image">
                        </button>
    
    @endforeach
</div>

<div class="brand-btn">
    <button class="button" onclick="continueSelection()" > Continue </button>
</div>

<div class="modal fade " id="scanCompleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center content">
                    <div class="image-check">
                        <div class="text-content">
                        <img class="icon-bg" src="{{ asset('images/badge1.png') }}" alt="Lock Image">

                            <p class="station-text">Station <span class="station_id"></span></p>
                            <p class="message">
                                    Check-in Successful
                            </p>
                        </div>
                        <div class="">
                            <a href="{{ route('station.brands') }}" class="button">
                                Okay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> <!-- Ensure Bootstrap JS is included -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
   
<script>
const questions = [
    {
        question: "Which is your first use brand?",
    },
];

let currentQuestionIndex = 0;

function continueSelection() {
            // Find the selected button
            const selectedButton = document.querySelector('.item.selected');

            if (selectedButton) {
                // Get the data-id attribute of the selected button
                const selectedId = selectedButton.getAttribute('data-id');
                console.log('Selected ID:', selectedId);
                sendMessage(selectedId);

            } else {
                console.log('No item selected');
            }
        }


function sendMessage(id) {
            // Fetch the CSRF token from the meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{ route('process_qr_code') }}', // Using Laravel's route() helper function
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
                },
                data: {
                    qrCodeMessage: 'example.com?station=2',
                    station: 2,
                    brand:id
                },
                success: function(response) {
                    console.log('QR Code message sent successfully:', response);
                    // Handle success response if needed
                
                        $('.station_id').html('2');
                  
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
    questionNumberImage.src = "{{ asset('images/logo-large.png') }}";
    questionElement.textContent = questions[currentQuestionIndex].question;



 
}
function checkAnswer(button) {
    const answerButtons = document.querySelectorAll('.answers .item');
    
    answerButtons.forEach((btn) => {
        btn.style.backgroundColor = ''; // or the default color
        btn.style.border = ''; // or the default border
        btn.style.color = ''; // or the default color
        btn.classList.remove('selected')
    });

    // Apply styles to the clicked button
    button.style.backgroundColor = '#8BC28C';
    button.style.border = '2px solid #fff';
    button.style.color = '#fff';
    
    // Add 'selected' class to the current button
    button.classList.add('selected');
     
}

// Initialize the first question
document.addEventListener('DOMContentLoaded', renderQuestion);
</script>
