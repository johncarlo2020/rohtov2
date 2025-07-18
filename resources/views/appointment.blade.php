<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config("app.name", "Ocean or Plastic") }}</title>

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
        <div id="dateForm" class=" bg-white p-3 rounded d-none">
                @csrf
                <div class="text-center mb-4 px-1">
                    <h2 class="heading-text text-center mb-2">Hi, {{auth()->user()->fname}}!</h2>
                    <p class="sub-heading-text text-center">Please select your preferred date for the Ocean or Plastic
                        Roadshow visit and redemption.</p>
                    <p class="sub-heading-text text-center">Note: Redemption is only valid on the selected date and can be rescheduled once after submission.
                    </p>
                </div>
                <div class="date-picker">

                        <h2 class="heading-text text-center mb-2">Date selected: <span id="selectedDateText">21-05-2025</span></h2>
                        <h4 class="text-center mb-4 d-none">Available Slots: <span id="availableSlotsText">0</span></h4>



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
        @if($is2000 == 1)
                <div id="qrContainer" class=" bg-white p-3 rounded d-none">
                    <div class="text-center mb-2 px-1">
                        <h2 class="heading-text text-center mb-2">Congratulations, {{auth()->user()->fname}}!</h2>
                        <p class="pharagraph-text text-center">You're among the first 500 sign-ups and are eligible to redeem our exclusive Upcycled Marine Phone Charm — sustainably
                        crafted as part of our commitment to protecting the ocean</p>
                        <br>
                        <p>To fully experience the roadshow journey, please ensure you log in to the microsite. It will be your personal guide
                        throughout — from interactive touchpoints to completing activities and unlocking rewards.
                        <br>
                        We look forward to seeing you there!</p>
                    </div>
                    <div id="qrCode" class="qr-code mb-3">

                    </div>
                        <div id="dateSelected">
                                <p class="sub-heading-text text-center mb-0">Date selected: <span id="selected-date">{{
                                        $selectedAppointment->appointment->name ?? '' }}</span>, {{ $convertedDate }} <br /> Venue: IOI City Mall,
                                    Putrajaya – West Court on Ground Floor</p>
                        </div>

                    <div class="p-3">
                        <p class="pharagraph-text mb-0"><Strong>Terms & Conditions</Strong></p>
                        <br>
                        <ol>
                            <li class="pharagraph-text">Redemption of the Upcycled Phone Charm is only available upon completion of all
                                five stations of the Ocean or Plastic
                                Roadshow journey.</li> <br>
                            <li class="pharagraph-text">Redemption is strictly limited to the selected date and stated venue. Attempts
                                to redeem on any other date or location
                                will not be accepted under any circumstances.
                            </li> <br>
                            <li class="pharagraph-text">Redemption must be made in person by the registered participant. It is
                                non-transferable and cannot be exchanged for
                                cash, products, or services.</li>
                        </ol>
                        <div class="button-container">
                            <a id="homeButton" href="{{ route('preRegEvent') }}" class="button button-primary w-100 mb-2">
                                Home
                            </a>

                            <button type="button" class="button button-secondary w-100 {{ $user->type != 'pre-reg' ? 'd-none' : '' }}" @disabled(!is_object($selectedAppointment) ||
                                ($selectedAppointment->rescheduled == 1)) data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Reschedule
                            </button>
                        </div>
                    </div>
                </div>

        @else
        <div id="qrContainer" class=" bg-white p-3 rounded d-none">
            <div class="text-center mb-2 px-1">
                <h2 class="heading-text text-center mb-2">Thank you, {{auth()->user()->fname}}!</h2>
                <p class="pharagraph-text text-center">We’re thrilled to have you join us in our commitment to protecting the ocean and making mindful choices for our planet.</p>
                <br>
                <h1 class="pharagraph-text text-center">As you explore the experience, the microsite will be your personal guide — helping you navigate each step of our
                roadshow journey.</h1>
                <p class="pharagraph-text text-center">Complete the experience to redeem your 5-piece sample kit, curated with care to nurture you from head to toe, while
                staying kind to the Earth.</p>

            </div>

            <div class="p-3">
                <div class="button-container">
                    <a id="homeButton" href="{{ route('preRegEvent') }}" class="button button-primary w-100 mb-2">
                        Home
                    </a>
                </div>
            </div>
        </div>
        @endif

         <div class="footer-container p-4 mt-auto">
            @include('components.footer')
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
                <p class="modal-main-text mb-1">Are you sure, you want to reschedule your visit?</p>
                <p class="warning-text text-center">Note: You may reschedule your selected date <strong>only once</strong>.</p>
                   <div class="">
                        <button id="reschedule" data-bs-dismiss="modal" type="submit" class="button button-primary w-100 mb-2">
                           YES
                        </button>
                         <button id="submitButton" data-bs-dismiss="modal" type="submit" class="button button-secondary w-100 mb-2">
                           NO
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<!-- TikTok Pixel -->
<script>

    !function (w, d, t) {

        w.TiktokAnalyticsObject = t; var ttq = w[t] = w[t] || []; ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie"], ttq.setAndDefer = function (t, e) { t[e] = function () { t.push([e].concat(Array.prototype.slice.call(arguments, 0))) } }; for (var i = 0; i < ttq.methods.length; i++)ttq.setAndDefer(ttq, ttq.methods[i]); ttq.instance = function (t) {
            for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++

            )ttq.setAndDefer(e, ttq.methods[n]); return e
        }, ttq.load = function (e, n) { var i = "https://analytics.tiktok.com/i18n/pixel/events.js"; ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = i, ttq._t = ttq._t || {}, ttq._t[e] = +new Date, ttq._o = ttq._o || {}, ttq._o[e] = n || {}; n = document.createElement("script"); n.type = "text/javascript", n.async = !0, n.src = i + "?sdkid=" + e + "&lib=" + t; e = document.getElementsByTagName("script")[0]; e.parentNode.insertBefore(n, e) };

        ttq.track('CompleteRegistration');

        ttq.load('CIHP63RC77U9G5MV8B0G');

        ttq.page();

    }(window, document, 'ttq');

</script>

<!-- Facebook Pixel Code -->
<!-- <script>
    !function (f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function () {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '800121447587288');
    fbq('track', 'CompleteRegistration');
</script> -->

<!-- Facebook Pixel Code -->
<script>
    !function (f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function () {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1857983581193229');
    fbq('track', 'CompleteRegistration');
</script>

   <script>
        document.addEventListener('DOMContentLoaded', function() {
            //if user is pre-reg or first 2000 users
            @if ($is2000 == 1 && $user->type=='pre-reg')
                document.getElementById('dateSelected').classList.remove('d-none');

                @if($is2000>=0)
                    document.getElementById('qrContainer').classList.remove('d-none');
                    setTimeout(() => {
                        document.getElementById('qrContainer').classList.add('fade-in');
                    }, 100);
                @endif

                const url = "{{ env('APP_URL') }}user?id={{ $user->id }}";

                generateQRCode(url);

                // Show QR container and hide date form
                @if ($userAppointment > 0)
                    document.getElementById('qrContainer').classList.remove('d-none');
                setTimeout(() => {
                    document.getElementById('qrContainer').classList.add('fade-in');
                }, 100);
                document.getElementById('dateForm').classList.add('d-none');

                    // If the appointment is rescheduled, hide the reschedule button
                    @if ($selectedAppointment -> rescheduled == 1)
                        document.getElementById('reschedule').classList.add('d-none');
                    @endif
                @else

                // If the user has not selected an appointment, show the date form
                document.getElementById('qrContainer').classList.add('d-none');
                document.getElementById('dateForm').classList.remove('d-none');
                setTimeout(() => {
                    document.getElementById('dateForm').classList.add('fade-in');
                }, 100);
                @endif
                // If the user has selected an appointment, show the QR code
            @elseif($is2000 == 1 && $user->type !='pre-reg')
            const url = "{{ env('APP_URL') }}user?id={{ $user->id }}";

            generateQRCode(url);
                console.log('User is not pre-reg but is in the first 2000');
                document.getElementById('dateSelected').classList.add('d-none');

                document.getElementById('qrContainer').classList.remove('d-none');
            setTimeout(() => {
                document.getElementById('qrContainer').classList.add('fade-in');
            }, 100);
            @else

                document.getElementById('qrContainer').classList.remove('d-none');
                setTimeout(() => {
                    document.getElementById('qrContainer').classList.add('fade-in');
                }, 100);
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
            const reschedule = document.getElementById('reschedule');
            reschedule.addEventListener('click', function () {
                // Fade out QR container
                document.getElementById('qrContainer').classList.remove('fade-in');
                document.getElementById('qrContainer').classList.add('fade-out');

                // After animation completes, hide QR and show date form with animation
                setTimeout(() => {
                    document.getElementById('qrContainer').classList.add('d-none');
                    document.getElementById('dateForm').classList.remove('d-none');

                    setTimeout(() => {
                        document.getElementById('dateForm').classList.add('fade-in');
                    }, 50);
                }, 300);
            });


            // Function to update selected date text
            function updateSelectedDate(inputElement) {
                const labelForInput = document.querySelector('label[for="' + inputElement.id + '"]');
                const selectedDate = document.getElementById('selected-date');
                if (labelForInput && selectedDateText) {
                    selectedDateText.textContent = labelForInput.textContent;
                    selectedDate.textContent = labelForInput.textContent;

                }
            }

            // Set initial selected date text based on checked input
            const initiallyCheckedInput = document.querySelector('.date-radio-input:checked');

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
                        // // Fade out date form
                        // document.getElementById('dateForm').classList.remove('fade-in');
                        // document.getElementById('dateForm').classList.add('fade-out');

                        // // After animation completes, hide date form and show QR with animation
                        // setTimeout(() => {
                        //     document.getElementById('dateForm').classList.add('d-none');
                        //     document.getElementById('qrContainer').classList.remove('d-none');

                        //     if(data['appointment']['rescheduled'] == 1){
                        //         document.getElementById('reschedule').classList.add('d-none');
                        //     }

                        //     setTimeout(() => {
                        //         document.getElementById('qrContainer').classList.add('fade-in');
                        //     }, 50);
                        // }, 300);

                        window.location.reload();
                    })
                    .catch(error => {

                    });
            });
        });
    </script>
</body>

</html>
