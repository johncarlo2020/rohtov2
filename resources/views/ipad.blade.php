<x-app-layout>
    <div class="py-5 container-fluid main-content ipad-container">
        <div class="col-12 d-flex justify-content-center mt-5">
            @include('components.branding')
        </div>
        <div class="row mt-5 w-100">
            <div class="w-100" id="welcomeContiner">
                @include('components.welcomeContainer')
            </div>
            <div class="w-100" id="getName" class="hidden">
                @include('components.getName')
            </div>
            <div class="w-100" id="selectCharacter" class="hidden">
                @include('components.selectCharacter')
            </div>
            <div class="w-100" id="editCharacter" class="hidden">
                @include('components.editCharacter')
            </div>
            <div class="w-100" id="completeContainer" class="hidden">
                @include('components.completeContainer')
            </div>
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

        function selectSkin(skin) {
            selectedCharacter.skin = skin;
            nextStep();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedStepIndex = localStorage.getItem('currentStepIndex');
            let initialStepIndex = 0;
            if (savedStepIndex !== null) {
                const parsedIndex = parseInt(savedStepIndex, 10);
                if (!isNaN(parsedIndex) && parsedIndex >= 0 && parsedIndex < step.length) {
                    initialStepIndex = parsedIndex;
                }
            }
            showStep(initialStepIndex);
        });
    </script>
</x-app-layout>
