<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Live Baby GIF Feed</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Pusher -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
</head>

<body class="container py-5">

    <h2 class="mb-4">Live Baby GIF Feed</h2>

    <div id="baby-feed" class="d-flex flex-wrap gap-3"></div>

    <script>
        Pusher.logToConsole = true;

        const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            encrypted: true
        });

        const channel = pusher.subscribe('baby-channel');
        channel.bind('baby-event', function (data) {
            const container = document.getElementById('baby-feed');

            const card = document.createElement('div');
            card.classList.add('card');
            card.style.width = '200px';

            // If data.img is like "/storage/babies/filename.gif"
            const imageUrl = data.img;

            card.innerHTML = `
                <img src="${imageUrl}" class="card-img-top" alt="Baby GIF">
                <div class="card-body">
                    <h5 class="card-title text-center">${data.name}</h5>
                </div>
            `;

            container.prepend(card);
        });
    </script>

</body>

</html>
