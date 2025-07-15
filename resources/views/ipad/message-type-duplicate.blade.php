<x-app-layout>
    <div class="pledge-page ipad-pledge main-content main-background with-scroll ipad-for-client">
        <div class="d-flex justify-content-center animate-entry">
            @include('components.branding')
        </div>
        <div id="type"
            class="mt-1 mb-2 d-flex flex-column align-items-center justify-content-center animate-entry delay-2 steps">

            <p class="heading mb-2 fw-thin">
                Save the Blue Pledge
            </p>
            <p class="mb-5 fw-thin">
                Start pledge
            </p>

            <button id="selectTypeBtn" class="custom-btn custom-btn-secondary animate-entry delay-5">start</button>
        </div>
        <div id="text" class="d-none steps">
            <h1 class="heading animate-entry delay-1 my-5">
                Save the Blue Pledge
            </h1>
            <p class="mb-3 fw-thin text-center animate-entry delay-2">
                Choose your name
            </p>

            <div class="form-group mb-3 animate-entry delay-3">
                <select class="form-select input-text" id="floatingSelect" aria-label="Floating label select example">
                    <option selected disabled>Please select your name</option>
                    <option value="1">MR. Hirofumi Kameda</option>
                    <option value="2">MR. Hidekazu Iwaoka</option>
                    <option value="3">MR. Julian Hyde</option>
                </select>
            </div>



            <button id="selectText" class="custom-btn custom-btn-secondary animate-entry delay-5 w-100"
                disabled>Continue</button>
        </div>

        <div id="finish" class="d-none steps">
            <h1 class="heading animate-entry delay-1 mt-5 mb-0">
                    Save the Blue Pledge
            </h1>

            <div id="bubbleContainer" class="bubble-container mb-4 animate-entry delay-4">
                <img class="bubble" src="{{ asset('images/brand/bubble_Overlay.webp') }}" crossOrigin="anonymous"
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

            const steps = ['type', 'text', 'finish'];
            let currentStep = 0;
            let pledgeData = {
                coral: '',
                finish: ''
            };

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
            const floatingSelect = document.getElementById('floatingSelect');

            //remove the disabled attribute from the selectText button if the floatingSelect has a value
            floatingSelect.addEventListener('change', function() {
                if (floatingSelect.value) {
                    selectText.removeAttribute('disabled');
                } else {
                    selectText.setAttribute('disabled', 'disabled');
                }
            });


            selectTypeBtn.addEventListener('click', function() {
                currentStep++;
                updateStep();
            });

            selectText.addEventListener('click', function() {
                //get value of floatingSelect
                pledgeData.coral = floatingSelect.value;
                console.log('Selected Coral:', pledgeData.coral);
                processFinish();
                currentStep++;
                updateStep();
            });

            function processFinish() {
                //add image on selectedOption
                // Use JS variable for coral ID and Blade asset helper for base path
                const image = document.createElement('img');
                image.className = 'coral';
                image.src = `{{ asset('images/vip') }}/${pledgeData.coral}.webp`;
                selectedOption.appendChild(image);
            }


            pledgeBtn.addEventListener('click', function() {
             //triger websocket
             trigerWebSocket();
            });


            function trigerWebSocket() {
                // Create a FormData object to hold the data
                const formData = new FormData();

                formData.append('coral_image_id', pledgeData.coral);


                 fetch('{{ route("ipad.pushCoral") }}', {
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
                    });
            }

            function updateStep() {
                steps.forEach((step, index) => {
                    document.getElementById(step).classList.toggle('d-none', index !== currentStep);
                });
            }

        </script>
    @endpush
</x-app-layout>
