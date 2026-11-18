@php
    $logoSrc = config('app.logo', env('APP_LOGO', config('porsche.campaign.porsche_logo', 'images/brand/logo.webp')));
    $targetUrl = Route::has('welcome') ? route('welcome') : url('/');
@endphp
<div>
    <div class="branding">
        <img onclick="window.location.href='{{ $targetUrl }}'" class="logo" src="{{ asset($logoSrc) }}" alt="{{ config('app.name', 'Brand Logo') }}" style="max-height: 50px; width: auto; object-fit: contain; cursor: pointer;" />
    </div>
</div>
