<x-app-layout>
    <div class="ipad-container">
        <button onclick="prevStep()" class="back-btn d-none">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <div class="col-12 d-flex justify-content-center mt-5">
            @include('components.branding')
        </div>
        <div class="mt-5 w-100">
            <div class="loader-container">
                {{-- <div class="loader"></div> --}}
                <img class="loading-gif" src="{{ asset('images/loading.gif') }}" alt="Face 4" />
                <p class="loading-text">Loading...</p>
            </div>
            <div class="w-100" id="welcomeContiner">
                @include('components.welcomeContainer')
            </div>
            <div class="w-100" id="selectCharacter" class="hidden">
                @include('components.selectCharacter')
            </div>
            <div class="" id="editCharacter" class="hidden">
                @include('components.editCharacter')
            </div>
            <div class="w-100" id="completeContainer" class="hidden">
                @include('components.completeContainer')
            </div>
            <form id="uploadForm" action="{{ route('upload.baby') }}" method="POST" enctype="multipart/form-data" style="display:none;">
                @csrf
                <input type="file" name="baby_img" id="baby_img" accept="image/png">
                <input type="text" name="baby_name" id="baby_name">
                <button type="submit" id="uploadButton"></button>
            </form>
        </div>
    </div>
    <script>
        const loaderContainer = document.querySelector('.loader-container');
        const step = [{
            elementId: 'welcomeContiner',
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
        }];

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
            localStorage.setItem('currentStepIndex', stepIndex);
        }

        function nextStep() {
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
            part.src = `{{ asset('images/character/${type}/${index}/${index}.png') }}`;
            part.alt = `Item ${index}`;
            characterContainer.appendChild(part);
        }

        function selectSkin(skin) {
            const characterName = 'characterName';
            const characterEditContainer = 'characterEditContainer';
            selectedCharacter.skin = skin;
            selectedCharacter.character = characterNameMap[skin - 1];
            initEditCharacter(characterName,characterEditContainer);
            nextStep();
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
                skinImage.src = `{{ asset('images/character/skin/${selectedCharacter.skin}/${selectedCharacter.skin}.png') }}`;
                skinImage.alt = 'Selected Skin';
                skinImage.classList.add('skin');
                nameElement.classList.add('selected-skin-name');
                characterNameContainer.appendChild(nameElement);
                characterContainer.appendChild(skinImage);
            }

            console.log(selectedCharacter);

            if (edit) {
                const hairImage = document.createElement('img');
                hairImage.src = `{{ asset('images/character/hair/${selectedCharacter.hair}/${selectedCharacter.hair}.png') }}`;
                hairImage.alt = 'Selected Hair';
                hairImage.classList.add('hair');
                characterContainer.appendChild(hairImage);

                const faceImage = document.createElement('img');
                faceImage.src = `{{ asset('images/character/face/${selectedCharacter.face}/${selectedCharacter.face}.png') }}`;
                faceImage.alt = 'Selected Face';
                faceImage.classList.add('face');
                characterContainer.appendChild(faceImage);
            }
        }

        function gotoFinishPage() {
            const characterName = 'characterNameFinish';
            const characterEditContainer = 'finishedCharacterContainer';
            initEditCharacter(characterName, characterEditContainer, true);
            showLoader();
            showStep(3);
            createSpriteSheet();
        }

        let sprites= [];
        const frameRate = 6;

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
            spriteSheetImage.src = tempCanvas.toDataURL("image/png");
            spriteSheetImageConverted.src = spriteSheetImage.src;
            hideLoader();

            } catch (error) {
            console.error("Error creating sprite sheet:", error);
            }

        }

        function uploadSpriteSheet() {
            const uploadButton = document.getElementById('uploadButton');
            const babyImgInput   = document.getElementById('baby_img');
            const babyNameInput  = document.getElementById('baby_name');

            // draw the spriteSheetImageConverted into a canvas so we can call toBlob()
            const img = spriteSheetImageConverted;
            const canvas = document.createElement('canvas');
            canvas.width  = img.naturalWidth || img.width;
            canvas.height = img.naturalHeight || img.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);

            canvas.toBlob((blob) => {
                if (!blob) {
                    console.error('Failed to generate blob from sprite sheet.');
                    return;
                }
                const file = new File([blob], "sprite_sheet.png", { type: "image/png" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                babyImgInput.files = dataTransfer.files;
                babyNameInput.value = 'empty';

                // Submit the form
                uploadButton.click();
            }, "image/png");
        }

        async function captureFrame(frameIndex) {
            // Update character images for this frame, using custom face if provided
            // skin.src = `/assets/green_0${frameIndex + 1}.png`;
            // hair.src = `/assets/green_hair0${frameIndex + 1}.png`;
            // face.src = customFaceSrc;

            // loop through selectedCharacter and update the src of each image
            const character = document.getElementById('finishedCharacterContainer');
            const skin = character.querySelector('.skin');
            const hair = character.querySelector('.hair');
            const face = character.querySelector('.face');

            console.log(skin, hair, face);


            Object.entries(selectedCharacter).forEach(([key, value]) => {
                // Skip empty values if you want
                if (key === 'name') return;
                if (key === 'character') return;

                skin.src = `{{ asset('images/character/skin/${value}/${frameIndex}.png') }}`;
                hair.src = `{{ asset('images/character/hair/${value}/${frameIndex}.png') }}`;
                face.src = `{{ asset('images/character/face/${value}/${frameIndex}.png') }}`;
            });

            // Ensure all images load before capturing
            await Promise.all([
                waitForImageLoad(skin),
                waitForImageLoad(hair),
                waitForImageLoad(face),
            ]);

            // Small delay to allow DOM updates
            await new Promise((resolve) => setTimeout(resolve, 100));

            return html2canvas(character, { backgroundColor: null });
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

        document.addEventListener('DOMContentLoaded', function() {

            const stepElements = step.map(s => document.getElementById(s.elementId)).filter(el => el);

            // Hide all steps initially and show loader
            stepElements.forEach(el => el.classList.add('hidden'));
            if (loaderContainer) {
                loaderContainer.style.display = 'flex'; // Or 'block', depending on your layout needs
            }

            const savedStepIndex = localStorage.getItem('currentStepIndex');
            let initialStepIndex = 0;
            if (savedStepIndex !== null) {
                const parsedIndex = parseInt(savedStepIndex, 10);
                if (!isNaN(parsedIndex) && parsedIndex >= 0 && parsedIndex < step.length) {
                    initialStepIndex = parsedIndex;
                }
            }

            // Wait for 2 seconds, then hide loader and show the correct step
            setTimeout(() => {
                if (loaderContainer) {
                    loaderContainer.style.display = 'none';
                }
                showStep(0);
            }, 2000);
        });
    </script>
</x-app-layout>
