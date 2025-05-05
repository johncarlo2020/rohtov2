<x-app-layout>
    <div class="ipad-container">
        <div class="col-12 d-flex justify-content-center mt-5">
            @include('components.branding')
        </div>
        <div class="mt-5 w-100">
            <div class="loader-container">
                <div class="loader"></div>
                <p class="loading-text">Loading...</p>
            </div>
            <div class="w-100" id="welcomeContiner">
                @include('components.welcomeContainer')
            </div>
            <div class="w-100" id="getName" class="hidden">
                @include('components.getName')
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
        </div>
        <div class="end-text">
            <p>Powered by WOWSOME®️ 2025</p>
            <img src="{{ asset('images/logo-rounded.png') }}" alt="Item 2" />
        </div>
    </div>
    <script>
        const step = [{
            elementId: 'welcomeContiner',
            completed: false,
        }, {
            elementId: 'getName',
            completed: false,
        }, {
            elementId: 'selectCharacter',
            completed: false,
        }, {
            elementId: 'editCharacter',
            completed: false,
        }, {
            elementId: 'completeContainer',
            completed: false,
        }];

        var selectedCharacter = {
            name: '',
            skin: '',
            hair: '',
            face: '',
        };

        function showStep(stepIndex) {
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
            part.src = `{{ asset('images/character/${type}/${index}.png') }}`;
            part.alt = `Item ${index}`;
            characterContainer.appendChild(part);
        }

        function selectSkin(skin) {
            selectedCharacter.skin = skin;
            initEditCharacter();
            nextStep();
        }

        function initEditCharacter() {
            const characterNameContainer = document.getElementById('characterName');
            const characterContainer = document.getElementById('characterEditContainer');
            if (characterContainer) {
                characterContainer.innerHTML = ''; // Clear previous content
                characterNameContainer.innerHTML = ''; // Clear previous content
                const nameElement = document.createElement('img');
                nameElement.src = `{{ asset('images/character/name/${selectedCharacter.skin}.png') }}`;
                const skinImage = document.createElement('img');
                skinImage.src = `{{ asset('images/character/skin/${selectedCharacter.skin}.png') }}`;
                skinImage.alt = 'Selected Skin';
                skinImage.classList.add('selected-skin-image');
                nameElement.alt = 'Selected Name';
                nameElement.classList.add('selected-skin-name');
                characterNameContainer.appendChild(nameElement);
                characterContainer.appendChild(skinImage);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const loaderContainer = document.querySelector('.loader-container');
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
            }, 2000); // 2000 milliseconds = 2 seconds
        });
    </script>
</x-app-layout>
