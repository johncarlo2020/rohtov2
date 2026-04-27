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
        
        {{-- head --}}
        <div class="animate-entry">
            @include('components.branding')
        </div>

        {{-- middle --}}
        <div class="mb-2 animate-entry delay-2">
            <!-- Center image (middle area) -->
            <div class="row">
                <div class="touchBox-container col-11 m-auto d-flex justify-content-center align-items-center p-0 animate-entry">
                    <div id="touchBox" class="d-block">
                        <p class="text-center mb-3 booth-description">Lucky Draw Booth</p>  
                        <img class="stamping-imagex"
                            src="{{ asset('images/gifts/GF' . request()->segment(2) . '.webp') }}"
                            alt="Stamp Image"
                            data-stamp-id="{{ request()->segment(2) }}">
                        <p class="text-center mb-3 booth-description">Lucky Draw Booth</p>  
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom CTA -->
            <div class="row">
                <div class="col-12 text-center">
                    <div class="d-block">
                        <div class="col mb-3 animate-entry delay-2">
                            <button type="button" class="custom-btn custom-btn-primary">
                                DONE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</x-app-layout>
