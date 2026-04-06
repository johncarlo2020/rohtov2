document.addEventListener('DOMContentLoaded', function () {
    const mainContent = document.getElementById('mainContent');
    const scannerContainer = document.getElementById('scannerContainer');
    const startScannerBtn = document.getElementById('start-scanner');
    const startQuizBtn = document.getElementById('start-quiz');
    const quizContainer = document.getElementById('quizContainer');
    const forceQrElement = document.getElementById('forceQr');

        // Access config passed from Blade
    const stationConfig = window.stationConfig || {};
    const processQrCodeUrl = stationConfig.urls.process_qr_code;
    const congratsUrl = stationConfig.urls.congrats;
    const dashboardUrl = stationConfig.urls.dashboard;

    const stationId = stationConfig.station_id;
    const stationName = stationConfig.station_name;
    const checkImageUrl = stationConfig.assets.check_image;
    const errorImageUrl = stationConfig.assets.error_image;
    
    const perfumes = window.stationConfig.perfumes;
    const assetBase = window.stationConfig.asset_base;
    const processQuizUrl = stationConfig.urls.submit_quiz;

    let quizStartTime = null;

    // $('#scanCompleteModal').modal('show'); for testing purposes don't remove

    let currentQuestion = 0;
    let answers = [];

    const quizData = [
        {
            question: "Which word best describes your personal style?",
            options: [
                "Bold & sophisticated",
                "Vibrant & playful",
                "Romantic & intense",
                "Natural & radiant",
                "Edgy & fearless"
            ]
        },
        {
            question: "What's your ideal summer escape?",
            options: [
                "Rooftop bar in the city",
                "Tropical beach club",
                "Flower-filled countryside",
                "Beach getaway",
                "Music festival"
            ]
        },
        {
            question: "What's your favourite summer accessory?",
            options: [
                "Statement jewelry",
                "Flower hair clip",
                "Floral scarf",
                "Minimalist jewelry",
                "Sunglasses"
            ]
        },
        {
            question: "What's your favourite summer activity?",
            options: [
                "Dancing the night away",
                "Rooftop sunset date",
                "Picnics in the park",
                "Sunbathing on the beach",
                "Exploring new places"
            ]
        },
        {
            question: "Which scent family do you gravitate towards?",
            options: [
                "Floral & warm",
                "Fruity & floral",
                "Floral & sweet",
                "Citrus & floral",
                "Floral & gourmand"
            ]
        }
    ];

    if(startQuizBtn)
    {
        startQuizBtn.addEventListener('click', startQuiz);
    }

    

    function startQuiz() {
        mainContent.classList.add('d-none');
        quizContainer.classList.remove('d-none');
        document.getElementById("quiz-container").style.display = "block";
        startQuizBtn.style.display = "none"; // hide button
        quizStartTime = Date.now();
        showQuestion();
    }

    function showQuestion() {
        const q = quizData[currentQuestion];

        document.getElementById("question-title").innerText = `Q${currentQuestion + 1}`;
        document.getElementById("question-text").innerText = q.question;

        const optionsContainer = document.getElementById("options");
        optionsContainer.innerHTML = "";

        q.options.forEach((option, index) => {
        const btn = document.createElement("button");
        btn.innerText = option;

        btn.classList.add("option-btn","p-3","mb-4","text-uppercase");


        btn.addEventListener('click', () => {
            selectAnswer(index + 1, option);
            });

            optionsContainer.appendChild(btn);
        });
    }

    function selectAnswer(value, text) {
        answers[currentQuestion] = {
            value: value, // ✅ number (1–5)
            text: text    // ✅ display text
        };

        currentQuestion++;

        if (currentQuestion < quizData.length) {
            showQuestion();
        } else {
            finishQuiz();
        }
    }

    // 🔥 Majority logic (your rule)
    function getPerfumeFromAnswers(answers) {
        const count = {};

        // count occurrences
        answers.forEach(ans => {
            count[ans.value] = (count[ans.value] || 0) + 1;
        });

        let maxCount = 0;
        let candidates = [];

        // find max + candidates
        for (const val in count) {
            if (count[val] > maxCount) {
                maxCount = count[val];
                candidates = [Number(val)];
            } else if (count[val] === maxCount) {
                candidates.push(Number(val));
            }
        }

        // ✅ no tie → return winner
        if (candidates.length === 1) {
            return candidates[0];
        }

        // 🔥 tie → ALWAYS pick last answer
        return answers[answers.length - 1].value;
    }

    function finishQuiz() {
        const perfumeNumber = getPerfumeFromAnswers(answers);
        const endTime = Date.now();

        // ✅ time in seconds
        const timeSpent = Math.floor((endTime - quizStartTime) / 1000);

        console.log("Time Spent:", timeSpent, "seconds");

        const perfumes = window.stationConfig.perfumes;
        const selectedPerfume = perfumes.find(p => p.id == perfumeNumber);
        const stationId = window.stationConfig.station_id;

        if (!selectedPerfume) {
            console.error("Perfume not found");
            return;
        }

        console.log("Perfume:", selectedPerfume);

        // ✅ SHOW RESULT UI
        document.getElementById("quiz-container").innerHTML = `
            <div class="text-center mt-4">

                <h3 class="mb-3 text-title text-center fw-bold">FIND YOUR LIBRE</h3>

                <!-- ✅ YOUR IMAGE FORMAT -->
                <img class="station-image mb-4"
                    src="${assetBase}images/perfumes/A${selectedPerfume.id}.webp"
                    alt="${selectedPerfume.title}">

                <h4 class="mb-4 text-uppercase">${selectedPerfume.title}</h4>

                <button id="perfume-next-btn" class="text-dark custom-btn-secondary px-3 py-2 m-auto">
                    NEXT
                </button>

            </div>
        `;
        

        // ✅ HANDLE NEXT CLICK (AJAX SAVE)
        document.getElementById("perfume-next-btn").addEventListener("click", function () {

            $.ajax({
                url: processQuizUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                data: {
                    perfume_id: selectedPerfume.id,
                    station_id: stationId,
                    time_spent: timeSpent
                },
                success: function () {
                    console.log("Saved!");

                    // ✅ reload current page
                    window.location.reload();
                },
                error: function (err) {
                    console.error("Save failed", err);
                }
            });

        });
    }


    let count = 0;
    let lastClick = 0;


    if(startScannerBtn) {
        startScannerBtn.addEventListener('click', function (event) {
            event.preventDefault();

            mainContent.classList.add('d-none');
            scannerContainer.classList.remove('d-none');

            const html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 200, aspectRatio: 1.0 },
                (qrCodeMessage) => {
                    sendMessage(qrCodeMessage);
                    html5QrCode.stop();
                },
                (errorMessage) => {
                    // console.log(`QR Code no longer in front of camera.`);
                }
            ).catch((err) => {
                console.log(`Unable to start scanning, error: ${err}`);
            });
    });

    }

    
    function sendMessage(message) {
        // Get selected gift ID for station 3
        let selectedGiftId = null;
        if (stationId == 7) {
            const giftSelect = document.getElementById('giftSelect');
            if (giftSelect && giftSelect.value) {
                selectedGiftId = giftSelect.value;
                // Show confirmation modal instead of directly processing
                if (window.showGiftConfirmation) {
                    window.showGiftConfirmation(message, selectedGiftId);
                }
                return; // Stop here, let modal handle the rest
            } else {
                alert('Please select a gift before scanning');
                return;
            }
        }

        // For other stations (not station 3), proceed normally
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: processQrCodeUrl,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            data: {
                qrCodeMessage: message,
                station: stationId,
            },
            success: function (response) {
                const confettiCanvas = document.createElement('canvas');
                // ... (confetti logic from original file)
                $('#badge').attr('src', checkImageUrl);
                $('#scanCompleteModal').modal('show');

                const trimmedMessage = message.trim();
                const lastCharacter = trimmedMessage.charAt(trimmedMessage.length - 1);

                $('.station_id').html(lastCharacter);
                $('.station_name').html(stationName);
                $('.status-text').html('Check-in Successful');
                $('#routeBtn').text('NEXT');

                if (lastCharacter == 5 ) {
                    document.getElementById('routeBtn').setAttribute('href', congratsUrl);
                }
                else 
                {
                    const stationParsed = parseInt(lastCharacter);
                    const nextStation = stationParsed  + 1;
                }
            },
            error: function (xhr, status, error) {
                console.error('Error sending QR Code message:', error);
                $('.modal-icon').addClass('d-none');
                $('.station_name_container').addClass('d-none');
                // $('.station-text').html('Failed');
                $('.message').html('Invalid QR Code');
                $('.check').attr('src', errorImageUrl);
                $('#scanCompleteModal').modal('show');
                $('#routeBtn')
                .removeAttr('href') // remove href if it exists
                .attr('onclick', `gotoStation(${stationId})`);
                
            }
        });
    }

    if (forceQrElement) {
        forceQrElement.addEventListener('click', function () {
            const now = new Date().getTime();
            if (now - lastClick < 500) {
                count++;
                if (count === 3) {
                    $('#manualQR').modal('show');
                    count = 0;
                }
            } else {
                count = 0;
            }
            lastClick = now;
        });
    }

});
