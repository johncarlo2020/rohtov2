<x-app-layout>
    <div class="content-box main-background d-flex flex-column min-vh-100 px-3">
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>

        <div class="guest-container rounded-3 bg-white p-3 mb-4 ">
            <div class="d-flex justify-content-center align-items-center gap-3 px-4 mb-2">
                <img src="{{ asset('files/main/globe.webp') }}" alt="Sea Turtle" class="mb-3 turtle" />
                <div>
                    <h2 class="heading-text mb-0">Guess & Win!</h2>
                    <p class="sub-heading-text-small mb-2">Join us on an eco-journey and make sustainable choices. The
                        best part? Complete at least 3 out of 5 eco-challenges and receive a RM20 cash voucher (min.
                        spend of RM150)!</p>
                    <p class="sub-heading-text-small mb-0">Kindly note that voucher redemption is strictly only at the
                        Ocean or Plastic Roadshow, IOI City Mall, from 27 May to 2 June.</p>
                </div>
            </div>
            <div class="px-2 d-flex justify-content-between align-items-center">
                <div class="progress w-75" style="height: 10px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%;"
                        aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <p class="sub-heading-text-small fw-bold mb-0">Completed {{ $userDone }}/5</p>
            </div>
            <div class=" pb-2 mb-3 border-bottom">
                <p class="warning-text text-center mb-0">*Once completed all 3 task, please walk in to our roadshow to
                    claim you voucher.</p>
            </div>

            <div class="journey-container">

                @foreach ($tasks as $task)
                    @if (!$loop->last)
                        <div class="custom-task-item pb-2 mb-3 border-bottom">
                            <a href="{{ route('embarckStation', ['station' => $task->id]) }}"
                                class="d-flex align-items-start guest-btn p-3 {{ $task['status'] === 'completed' ? 'completed' : '' }}">
                                <!-- Image Placeholder -->
                                <div class="task-image-placeholder me-2">
                                    <img src="{{ asset('files/main/task_' . $task->id . '_3x.webp') }}" alt="Task Image"
                                        style="width:50px; height:50px; object-fit:cover;">

                                </div>

                                <!-- Main Content Area -->
                                <div class="task-content-area flex-grow-1 py-1 px-2 me-2">
                                    <h6 class="task-title fw-bold mb-1">
                                        {{ $task['name'] }}
                                        @if ($task['status'] === 'completed')
                                            <i class="fa-solid fa-circle-check ml-1 text-success"></i>
                                        @endif
                                    </h6>
                                    <p class="task-description text-muted small mb-0" style="line-height: 1.3;">
                                        {{ $task['description'] }}
                                    </p>
                                </div>
                            </a>
                        </div>
                        {{-- if this is the last task --}}
                    @else
                        <div class="custom-task-item pb-2 mb-3 border-bottom">
                            <div class="accordion" id="accordionTask{{ $task->id }}">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header border-none bg-white" id="heading{{ $task->id }}">
                                        <button
                                            class="accordion-button border-none bg-white d-flex align-items-start p-3 {{ $task['status'] === 'completed' ? 'completed' : '' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $task->id }}" aria-expanded="true"
                                            aria-controls="collapse{{ $task->id }}">
                                            <!-- Image Placeholder -->
                                            <div class="task-image-placeholder me-2">
                                                <img src="{{ asset('files/main/task_' . $task->id . '_3x.webp') }}"
                                                    alt="Task Image" style="width:50px; height:50px; object-fit:cover;">
                                            </div>

                                            <!-- Main Content Area - Title Only -->
                                            <div class="task-content-area flex-grow-1 py-1 px-2 me-2">
                                                <h6 class="task-title fw-bold mb-0">
                                                    {{ $task['name'] }}
                                                    @if ($task['status'] === 'completed')
                                                        <i class="fa-solid fa-circle-check ml-1 text-success"></i>
                                                    @endif
                                                </h6>
                                                <p class="task-description text-muted small mb-3"
                                                    style="line-height: 1.3;">
                                                    {{ $task['description'] }}
                                                </p>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $task->id }}" class="accordion-collapse collapse show"
                                        aria-labelledby="heading{{ $task->id }}"
                                        data-bs-parent="#accordionTask{{ $task->id }}">
                                        <div class="accordion-body pt-0 pb-3 px-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="checkDefault">
                                                <label class="form-check-label fw-bold" for="checkDefault">
                                                    Data Protection & Privacy Notice
                                                </label>
                                            </div>
                                            <div class="pl-4 task-description text-muted small">
                                                By agreeing and ticking the box, you consent to allow Alliance Bank
                                                Malaysia Berhad to collect and use your personal information (including
                                                your name and contact details) for the purpose of contacting you about
                                                sustainable banking solutions, offers, and services.
                                                Your data will be handled in accordance with the Personal Data
                                                Protection Act 2010 (PDPA) and Alliance Bank Malaysia Berhad’s privacy
                                                policy. Your information will not be shared with any third party without
                                                your consent.
                                                You may withdraw your consent at any time by contacting Alliance Bank
                                                directly.
                                            </div>
                                             <div class="button-container mt-3 d-flex justify-content-end">
                                                <a id="homeButton" href="{{ route('embarckJourney') }}" class="button py-2 px-3 small button-secondary w-25">
                                                    Submit
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>

        <div class="text-center mt-auto px-4 d-flex justify-content-center">
            <a href="{{ route('preRegEvent') }}" class="button button-white w-50">Back</a>
        </div>
        <div class="footer-container p-4">
            @include('components.footer')
        </div>
    </div>
</x-app-layout>
