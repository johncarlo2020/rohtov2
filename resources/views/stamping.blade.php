<x-app-layout>
    <style>
        #touchBox {
        width: 40svh;
        height: 40svh;
        padding:10%;
        display: flex;
        align-items: center;
        justify-content: center;
        user-select: none;
        touch-action: none; /* Important for multi-touch */
        position: relative;
    }

    #touchBox img.stamping-image {
        width: 100%;
        height: 100%;
        object-fit: contain; /* or cover if you want it to fill */
        pointer-events: none; /* ← prevents blocking touch events */
        user-select: none;
    }

        .touchBox-container 
        {
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.18), 0 10px 20px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.05), inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }
    </style>

    <div class="py-4 map-page main-content stamping-page" data-id="{{ request()->segment(2) }}">
        <div class="overlay station-{{ request()->segment(2) }}"></div>
        <div class="animate-entry">
            @include('components.branding')
        </div>
        <!-- login Modal -->
        <!-- Welcome Modal -->
        <div class="modal fade transparent-modal" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center position-relative">

                    <!-- Close Button (top-right) -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                    <!-- Image -->
                    <img src="{{ asset('images/dutchlady/dutchLadyWelcomeModal.webp') }}" alt="Welcome"
                        class="img-fluid">

                </div>
            </div>
        </div>
        <div class=" success-text text-center mt-4 d-none">
            {{-- <h2 class="sub-heading-text animate-entry">Contratulations<br>
            Stamp Collected!</h2> --}}
        </div>
        <div class="station-selection-container mb-2 animate-entry delay-2">
            @if(request()->segment(2) == 1)
                <h2 class="text-center mb-3 mt-5 booth-description">Win the game and get a stamp <br> to unlock a prize</h2>
            @elseif(request()->segment(2) == 2)
                <h2 class="text-center mb-3 mt-5 booth-description">Leave your best wishes <br> in our video! </h2>  
            @else
                <h2 class="text-center mb-3 mt-5 booth-description">Lucky Draw Booth</h2>  
            @endif
            <!-- Center image (middle area) -->
            <div class="row">
                <div class="touchBox-container col-11 m-auto d-flex justify-content-center align-items-center p-0 animate-entry">
                    <div id="touchBox">
                        <img class="stamping-image "
                            src="{{ asset('images/station/ST' . request()->segment(2) . '.webp') }}"
                            alt="Stamp Image"
                            data-stamp-id="{{ request()->segment(2) }}">
                    </div>
                </div>
            </div>
            <p class="text-center mt-4 mb-5">Please redeem the prize from our crew</p>
            <div id="countDisplay" class="text-center mt-3 d-none">
                Touches inside count: <span id="countNum">0</span>
            </div>


            <!-- Bottom CTA -->
            <div class="row">
                <div class="col-12 text-center">
                    <div class="d-block">
                        <div class="col mb-3 animate-entry delay-2">
                            <button type="button" class="custom-btn custom-btn-primary d-none nextBtn">
                                NEXT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        


        document.addEventListener("DOMContentLoaded", () => {
            const box = document.getElementById("touchBox");
            const countDisplay = document.getElementById("countNum");
            const stampingPage = document.querySelector(".stamping-page");
            const stationid = stampingPage ? parseInt(stampingPage.dataset.id) : null;
            const activeInside = new Map();
            let hasStamped = false;
            const user = @json($user);
            isStamped();  

            function isStamped()
            {
                if(user)
                {
                    hasStamped = true;
                    stampingPage.classList.add("active");
                    const text = document.querySelector(".booth-description");
                        if(stationid == 1)
                        {
                            text.innerHTML = "Conratulations!<br>You've won the game.";
                        }
                        else if(stationid == 2)
                        {   
                            text.innerHTML = "The video is fantastic, well<br>done!";
                        }
                        else 
                        {
                            text.innerHTML = "Congratulations";
                        }
                        
                        // text.classList.remove("d-none");
                            const button = document.querySelector(".nextBtn");
                            if (button) {
                                button.classList.remove("d-none");
                                button.style.pointerEvents = "auto";
                                console.log('test');
                            }
                }
            }

            function isInside(event, element) {
                const rect = element.getBoundingClientRect();
                return (
                    event.clientX >= rect.left &&
                    event.clientX <= rect.right &&
                    event.clientY >= rect.top &&
                    event.clientY <= rect.bottom
                );
            }

            function updateDisplay() {
                countDisplay.textContent = activeInside.size;
                // Toggle grayscale removal
                // Only trigger once
                const requiredCount = 1;
                console.log(requiredCount);
                console.log('activeInside.size:', activeInside.size, 'requiredCount:', requiredCount, 'hasStamped:', hasStamped);

                    if (!hasStamped && activeInside.size === requiredCount ) {
                      console.log('activeInside.size:', activeInside.size, 'requiredCount:', requiredCount, 'hasStamped:', hasStamped);

                        hasStamped = true;
                        stampingPage.classList.add("active");

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const stampImage = document.querySelector('.stamping-image');
                        const stampId = stampingPage ? parseInt(stampingPage.dataset.id) : null;
                        
                            $.ajax({
                                url: '{{ route('process_stamp') }}',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                data: {
                                    station: stampId,
                                },
                                success: function (response) {
                                    $('.nextBtn').removeClass('d-none');

                                    // ✅ attach redirect dynamically
                                    $('.nextBtn').on('click', function () {
                                        window.location.href = response.redirect_url;
                                    });
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error sending QR Code message:', error);
                                }
                            });
                            const text = document.querySelector(".booth-description");

                            if(stationid == 1)
                            {
                                text.innerHTML = "Congratulations!<br>You've won the game.";
                            }
                            else if(stationid == 2)
                            {   
                                text.innerHTML = "The video is fantastic, well<br>done!";
                            }
                            else 
                            {
                                text.innerHTML = "Congratulations";
                            }

                            // text.textContent = "Stamp Collected!";  
                            const button = document.querySelector(".nextBtn");
                            if (button) {
                                button.classList.remove("d-none");
                                button.style.pointerEvents = "auto";
                                console.log('test');
                            }
                    }
                    else 
                    {
                        console.log('invalid touchpoint');
                    }
                    
            }

            document.addEventListener("pointerdown", (event) => {
                if (event.pointerType === "mouse") return;
                if (isInside(event, box)) {
                    activeInside.set(event.pointerId, event);
                }
                updateDisplay();
            });

            document.addEventListener("pointermove", (event) => {
                if (event.pointerType === "mouse") return;

                if (activeInside.has(event.pointerId) && !isInside(event, box)) {
                    activeInside.delete(event.pointerId);
                } else if (!activeInside.has(event.pointerId) && isInside(event, box)) {
                    activeInside.set(event.pointerId, event);
                }

                updateDisplay();
            });

            document.addEventListener("pointerup", (event) => {
                activeInside.delete(event.pointerId);
                updateDisplay();
            });

            document.addEventListener("pointercancel", (event) => {
                activeInside.delete(event.pointerId);
                updateDisplay();
            });
        });
        </script>
</x-app-layout>
