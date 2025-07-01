<x-guest-layout>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Your Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Guardian Name:</strong> <span id="modalGuardian"></span></p>
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                    <p><strong>Workshop:</strong> <span id="modalWorkshop"></span></p>
                    <p><strong>No. of Attendees:</strong> <span id="modalAttendee"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="slot" tabindex="-1" aria-labelledby="slotLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="slotLabel">DIY Bento Workshop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong></strong>  <span id="modalGuardian" class="text-danger">Insufficient Slot</span></p>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-primary" id="okayBtn">Okay</button>
                </div>
            </div>
        </div>
    </div>

    <div class="register-workflow with-scroll">
        <div class="justify-content-center w-100">
            <div class="mt-5 col-12 d-flex justify-content-center">
                @include('components.branding')
            </div>
            <div class="mt-3 w-100 px-2">
                <h1 class="mb-4 text-center heading-dutch text-center">
                    Register for the workshop
                </h1>
                <div class="card register-info-box mb-3 p-3">
                    <h2 class="text-center mb-3 register-info-title">
                        Workshop Info!
                    </h2>
                    <ul class="register-info-list">
                        <li>
                            <strong>DIY Bento</strong> Kids only (Let the little
                            chefs shine!)
                        </li>
                        <li>
                            <strong>Sip & Paint</strong> For everyone, adults
                            and kids welcome!
                        </li>
                        <li>One session per customer, everyone gets a turn!</li>
                        <li>
                            Please arrive 15 minutes early so you don't miss the
                            fun!
                        </li>
                    </ul>
                </div>
                <div class="card py-5 px-4 register-workflow-form-parent">
                    <form id="form" method="POST" action="">
                        @csrf
                        <div class="mb-2 row">
                            <div class="col-12">
                                <label for="" class="text-blue"><strong>Guardian Name</strong></label>
                                <input id="fname" placeholder="Enter your name" type="text"
                                    class="input-text form-control @error('fname') is-invalid @enderror" name="guardian"
                                    value="{{ old('fname') }}" required autocomplete="fname" autofocus />
                                @error('fname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-12">
                                <label for="" class="text-blue"><strong>Date</strong></label>

                                <select class="form-select input-text" name="date" aria-label="Default select example"
                                    required>
                                    <option value="" selected disabled>
                                        Select your preferred date
                                    </option>
                                    @foreach ($appointmentDates as $date)
                                    <option value="{{ $date->id }}">
                                        {{ \Carbon\Carbon::parse($date->date)->format('jS F Y (l)') }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-12">
                                <label for="" class="text-blue"><strong>Workshop Session</strong></label>
                                <span class="text-danger">*</span><small>Limited to 20 pax only</small>
                                <select id="workshop-select" class="form-select input-text" name="workshop"
                                    aria-label="Default select example" required>
                                    <option value="" selected disabled>
                                        Select your preferred workshop
                                    </option>
                                    @foreach ($workshops as $workshop)
                                    <option value="{{ $workshop->id }}">
                                        {{ $workshop->title }}
                                        ({{ $workshop->time }})
                                    </option>

                                    @endforeach
                                </select>
                                @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <span class="text-danger"><strong> <span id="available-slots">0</span> slots left</strong></span>
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-12 mb-3">
                                <label for="" class="text-blue"><strong>No. of Attendee</strong></label>
                                <span class="text-danger">*</span><small>No. of joining attendee</small>
                                <div class="quantity-selector">
                                    <button type="button" class="qty-btn minus">
                                        −
                                    </button>
                                    <input type="text" id="attendee" name="attendee" class="qty-input" value="1" max="3" readonly />
                                    <button type="button" class="qty-btn plus">
                                        +
                                    </button>
                                </div>
                                @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0 row">
                            <div class="col-12 text-center">
                                <button id="submitButton" type="button"
                                    class="w-auto main-btn button-dutch button-dutch-primary">
                                    {{ __("SUBMIT") }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const minusBtn = document.querySelector(".minus");
        const plusBtn = document.querySelector(".plus");
        const qtyInput = document.querySelector(".qty-input");

        minusBtn.addEventListener("click", () => {
            let value = parseInt(qtyInput.value);
            if (value > 1) qtyInput.value = value - 1;
        });

        plusBtn.addEventListener("click", () => {
            let value = parseInt(qtyInput.value);
            let max = parseInt(qtyInput.max);
            if (value < max) qtyInput.value = value + 1;
        });
        const select = document.getElementById("workshop-select");
        const slotInfo = document.getElementById("slot-info");
        const workshopCheckUrl = "{{ route('workshop.check') }}";


        select.addEventListener('change', function () {
            const workshopId = this.value;
            const appointmentDates = document.querySelector('select[name="date"]').value;

            const url = `${workshopCheckUrl}?id=${workshopId}&date=${appointmentDates}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var availableSlots = data.slots; // Don't subtract again
                    document.getElementById("available-slots").textContent = availableSlots;

                    const attendeeInput = document.getElementById("attendee");

                    if (availableSlots <= 3 && availableSlots > 0) {
                        document.getElementById("available-slots").classList.add("text-danger");
                        attendeeInput.max = availableSlots;
                        attendeeInput.readOnly = availableSlots <= 1;
                        document.getElementById("submitButton").disabled = false;
                    } else if (availableSlots == 0) {
                        attendeeInput.max = 0;
                        attendeeInput.value = 0;
                        attendeeInput.readOnly = false;
                        document.getElementById("submitButton").disabled = true;
                        document.getElementById("available-slots").classList.remove("text-danger");
                    } else {
                        attendeeInput.max = 3;
                        attendeeInput.readOnly = availableSlots <= 1;
                        document.getElementById("submitButton").disabled = false;
                        document.getElementById("available-slots").classList.remove("text-danger");
                    }
                })
                .catch(error => {
                    slotInfo.textContent = 'Error fetching slot info.';
                    console.error(error);
                });
        });

        document.getElementById("submitButton").addEventListener("click", function () {
            const guardian = document.getElementById("fname").value;
            const dateSelect = document.querySelector('select[name="date"]');
            const dateText = dateSelect.options[dateSelect.selectedIndex].text;
            const workshopSelect = document.getElementById("workshop-select");
            const workshopText = workshopSelect.options[workshopSelect.selectedIndex].text;
            const attendee = document.getElementById("attendee").value;

            // Fill modal details
            document.getElementById("modalGuardian").textContent = guardian;
            document.getElementById("modalDate").textContent = dateText;
            document.getElementById("modalWorkshop").textContent = workshopText;
            document.getElementById("modalAttendee").textContent = attendee;

            // Show modal (Bootstrap 5)
            const confirmModal = new bootstrap.Modal(document.getElementById("confirmModal"));
            confirmModal.show();
        });
        document.getElementById("okayBtn").addEventListener("click", function () {
            location.reload();
        });

        document.getElementById("confirmBtn").addEventListener("click", function () {
            const guardian = document.getElementById("fname").value;
            const dateSelect = document.querySelector('select[name="date"]').value;
            const workshopSelect = document.getElementById("workshop-select").value;
            const attendee = document.getElementById("attendee").value;
            const workshopCheckUrl = "{{ route('workshop.submit') }}";

            const url = `${workshopCheckUrl}?workshop=${workshopSelect}&guardian=${encodeURIComponent(guardian)}&date=${dateSelect}&attendee=${attendee}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || 'Something went wrong');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Success logic here if needed
                    // document.getElementById("form").submit(); // for example
                    window.location.href = "{{ route('workshop.congrats') }}"; // Redirect to the success page
                })
                .catch(error => {
                    // Hide the confirmation modal properly
                    const confirmModalEl = document.getElementById("confirmModal");
                    const confirmModalInstance = bootstrap.Modal.getInstance(confirmModalEl);
                    if (confirmModalInstance) {
                        confirmModalInstance.hide();
                    }

                    // Show the error message in the "slot" modal
                    document.getElementById("modalGuardian").textContent = error.message;
                    const slotModal = new bootstrap.Modal(document.getElementById("slot"));
                    slotModal.show();
                });
        });

    });
</script>
