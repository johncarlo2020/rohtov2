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
                            <div class="coral item-container">
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
        <div id="finish" class="d-none steps"></div>
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

            // On clicking select, grab the image from the current slick slide
            selectCoralBtn.addEventListener('click', function() {
                const currentImg = document.querySelector('.coral-container .slick-current img');
                pledgeData.coral = currentImg ? currentImg.src : '';
                console.log('Selected coral:', pledgeData.coral);

            });

            function processTextSelection() {
                pledgeData.text = bubbleText.value;
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

            function nextStep() {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    updateUI();
                }
            }

            function previousStep() {
                if (currentStep > 0) {
                    currentStep--;
                    updateUI();
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
