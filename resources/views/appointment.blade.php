<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hadalabo Experience</title>

      <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
            integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet" />
</head>

<body class="antialiased welcome-page">
    <div class="content-box main-background px-3 d-flex flex-column min-vh-100">
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>
        <div id="dateForm" class=" bg-white p-3 rounded ">
                @csrf
                <div class="text-center mb-4 px-1">
                    <h2 class="heading-text text-center mb-2">Hi {{auth()->user()->fname}}</h2>
                    <p class="sub-heading-text text-center">Please select your preferred date for the Ocean or Plastic
                        Roadshow visit and redemption.</p>
                    <p class="sub-heading-text text-center">Kindly note that redemption is only valid on the selected
                        date. Redemption on a different date will not be accommodated. You may only reschedule once,
                        after submission.
                    </p>
                </div>
                <div class="date-picker">
                    <h2 class="heading-text text-center mb-2">Date selected: <span
                            id="selectedDateText">21-05-2025</span></h2>
                    <h4 class="text-center mb-4">Available Slots: <span id="availableSlotsText">0</span></h4>

                    <div class="date-grid-container">
                        @foreach($appointments as $appointment)
                        @php
                        $isFull = isset($appointment->status) && $appointment->status === 'full';
                        @endphp

                        <div class="date-button-item">
                            <input type="radio" id="date{{ $appointment->id }}" name="date" value="{{ $appointment->id }}"
                                class="date-radio-input" data-name="{{ $appointment->name }}"
                                data-available="{{ $appointment->available_slots }}" required {{ $isFull ? 'disabled' : '' }}>

                            <label for="date{{ $appointment->id }}" class="date-radio-label {{ $isFull ? 'disabled' : '' }}">
                                {{ $appointment->name }}
                            </label>
                        </div>
                        @endforeach


                    </div>
                </div>
                <div class="text-center mb-3">
                    <button type="button" id="submitDate" class="button button-primary w-100">Submit</button>
                </div>
        </div>
        <div id="qrContainer" class=" bg-white p-3 rounded d-none">
            <div class="text-center mb-2 px-1">
                <h2 class="heading-text text-center mb-2">Congratulations! Selena</h2>
                <p class="pharagraph-text text-center">You’re among the first 2,000 sign-ups and eligible to redeem our
                    exclusive Upcycled Phone Charm!Kindly present this QR code at the redemption counter during our
                    Ocean or Plastic Roadshow.</p>
            </div>
            <div id="qrCode" class="qr-code mb-3">

            </div>

            <p class="sub-heading-text text-center mb-0">Date selected: <span id="selected-date"></span>, Wednesday Venue: IOI City Mall,
                Putrajaya – West Court on Ground Floor</p>
            <div class="p-3">
                <p class="pharagraph-text mb-0"><Strong>Terms & Conditions</Strong></p>
                <ol>
                    <li class="pharagraph-text">Redemption of the Upcycled Phone Charm is only available upon
                        completion of all five stations of the Ocean or Plastic Roadshow journey.</li>
                    <li class="pharagraph-text">Redemption is strictly limited to the selected date and stated venue.
                        Attempts to redeem on any other date or location will not be accepted under any circumstances.
                    </li>
                    <li class="pharagraph-text">Redemption must be made in person by the registered participant. It is
                        non-transferable and cannot be exchanged for cash, products, or services.</li>
                </ol>
                  <button id="submitButton" type="button" class="button button-primary w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          Home
                    </button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-body">
                <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></a>
                  <div  class="info-icon mb-3">
                    <img src="{{ asset('files/main/info.png') }}" alt="" />
                </div>
               <p class="modal-main-text mb-1">Do you want to pick this date for your visit ?</p>
               <p class="warning-text text-center">Note: You may reschedule your selected date only once.</p>
                   <div class="">
                        <button id="submitButton" type="submit" class="button button-primary w-100 mb-2">
                           YES
                        </button>
                         <button id="submitButton" type="submit" class="button button-secondary w-100 mb-2">
                           NO
                        </button>
                    </div>
              </div>
            </div>
          </div>
        </div>

        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
        </div>
    </div>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

   <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($is2000 == 1)
                const url = "{{ env('APP_URL') }}/user?id={{ $user->id }}";
            generateQRCode(url);
            @endif

            @if ($userAppointment > 0)
                document.getElementById('qrContainer').classList.remove('d-none');
            document.getElementById('dateForm').classList.add('d-none');
            @endif

            document.querySelectorAll('.date-radio-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    const selectedName = this.dataset.name;
                    const available = this.dataset.available;

                    document.getElementById('selectedDateText').textContent = selectedName;
                    document.getElementById('availableSlotsText').textContent = available;
                });
            });

            const dateInputs = document.querySelectorAll('.date-radio-input');
            const selectedDateText = document.getElementById('selectedDateText');

            // Function to update selected date text
            function updateSelectedDate(inputElement) {
                const labelForInput = document.querySelector('label[for="' + inputElement.id + '"]');
                if (labelForInput && selectedDateText) {
                    selectedDateText.textContent = labelForInput.textContent;
                }
            }

            // Set initial selected date text based on checked input
            const initiallyCheckedInput = document.querySelector('.date-radio-input:checked');
            const selectedDate = document.getElementById('selected-date');
            if (initiallyCheckedInput) {
                updateSelectedDate(initiallyCheckedInput);
                updateSelectedDate(selectedDate);
            }

            dateInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.checked) {
                        updateSelectedDate(this);
                    }
                });
            });

            function generateQRCode(url) {
                var qrCodeContainer = document.getElementById("qrCode");
                qrCodeContainer.innerHTML = ""; // Clear previous QR code
                var qrCode = new QRCode(qrCodeContainer, {
                    text: url,
                    width: 256,
                    height: 256,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H,
                });
            }

            document.getElementById('submitDate').addEventListener('click', function () {
                const selectedInput = document.querySelector('.date-radio-input:checked');

                if (!selectedInput) {
                    alert('Please select a date.');
                    return;
                }

                const appointmentId = selectedInput.value;

                fetch("{{ route('appointments.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        appointment_id: appointmentId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('qrContainer').classList.remove('d-none');
                        document.getElementById('dateForm').classList.add('d-none');

                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Something went wrong.');
                    });
            });
        });
    </script>
</body>

</html>
