<x-app-layout>
    <main class="container py-5 text-center">
        <img src="{{ asset('files/main/logo.webp') }}" alt="Maybank" width="165" class="mb-4" />
        <h1>Card Sales Booth</h1>
        <p>To unlock more rewards, open your journey map, select “Click here to apply!” and scan the booth QR code.</p>
        <a href="{{ route('map') }}" class="btn btn-warning">Open journey map</a>
    </main>
</x-app-layout>
