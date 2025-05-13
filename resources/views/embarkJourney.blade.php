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
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 75%;" aria-valuenow="75"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="sub-heading-text-small fw-bold mb-0">Completed 1/5</p>
            </div>
            <div class=" pb-2 mb-3 border-bottom">
                <p class="warning-text text-center mb-0">*Once completed all 3 task, please walk in to our roadshow to
                    claim you voucher.</p>
            </div>

            <div class="journey-container">
                <div class="custom-task-item pb-2  mb-3 border-bottom">
                    <a href="{{ route('embarckStation', ['station' => 3]) }}" class="d-flex align-items-start guest-btn p-3">
                        <!-- Image Placeholder -->
                        <div class="task-image-placeholder me-2">
                            <div
                                style="width:50px; height:50px; background-color: #e0f2ff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; color:#99cfff; text-align:center;">
                                Image</div>
                        </div>

                        <!-- Main Content Area (with dotted border) -->
                        <div class="task-content-area flex-grow-1 py-1 px-2 me-2">
                            <h6 class="task-title fw-bold mb-1 ">Task 3: Skip Single-use Straw & Bottle or Cup</h6>
                            <p class="task-description text-muted small mb-0 sub-heading-text-small"
                                style="line-height: 1.3;">
                                Make a conscious choice to avoid plastic straws and bottles – opt for reusable
                                alternatives.
                            </p>
                        </div>

                        <!-- Checkmark Area -->
                        <div
                            class="task-status-checkmark d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </a>
                </div>

                  <div class="custom-task-item pb-2  mb-3 border-bottom">
                    <a class="d-flex align-items-start guest-btn p-3 completed">
                        <!-- Image Placeholder -->
                        <div class="task-image-placeholder me-2">
                            <div
                                style="width:50px; height:50px; background-color: #e0f2ff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; color:#99cfff; text-align:center;">
                                Image</div>
                        </div>

                        <!-- Main Content Area (with dotted border) -->
                        <div class="task-content-area flex-grow-1 py-1 px-2 me-2">
                            <h6 class="task-title fw-bold mb-1 ">Task 3: Skip Single-use Straw & Bottle or Cup</h6>
                            <p class="task-description text-muted small mb-0 sub-heading-text-small"
                                style="line-height: 1.3;">
                                Make a conscious choice to avoid plastic straws and bottles – opt for reusable
                                alternatives.
                            </p>
                        </div>

                        <!-- Checkmark Area -->
                        <div
                            class="task-status-checkmark d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-auto px-4 d-flex justify-content-center">
            <button type="button" class="button button-white w-50">Back</button>
        </div>
        <div class="footer-container p-4">
            @include('components.footer')
        </div>
    </div>
</x-app-layout>
