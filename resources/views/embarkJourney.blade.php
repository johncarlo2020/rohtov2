<x-app-layout>
    <div class="content-box main-background d-flex flex-column min-vh-100 px-3">
        <a href="{{ route('preRegEvent') }}" class="go-home"><i class="fa-solid fa-arrow-left"></i></a>
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>

        <div class="guest-container fade-in">
            <div class="header-box rounded-3 bg-white p-3 mb-3 ">
                <div class="d-flex justify-content-center align-items-center gap-3 px-4 mb-2">
                    <img src="{{ asset('files/main/globe.webp') }}" alt="Sea Turtle" class="mb-3 turtle" />
                    <div>
                        <h2 class="heading-text mb-0">Be Part of The Change <br> & Get Rewarded</h2>
                        <p class="sub-heading-text mb-2">Complete at least 3 of our 5 eco-challenges and earn a RM20 cash voucher (min. spend RM150).
                        </p>
                        <p class="sub-heading-text mb-0">Redeem exclusively at our Ocean or Plastic Roadshow, One Utama.
                        </p>
                    </div>
                </div>
                <div class="px-2 d-flex justify-content-between align-items-center mb-2">
                    <div class="progress w-75" style="height: 10px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%;"
                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <p class="sub-heading-text-small fw-bold mb-0">Completed {{ $userDone }}/5</p>
                </div>
                <div class="mb-2 px-3">
                    <p class="warning-text sub-heading-text-small small text-left mb-0">Note: Once you complete at least 3 tasks, visit our roadshow to validate your participation and claim your voucher.</p>
                </div>
            </div>

            <div class="journey-container">
                @foreach ($tasks as $task)
                    @if (!$loop->last)
                        <div class="custom-task-item pb-2 mb-3 rounded-3 bg-white">
                            <a href="{{ route('embarckStation', ['station' => $task->id]) }}"
                                class="d-flex align-items-start guest-btn p-3 {{ ($task['status'] === 'completed' || $task['status'] === 'in-progress') ? 'completed' : '' }}">
                                <!-- Image Placeholder -->
                                <div class="task-image-placeholder me-2">
                                    <img src="{{ asset('files/main/task_' . $task->id . '_3x.webp') }}" alt="Task Image"
                                        style="width:50px; height:50px; object-fit:cover;">

                                </div>

                                <!-- Main Content Area -->
                                <div class="task-content-area flex-grow-1 py-1 px-2 me-2">
                                    <h6 class="task-title fw-bold pr-4">
                                        @if($task['id'] == 1)
                                            Task {{ $task->id }} : {{ $task['name'] }}
                                        @else
                                            @if($task['id'] == 4)
                                                Task {{ $task->id }} : Pledge for the Ocean
                                            @else
                                            Task {{ $task->id }}: {{ $task['name'] }}
                                            @endif
                                        @endif
                                         <i class="fa-solid fa-circle-check ml-1"></i>
                                    </h6>
                                    <p class="task-description text-muted small mb-0" style="line-height: 1.3;">
                                        @if($task['id'] == 4)
                                            Make a conscious choice to opt for reusable alternatives
                                        @else
                                        {{ $task['description'] }}

                                        @endif
                                    </p>
                                </div>
                            </a>
                        </div>
                        {{-- if this is the last task --}}
                    @else
                        <div class="custom-task-item mb-3 border-bottom rounded-3 bg-white">
                            <div class="accordion" id="accordionTask{{ $task->id }}">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header border-none" id="heading{{ $task->id }}">
                                        <button
                                            class="accordion-button collapsed  border-bottom guest-btn bg-white d-flex align-items-start p-3 {{ ($task['status'] === 'completed' || $task['status'] === 'in-progress') ? 'completed' : '' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $task->id }}" aria-expanded="false"
                                            aria-controls="collapse{{ $task->id }}">
                                            <!-- Image Placeholder -->
                                            <div class="task-image-placeholder me-2">
                                                <img src="{{ asset('files/main/task_' . $task->id . '_3x.webp') }}"
                                                    alt="Task Image" style="width:50px; height:50px; object-fit:cover;">
                                            </div>

                                            <!-- Main Content Area - Title Only -->
                                            <div class="task-content-area flex-grow-1 py-1 px-2 me-2">
                                                <h6 class="task-title fw-bold pr-5">
                                                    Task {{ $task->id }}: {{ $task['name'] }}
                                                     <i class="fa-solid fa-circle-check ml-1"></i>
                                                </h6>
                                                <p class="task-description text-muted small mb-0"
                                                    style="line-height: 1.3;">
                                                    {{ $task['description'] }}
                                                </p>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $task->id }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $task->id }}"
                                        data-bs-parent="#accordionTask{{ $task->id }}">
                                        <div class="accordion-body pt-3 pb-3 px-3">
                                            <form action="{{ route('consent.submit') }}" method="POST">
                                                @csrf

                                                <div class="form-check">
                                                    <input class="form-check-input consent-checkbox" type="checkbox"
                                                        id="checkDefault{{ $task->id }}" name="consent"
                                                        value="1" data-task-id="{{ $task->id }}">

                                                    <label class="form-check-label fw-bold" for="checkDefault">
                                                        Data Protection & Privacy Notice
                                                    </label>
                                                </div>
                                                <div class="pl-4 task-description text-muted small">
                                                    By agreeing and ticking the box, you consent to allow Alliance Bank
                                                    Malaysia Berhad to collect and use your personal information
                                                    (including
                                                    your name and contact details) for the purpose of contacting you
                                                    about
                                                    sustainable banking solutions, offers, and services.
                                                    Your data will be handled in accordance with the Personal Data
                                                    Protection Act 2010 (PDPA) and Alliance Bank Malaysia Berhad’s
                                                    privacy
                                                    policy. Your information will not be shared with any third party
                                                    without
                                                    your consent.
                                                    You may withdraw your consent at any time by contacting Alliance
                                                    Bank
                                                    directly.
                                                </div>
                                                <div class="button-container mt-3 d-flex justify-content-end">
                                                    <button type="submit"
                                                        class="button py-2 px-3 small button-secondary w-25">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>

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
