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
                                <img class="coral slick-img mx-auto mb-3"
                                    src="{{ asset('images/character/bubbles/' . $i . '.webp') }}"
                                    alt="coral {{ $i }}" />
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
                class="custom-btn custom-btn-secondary animate-entry delay-5 w-100">Download</button>
            </div>

        </div>
    </div>
    @push('scripts')
        <script>
            const steps = ['type', 'text', 'coral', 'finish'];
            let currentStep = 0;
            let pledgeData = {
                type: '',
                text: '',
                coral: '',
                finish: ''
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
                    bubbleTextElement.style.fontSize = '25px';
                    bubbleTextElement.style.fontWeight = 'bold';
                    bubbleTextElement.style.textAlign = 'center';
                    bubbleTextElement.style.zIndex = '999';
                    bubbleTextElement.style.pointerEvents = 'none';

                    selectedOption.appendChild(bubbleTextElement);

                    console.log('Text element created and added:', {
                        text: bubbleTextElement.textContent,
                        innerHTML: selectedOption.innerHTML
                    });
                } else if (pledgeData.type === 'coral') {
                    // For coral type, show the selected coral image and name
                    const coralContainer = document.createElement('div');
                    coralContainer.className = 'coral-display';

                    const coralImg = document.createElement('img');
                    coralImg.src = `{{ asset('images/character/bubbles/') }}/${pledgeData.coral}.webp`;
                    coralImg.alt = `Coral ${pledgeData.coral}`;
                    coralImg.className = 'selected-coral-img';

                    const nameElement = document.createElement('p');
                    nameElement.textContent = pledgeData.text;
                    nameElement.className = 'coral-name';

                    coralContainer.appendChild(coralImg);
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

            function processDownload() {
                if (pledgeData.type === 'text') {
                    downloadPledgeTypeText();
                } else if (pledgeData.type === 'coral') {
                    downloadPledgeTypeText(); // Use the same function for both types
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

                    console.log('Starting html2canvas capture...');

                    // First try to capture just the selectedOption div to test text rendering
                    console.log('Testing text capture first...');
                    html2canvas(selectedOption, {
                        backgroundColor: 'rgba(0,0,0,0.5)', // Semi-transparent background to see text
                        useCORS: true,
                        allowTaint: true,
                        scale: 1
                    }).then(textCanvas => {
                        console.log('Text canvas created:', {
                            width: textCanvas.width,
                            height: textCanvas.height
                        });

                        const textCtx = textCanvas.getContext('2d');
                        const textImageData = textCtx.getImageData(0, 0, textCanvas.width, textCanvas.height);
                        const textHasContent = textImageData.data.some(pixel => pixel !== 0);
                        console.log('Text canvas has content:', textHasContent);

                        if (textHasContent) {
                            console.log('Text renders fine, now trying full bubble...');
                            // Text renders fine, now try the full bubble
                            captureFullBubble();
                        } else {
                            console.log('Text not rendering, downloading text canvas for debugging');
                            downloadCanvas(textCanvas, 'text-only-debug.png');
                        }
                    }).catch(error => {
                        console.error('Text capture failed:', error);
                        // Fallback to full bubble capture
                        captureFullBubble();
                    });

                    function captureFullBubble() {
                        html2canvas(bubbleContainer, {
                            backgroundColor: null,
                            useCORS: true,
                            allowTaint: true,
                            scale: 1,
                            logging: false,
                            removeContainer: false,
                            foreignObjectRendering: false,
                            proxy: null, // Disable proxy
                            imageTimeout: 0, // No timeout
                            ignoreElements: function(element) {
                                return false;
                            }
                        }).then(canvas => {
                            console.log('Full canvas created successfully:', {
                                width: canvas.width,
                                height: canvas.height
                            });

                            // Check if canvas has content
                            if (canvas.width === 0 || canvas.height === 0) {
                                console.error('Canvas is empty');
                                alert('Unable to generate image. Please try again.');
                                return;
                            }

                            // Also check if canvas actually has drawn content by checking image data
                            const ctx = canvas.getContext('2d');
                            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                            const hasContent = imageData.data.some(pixel => pixel !== 0);
                            console.log('Canvas has actual content:', hasContent);

                            downloadCanvas(canvas, 'pledge_bubble.png');
                        }).catch(error => {
                            console.error('Error generating image:', error);
                            alert('Error generating image. Please try again.');
                        });
                    }

                    function downloadCanvas(canvas, filename) {
                        const link = document.createElement('a');
                        link.href = canvas.toDataURL('image/png');
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        alert('Your image has been downloaded: ' + filename);
                    }
                }
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
