<x-app-layout>
    <div class="container-fluid home completed-screen pt-4">

        <div class="col-12 d-flex justify-content-center">
            @include('components.branding')
        </div>
        <p class="yellow-text mt-4">
            Thank You<br />
            <span>For Voting</span><br />
        </p>

        <div class="product-image">
            <img class="" src="{{ asset('images/congrats.png') }}" alt="" />
        </div>
        <div class="title-container">
            <p class="title small">
                Join our <br /> Journey Now

            </p>
        </div>
        <div class="bottom-text mt-3">
            <br />
            <p class="text-success text-center">Visit our official website</p>
            <br />
            <img src="{{ asset('images/logo-large.png') }}" alt="" />
            <a class="mt-3" href=""> Click Here for more Information </a>
        </div>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>

    <script>
        const confettiCanvas = document.createElement('canvas');
        confettiCanvas.style.position = 'fixed';
        confettiCanvas.style.top = 0;
        confettiCanvas.style.left = 0;
        confettiCanvas.style.width = '100%';
        confettiCanvas.style.height = '100%';
        confettiCanvas.style.pointerEvents = 'none';
        confettiCanvas.style.zIndex = 9999;
        document.body.appendChild(confettiCanvas);

        // Trigger confetti using the new canvas
        const myConfetti = confetti.create(confettiCanvas, {
            resize: true,
            useWorker: true
        });

        myConfetti({
            particleCount: 100,
            spread: 70,
            origin: {
                y: 0.6
            }
        });

        // Optional: Remove the canvas after a short delay
        setTimeout(() => {
            document.body.removeChild(confettiCanvas);
        }, 5000);
    </script>
</x-app-layout>
