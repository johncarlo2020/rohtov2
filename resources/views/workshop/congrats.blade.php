<x-app-layout>
    <div class="promotion-main with-scroll">
        <div class="justify-content-center px-3">
            <div class="col-12 d-flex justify-content-center mt-5">
                @include('components.branding')
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center my-3">
                <img class="welcome_img w-50" src="{{ asset('images/dutchlady/DL Station Map (5) Registered.webp') }}" alt="" />
            </div>
            <div class="content congrats-workshop text-center">
               <h1 class="heading">DIY Bento Workshop</h1>
               <img class="qr" src="" alt="">
               <p class="name">NIko</p>
               <p class="date">5th July 2025 (Saturday)</p>
               <p class="time"> (2:00 PM - 3:00 PM)</p>
               <p class="count">1 person</p>

            </div>
            <div class="d-flex justify-content-center mt-4">
                   <a href="{{ route('dashboard') }}" class="button-dutch button-dutch-primary text-center">
                        done
                    </a>
            </div>
        </div>
    </div>
</x-app-layout>
