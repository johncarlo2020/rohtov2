<x-app-layout>
    <div class="pledge-page main-content main-background with-scroll">
        <div class="back-btn animate-entry">
            <button onclick="previousStep()" href="{{ route('dashboard') }}" class="">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        <div class="d-flex justify-content-center animate-entry">
            @include('components.branding')
        </div>
        <div id="type"
            class="mt-1 mb-2 d-flex flex-column align-items-center justify-content-center animate-entry delay-2 steps">
            <h1 class="heading mb-4 mt-3">STATION {{ $station->id }}</h1>
            <p class="sub-heading mb-2 fw-thin">
                {{ isset($station->name) ? $station->name : '' }}
            </p>
            <span class="mb-5 fw-thin">
                Choose your design
            </span>

            <div class="pledge-selection row animate-entry delay-4 px-3 mb-5">
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
                    <button id="slickPrevBtn" class="icon-btn prev"><i class="fa-solid fa-caret-left"></i></button>
                    <button id="slickNextBtn" class="icon-btn next"><i class="fa-solid fa-caret-right"></i></button>
                    <div class="coral-container w-100">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="coral item-container" data-id="{{ $i }}">
                                <div class="coral-image-container">
                                     <img class="bubble" src="{{ asset('images/brand/bubble_Overlay.webp') }}" crossOrigin="anonymous" alt="Design 2">
                                      <img class="coral slick-img mx-auto"
                                    src="{{ asset('images/brand/coral/' . $i . '.webp') }}"
                                    alt="coral {{ $i }}" />
                                </div>
                                <p class="text-center">Coral {{ $i }} </p>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            <button id="selectCoralBtn"
                class="custom-btn custom-btn-secondary animate-entry delay-5 w-100">Select</button>
        </div>
        <div id="finish" class="d-none steps">
            <h1 class="heading animate-entry delay-1 mt-5 mb-2">
                Total pledge
            </h1>
            <div class="counter-container mb-2 animate-entry delay-3">
                @include('components.counter')
            </div>
            <div id="bubbleContainer" class="bubble-container mb-4 animate-entry delay-4">
                <img class="bubble" src="{{ asset('images/brand/bubble_Overlay.webp') }}" crossOrigin="anonymous" alt="Design 2">
                <div id="selectedOption"></div>
            </div>

            <div class="buttons">
                  <button id="pledgeBtn"
                class="custom-btn custom-btn-secondary animate-entry delay-5 w-100 mb-3">Pledge now</button>
                  <button id="downloadBtn"
                class="custom-btn custom-btn-secondary animate-entry delay-5 w-100 mb-2">Download</button>
                  <button id="uploadBtn"
                class="custom-btn custom-btn-primary animate-entry delay-5 w-100">Get Content for Upload</button>
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
                    backgroundColor: '#3852A5',
                    borderColor: '#ffffff',
                    textColor: '#ffffff',
                    position: { top: '20%', left: '50%' },
                    stickPosition: { top: '35%', left: '50%' },
                    tilt: -8 // degrees
                },
                2: {
                    backgroundColor: '#ffffff',
                    borderColor: '#000000',
                    textColor: '#000000',
                    position: { top: '25%', left: '45%' },
                    stickPosition: { top: '40%', left: '45%' },
                    tilt: 5 // degrees
                },
                3: {
                    backgroundColor: '#3852A5',
                    borderColor: '#ffffff',
                    textColor: '#ffffff',
                    position: { top: '30%', left: '55%' },
                    stickPosition: { top: '45%', left: '55%' },
                    tilt: -12 // degrees
                },
                4: {
                    backgroundColor: '#ffffff',
                    borderColor: '#000000',
                    textColor: '#000000',
                    position: { top: '22%', left: '40%' },
                    stickPosition: { top: '37%', left: '40%' },
                    tilt: 7 // degrees
                },
                5: {
                    backgroundColor: '#3852A5',
                    borderColor: '#ffffff',
                    textColor: '#ffffff',
                    position: { top: '28%', left: '60%' },
                    stickPosition: { top: '43%', left: '60%' },
                    tilt: -6 // degrees
                },
                6: {
                    backgroundColor: '#ffffff',
                    borderColor: '#000000',
                    textColor: '#000000',
                    position: { top: '25%', left: '50%' },
                    stickPosition: { top: '40%', left: '50%' },
                    tilt: 10 // degrees
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
            const uploadBtn = document.getElementById('uploadBtn');
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
                selectText.disabled = this.value.length === 0;
            });

            selectText.addEventListener('click', function() {
                processTextSelection();
            });

             selectCoralBtn.addEventListener('click', function() {
                if (currentStep === 2) {
                    processCoralSelection();
                }
            });

            function processTextSelection() {
                pledgeData.text = bubbleText.value;
                console.log('processTextSelection called:', {
                    text: pledgeData.text,
                    type: pledgeData.type
                });
                createBubble();
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

            function createBubble() {
                console.log('createBubble called with:', {
                    type: pledgeData.type,
                    text: pledgeData.text,
                    selectedOption: selectedOption
                });

                // Clear any existing content
                selectedOption.innerHTML = '';

                if (pledgeData.type === 'text') {
                    const bubbleTextElement = document.createElement('p');
                    bubbleTextElement.textContent = pledgeData.text;
                    bubbleTextElement.className = 'bubble-text';

                    // Add explicit inline styles to ensure visibility in html2canvas
                    bubbleTextElement.style.position = 'absolute';
                    bubbleTextElement.style.top = '0';
                    bubbleTextElement.style.left = '0';
                    bubbleTextElement.style.width = '100%';
                    bubbleTextElement.style.height = '100%';
                    bubbleTextElement.style.display = 'flex';
                    bubbleTextElement.style.alignItems = 'center';
                    bubbleTextElement.style.justifyContent = 'center';
                    bubbleTextElement.style.color = 'white';
                    // Use same logic as download for font size - keep consistent 25px
                    const fontSize = '25px';
                    bubbleTextElement.style.fontSize = fontSize;
                    bubbleTextElement.style.fontWeight = 'bold';
                    bubbleTextElement.style.textAlign = 'center';
                    bubbleTextElement.style.zIndex = '999';
                    bubbleTextElement.style.pointerEvents = 'none';
                    bubbleTextElement.style.fontFamily = '"Palatino", "Palatino Linotype", "Book Antiqua", Georgia, serif';
                    // Add text wrapping properties
                    bubbleTextElement.style.wordWrap = 'break-word';
                    bubbleTextElement.style.whiteSpace = 'normal';
                    bubbleTextElement.style.lineHeight = '1.2';
                    bubbleTextElement.style.padding = '0 60px'; // Add some padding to match canvas text area
                    // Add text shadow to match download
                    bubbleTextElement.style.textShadow = '2px 2px 6px rgba(0, 0, 0, 0.8)';

                    selectedOption.appendChild(bubbleTextElement);

                    console.log('Text element created and added:', {
                        text: bubbleTextElement.textContent,
                        innerHTML: selectedOption.innerHTML
                    });
                } else if (pledgeData.type === 'coral') {
                    // For coral type, show the coral with stick sign
                    const coralContainer = document.createElement('div');
                    coralContainer.className = 'coral-display';

                    // Add inline styles for proper positioning
                    coralContainer.style.position = 'absolute';
                    coralContainer.style.top = '0';
                    coralContainer.style.left = '0';
                    coralContainer.style.width = '100%';
                    coralContainer.style.height = '100%';

                    // Create coral image (bottom layer)
                    const coralImg = document.createElement('img');
                    coralImg.src = `{{ asset('images/brand/coral-seperate') }}/${pledgeData.coral}.webp`;
                    coralImg.alt = `Coral ${pledgeData.coral}`;
                    coralImg.className = 'selected-coral-img';
                    coralImg.style.position = 'absolute';
                    coralImg.style.bottom = '20%';
                    coralImg.style.left = '50%';
                    coralImg.style.transform = 'translateX(-50%)';
                    coralImg.style.maxWidth = '60%';
                    coralImg.style.maxHeight = '40%';
                    coralImg.style.objectFit = 'contain';
                    coralImg.style.zIndex = '1';

                    // Create stick image (middle layer)
                    const stickImg = document.createElement('img');
                    stickImg.src = `{{ asset('images/brand/coral-seperate/stick.webp') }}`;
                    stickImg.alt = 'Sign stick';
                    stickImg.className = 'coral-stick';

                    // Get configuration for this coral ID
                    const signConfig = coralSignConfig[pledgeData.coral] || coralSignConfig[1]; // fallback to coral 1 config

                    stickImg.style.position = 'absolute';
                    stickImg.style.top = signConfig.stickPosition.top;
                    stickImg.style.left = signConfig.stickPosition.left;
                    stickImg.style.transform = 'translate(-50%, -50%)';
                    stickImg.style.maxWidth = '15%';
                    stickImg.style.maxHeight = '45%';
                    stickImg.style.objectFit = 'contain';
                    stickImg.style.zIndex = '0'; // Behind everything - lowest layer

                    // Create text element (top layer - on the stick like a sign)
                    const nameElement = document.createElement('p');
                    nameElement.textContent = pledgeData.text;
                    nameElement.className = 'coral-name';

                    nameElement.style.position = 'absolute';
                    nameElement.style.top = signConfig.position.top;
                    nameElement.style.left = signConfig.position.left;
                    nameElement.style.transform = `translate(-50%, -50%) rotate(${signConfig.tilt}deg)`;
                    nameElement.style.color = signConfig.textColor;
                    nameElement.style.fontSize = '16px';
                    nameElement.style.fontWeight = 'bold';
                    nameElement.style.fontFamily = '"Palatino", "Palatino Linotype", "Book Antiqua", Georgia, serif';
                    nameElement.style.textAlign = 'center';
                    nameElement.style.textShadow = signConfig.textColor === '#ffffff' ? '2px 2px 4px rgba(0, 0, 0, 0.8)' : '1px 1px 2px rgba(0, 0, 0, 0.3)';
                    nameElement.style.backgroundColor = signConfig.backgroundColor;
                    nameElement.style.padding = '6px 10px';
                    nameElement.style.borderRadius = '6px';
                    nameElement.style.border = `2px solid ${signConfig.borderColor}`;
                    nameElement.style.minWidth = '70px';
                    nameElement.style.maxWidth = '120px';
                    nameElement.style.zIndex = '100'; // Above everything else

                    coralContainer.appendChild(coralImg);
                    coralContainer.appendChild(stickImg);
                    coralContainer.appendChild(nameElement);
                    selectedOption.appendChild(coralContainer);
                }
            }

            function processCoralSelection() {
               const slickInstance = $('.coral-container').slick('getSlick');
                const firstVisible = $(slickInstance.$slides.get(slickInstance.currentSlide)).data('id');
                pledgeData.coral = firstVisible;
                createBubble();
                nextStep();
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

            downloadBtn.addEventListener('click', function() {
                processDownload();
            });

            uploadBtn.addEventListener('click', function() {
                uploadPledgeToServer();
            });

            function processDownload() {
                if (pledgeData.type === 'text') {
                    downloadPledgeTypeText();
                } else if (pledgeData.type === 'coral') {
                    downloadPledgeTypeCoral();
                } else {
                    alert('Please complete your pledge selection first.');
                }
            }

            function downloadPledgeTypeText(){
                console.log('=== DOWNLOAD DEBUG ===');
                console.log('Pledge data:', pledgeData);
                console.log('Bubble container:', bubbleContainer);
                console.log('Selected option element:', selectedOption);
                console.log('Selected option innerHTML:', selectedOption ? selectedOption.innerHTML : 'null');
                console.log('Bubble container innerHTML:', bubbleContainer ? bubbleContainer.innerHTML : 'null');

                // Ensure the bubble container is visible and has content
                if (!bubbleContainer || bubbleContainer.classList.contains('d-none')) {
                    alert('Please complete your pledge before downloading.');
                    return;
                }

                // Check if we have content in selectedOption
                if (!selectedOption.innerHTML.trim()) {
                    console.log('No content in selectedOption, recreating bubble...');
                    createBubble();
                    console.log('After recreating - selectedOption innerHTML:', selectedOption.innerHTML);
                }

                // Wait for images to load before capturing
                const img = bubbleContainer.querySelector('img.bubble');
                if (img && !img.complete) {
                    img.onload = () => {
                        // Add a small delay to ensure everything is rendered
                        setTimeout(captureAndDownload, 200);
                    };
                } else {
                    // Add a small delay to ensure everything is rendered
                    setTimeout(captureAndDownload, 200);
                }

                function captureAndDownload() {
                    console.log('=== CAPTURE DEBUG ===');
                    console.log('Bubble container dimensions:', {
                        width: bubbleContainer.offsetWidth,
                        height: bubbleContainer.offsetHeight,
                        visible: !bubbleContainer.classList.contains('d-none'),
                        computedStyle: window.getComputedStyle(bubbleContainer).display
                    });

                    // Debug the bubble text positioning
                    const bubbleTextEl = selectedOption.querySelector('.bubble-text');
                    if (bubbleTextEl) {
                        const textRect = bubbleTextEl.getBoundingClientRect();
                        const containerRect = bubbleContainer.getBoundingClientRect();
                        console.log('Bubble text positioning:', {
                            text: bubbleTextEl.textContent,
                            textRect: textRect,
                            containerRect: containerRect,
                            textComputedStyle: {
                                position: window.getComputedStyle(bubbleTextEl).position,
                                top: window.getComputedStyle(bubbleTextEl).top,
                                left: window.getComputedStyle(bubbleTextEl).left,
                                transform: window.getComputedStyle(bubbleTextEl).transform,
                                zIndex: window.getComputedStyle(bubbleTextEl).zIndex,
                                color: window.getComputedStyle(bubbleTextEl).color,
                                fontSize: window.getComputedStyle(bubbleTextEl).fontSize,
                                fontFamily: window.getComputedStyle(bubbleTextEl).fontFamily
                            }
                        });
                    }

                    // Check if html2canvas is available
                    if (typeof html2canvas === 'undefined') {
                        console.error('html2canvas library not loaded');
                        alert('Download feature is not available. Please refresh the page and try again.');
                        return;
                    }

                    console.log('Starting manual canvas creation...');

                    // Create canvas manually
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // Get bubble container dimensions
                    const containerWidth = bubbleContainer.offsetWidth;
                    const containerHeight = bubbleContainer.offsetHeight;

                    // Set canvas size
                    canvas.width = containerWidth;
                    canvas.height = containerHeight;

                    console.log('Canvas dimensions set to:', {
                        width: canvas.width,
                        height: canvas.height
                    });

                    // Load the bubble image
                    const bubbleImg = bubbleContainer.querySelector('img.bubble');
                    if (bubbleImg && bubbleImg.complete) {
                        console.log('Drawing bubble image...');

                        // Draw the bubble image
                        ctx.drawImage(bubbleImg, 0, 0, canvas.width, canvas.height);

                        // Draw the text
                        if (pledgeData.text) {

                            // Set text properties
                            ctx.fillStyle = 'white';
                            ctx.font = 'bold 25px "Palatino", "Palatino Linotype", "Book Antiqua", Georgia, serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            // Add text shadow for better visibility
                            ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
                            ctx.shadowBlur = 6;
                            ctx.shadowOffsetX = 2;
                            ctx.shadowOffsetY = 2;

                            // Calculate text area (bubble center area, smaller than full canvas)
                            const textAreaWidth = canvas.width * 0.6; // 60% of canvas width
                            const textAreaHeight = canvas.height * 0.4; // 40% of canvas height
                            const centerX = canvas.width / 2;
                            const centerY = canvas.height / 2;

                            // Split text into words and wrap lines
                            const words = pledgeData.text.split(' ');
                            const lines = [];
                            let currentLine = '';

                            // Calculate optimal font size - keep consistent with preview (25px)
                            let fontSize = 25;
                            let lineHeight = fontSize * 1.2;

                            // Keep font size consistent, don't reduce it
                            // Text wrapping will handle longer text

                            // Create lines that fit within the text area
                            for (let i = 0; i < words.length; i++) {
                                const testLine = currentLine + (currentLine ? ' ' : '') + words[i];
                                const metrics = ctx.measureText(testLine);

                                if (metrics.width > textAreaWidth && currentLine) {
                                    lines.push(currentLine);
                                    currentLine = words[i];
                                } else {
                                    currentLine = testLine;
                                }
                            }
                            if (currentLine) {
                                lines.push(currentLine);
                            }

                            // Calculate starting Y position to center all lines
                            const totalTextHeight = lines.length * lineHeight;
                            let startY = centerY - (totalTextHeight / 2) + (lineHeight / 2);

                            // Draw each line
                            lines.forEach((line, index) => {
                                const y = startY + (index * lineHeight);
                                ctx.fillText(line, centerX, y);
                            });
                        }

                        downloadCanvas(canvas, 'pledge_bubble_manual.png');

                    } else {
                        console.log('Bubble image not loaded, creating fallback...');

                        // Create a simple background
                        ctx.fillStyle = '#4a90e2';
                        ctx.fillRect(0, 0, canvas.width, canvas.height);

                        // Draw border
                        ctx.strokeStyle = '#ffffff';
                        ctx.lineWidth = 4;
                        ctx.strokeRect(0, 0, canvas.width, canvas.height);

                        // Draw text
                        if (pledgeData.text) {
                            ctx.fillStyle = 'white';
                            ctx.font = 'bold 25px "Palatino", "Palatino Linotype", "Book Antiqua", Georgia, serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            // Add shadow
                            ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
                            ctx.shadowBlur = 4;
                            ctx.shadowOffsetX = 2;
                            ctx.shadowOffsetY = 2;

                            ctx.fillText(pledgeData.text, canvas.width / 2, canvas.height / 2);
                        }

                        downloadCanvas(canvas, 'pledge_bubble_fallback.png');
                    }

                    function downloadCanvas(canvas, filename) {
                        const link = document.createElement('a');
                        link.href = canvas.toDataURL('image/png');
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                }
            }

            function downloadPledgeTypeCoral() {
                console.log('=== CORAL DOWNLOAD DEBUG ===');
                console.log('Pledge data:', pledgeData);
                console.log('Bubble container:', bubbleContainer);

                // Ensure we have coral data
                if (!pledgeData.coral || !pledgeData.text) {
                    alert('Please complete your coral pledge before downloading.');
                    return;
                }

                // Get coral configuration
                const config = coralSignConfig[pledgeData.coral];
                if (!config) {
                    alert('Coral configuration not found. Please try again.');
                    return;
                }

                console.log('Using coral config:', config);

                // Create canvas for download
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // Set canvas size to match the bubble container
                const containerWidth = bubbleContainer.offsetWidth;
                const containerHeight = bubbleContainer.offsetHeight;
                canvas.width = containerWidth;
                canvas.height = containerHeight;

                console.log('Canvas dimensions set to:', {
                    width: canvas.width,
                    height: canvas.height
                });

                // Track loaded images
                let imagesLoaded = 0;
                const totalImages = 2; // coral and stick images
                const images = {};

                function checkAllImagesLoaded() {
                    if (imagesLoaded === totalImages) {
                        renderCoralCanvas();
                    }
                }

                // Load coral image
                const coralImg = new Image();
                coralImg.crossOrigin = 'anonymous';
                coralImg.onload = function() {
                    images.coral = coralImg;
                    imagesLoaded++;
                    console.log('Coral image loaded');
                    checkAllImagesLoaded();
                };
                coralImg.onerror = function() {
                    console.error('Failed to load coral image');
                    alert('Failed to load coral image. Please try again.');
                };
                coralImg.src = `{{ asset('images/brand/coral-seperate') }}/${pledgeData.coral}.webp`;

                // Load stick image
                const stickImg = new Image();
                stickImg.crossOrigin = 'anonymous';
                stickImg.onload = function() {
                    images.stick = stickImg;
                    imagesLoaded++;
                    console.log('Stick image loaded');
                    checkAllImagesLoaded();
                };
                stickImg.onerror = function() {
                    console.error('Failed to load stick image');
                    alert('Failed to load stick image. Please try again.');
                };
                stickImg.src = `{{ asset('images/brand/coral-seperate/stick.webp') }}`;

                function renderCoralCanvas() {
                    console.log('Rendering coral canvas...');

                    // First, draw the bubble background (like in text download)
                    const bubbleImg = bubbleContainer.querySelector('img.bubble');
                    if (bubbleImg && bubbleImg.complete) {
                        // Draw the bubble background
                        ctx.drawImage(bubbleImg, 0, 0, canvas.width, canvas.height);
                    }

                    // Convert percentage positions to pixel positions for canvas
                    const stickPosX = (parseFloat(config.stickPosition.left) / 100) * canvas.width;
                    const stickPosY = (parseFloat(config.stickPosition.top) / 100) * canvas.height;
                    const signPosX = (parseFloat(config.position.left) / 100) * canvas.width;
                    const signPosY = (parseFloat(config.position.top) / 100) * canvas.height;

                    // Layer 1: Draw stick (behind coral)
                    if (images.stick) {
                        const stickWidth = canvas.width * 0.05; // 5% of canvas width
                        const stickHeight = canvas.height * 0.45; // 45% of canvas height
                        const stickX = stickPosX - stickWidth / 2;
                        const stickY = stickPosY - stickHeight / 2;

                        ctx.drawImage(images.stick, stickX, stickY, stickWidth, stickHeight);
                        console.log('Drew stick at:', { x: stickX, y: stickY, width: stickWidth, height: stickHeight });
                    }

                    // Layer 2: Draw coral (middle layer)
                    if (images.coral) {
                        const coralWidth = canvas.width * 0.6; // 60% of canvas width
                        const coralHeight = canvas.height * 0.4; // 40% of canvas height
                        const coralX = canvas.width * 0.5 - coralWidth / 2; // Center horizontally
                        const coralY = canvas.height * 0.8 - coralHeight; // Position at bottom 20%

                        ctx.drawImage(images.coral, coralX, coralY, coralWidth, coralHeight);
                        console.log('Drew coral at:', { x: coralX, y: coralY, width: coralWidth, height: coralHeight });
                    }                    // Layer 3: Draw sign with text (front layer)
                    if (pledgeData.text) {
                        const signWidth = canvas.width * 0.3; // 30% of canvas width
                        const signHeight = canvas.height * 0.15; // 15% of canvas height
                        const signX = signPosX - signWidth / 2;
                        const signY = signPosY - signHeight / 2;

                        // Save the current canvas state before applying rotation
                        ctx.save();

                        // Apply rotation around the sign center
                        ctx.translate(signPosX, signPosY);
                        ctx.rotate((config.tilt * Math.PI) / 180); // Convert degrees to radians
                        ctx.translate(-signPosX, -signPosY);

                        // Draw sign background with rounded corners
                        const borderRadius = 6; // Match the CSS border-radius

                        // Clear any previous shadow settings
                        ctx.shadowColor = 'transparent';
                        ctx.shadowBlur = 0;
                        ctx.shadowOffsetX = 0;
                        ctx.shadowOffsetY = 0;

                        ctx.fillStyle = config.backgroundColor;

                        // Create rounded rectangle path
                        ctx.beginPath();
                        ctx.moveTo(signX + borderRadius, signY);
                        ctx.lineTo(signX + signWidth - borderRadius, signY);
                        ctx.quadraticCurveTo(signX + signWidth, signY, signX + signWidth, signY + borderRadius);
                        ctx.lineTo(signX + signWidth, signY + signHeight - borderRadius);
                        ctx.quadraticCurveTo(signX + signWidth, signY + signHeight, signX + signWidth - borderRadius, signY + signHeight);
                        ctx.lineTo(signX + borderRadius, signY + signHeight);
                        ctx.quadraticCurveTo(signX, signY + signHeight, signX, signY + signHeight - borderRadius);
                        ctx.lineTo(signX, signY + borderRadius);
                        ctx.quadraticCurveTo(signX, signY, signX + borderRadius, signY);
                        ctx.closePath();
                        ctx.fill();

                        // Draw sign border with rounded corners
                        ctx.strokeStyle = config.borderColor;
                        ctx.lineWidth = 2;
                        ctx.stroke();

                        // Draw text on sign
                        ctx.fillStyle = config.textColor;
                        const fontSize = Math.max(12, canvas.width * 0.04); // Dynamic font size based on canvas
                        ctx.font = `bold ${fontSize}px "Palatino", "Palatino Linotype", "Book Antiqua", Georgia, serif`;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        // Add text shadow for better visibility
                        ctx.shadowColor = config.textColor === '#ffffff' ? 'rgba(0, 0, 0, 0.8)' : 'rgba(0, 0, 0, 0.3)';
                        ctx.shadowBlur = 2;
                        ctx.shadowOffsetX = 1;
                        ctx.shadowOffsetY = 1;

                        // Calculate text area for the sign
                        const textAreaWidth = signWidth * 0.8; // 80% of sign width for padding
                        const textCenterX = signX + signWidth / 2;
                        const textCenterY = signY + signHeight / 2;

                        // Split text into words and wrap lines for the sign
                        const words = pledgeData.text.split(' ');
                        const lines = [];
                        let currentLine = '';

                        for (let i = 0; i < words.length; i++) {
                            const testLine = currentLine + (currentLine ? ' ' : '') + words[i];
                            const metrics = ctx.measureText(testLine);

                            if (metrics.width > textAreaWidth && currentLine) {
                                lines.push(currentLine);
                                currentLine = words[i];
                            } else {
                                currentLine = testLine;
                            }
                        }
                        if (currentLine) {
                            lines.push(currentLine);
                        }

                        // Draw each line centered on the sign
                        const lineHeight = fontSize * 1.2;
                        const totalTextHeight = lines.length * lineHeight;
                        let startY = textCenterY - (totalTextHeight / 2) + (lineHeight / 2);

                        lines.forEach((line, index) => {
                            const y = startY + (index * lineHeight);
                            ctx.fillText(line, textCenterX, y);
                        });

                        // Restore the canvas state to remove rotation
                        ctx.restore();

                        console.log('Drew sign at:', { x: signX, y: signY, width: signWidth, height: signHeight });
                        console.log('Drew text lines:', lines);
                    }

                    // Download the canvas
                    downloadCanvas(canvas, `coral_pledge_${pledgeData.coral}.png`);
                }

                function downloadCanvas(canvas, filename) {
                    const link = document.createElement('a');
                    link.href = canvas.toDataURL('image/png');
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    console.log('Downloaded coral pledge as:', filename);
                }
            }


            // Functions to get content-only images for server upload (without bubble background)
            function getBubbleContentOnly() {
                return new Promise((resolve, reject) => {
                    if (pledgeData.type === 'text') {
                        getBubbleTextContentOnly().then(resolve).catch(reject);
                    } else if (pledgeData.type === 'coral') {
                        getCoralContentOnly().then(resolve).catch(reject);
                    } else {
                        reject('Please complete your pledge selection first.');
                    }
                });
            }

            function getBubbleTextContentOnly() {
                return new Promise((resolve, reject) => {
                    console.log('=== GETTING TEXT CONTENT ONLY ===');
                    console.log('Pledge data:', pledgeData);

                    if (!pledgeData.text) {
                        reject('No text content to generate image');
                        return;
                    }

                    // Create canvas for content only
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // Set canvas size to a standard size for content
                    canvas.width = 400;
                    canvas.height = 300;

                    console.log('Content canvas dimensions:', {
                        width: canvas.width,
                        height: canvas.height
                    });

                    // Set transparent background
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    // Set text properties
                    ctx.fillStyle = 'white';
                    ctx.font = 'bold 25px "Palatino", "Palatino Linotype", "Book Antiqua", Georgia, serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    // Add text shadow for better visibility
                    ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
                    ctx.shadowBlur = 6;
                    ctx.shadowOffsetX = 2;
                    ctx.shadowOffsetY = 2;

                    // Calculate text area
                    const textAreaWidth = canvas.width * 0.8; // 80% of canvas width
                    const centerX = canvas.width / 2;
                    const centerY = canvas.height / 2;

                    // Split text into words and wrap lines
                    const words = pledgeData.text.split(' ');
                    const lines = [];
                    let currentLine = '';

                    let fontSize = 25;
                    let lineHeight = fontSize * 1.2;

                    // Create lines that fit within the text area
                    for (let i = 0; i < words.length; i++) {
                        const testLine = currentLine + (currentLine ? ' ' : '') + words[i];
                        const metrics = ctx.measureText(testLine);

                        if (metrics.width > textAreaWidth && currentLine) {
                            lines.push(currentLine);
                            currentLine = words[i];
                        } else {
                            currentLine = testLine;
                        }
                    }
                    if (currentLine) {
                        lines.push(currentLine);
                    }

                    // Calculate starting Y position to center all lines
                    const totalTextHeight = lines.length * lineHeight;
                    let startY = centerY - (totalTextHeight / 2) + (lineHeight / 2);

                    // Draw each line
                    lines.forEach((line, index) => {
                        const y = startY + (index * lineHeight);
                        ctx.fillText(line, centerX, y);
                    });

                    // Return the canvas data URL
                    const dataURL = canvas.toDataURL('image/png');
                    console.log('Generated text content-only image');
                    resolve({
                        dataURL: dataURL,
                        canvas: canvas,
                        type: 'text',
                        content: pledgeData.text
                    });
                });
            }

            function getCoralContentOnly() {
                return new Promise((resolve, reject) => {
                    console.log('=== GETTING CORAL CONTENT ONLY ===');
                    console.log('Pledge data:', pledgeData);

                    if (!pledgeData.coral || !pledgeData.text) {
                        reject('Incomplete coral pledge data');
                        return;
                    }

                    // Get coral configuration
                    const config = coralSignConfig[pledgeData.coral];
                    if (!config) {
                        reject('Coral configuration not found');
                        return;
                    }

                    console.log('Using coral config:', config);

                    // Create canvas for content only
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    // Set canvas size to standard size
                    canvas.width = 400;
                    canvas.height = 400;

                    console.log('Coral content canvas dimensions:', {
                        width: canvas.width,
                        height: canvas.height
                    });

                    // Track loaded images
                    let imagesLoaded = 0;
                    const totalImages = 2; // coral and stick images
                    const images = {};

                    function checkAllImagesLoaded() {
                        if (imagesLoaded === totalImages) {
                            renderCoralContentCanvas();
                        }
                    }

                    // Load coral image
                    const coralImg = new Image();
                    coralImg.crossOrigin = 'anonymous';
                    coralImg.onload = function() {
                        images.coral = coralImg;
                        imagesLoaded++;
                        console.log('Coral image loaded for content');
                        checkAllImagesLoaded();
                    };
                    coralImg.onerror = function() {
                        console.error('Failed to load coral image');
                        reject('Failed to load coral image');
                    };
                    coralImg.src = `{{ asset('images/brand/coral-seperate') }}/${pledgeData.coral}.webp`;

                    // Load stick image
                    const stickImg = new Image();
                    stickImg.crossOrigin = 'anonymous';
                    stickImg.onload = function() {
                        images.stick = stickImg;
                        imagesLoaded++;
                        console.log('Stick image loaded for content');
                        checkAllImagesLoaded();
                    };
                    stickImg.onerror = function() {
                        console.error('Failed to load stick image');
                        reject('Failed to load stick image');
                    };
                    stickImg.src = `{{ asset('images/brand/coral-seperate/stick.webp') }}`;

                    function renderCoralContentCanvas() {
                        console.log('Rendering coral content canvas...');

                        // Clear canvas (transparent background)
                        ctx.clearRect(0, 0, canvas.width, canvas.height);

                        // Convert percentage positions to pixel positions for standard canvas
                        const stickPosX = (parseFloat(config.stickPosition.left) / 100) * canvas.width;
                        const stickPosY = (parseFloat(config.stickPosition.top) / 100) * canvas.height;
                        const signPosX = (parseFloat(config.position.left) / 100) * canvas.width;
                        const signPosY = (parseFloat(config.position.top) / 100) * canvas.height;

                        // Layer 1: Draw stick (behind coral)
                        if (images.stick) {
                            const stickWidth = 20; // Smaller for content-only
                            const stickHeight = canvas.height * 0.6; // 60% of canvas height
                            const stickX = stickPosX - stickWidth / 2;
                            const stickY = stickPosY - stickHeight / 2;

                            ctx.drawImage(images.stick, stickX, stickY, stickWidth, stickHeight);
                            console.log('Drew stick at:', { x: stickX, y: stickY, width: stickWidth, height: stickHeight });
                        }

                        // Layer 2: Draw coral (middle layer)
                        if (images.coral) {
                            const coralWidth = canvas.width * 0.6; // 60% of canvas width
                            const coralHeight = canvas.height * 0.6; // 60% of canvas height
                            const coralX = (canvas.width - coralWidth) / 2; // Center horizontally
                            const coralY = (canvas.height - coralHeight) / 2; // Center vertically

                            ctx.drawImage(images.coral, coralX, coralY, coralWidth, coralHeight);
                            console.log('Drew coral at:', { x: coralX, y: coralY, width: coralWidth, height: coralHeight });
                        }                        // Layer 3: Draw sign with text (front layer)
                        if (pledgeData.text) {
                            const signWidth = 120; // Smaller for content-only
                            const signHeight = 60;
                            const signX = signPosX - signWidth / 2;
                            const signY = signPosY - signHeight / 2;

                            // Save the current canvas state before applying rotation
                            ctx.save();

                            // Apply rotation around the sign center
                            ctx.translate(signPosX, signPosY);
                            ctx.rotate((config.tilt * Math.PI) / 180); // Convert degrees to radians
                            ctx.translate(-signPosX, -signPosY);

                            // Draw sign background with rounded corners
                            const borderRadius = 6; // Match the CSS border-radius

                            // Clear any previous shadow settings
                            ctx.shadowColor = 'transparent';
                            ctx.shadowBlur = 0;
                            ctx.shadowOffsetX = 0;
                            ctx.shadowOffsetY = 0;

                            ctx.fillStyle = config.backgroundColor;

                            // Create rounded rectangle path
                            ctx.beginPath();
                            ctx.moveTo(signX + borderRadius, signY);
                            ctx.lineTo(signX + signWidth - borderRadius, signY);
                            ctx.quadraticCurveTo(signX + signWidth, signY, signX + signWidth, signY + borderRadius);
                            ctx.lineTo(signX + signWidth, signY + signHeight - borderRadius);
                            ctx.quadraticCurveTo(signX + signWidth, signY + signHeight, signX + signWidth - borderRadius, signY + signHeight);
                            ctx.lineTo(signX + borderRadius, signY + signHeight);
                            ctx.quadraticCurveTo(signX, signY + signHeight, signX, signY + signHeight - borderRadius);
                            ctx.lineTo(signX, signY + borderRadius);
                            ctx.quadraticCurveTo(signX, signY, signX + borderRadius, signY);
                            ctx.closePath();
                            ctx.fill();

                            // Draw sign border with rounded corners
                            ctx.strokeStyle = config.borderColor;
                            ctx.lineWidth = 2;
                            ctx.stroke();

                            // Draw text on sign
                            ctx.fillStyle = config.textColor;
                            ctx.font = 'bold 12px "Palatino", "Palatino Linotype", "Book Antiqua", Georgia, serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            // Add text shadow for better visibility
                            ctx.shadowColor = 'rgba(0, 0, 0, 0.3)';
                            ctx.shadowBlur = 1;
                            ctx.shadowOffsetX = 1;
                            ctx.shadowOffsetY = 1;

                            // Calculate text area for the sign
                            const textAreaWidth = signWidth * 0.8; // 80% of sign width for padding
                            const textCenterX = signX + signWidth / 2;
                            const textCenterY = signY + signHeight / 2;

                            // Split text into words and wrap lines for the sign
                            const words = pledgeData.text.split(' ');
                            const lines = [];
                            let currentLine = '';

                            for (let i = 0; i < words.length; i++) {
                                const testLine = currentLine + (currentLine ? ' ' : '') + words[i];
                                const metrics = ctx.measureText(testLine);

                                if (metrics.width > textAreaWidth && currentLine) {
                                    lines.push(currentLine);
                                    currentLine = words[i];
                                } else {
                                    currentLine = testLine;
                                }
                            }
                            if (currentLine) {
                                lines.push(currentLine);
                            }

                            // Draw each line centered on the sign
                            const lineHeight = 14;
                            const totalTextHeight = lines.length * lineHeight;
                            let startY = textCenterY - (totalTextHeight / 2) + (lineHeight / 2);

                            lines.forEach((line, index) => {
                                const y = startY + (index * lineHeight);
                                ctx.fillText(line, textCenterX, y);
                            });

                            console.log('Drew sign at:', { x: signX, y: signY, width: signWidth, height: signHeight });
                            console.log('Drew text lines:', lines);

                            // Restore the canvas state to remove rotation
                            ctx.restore();
                        }

                        // Return the canvas data URL
                        const dataURL = canvas.toDataURL('image/png');
                        console.log('Generated coral content-only image');
                        resolve({
                            dataURL: dataURL,
                            canvas: canvas,
                            type: 'coral',
                            coralId: pledgeData.coral,
                            content: pledgeData.text
                        });
                    }
                });
            }

            // Example usage function for server upload
            function uploadPledgeToServer() {
                getBubbleContentOnly()
                    .then(result => {
                        console.log('Content-only image generated:', result);

                        // Convert data URL to blob for upload
                        fetch(result.dataURL)
                            .then(res => res.blob())
                            .then(blob => {
                                // Create FormData for server upload
                                const formData = new FormData();
                                formData.append('pledge_image', blob, `pledge_${result.type}_${Date.now()}.png`); // must match controller
                                formData.append('pledge_text', result.content); // must match controller
                                // If you want to use the pledge as charname, uncomment below:
                                // formData.append('charname', result.content);

                                // Upload to server using the provided endpoint and headers
                                fetch('{{ route('upload.baby') }}', {
                                    method: 'POST',
                                    body: formData, // FormData will set Content-Type to multipart/form-data with boundary
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken, // Standard CSRF header for Laravel
                                        'Accept': 'application/json', // Expect a JSON response
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        console.error('Network response was not ok:', response);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    // location.reload();
                                })
                                .catch(error => {
                                    console.error('Upload failed:', error);
                                });
                            });
                    })
                    .catch(error => {
                        console.error('Error generating content:', error);
                        alert('Error: ' + error);
                    });
            }

            pledgeBtn.addEventListener('click', function() {
               uploadPledgeToServer();
            });


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
