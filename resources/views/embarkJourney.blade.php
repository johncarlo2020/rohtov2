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
                @php
                    $tasks = [
                        [
                            'id' => 1,
                            'title' => 'Task 1: Learn About Plastic Pollution',
                            'description' => 'Dive into the facts about how plastic waste impacts our oceans and marine life.',
                            'completed' => true,
                            'image' => 'files/main/task1.webp'
                        ],
                        [
                            'id' => 2,
                            'title' => 'Task 2: Say No to Plastic Bags',
                            'description' => 'Skip single-use plastic bags and bring your own reusable bag when you shop.',
                            'completed' => false,
                            'link' => 'embarckStation',
                            'station_id' => 1,
                            'image' => 'files/main/task2.webp'
                        ],
                        [
                            'id' => 3,
                            'title' => 'Task 3: Skip Single-use Straw & Bottle or Cup',
                            'description' => 'Make a conscious choice to avoid plastic straws and bottles – opt for reusable alternatives.',
                            'completed' => false,
                            'link' => 'embarckStation',
                            'station_id' => 2,
                            'image' => 'files/main/task3.webp'
                        ],
                        [
                            'id' => 4,
                            'title' => 'Task 4: Clean Up Your Community',
                            'description' => 'Join or organize a local beach or park cleanup to remove plastic waste.',
                            'completed' => false,
                            'link' => 'embarckStation',
                            'station_id' => 3,
                            'image' => 'files/main/task4.webp'
                        ],
                        [
                            'id' => 5,
                            'title' => 'Task 5: Spread Awareness',
                            'description' => 'Share your plastic-free journey on social media to inspire others.',
                            'completed' => false,
                            'link' => 'embarckStation',
                            'station_id' => 4,
                            'image' => 'files/main/task5.webp'
                        ]
                    ];
                @endphp

                @foreach($tasks as $task)
                  <div class="custom-task-item pb-2 mb-3 border-bottom">
                    <a href="{{ $task['completed'] ? '#' : (isset($task['link']) ? route($task['link'], ['station' => $task['station_id']]) : '#') }}"
                       class="d-flex align-items-start guest-btn p-3 {{ $task['completed'] ? 'completed' : '' }}">
                        <!-- Image Placeholder -->
                        <div class="task-image-placeholder me-2">
                            @if(isset($task['image']) && file_exists(public_path($task['image'])))
                                <img src="{{ asset($task['image']) }}" alt="Task Image" style="width:50px; height:50px; object-fit:cover;">
                            @else
                                <div style="width:50px; height:50px; background-color: #e0f2ff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; color:#99cfff; text-align:center;">
                                    Image
                                </div>
                            @endif
                        </div>

                        <!-- Main Content Area (with dotted border) -->
                        <div class="task-content-area flex-grow-1 py-1 px-2 me-2">
                            <h6 class="task-title fw-bold mb-1">{{ $task['title'] }} <i class="fa-solid fa-circle-check ml-1"></i></h6>
                            <p class="task-description text-muted small mb-0 sub-heading-text-small"
                                style="line-height: 1.3;">
                                {{ $task['description'] }}
                            </p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-auto px-4 d-flex justify-content-center">
            <a href="{{ route('guestAndWin') }}" class="button button-white w-50">Back</a>
        </div>
        <div class="footer-container p-4">
            @include('components.footer')
        </div>
    </div>
</x-app-layout>
