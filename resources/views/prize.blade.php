<x-app-layout>
    <style>

        img.stamping-imagex {
        height: 86px;
        width: 80vw;
        object-fit: contain;
        display: block;
        margin: 0 auto;
        filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.08));
    }
        .flex-page {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* TOP */
        .flex-top {
            flex: 0 0 auto;
        }

        /* CENTER */
        .flex-center {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* BOTTOM */
        .flex-bottom {
            flex: 0 0 auto;
        }

        #touchBox {
            padding: 10%;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            touch-action: none;
            position: relative;
        }

        #touchBox img.stamping-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
            user-select: none;
        }

        .touchBox-container {
            background: #ffffff !important;
            border-radius: 28px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            padding: 24px 16px;
        }

        .booth-description {
            color: #F00B0B !important;
            font-weight: 700;
        }
    </style>

    <div class="map-page main-content stamping-page flex-page" data-id="{{ request()->segment(2) }}">
        <div class="overlay station-{{ request()->segment(2) }}"></div>

        {{-- TOP --}}
        <div class="flex-top animate-entry">
            @include('components.branding')
        </div>

        {{-- CENTER --}}
        <div class="animate-entry delay-2 my-5 py-5">
            <div class="w-100">
                <div class="touchBox-container col-11 m-auto d-flex justify-content-center align-items-center p-0">

                    <div id="touchBox" class="d-block text-center">

                        <h2 class="mb-3 booth-description">You Win</h2>

                        <img class="stamping-imagex"
                            src="{{ asset('images/gifts/GF' . $prize_id . '.webp') }}"
                            alt="Stamp Image"
                            data-stamp-id="{{ $prize_id }}">

                        <h4 class="mt-3 booth-description">
                            {{ $prize->name ?? 'Lucky Draw Booth' }}
                        </h4>

                    </div>

                </div>
            </div>
        </div>

        {{-- BOTTOM --}}
        <div class="flex-bottom text-center mb-3 animate-entry delay-2">

            <form method="POST" action="{{ route('prize.done') }}">
                @csrf
                <button type="submit" class="custom-btn custom-btn-secondary" style="font-weight: 800; color: #F00B0B; background: #ffffff;">
                    DONE
                </button>
            </form>

        </div>

    </div>
</x-app-layout>