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
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.18),
                        0 10px 20px rgba(0,0,0,0.08),
                        0 2px 4px rgba(0,0,0,0.05),
                        inset 0 1px 0 rgba(255,255,255,0.7);
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
            <div class="row w-100">
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
                <button type="submit" class="custom-btn custom-btn-primary">
                    DONE
                </button>
            </form>

        </div>

    </div>
</x-app-layout>