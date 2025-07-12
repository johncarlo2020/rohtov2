<x-app-layout>
    <div class="pledge-page ipad-pledge main-content main-background with-scroll">
        <div class="d-flex justify-content-center animate-entry">
            @include('components.branding')
        </div>
        <div id="type"
            class="mt-1 mb-2 d-flex flex-column align-items-center justify-content-center animate-entry delay-2 steps">

            <p class="heading mb-2 fw-thin">
                Save the Blue Pledge
            </p>
            <p class="mb-5 fw-thin">
                Choose your design
            </p>

            <div class="pledge-selection row animate-entry delay-4 px-5 mb-5">
                <div class="radio-image col">
                    <input type="radio" id="design1" name="design" value="text" class="d-none">
                    <label for="design1">
                        <img class="mb-3" src="{{ asset('images/brand/withMessage.webp') }}" alt="Design 1">
                        <p class="text-center">Bubble with message</p>
                    </label>
                </div>
                <div class="radio-image col">
                    <input type="radio" id="design2" name="design" value="coral" class="d-none">
                    <label for="design2">
                        <img class="mb-3" src="{{ asset('images/brand/CoralWithName.webp') }}" alt="Design 2">
                        <p class="text-center">Coral with name</p>
                    </label>
                </div>
            </div>

            <button id="selectTypeBtn" class="custom-btn custom-btn-secondary animate-entry delay-5"
                disabled>Continue</button>
        </div>
        <div id="text" class="d-none steps">
            <h1 class="heading animate-entry delay-1 my-5">
                Write your <span id="selectionTypeLabel"></span>
            </h1>
            <div class="mb-3 animate-entry delay-3">
                <input type="text" class="form-control" id="bubbleText">
                <p id="textHelp" class="small-text mt-1">*Maximum <span id="numberOfCharacters"></span> character</p>
            </div>
            <button id="selectText" class="custom-btn custom-btn-secondary animate-entry delay-5 w-100"
                disabled>Submit</button>
        </div>
        <div id="coral" class="d-none steps">
            <h1 class="heading animate-entry delay-1 my-5">
                Choose your coral
            </h1>
            <div class="mb-5 animate-entry delay-3">
                <div class="coral-selection-container w-100">
                    <div class="coral-ipad-container">
                        @for ($i = 1; $i <= 6; $i++)
                            <label class="coral item-container" data-id="{{ $i }}">
                                <input type="radio" name="coral" value="{{ $i }}"
                                    class="d-none coral-radio" />
                                <div class="coral-image-container">
                                    <img class="bubble" src="{{ asset('images/brand/bubble_Overlay.webp') }}"
                                        crossOrigin="anonymous" alt="Design 2">
                                    <img class="coral slick-img mx-auto"
                                        src="{{ asset('images/brand/coral/' . $i . '.webp') }}"
                                        alt="coral {{ $i }}" />
                                </div>
                            </label>
                        @endfor
                    </div>
                </div>
            </div>
            <div class="button-container d-flex justify-content-center animate-entry delay-5">
                <button id="selectCoralBtn"
                    class="custom-btn custom-btn-secondary animate-entry delay-5 mx-auto">Select</button>
            </div>
        </div>
        <div id="finish" class="d-none steps">
            <h1 class="heading animate-entry delay-1 mt-5 mb-0">
                Total pledge
            </h1>
            <div class="counter-container mb-2 animate-entry delay-3">
                @include('components.counter')
            </div>
            <div id="bubbleContainer" class="bubble-container mb-4 animate-entry delay-4">
                <img class="bubble d-none" src="{{ asset('images/brand/bubble_Overlay.webp') }}" crossOrigin="anonymous"
                    alt="Design 2">
                <div id="selectedOption"></div>
            </div>

            <div class="buttons d-flex justify-content-center animate-entry delay-5">
                <button id="pledgeBtn" class="custom-btn custom-btn-secondary animate-entry delay-5 mb-3 w-auto mx-auto">Pledge
                    now</button>
            </div>

        </div>
        <!-- Modal -->
        <div class="modal fade custom-modal animate-entry delay-2" id="thankYouModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered w-75 m-auto">
                <div class="modal-content card">
                    <div class="modal-body">
                        <div class="text-center content">
                            <div class="text-content mt-4 mb-4">
                                <p class="message">
                                    #4<br>
                                    Thank you!
                                </p>
                            </div>
                            <div class="d-block gap-3">
                                <button id="modalCloseBtn" type="button"
                                    class="custom-btn custom-btn-primary w-50 mb-3">
                                    CLOSE
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const steps = ['type', 'text', 'coral', 'finish'];
            let currentStep = 0;
            let pledgeData = {
                type: '',
                text: '',
                coral: '',
                finish: ''
            };

            // Configuration for each coral ID - sign colors and positions
            const coralSignConfig = {
                1: {
                    backgroundColor: '#ffffff',
                    borderColor: '#3852A5',
                    textColor: '#3852A5',
                    position: {
                        top: '38%',
                        left: '73%'
                    },
                    stickPosition: {
                        top: '70%',
                        left: '65%'
                    },
                    tilt: 15, // degrees
                    stickTilt: 15 // degrees, new property
                },
                2: {
                    backgroundColor: '#3852A5',
                    borderColor: '#ffff',
                    textColor: '#ffff',
                    position: {
                        top: '38%',
                        left: '73%'
                    },
                    stickPosition: {
                        top: '70%',
                        left: '65%'
                    },
                    tilt: 15, // degrees
                    stickTilt: 15 // degrees, new property
                },
                3: {
                    backgroundColor: '#3852A5',
                    borderColor: '#ffff',
                    textColor: '#ffff',
                    position: {
                        top: '35%',
                        left: '35%'
                    },
                    stickPosition: {
                        top: '60%',
                        left: '40%'
                    },
                    tilt: -12,
                    stickTilt: -12
                },
                4: {
                    backgroundColor: '#ffffff',
                    borderColor: '#3852A5',
                    textColor: '#3852A5',
                    position: {
                        top: '35%',
                        left: '40%'
                    },
                    stickPosition: {
                        top: '64%',
                        left: '40%'
                    },
                    tilt: 0,
                    stickTilt: 0
                },
                5: {
                    backgroundColor: '#3852A5',
                    borderColor: '#ffffff',
                    textColor: '#ffffff',
                    position: {
                        top: '32%',
                        left: '40%'
                    },
                    stickPosition: {
                        top: '60%',
                        left: '40%'
                    },
                    tilt: 6,
                    stickTilt: 0
                },
                6: {
                    backgroundColor: '#ffffff',
                    borderColor: '#3852A5',
                    textColor: '#3852A5',
                    position: {
                        top: '40%',
                        left: '33%'
                    },
                    stickPosition: {
                        top: '70%',
                        left: '40%'
                    },
                    tilt: -10,
                    stickTilt: -10
                }
            };

            //type: coral,text

            const selectTypeBtn = document.getElementById('selectTypeBtn');
            const selectionType = document.getElementById('selectionType');
            const selectionTypeLabel = document.getElementById('selectionTypeLabel');
            const numberOfCharacters = document.getElementById('numberOfCharacters');
            const bubbleText = document.getElementById('bubbleText');
            const selectText = document.getElementById('selectText');
            const selectCoralBtn = document.getElementById('selectCoralBtn');
            const bubbleContainer = document.getElementById('bubbleContainer');
            const selectedOption = document.getElementById('selectedOption');
            const downloadBtn = document.getElementById('downloadBtn');
            const pledgeBtn = document.getElementById('pledgeBtn');

            // no longer using individual buttons; we'll get the current slick slide image

            // get the value of the selected radio button
            document.querySelectorAll('input[name="design"]').forEach((input) => {
                input.addEventListener('change', function() {
                    pledgeData.type = this.value;
                    selectTypeBtn.disabled = false;
                });
            });

            selectTypeBtn.addEventListener('click', function() {
                if (currentStep === 0) {
                    processTypeSelection();
                }
            });

            bubbleText.addEventListener('input', function() {
                const maxLength = pledgeData.type === 'text' ? 25 : 6;
                if (this.value.length > maxLength) {
                    this.value = this.value.slice(0, maxLength);
                }
                // Bad word checker
                const badWords = ['Fuck', 'Nigga', 'cibai', 'lancau', 'puki', 'bitch']; // Add your list here
                const inputValue = this.value.toLowerCase();
                const foundBadWord = badWords.find(word => inputValue.includes(word));
                if (foundBadWord) {
                    textHelp.textContent = 'Inappropriate language is not allowed.';
                    selectText.disabled = true;
                } else {
                    textHelp.textContent = `*Maximum ${maxLength} character`;
                    selectText.disabled = this.value.length === 0;
                }
            });

            selectText.addEventListener('click', function() {
                processTextSelection();
            });

            selectCoralBtn.addEventListener('click', function() {
                if (currentStep === 2) {
                    processCoralSelection();
                }
            });

            pledgeBtn.addEventListener('click', function() {
                uploadPledgeToServer();
            });

            function processTextSelection() {
                pledgeData.text = bubbleText.value;
                console.log('processTextSelection called:', {
                    text: pledgeData.text,
                    type: pledgeData.type
                });
                createBubbleCanvas(pledgeData, bubbleContainer);
                nextStep();
            }

            function processTypeSelection() {
                const nextStepIndex = steps.indexOf(pledgeData.type);
                if (nextStepIndex > 0) {

                    if (pledgeData.type === 'text') {
                        selectionTypeLabel.textContent = 'message';
                        numberOfCharacters.textContent = '25';
                        //change placeholder text
                        bubbleText.placeholder = 'Write your message here...';
                    } else {
                        selectionTypeLabel.textContent = 'name';
                        numberOfCharacters.textContent = '6';
                        bubbleText.placeholder = 'Write your name here...';
                    }

                    nextStep();
                }
            }

            function createBubbleCanvas(pledgeData, bubbleContainer) {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // Use higher resolution for better quality
                const width = 900;
                const height = 900;

                canvas.width = width;
                canvas.height = height;
                // Remove fixed style width/height to allow CSS or parent to control size
                canvas.removeAttribute('style');
                // Optionally, add a class for custom CSS control
                canvas.classList.add('pledge-bubble-canvas');
                bubbleContainer.innerHTML = '';
                bubbleContainer.appendChild(canvas);

                // Load image helper
                const loadImage = (src) =>
                    new Promise((resolve) => {
                        const img = new Image();
                        img.onload = () => resolve(img);
                        img.src = src;
                    });

                async function drawBubble() {
                    const bg = await loadImage(`{{ asset('images/brand/bubble_Overlay.webp') }}`);
                    // Draw the bubble slightly larger than the canvas, but not cut off
                    const bubbleScale = 1; // Reduced from 1.3
                    const bubbleW = width * bubbleScale;
                    const bubbleH = height * bubbleScale;
                    const bubbleX = (width - bubbleW) / 2;
                    const bubbleY = (height - bubbleH) / 2;
                    ctx.drawImage(bg, bubbleX, bubbleY, bubbleW, bubbleH);
                    if (pledgeData.type === 'text') {
                        ctx.fillStyle = 'white';
                        ctx.font = 'bold 75px "Palatino", serif';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
                        ctx.shadowBlur = 18;

                        // Improved text wrapping logic: split long words if needed
                        const maxWidth = width * 0.6;
                        const words = pledgeData.text.split(' ');
                        const lines = [];
                        let currentLine = '';
                        for (let i = 0; i < words.length; i++) {
                            let word = words[i];
                            // If the word itself is too long, split it
                            while (ctx.measureText(word).width > maxWidth) {
                                // Find the largest substring of word that fits
                                let fitLength = word.length;
                                while (fitLength > 0 && ctx.measureText(word.substring(0, fitLength)).width > maxWidth) {
                                    fitLength--;
                                }
                                if (fitLength === 0) break; // Should not happen
                                const fitting = word.substring(0, fitLength);
                                // Add currentLine if not empty
                                if (currentLine) {
                                    lines.push(currentLine);
                                    currentLine = '';
                                }
                                lines.push(fitting);
                                word = word.substring(fitLength);
                            }
                            const testLine = currentLine + (currentLine ? ' ' : '') + word;
                            const metrics = ctx.measureText(testLine);
                            if (metrics.width > maxWidth && currentLine) {
                                lines.push(currentLine);
                                currentLine = word;
                            } else {
                                currentLine = testLine;
                            }
                        }
                        if (currentLine) {
                            lines.push(currentLine);
                        }
                        // Center lines vertically
                        const lineHeight = 90;
                        const totalTextHeight = lines.length * lineHeight;
                        let startY = height / 2 - totalTextHeight / 2 + lineHeight / 2;
                        lines.forEach((line, i) => {
                            ctx.fillText(line, width / 2, startY + i * lineHeight);
                        });
                    } else if (pledgeData.type === 'coral') {
                        const coralId = pledgeData.coral;
                        const signConfig = coralSignConfig[coralId] || coralSignConfig[1];

                        const [coralImg, stickImg] = await Promise.all([
                            loadImage(`{{ asset('images/brand/coral-seperate') }}/${coralId}.webp`),
                            loadImage(`{{ asset('images/brand/coral-seperate/stick.webp') }}`)
                        ]);

                        // Draw stick using stickPosition and stickTilt
                        const stickWidth = width * 0.020;
                        const stickHeight = height * 0.30;
                        let stickX = width / 2;
                        let stickY = height * 0.62;
                        if (signConfig.stickPosition) {
                            const percentToPx = (percent, total) => {
                                if (typeof percent === 'string' && percent.endsWith('%')) {
                                    return parseFloat(percent) / 100 * total;
                                }
                                return percent;
                            };
                            stickX = percentToPx(signConfig.stickPosition.left, width);
                            stickY = percentToPx(signConfig.stickPosition.top, height);
                        }
                        const stickAngle = (signConfig.stickTilt || 0) * Math.PI / 180;
                        ctx.save();
                        ctx.translate(stickX, stickY);
                        ctx.rotate(stickAngle);
                        ctx.drawImage(stickImg, -stickWidth / 2, -stickHeight, stickWidth, stickHeight);
                        ctx.restore();

                        // Draw coral image (on top of stick)
                        const coralWidth = width * 0.6;
                        const coralHeight = height * 0.4;
                        const coralX = width / 2 - coralWidth / 2;
                        const coralY = height * 0.8 - coralHeight;
                        ctx.drawImage(coralImg, coralX, coralY, coralWidth, coralHeight);

                        // Draw name label (sign) at top of stick
                        let textX = stickX;
                        let textY = stickY - stickHeight;
                        if (signConfig.position) {
                            const percentToPx = (percent, total) => {
                                if (typeof percent === 'string' && percent.endsWith('%')) {
                                    return parseFloat(percent) / 100 * total;
                                }
                                return percent;
                            };
                            textX = percentToPx(signConfig.position.left, width);
                            textY = percentToPx(signConfig.position.top, height);
                        }
                        const angle = signConfig.tilt * Math.PI / 180;
                        ctx.save();
                        ctx.translate(textX, textY);
                        ctx.rotate(angle);

                        // Draw background rectangle for text
                        const padding = 54; // 3x original
                        const text = pledgeData.text;
                        ctx.font = 'bold 54px "Palatino", serif'; // 3x original
                        const textWidth = ctx.measureText(text).width + padding * 2;
                        const textHeight = 114; // 3x original
                        ctx.fillStyle = signConfig.backgroundColor;
                        ctx.strokeStyle = signConfig.borderColor;
                        ctx.lineWidth = 6;
                        ctx.beginPath();
                        ctx.roundRect(-textWidth / 2, -textHeight / 2, textWidth, textHeight, 24);
                        ctx.fill();
                        ctx.stroke();

                        // Draw text
                        ctx.fillStyle = signConfig.textColor;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.shadowColor = signConfig.textColor === '#ffffff' ?
                            'rgba(0,0,0,0.8)' : 'rgba(0,0,0,0.3)';
                        ctx.shadowBlur = 6;
                        ctx.fillText(text, 0, 0);

                        ctx.restore();
                    }
                }

                drawBubble();
            }

            // Helper to add roundRect support if not already there (optional in modern browsers)
            if (!CanvasRenderingContext2D.prototype.roundRect) {
                CanvasRenderingContext2D.prototype.roundRect = function(x, y, w, h, r) {
                    this.beginPath();
                    this.moveTo(x + r, y);
                    this.lineTo(x + w - r, y);
                    this.quadraticCurveTo(x + w, y, x + w, y + r);
                    this.lineTo(x + w, y + h - r);
                    this.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
                    this.lineTo(x + r, y + h);
                    this.quadraticCurveTo(x, y + h, x, y + h - r);
                    this.lineTo(x, y + r);
                    this.quadraticCurveTo(x, y, x + r, y);
                    this.closePath();
                };
            }


            function processCoralSelection() {
                // Get the selected coral radio value
                const selectedRadio = document.querySelector('input[name="coral"]:checked');
                if (selectedRadio) {
                    pledgeData.coral = selectedRadio.value;
                    createBubbleCanvas(pledgeData, bubbleContainer);
                    nextStep();
                } else {
                    // Optionally, show a warning or do nothing if nothing is selected
                }
            }

            function nextStep() {
                if (currentStep === steps.indexOf('text') && pledgeData.type === 'text') {
                    // On text step with text type, skip coral and jump to finish
                    currentStep = steps.indexOf('finish');
                    updateUI();
                } else if (currentStep < steps.length - 1 && pledgeData.type) {
                    // Normal progression for other steps
                    currentStep++;
                    updateUI();
                }
            }

            function previousStep() {
                // Handle step back
                if (currentStep === steps.indexOf('finish') && pledgeData.type === 'text') {
                    // from finish to text for text type
                    currentStep = steps.indexOf('text');
                } else if (currentStep > 0) {
                    currentStep--;
                }
                updateUI();
                // Clear data for the current step container
                const sel = document.getElementById('selectedOption');
                if (currentStep === steps.indexOf('text')) {
                    // clear text data and bubble preview
                    pledgeData.text = '';
                    if (bubbleText) bubbleText.value = '';
                    if (sel) sel.innerHTML = '';
                } else if (currentStep === steps.indexOf('coral')) {
                    // clear coral selection and bubble preview
                    pledgeData.coral = '';
                    if (sel) sel.innerHTML = '';
                }
            }

            function updateUI() {
                document.querySelectorAll('.steps').forEach((div) => {
                    div.classList.add('d-none');
                });

                const currentDiv = document.getElementById(steps[currentStep]);
                if (currentDiv) {
                    currentDiv.classList.remove('d-none');
                }

                // initialize slider when coral step appears
                if (steps[currentStep] === 'coral' && !$('.coral-container').hasClass('slick-initialized')) {
                    initializeSlick();
                }
            }


            function processDownload() {
                // Find the canvas inside bubbleContainer
                const canvas = bubbleContainer.querySelector('canvas');
                if (!canvas) {

                    return;
                }
                // Create a link and trigger download
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = 'pledge_canvas.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }



            // Example usage function for server upload
            function uploadPledgeToServer() {
                if (pledgeData.type === 'text') {
                    // Prefer uploading the actual canvas if present
                    const canvas = bubbleContainer.querySelector('canvas');
                    if (canvas) {
                        canvas.toBlob(blob => {
                            if (!blob) {
                                pledgeBtn.disabled = false;
                                return;
                            }
                            const formData = new FormData();
                            formData.append('pledge_image', blob, `pledge_text_${Date.now()}.png`);
                            formData.append('pledge_text', pledgeData.text);
                            formData.append('pledge_type', pledgeData.type);
                            fetch('{{ route('upload.babyIpad') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: formData
                                })
                                .then(async response => {
                                    if (!response.ok) {
                                        const text = await response.text();
                                        throw new Error('Upload failed: ' + text);
                                    }
                                    return response.json();

                                })
                                .then(data => {

                                })
                                .catch(error => {

                                    console.error('Upload failed:', error);
                                })
                                .finally(() => {
                                    pledgeBtn.disabled = false;
                                    returnToDashboard()
                                });
                        }, 'image/png');
                    } else {
                        // Fallback: use html2canvas if no canvas found
                        if (typeof html2canvas === 'undefined') {
                            pledgeBtn.disabled = false;
                            return;
                        }
                        html2canvas(bubbleContainer, {
                            backgroundColor: null
                        }).then(canvas => {
                            canvas.toBlob(blob => {
                                if (!blob) {
                                    pledgeBtn.disabled = false;
                                    return;
                                }
                                const formData = new FormData();
                                formData.append('pledge_image', blob, `pledge_text_${Date.now()}.png`);
                                formData.append('pledge_text', pledgeData.text);
                                formData.append('pledge_type', pledgeData.type);
                                fetch('{{ route('upload.babyIpad') }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                        body: formData
                                    })
                                    .then(async response => {
                                        if (!response.ok) {
                                            const text = await response.text();
                                            throw new Error('Upload failed: ' + text);
                                        }
                                        return response.json();
                                    })
                                    .then(data => {

                                    })
                                    .catch(error => {

                                        console.error('Upload failed:', error);
                                    })
                                    .finally(() => {
                                        pledgeBtn.disabled = false;
                                        returnToDashboard()
                                    });
                            }, 'image/png');
                        });
                    }
                } else if (pledgeData.type === 'coral') {
                    uploadCoralCanvas();
                }
            }

            function uploadCoralCanvas() {
                const width = 900;
                const height = 900;
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = width;
                canvas.height = height;
                // Helper to load images
                const loadImage = (src) => new Promise((resolve) => {
                    const img = new Image();
                    img.onload = () => resolve(img);
                    img.src = src;
                });
                (async function() {
                    const coralId = pledgeData.coral;
                    const signConfig = coralSignConfig[coralId] || coralSignConfig[1];
                    const [coralImg, stickImg] = await Promise.all([
                        loadImage(`{{ asset('images/brand/coral-seperate') }}/${coralId}.webp`),
                        loadImage(`{{ asset('images/brand/coral-seperate/stick.webp') }}`)
                    ]);
                    // Do NOT draw the bubble background for coral uploads
                    // Draw stick using stickPosition and stickTilt
                    const stickWidth = width * 0.020;
                    const stickHeight = height * 0.30;
                    let stickX = width / 2;
                    let stickY = height * 0.62;
                    if (signConfig.stickPosition) {
                        const percentToPx = (percent, total) => {
                            if (typeof percent === 'string' && percent.endsWith('%')) {
                                return parseFloat(percent) / 100 * total;
                            }
                            return percent;
                        };
                        stickX = percentToPx(signConfig.stickPosition.left, width);
                        stickY = percentToPx(signConfig.stickPosition.top, height);
                    }
                    const stickAngle = (signConfig.stickTilt || 0) * Math.PI / 180;
                    ctx.save();
                    ctx.translate(stickX, stickY);
                    ctx.rotate(stickAngle);
                    ctx.drawImage(stickImg, -stickWidth / 2, -stickHeight, stickWidth, stickHeight);
                    ctx.restore();
                    // Draw coral
                    const coralWidth = width * 0.6;
                    const coralHeight = height * 0.4;
                    const coralX = width / 2 - coralWidth / 2;
                    const coralY = height * 0.8 - coralHeight;
                    ctx.drawImage(coralImg, coralX, coralY, coralWidth, coralHeight);
                    // Draw sign
                    let textX = stickX;
                    let textY = stickY - stickHeight;
                    if (signConfig.position) {
                        const percentToPx = (percent, total) => {
                            if (typeof percent === 'string' && percent.endsWith('%')) {
                                return parseFloat(percent) / 100 * total;
                            }
                            return percent;
                        };
                        textX = percentToPx(signConfig.position.left, width);
                        textY = percentToPx(signConfig.position.top, height);
                    }
                    ctx.save();
                    ctx.translate(textX, textY);
                    ctx.rotate((signConfig.tilt || 0) * Math.PI / 180);
                    const padding = 54;
                    const text = pledgeData.text;
                    ctx.font = 'bold 54px "Palatino", serif';
                    const textWidth = ctx.measureText(text).width + padding * 2;
                    const textHeight = 114;
                    ctx.fillStyle = signConfig.backgroundColor;
                    ctx.strokeStyle = signConfig.borderColor;
                    ctx.lineWidth = 6;
                    ctx.beginPath();
                    ctx.roundRect(-textWidth / 2, -textHeight / 2, textWidth, textHeight, 24);
                    ctx.fill();
                    ctx.stroke();
                    ctx.fillStyle = signConfig.textColor;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.shadowColor = signConfig.textColor === '#ffffff' ? 'rgba(0,0,0,0.8)' : 'rgba(0,0,0,0.3)';
                    ctx.shadowBlur = 6;
                    ctx.fillText(text, 0, 0);
                    ctx.restore();
                    // Upload the generated canvas
                    canvas.toBlob(blob => {
                        if (!blob) {
                            pledgeBtn.disabled = false;
                            return;
                        }
                        const formData = new FormData();
                        formData.append('pledge_image', blob, `pledge_coral_${Date.now()}.png`);
                        formData.append('pledge_text', pledgeData.text);
                        formData.append('pledge_type', pledgeData.type);
                        fetch('{{ route('upload.babyIpad') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: formData
                            })
                            .then(async response => {
                                if (!response.ok) {
                                    const text = await response.text();
                                    throw new Error('Upload failed: ' + text);
                                }
                                return response.json();
                            })
                            .then(data => {
                                // go to station 4 again
                                // window.location.href = '{{ route('station', 4) }}';
                            })
                            .catch(error => {
                                console.error('Upload failed:', error);
                            })
                            .finally(() => {
                                pledgeBtn.disabled = false;
                                returnToDashboard()
                            });
                    }, 'image/png');
                })();
            }

            function returnToDashboard() {
                window.location.href = "{{ route('ipad.index') }}"; // Adjust route if needed
            }


            // add deferred initialization function
            function initializeSlick() {
                const $carousel = $('.coral-container');
                if ($carousel.length) {
                    $carousel.slick({
                        dots: false,
                        arrows: false,
                        infinite: false,
                        speed: 300,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        focusOnSelect: true,
                    });
                    // bind custom nav buttons
                    document.getElementById('slickPrevBtn').addEventListener('click', () => $carousel.slick('slickPrev'));
                    document.getElementById('slickNextBtn').addEventListener('click', () => $carousel.slick('slickNext'));
                }
            }
        </script>
    @endpush
</x-app-layout>
