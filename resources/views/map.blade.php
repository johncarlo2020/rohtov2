<x-app-layout>
    <div class="content-box main-background px-3 d-flex flex-column min-vh-100 pt-5">
        <a href="{{route('preRegEvent')}}" class="go-home"><i class="fa-solid fa-house"></i></a>
        <div class="container mb-4">
            <div ><a href="{{ route('preRegEvent') }}">
                @include('components.branding')
            </a>
            </div>
        </div>
        <div class="container mb-4">
            <div class="station-logo" onclick="showStaffIdModal()">
                <img id="station-branding" src="{{ asset('files/main/station_branding.webp') }}" alt="" />
            </div>
        </div>
        <div class="pledge mb-4">
            <img id="pledge-image" onclick="showModal()" src="{{ asset('files/main/ocean_or_platic.webp') }}"
                alt="" />
        </div>
        <div class="map mb-5">
            <img class="map-img" src="{{ asset('files/main/Loccitane Map.webp') }}" alt="" />
            {{-- loop trough the $stations --}}
            <a class="map-pin start-pin"><span class="start-text">BEGIN HERE</span></a>
            @foreach ($stations as $station)
                @if($canStation6 == false && $station->id == 6)
                <a href="javascript:void(0);" class="map-pin station-{{ $station->id }} @if ($station->status == true) completed @endif"
                    data-bs-toggle="modal" data-bs-target="#redemption">
                    @if ($station->status != true)
                    {{ $station->id }}
                    @else
                    <i class="fa-solid fa-check"></i>
                    @endif
                </a>
                @else
                <a href="{{ route('station', $station) }}"
                    class="map-pin station-{{ $station->id }} @if ($station->status == true) completed @endif">
                    @if ($station->status != true)
                    {{ $station->id }}
                    @else
                    <i class="fa-solid fa-check"></i>
                    @endif
                </a>
                @endif


            @endforeach
        </div>
        @if($is2000 == true)
        <div class="redeem mb-1">
            <img onclick="showDateModal()" src="{{ asset('files/main/Loccitane Gift.webp') }}" alt="" />
        </div>
        @endif
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-xmark"></i></a>

                        <div id="choose">
                            <div class="info-icon mb-2">
                                <img class="px-3" src="{{ asset('files/main/station_branding.webp') }}"
                                    alt="" />
                            </div>
                            <p class="modal-main-text mb-3 mt-4">Will you pledge to protect our oceans?</p>
                            <div class="radio-button-choice p-3 mb-3">
                                <div class="form-check form-check">
                                    <input class="form-check-input" type="radio" name="pledgeOptions" id="pledgeYes"
                                        value="yes">
                                    <label class="form-check-label" for="pledgeYes">Yes</label>
                                </div>
                                <div class="form-check form-check">
                                    <input class="form-check-input" type="radio" name="pledgeOptions" id="pledgeNo"
                                        value="no">
                                    <label class="form-check-label" for="pledgeNo">No</label>
                                </div>
                            </div>
                            <div class="">
                                <button id="confirmVisitButton" type="submit" class="button button-primary w-100 mb-2">
                                    Submit
                                </button>
                            </div>
                        </div>

                        <div id="selected" class="d-none">
                            <div class="pledge-answer mb-2">
                                <img class="px-3" id="selected-answer-img" src="" alt="" />
                            </div>

                            <button id="close" data-bs-dismiss="modal" type="button"
                                class="button button-primary w-100 mb-2">
                                Close
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="date" tabindex="-1" aria-labelledby="dateLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"
                            style="background: none; border: none;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div class="container mb-3">
                            <div>
                                @include('components.branding')
                            </div>
                        </div>
                        <p class="text-center">Flash your QR code at the Redemption Counter upon completing the Ocean or
                            Plastic Roadshow Journey (5 stations) for verification.</p>
                        <div class="qr d-flex justify-content-center"></div>
                        <p class="text-center mt-4"><span
                                id="selected-date">{{ $selectedAppointment->appointment->name ?? '' }}</span>,
                            {{ $convertedDate }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="redemption" tabindex="-1" aria-labelledby="dateLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></a>
                        <div class="container mb-3">
                            <div>
                                @include('components.branding')
                            </div>
                        </div>
                        <p class="text-center">Oops, Complete all your stations to redeem your free gifts</p>
                        <button id="close" data-bs-dismiss="modal" type="button" class="button button-primary w-100 mb-2">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="staffId" tabindex="-1" aria-labelledby="dateLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></a>
                        <div class="container mb-3">
                            <div>
                                @include('components.branding')
                            </div>
                        </div>
                        <p class="text-center py-4 fw-bold">{{ $selectedStaff }}</p>
                    </div>
                </div>
            </div>
        </div>



        <div class="footer-container p-0 mt-auto">
            @include('components.footer')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script>
        let pledgeModalInstance; // Instance for the pledge modal (exampleModal)
        let dateModalInstance; // Instance for the date modal

        function showStaffIdModal() {
            $('#staffId').modal('show');
        }

        function showDateModal() {
            const modalElement = document.getElementById('date');
            if (!modalElement) return;

            const qrDiv = modalElement.querySelector('.qr');
            if (qrDiv) {
                qrDiv.innerHTML = ''; // Clear previous QR code if any

                // Ensure the $user variable (with an 'id' property) is passed to this Blade view from your controller.
                // If this QR code is for the currently authenticated user, you could use Auth::id().
                // Example: const userSpecificUrl = "{{ rtrim(env('APP_URL', 'http://localhost'), '/') }}/user?id={{ Auth::id() }}";
                const userSpecificUrl = "{{ env('APP_URL') }}user?id={{ $user->id }}";

                new QRCode(qrDiv, {
                    text: userSpecificUrl,
                    width: 128, // You can adjust the size as needed
                    height: 128,
                    correctLevel: QRCode.CorrectLevel.H // Error correction level
                });
            }

            if (!dateModalInstance) { // Initialize if not already done for the date modal
                dateModalInstance = new bootstrap.Modal(modalElement);
            }
            dateModalInstance.show();
        }

        // Define showModal in the global scope so it can be accessed by the onclick attribute
        function showModal() {
            const modalElement = document.getElementById('exampleModal');
            if (!modalElement) return;

            if (!pledgeModalInstance) { // Initialize if not already done for the pledge modal
                pledgeModalInstance = new bootstrap.Modal(modalElement);
            }

            // Get elements every time modal is shown to ensure fresh state
            const chooseDivLocal = modalElement.querySelector('#choose');
            const selectedDivLocal = modalElement.querySelector('#selected');
            const selectedAnswerImgLocal = modalElement.querySelector('#selected-answer-img');
            const pledgeYesRadioLocal = modalElement.querySelector('#pledgeYes');
            const pledgeNoRadioLocal = modalElement.querySelector('#pledgeNo');

            // if (pledgeYesRadioLocal) pledgeYesRadioLocal.checked = false;
            // if (pledgeNoRadioLocal) pledgeNoRadioLocal.checked = false;
            // if (selectedAnswerImgLocal) selectedAnswerImgLocal.src = ''; // Clear previous image
            // }
            // pledgeModalInstance.show(); // Corrected: use the specific instance

            const storedPledge = localStorage.getItem('userPledgeChoice');

            if (storedPledge) {
                if (selectedAnswerImgLocal) {
                    selectedAnswerImgLocal.src = storedPledge === 'yes' ? "{{ asset('files/main/yes.png') }}" :
                        "{{ asset('files/main/no.png') }}";
                }
                if (chooseDivLocal) chooseDivLocal.classList.add('d-none');
                if (selectedDivLocal) selectedDivLocal.classList.remove('d-none');
            } else {
                if (chooseDivLocal) chooseDivLocal.classList.remove('d-none');
                if (selectedDivLocal) selectedDivLocal.classList.add('d-none');
                if (pledgeYesRadioLocal) pledgeYesRadioLocal.checked = false;
                if (pledgeNoRadioLocal) pledgeNoRadioLocal.checked = false;
                if (selectedAnswerImgLocal) selectedAnswerImgLocal.src = ''; // Clear previous image
            }
            pledgeModalInstance.show(); // Use the correct instance to show
        }

        document.addEventListener('DOMContentLoaded', function() {
            const confirmVisitButton = document.getElementById('confirmVisitButton');
            const chooseDiv = document.getElementById('choose'); // Used by confirmVisitButton listener
            const selectedDiv = document.getElementById('selected'); // Used by confirmVisitButton listener
            const selectedAnswerImg = document.getElementById(
                'selected-answer-img'); // Used by confirmVisitButton listener
            const pledgeYesRadio = document.getElementById('pledgeYes');
            const pledgeNoRadio = document.getElementById('pledgeNo');
            const pledgeImage = document.getElementById(
                'pledge-image'); // The main clickable image outside the modal

            function applyStoredPledge() {
                const storedPledge = localStorage.getItem('userPledgeChoice');
                if (pledgeImage) { // Ensure pledgeImage element exists
                    if (storedPledge === 'yes') {
                        pledgeImage.src = "{{ asset('files/main/pledge.webp') }}";
                    } else { // Covers 'no' or null/undefined, reverting to default
                        pledgeImage.src = "{{ asset('files/main/ocean_or_platic.webp') }}";
                    }
                }
            }

            applyStoredPledge(); // Apply on page load

            // Ensure the modal instance is created if not already by showModal (e.g. if other scripts need it)
            // This is somewhat redundant if showModal is the only entry point, but safe.
            const exampleModalElement = document.getElementById('exampleModal');
            if (exampleModalElement && !pledgeModalInstance) {
                pledgeModalInstance = new bootstrap.Modal(exampleModalElement);
            }
            const dateModalElement = document.getElementById('date');
            if (dateModalElement && !dateModalInstance) {
                dateModalInstance = new bootstrap.Modal(dateModalElement);
            }

            if (confirmVisitButton) {
                confirmVisitButton.addEventListener('click', function() {
                    let choiceValue = '';
                    let modalImgSrc = ''; // Image for inside the modal

                    if (pledgeYesRadio && pledgeYesRadio.checked) {
                        choiceValue = 'yes';
                        modalImgSrc = "{{ asset('files/main/yes.png') }}";
                        if (pledgeImage) {
                            pledgeImage.src =
                                "{{ asset('files/main/pledge.webp') }}"; // Update main page image
                        }
                    } else if (pledgeNoRadio && pledgeNoRadio.checked) {
                        choiceValue = 'no';
                        modalImgSrc = "{{ asset('files/main/no.png') }}";
                        if (pledgeImage) {
                            pledgeImage.src =
                                "{{ asset('files/main/ocean_or_platic.webp') }}"; // Revert main page image
                        }
                    } else {
                        // Optionally handle the case where neither is selected, e.g., show an alert
                        console.log('No pledge option selected.');
                        return;
                    }

                    if (selectedAnswerImg) {
                        selectedAnswerImg.src = modalImgSrc; // Set the image inside the modal
                    }

                    localStorage.setItem('userPledgeChoice', choiceValue);

                    // Switch views inside the modal
                    if (chooseDiv) {
                        chooseDiv.classList.add('d-none');
                    }
                    if (selectedDiv) {
                        selectedDiv.classList.remove('d-none');
                    }
                });
            }

            // The event listener for the 'close' button (id="close") has been removed earlier
            // as it relies on data-bs-dismiss="modal".

            // Example of how to clear the pledge for testing (you can adapt this to a button or other event)
            // document.getElementById('someClearButton').addEventListener('click', function() {
            //     localStorage.removeItem('userPledgeChoice');
            //     applyStoredPledge(); // Re-apply default state to pledgeImage
            //     alert('Pledge cleared from local storage.');
            // });
        });
    </script>

</x-app-layout>
