<x-app-layout>
    <div class="promotion-main with-scroll">
        <div class="justify-content-center px-3">
            <div class="col-12 d-flex justify-content-center mt-5">
                @include('components.branding')
            </div>

            <div class="col-12 d-flex justify-content-center align-items-center my-3">
                <img class="welcome_img w-50" src="{{ asset('images/dutchlady/DL Station Map (5) Registered.webp') }}"
                    alt="" />
            </div>

            <div class="content congrats-workshop text-center">
                <h1 class="heading">{{ $appointment->workshop->title ?? 'Workshop' }}</h1>

                {{-- QR Code --}}
                <img class="qr"
                    src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(config('app.url') . '/appointment/confirm?user_id=' . auth()->id()) }}"
                    alt="QR Code">

                <p class="name">{{ $appointment->guardian }}</p>
                <p class="date">{{ \Carbon\Carbon::parse($appointment->appointmentDate->date)->format('jS F Y (l)') }}
                </p>
                <p class="time">{{ $appointment->workshop->time }}</p>
                <p class="count">{{ $appointment->attendee }} person{{ $appointment->attendee > 1 ? 's' : '' }}</p>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <a href="{{ route('dashboard') }}" class="button-dutch button-dutch-primary text-center">
                    Done
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
