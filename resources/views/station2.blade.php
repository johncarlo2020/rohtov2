<x-app-layout>
    <div class="container station p-0">
        <div class="mb-3 branding-container mt-5">
            @include('components.branding')
        </div>
        <button onclick="prevStep()" class="back-btn d-none">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div class="loader-container">
            {{-- <div class="loader"></div> --}}
            <img class="loading-gif" src="{{ asset('images/loading.gif') }}" alt="Face 4" />
            <p class="loading-text">Loading...</p>
        </div>
        <div id="getName" class="get-name-container px-4">
            <h5 class="text-center text-primary">PLEASE INSERT YOUR NAME</h5>
            <div class="mb-3">
                <input type="text" class="form-control rounded-pill get-name" id="name" placeholder="Your name"
                    maxlength="5">
                <div id="emailHelp" class="form-text">*Maximum 5 character</div>
            </div>
            <button onclick="addName()" class="station-button main-btn btn btn-primary">
                Start
            </button>
        </div>
        <div id="selectCharacter" class="px-4 hidden">
            <h5 class="text-center text-primary mb-5">Please select your skin</h5>
            <div class="sliders w-100">
                <div class="container-fluid p-0 m-0">
                    <div class="row p-0 m-0 justify-content-center">
                        <div class="col-md-10 slider-container">
                            <div class="slider-prev slider-navigation">
                                <button id="prev" class="slider-btn"><i
                                        class="fa-solid fa-caret-left"></i></button>
                            </div>
                            <div class="slider-next slider-navigation">
                                <button id="next" class="slider-btn"><i
                                        class="fa-solid fa-caret-right"></i></button>
                            </div>
                            <!-- Slick Slider Component -->
                            <div class="slick-carousel mt-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="slick-slide-item" onclick="selectSkin({{ $i }})">
                                        <div class="station-container d-flex justify-content-center align-items-center">
                                            <img class="station-img"
                                                src="{{ asset('images/character/bubbles/' . $i . '.webp') }}"
                                                alt="Character {{ $i }}" />
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <button onclick="nextStep()" class="station-button main-btn btn btn-primary">
                Select
             </button> --}}
        </div>
        <div id="editCharacter">
            <div class="edit-character-container">
                <div class="selected-skin-container">
                    <div id="characterName" class="skin-name-container">
                    </div>
                    <div id="characterEditContainer" class="character-container">
                    </div>
                </div>
                <div class="options-container">
                    <div class="option with-border">
                        <div class="button-container">
                            <p class="edit-btn">
                                <span>Hair</span>
                            </p>
                        </div>
                        <div class="items">
                            <div class="left-nav">
                                <div class="nav-items">
                                    <i class="fa-solid fa-chevron-left"></i>
                                    <img src="{{ asset('images/navbacground.webp') }}" alt="Hair 1" />
                                </div>
                            </div>
                            <div class="slider">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button class="item" onclick="selectItem('hair', {{ $i }}, this)">
                                        <img src="{{ asset('images/character/choises/hair/' . $i . '.webp') }}"
                                            alt="Hair {{ $i }}" />
                                    </button>
                                @endfor
                            </div>
                            <div class="right-nav">
                                <div class="nav-items">
                                    <img src="{{ asset('images/navbacground.webp') }}" alt="Hair 1" />
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="devider">
                        <img src="{{ asset('images/line.webp') }}" alt="Item 2" />
                    </div>
                    <div class="option">
                        <div class="button-container">
                            <p class="edit-btn">
                                <span>Face</span>
                            </p>
                        </div>
                        <div class="items">
                            <div class="left-nav">
                                <div class="nav-items">
                                    <i class="fa-solid fa-chevron-left"></i>
                                    <img src="{{ asset('images/navbacground.webp') }}" alt="Previous" />
                                </div>
                            </div>
                            <div class="slider">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button class="item" onclick="selectItem('face', {{ $i }}, this)">
                                        <img src="{{ asset('images/character/choises/face/' . $i . '.webp') }}"
                                            alt="Face {{ $i }}" />
                                    </button>
                                @endfor
                            </div>
                            <div class="right-nav">
                                <div class="nav-items">
                                    <img src="{{ asset('images/navbacground.webp') }}" alt="Next" />
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="next-button-container text-center mt-5">
                    <button onclick="gotoFinishPage()" class="next-button"><span>Done</span></button>
                </div>
            </div>


            <script>
                document.querySelectorAll('.option .items').forEach(itemsContainer => {
                    const leftNav = itemsContainer.querySelector('.left-nav');
                    const rightNav = itemsContainer.querySelector('.right-nav');
                    const slider = itemsContainer.querySelector('.slider');

                    leftNav.addEventListener('click', () => {
                        slider.scrollBy({
                            left: -200,
                            behavior: 'smooth'
                        });
                    });
                    rightNav.addEventListener('click', () => {
                        slider.scrollBy({
                            left: 200,
                            behavior: 'smooth'
                        });
                    });
                });
            </script>

        </div>

        <div id="completeContainer" class="hidden">
            <div class="finish-container px-4">
                <div class="character-finish">

                    <div class="selected-skin-container">
                        <div class="with-bg">
                            <img class="background-img" src="{{ asset('images/finighPage.webp') }}"
                                alt="Face 4" />
                            <div id="finishedCharacterContainer" class="finish-character-container">
                            </div>
                        </div>

                        <div class="with-bubble">
                            <img class="bubble" src="{{ asset('images/bubble.webp') }}" alt="Face 4" />
                            <div id="characterNameFinish" class="skin-name-container">
                            </div>
                        </div>
                    </div>
                </div>
                <a id="download" class="btn btn-primary" style="display:none;">
                    Download Sprite Sheet
                </a>

                <button onclick="uploadSpriteSheet()" class="next-button mt-5 w-100"><span>Share to screen and check
                        in</span></button>
                <button onclick="downloadGif()" class="next-button mt-2 w-100"><span>Download</span></button>
            </div>
            <form id="uploadForm" action="{{ route('upload.baby') }}" method="POST" enctype="multipart/form-data"
                style="display:none;">
                @csrf
                <input type="file" name="baby_img" id="baby_img" accept="image/webp">
                <input type="text" name="baby_name" id="baby_name">
                <button type="submit" id="uploadButton"></button>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body text-center">
                    <i class="fa-solid fa-circle-check modal-icon"></i>
                    <h2>Station 2</h2>
                    <p>Check-in Successful</p>
                    <a href="{{ route('dashboard') }}" class="next-button mt-2 px-5"><span>okay</span></a>
                </div>

            </div>
        </div>
    </div>

    <script>
        const loaderContainer = document.querySelector('.loader-container');
        const nameContainer = document.getElementById('nameContainer');
        const characterStyleContainer = document.getElementById('characterStyle');

        const step = [{
                elementId: 'getName',
                completed: false,
            },
            {
                elementId: 'selectCharacter',
                completed: false,
            }, {
                elementId: 'editCharacter',
                completed: false,
            }, {
                elementId: 'completeContainer',
                completed: false,
            }
        ];

        const characterNameMap = [
            'Bounci',
            'Chubbi',
            'Globelle',
            'mochimura',
            'Dewy',
        ]

        var selectedCharacter = {
            name: '',
            skin: '',
            hair: '',
            face: '',
            character:'',
        };

        let stepIndex = 0;

        function selectSkin(skin) {
            const characterName = 'characterName';
            const characterEditContainer = 'characterEditContainer';
            selectedCharacter.skin = skin;
            selectedCharacter.character = characterNameMap[skin - 1];
            initEditCharacter(characterName, characterEditContainer);
            nextStep();
        }

        function gotoFinishPage() {
            const characterName = 'characterNameFinish';
            const characterEditContainer = 'finishedCharacterContainer';
            initEditCharacter(characterName, characterEditContainer, true);
            showLoader();
            showStep(3);
            createSpriteSheet();
        }

        const nameInput = document.getElementById('name');
        // add checker for name input if not empty
        function addName() {
            selectedCharacter.name = nameInput.value;
            // Check if the name is empty
            if (selectedCharacter.name.trim() === '') {
                return;
            }
            // Validate max length of 5 characters
            if (selectedCharacter.name.length > 5) {
                alert('Name must not exceed 5 characters.');
                return;
            }
            selectedCharacter.name = nameInput.value;

            console.log('Selected Character:', selectedCharacter);
            nextStep();
        }

        function showLoader() {
            if (loaderContainer) {
                loaderContainer.style.display = 'flex';
            }
        }

        function hideLoader() {
            if (loaderContainer) {
                loaderContainer.style.display = 'none';
            }
        }

        function uploadSpriteSheet() {

            // open modal uploadSpriteSheet
            const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
            modal.show();

            return;
            const uploadButton = document.getElementById('uploadButton');
            const babyImgInput = document.getElementById('baby_img');
            const babyNameInput = document.getElementById('baby_name');

            // draw the spriteSheetImageConverted into a canvas so we can call toBlob()
            const img = spriteSheetImageConverted;
            const canvas = document.createElement('canvas');
            canvas.width = img.naturalWidth || img.width;
            canvas.height = img.naturalHeight || img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);

            canvas.toBlob((blob) => {
                if (!blob) {
                    console.error('Failed to generate blob from sprite sheet.');
                    return;
                }
                const file = new File([blob], "sprite_sheet.webp", {
                    type: "image/webp"
                });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                babyImgInput.files = dataTransfer.files;
                babyNameInput.value = 'empty';

                // Submit the form
                uploadButton.click();
            }, "image/webp");
        }

        async function captureFrame(frameIndex) {

            // loop through selectedCharacter and update the src of each image
            const character = document.getElementById('finishedCharacterContainer');
            // Store original style to revert later
            const originalHeight = character.style.height;
            const originalWidth = character.style.width; // Store original width
            // Set fixed height for consistent capture - adjust '300px' as needed

            console.log(character.style.height, character.style.width);
            character.style.height = '300px';
            character.style.width = '300px';

            const skin = character.querySelector('.skin');
            const hair = character.querySelector('.hair');
            const face = character.querySelector('.face');


            // Directly set the src for each part using the correct selectedCharacter property
            if (selectedCharacter.skin) {
                skin.src = `{{ asset('images/character/skin/${selectedCharacter.skin}/${frameIndex}.webp') }}`;
            }
            if (selectedCharacter.hair) {
                hair.src = `{{ asset('images/character/hair/${selectedCharacter.hair}/${frameIndex}.webp') }}`;
            }
            if (selectedCharacter.face) {
                face.src = `{{ asset('images/character/face/${selectedCharacter.face}/${frameIndex}.webp') }}`;
            }

            console.log(skin, hair, face);

            // Ensure all images load before capturing
            await Promise.all([
                waitForImageLoad(skin),
                waitForImageLoad(hair),
                waitForImageLoad(face),
            ]);

            // Small delay to allow DOM updates
            await new Promise((resolve) => setTimeout(resolve, 100));

            let canvas;
            try {
                canvas = await html2canvas(character, {
                    backgroundColor: null,
                    scale: 1, // Use a 1:1 pixel scale, ignoring device pixel ratio
                    width: 300, // Explicitly set the desired output canvas width
                    height: 300 // Explicitly set the desired output canvas height
                });

            } finally {
                // Revert to original style
                character.style.height = originalHeight;
                character.style.width = originalWidth; // Revert width
            }
            return canvas;
        }

        function waitForImageLoad(image) {
            return new Promise((resolve) => {
                if (image.complete) resolve();
                else image.onload = resolve;
            });
        }

        let spriteSheetImageConverted = new Image();

        async function createSpriteSheet() {
            // Get entered character name or default to "Unnamed"
            const frameCount = 7;

            try {
                const frames = [];
                for (let i = 1; i < frameCount; i++) {
                    const frameCanvas = await captureFrame(i);
                    frames.push(frameCanvas);
                }

                // Create a single sprite sheet from the captured frames
                const tempCanvas = document.createElement("canvas");
                const tempCtx = tempCanvas.getContext("2d");
                const frameWidth = frames[0].width;
                const frameHeight = frames[0].height;

                tempCanvas.width = frameWidth * frameCount;
                tempCanvas.height = frameHeight;
                frames.forEach((frame, index) => {
                    tempCtx.drawImage(frame, index * frameWidth, 0);
                });

                // Convert temporary canvas to image
                const spriteSheetImage = new Image();
                spriteSheetImage.src = tempCanvas.toDataURL("image/webp");
                spriteSheetImageConverted.src = spriteSheetImage.src;
                hideLoader();

            } catch (error) {
                console.error("Error creating sprite sheet:", error);
            }

        }

        function selectItem(type, index, element) {
            const characterContainer = document.getElementById('characterEditContainer');

            // Remove active class from siblings
            const parentItems = element.closest('.items');
            if (parentItems) {
                const siblings = parentItems.querySelectorAll('.item');
                siblings.forEach(sibling => sibling.classList.remove('active'));
            }

            // Add active class to the clicked element
            element.classList.add('active');

            if (type === 'hair') {
                selectedCharacter.hair = index;
            } else if (type === 'face') {
                selectedCharacter.face = index;
            }

            const existingItem = characterContainer.querySelector(`.${type}`);
            if (existingItem) {
                existingItem.remove();
            }

            const part = document.createElement('img');
            part.classList.add(type);
            part.classList.add('parts');
            part.src = `{{ asset('images/character/${type}/${index}/${index}.webp') }}`;
            part.alt = `Item ${index}`;
            characterContainer.appendChild(part);
        }


        function initEditCharacter(characterName, characterEditContainer, edit = false) {
            const characterNameContainer = document.getElementById(characterName);
            const characterContainer = document.getElementById(characterEditContainer);
            if (characterContainer) {
                characterContainer.innerHTML = ''; // Clear previous content
                characterNameContainer.innerHTML = ''; // Clear previous content
                const nameElement = document.createElement('p');

                nameElement.textContent = selectedCharacter.character;

                const skinImage = document.createElement('img');
                skinImage.src =
                    `{{ asset('images/character/skin/${selectedCharacter.skin}/${selectedCharacter.skin}.webp') }}`;
                skinImage.alt = 'Selected Skin';
                skinImage.classList.add('skin');
                nameElement.classList.add('selected-skin-name');
                characterNameContainer.appendChild(nameElement);

                characterContainer.appendChild(skinImage);
            }

            console.log(selectedCharacter);

            if (edit) {
                const hairImage = document.createElement('img');
                hairImage.src =
                    `{{ asset('images/character/hair/${selectedCharacter.hair}/${selectedCharacter.hair}.webp') }}`;
                hairImage.alt = 'Selected Hair';
                hairImage.classList.add('hair');
                characterContainer.appendChild(hairImage);

                const faceImage = document.createElement('img');
                faceImage.src =
                    `{{ asset('images/character/face/${selectedCharacter.face}/${selectedCharacter.face}.webp') }}`;
                faceImage.alt = 'Selected Face';
                faceImage.classList.add('face');
                characterContainer.appendChild(faceImage);

                const inputedName = document.createElement('p');
                inputedName.textContent = selectedCharacter.name;
                characterNameContainer.appendChild(inputedName);
            }
        }

        function nextStep() {
            console.log('Selected Character:', selectedCharacter);
            const currentStepIndex = step.findIndex(s => {
                const element = document.getElementById(s.elementId);
                return element && !element.classList.contains('hidden');
            });

            if (currentStepIndex !== -1 && currentStepIndex < step.length - 1) {
                showStep(currentStepIndex + 1);
            } else if (currentStepIndex === -1 && step.length > 0) {
                showStep(0);
            }
        }

        function prevStep() {
            const currentStepIndex = step.findIndex(s => {
                const element = document.getElementById(s.elementId);
                return element && !element.classList.contains('hidden');
            });

            if (currentStepIndex > 0) {
                showStep(currentStepIndex - 1);
            }
        }

        function downloadGif() {
            // use local worker script installed via npm (ensure copied to public/js)
            const gif = new GIF({
                workers: 2,
                quality: 10,
                width: spriteSheetImageConverted.width,
                height: spriteSheetImageConverted.height,
                workerScript: '{{ asset('js/gif.worker.min.js') }}',
            });
            const frameCount = 7;
            for (let i = 0; i < frameCount; i++) {
                gif.addFrame(spriteSheetImageConverted, {
                    delay: 200
                });
            }
            gif.on('finished', function(blob) {
                const downloadLink = document.getElementById('download');
                downloadLink.href = URL.createObjectURL(blob);
                downloadLink.download = 'sprite_sheet.gif';
                downloadLink.style.display = 'block';
            });
            gif.render();
        }

        function showStep(stepIndex) {
            const backBtn = document.querySelector('.back-btn');

            // console.log("Step Index:", stepIndex);

            if (stepIndex > 1) {
                backBtn.classList.remove('d-none');
            } else {
                backBtn.classList.add('d-none');
            }

            if (stepIndex < 0 || stepIndex >= step.length) {
                console.error("Invalid step index:", stepIndex);
                stepIndex = 0;
            }

            // show/hide steps
            step.forEach((s, index) => {
                const element = document.getElementById(s.elementId);
                if (!element) return;
                if (index === stepIndex) {
                    element.classList.remove('hidden');
                    element.classList.add('fade-in');
                } else {
                    element.classList.add('hidden');
                    element.classList.remove('fade-in');
                }
            });

            // initialize/destroy Slick after toggling visibility
            const $carousel = $('.slick-carousel');
            if (stepIndex === 1) {
                if (!$carousel.hasClass('slick-initialized')) {
                    $carousel.slick({
                        dots: false,
                        arrows: false,
                        infinite: true,
                        speed: 500,
                        cssEase: 'linear',
                        autoplay: false,
                        autoplaySpeed: 4000,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        customPaging: function(slider, i) {
                            return '<button type="button" class="slick-dot-number">' + (i + 1) + '</button>';
                        }
                    });
                    // navigation buttons
                    $('#prev').off('click').on('click', () => $carousel.slick('slickPrev'));
                    $('#next').off('click').on('click', () => $carousel.slick('slickNext'));
                }
            } else {
                if ($carousel.hasClass('slick-initialized')) {
                    $carousel.slick('unslick');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            let currentSlide = 0;
            const stepElements = step.map(s => document.getElementById(s.elementId)).filter(el => el);
            stepElements.forEach(el => el.classList.add('hidden'));
            let initialStepIndex = 0;



            setTimeout(() => {
                if (loaderContainer) {
                    loaderContainer.style.display = 'none';
                }
                showStep(0);
            }, 2000);
        });
    </script>
</x-app-layout>
