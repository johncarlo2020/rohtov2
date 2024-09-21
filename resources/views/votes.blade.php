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
            border: 2px solid #8BC28C;
            border-radius: 20px;
            margin: 0 auto;
            margin-top: 40px;
        }

        .puzzle-piece {
            height: 100%;
            margin-bottom: 2px;
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
            height: 58px;
        }

        .heading-brands {
            font-size: 24px;
            font-weight: 800;
            color: #303030;
            text-align: center
        }
.brand-details {
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    text-align: center; /* Centers the content including the image */
    overflow: hidden; /* Prevents the image from overflowing outside the container */
}

.brand-details img {
    height: auto;
    max-width: 100%; /* Ensures the image doesn't exceed the container's width */
    display: block;
    margin: 0 auto; /* Center the image horizontally */
}

        .brand-details .brand-count {
            font-size: 36px;
            font-weight: 700;
            color: #0C5A40;
            text-align: center;
            margin-top: 10px;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .brand-details .brand-text {
            font-size: 12px;
            font-weight: 700;
            color: #0C5A40;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 0;
        }
        .brand-btn {
            border-radius: 36px;
        }
    </style>
    <div class="container-fluid start home dashboard">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col-12 d-flex justify-content-center">
                    @include('components.branding')
                </div>
            </div>
            <div class="mt-2 text-center col-12">
                <div class="px-3 row brand-container">
                    @foreach ($brands as $brand)
                        <div class="p-2 col-6">
                            <div class="brand-details">
                                <img src="{{ asset('images/brand' . $brand->brand_id . '.png') }}" alt="">
                                <p class="brand-count">{{ $brand->count }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 brand-btn">
                    <a href="{{ route('congratsVote') }}" class="button">
                        Okay
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
