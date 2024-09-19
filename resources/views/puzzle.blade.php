<x-app-layout>
    <style>
        .puzzle-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(3, 1fr);
            width: 215px;
            height: 215px;
            justify-content: center;
            align-items: center;
            background: #fff;
            padding: 20px;
            border: 2px solid #8bc28c;
            border-radius: 20px;
            margin: 0 auto;
            margin-top: 40px;
        }

        .puzzle-piece {
            height: 100%;
            margin-bottom: 2px;
        }

        .piece-1 {
            border-bottom: 2px solid #C7C7C7;
            border-right: 2px solid #C7C7C7;
        }

        .piece-2 {
            border-bottom: 2px solid #C7C7C7;
        }

        .piece-3 {
            border-right: 2px solid #C7C7C7;
        }

        .piece-4 {}

        .piece-5 {
            border-top: 2px solid #C7C7C7;
            border-right: 2px solid #C7C7C7;
        }

        .piece-6 {
            border-top: 2px solid #C7C7C7;
        }

        .puzzle-img {
            width: 80px;
        }

        .badge-container-bottom {
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 1fr);
            display: grid;
        }

        .badge-piece {
            width: 100%;
            height: 92px;
        }

        .badge-text {
            font-size: 9px;
            color: #303030;
            text-align: center;
            margin-top: 5px;
            font-weight: 700;
        }

        .badge-piece img {
            width: 58px;
            height: auto;
        }

        .gift-icon {
            width: 38px;
        }

        .old {
            -webkit-filter: grayscale(100%);
            /* Safari 6.0 - 9.0 */
            filter: grayscale(100%);
        }

        .icon-bg {
            width: 150px;
            height: auto;
            margin-bottom: 25px;
        }

        .iconNew {
            width: 60px;
        }
    </style>
    <div class="modal fade" id="scanCompleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center content">
                        <div class="image-check">
                            <div class="text-content">
                                <img id="badge" class="icon-bg" src="" alt="Lock Image" />
                                <p class="name"></p>
                            </div>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid start home dashboard">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col-12 d-flex justify-content-center">
                    @include('components.branding')
                </div>

                <div class="mt-3 text-center col-12 text-content">
                    <div class="puzzle-container">
                        @foreach ($puzzleRequired as $item)
                            <div class="puzzle-piece piece-{{ $item->station_id }}">
                                <img class="puzzle-img
                                    puzzle-img {{ $item->is_gotten == 1 ? '' : 'd-none' }}"
                                    src="{{ asset('images/puzzle/' . $item->station_id . '.png') }}" alt="" />
                            </div>
                            @endforeach @foreach ($puzzleNotRequired as $item)
                                <div class="puzzle-piece piece-{{ $loop->iteration + 4 }}">
                                    <img class=" puzzle-img {{ $item->is_gotten == 1 ? '' : 'd-none' }}"
                                        src="{{ asset('images/puzzle/' . $loop->iteration + 4 . '.png') }}"
                                        alt="" />
                                </div>
                            @endforeach
                    </div>
                    <p class="mt-3 station-progress-heading">Little Nurse</p>
                    <div class="badge-container-bottom">
                        @foreach ($nurse as $item)
                            @if ($item->station_id != 9)
                                <div class="badge-piece">
                                    <img class="{{ $item->is_gotten == 1 ? '' : 'old' }}"
                                        src="{{ asset('images/badge' . $item->station_id . '.png') }}"
                                        @if ($item->is_gotten == 1) onclick="openModal({{ $item->station_id }}, '{{ $item->station_name }}')" @endif />

                                    <p class="badge-text">
                                        {{ preg_replace('/\s*\(.*?\)\s*/', '', $item->station_name) }}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="text-center" style="text-align: center">
                        <!-- Ensures content inside is centered -->
                        <a href="{{ route('dashboard') }}" class="button" style="display: inline-block">
                            <!-- Keeps the button inline -->
                            BACK
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Ensure Bootstrap JS is included -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>

    <script>
        function openModal(id, name) {
            const dynamicImage = `{{ asset('images/badge') }}${id}.png`;
            $('#badge').attr('src', dynamicImage);
            $('.name').html(name);
            $(scanCompleteModal).modal("show");

        }
    </script>
</x-app-layout>
