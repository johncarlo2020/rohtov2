const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true,
});

const channel = pusher.subscribe("live-feed-channel");

channel.bind("live-feed-event", (data) => {
    console.log("Received live feed event:", data);

    // Handle different actions
    switch(data.action) {
        case 'start':
            handleGameStart(data.data);
            break;
        case 'update':
            handleGameUpdate(data.data);
            break;
        case 'complete':
            handleGameComplete(data.data);
            break;
        case 'reset':
            handleGameReset(data.data);
            break;
        default:
            console.log('Unknown action:', data.action);
    }
});

// Game action handlers
function handleGameStart(data) {
    console.log('Game started:', data);
    // Add your game start logic here
}

function handleGameUpdate(data) {
    console.log('Game updated:', data);
    // Add your game update logic here
}

function handleGameComplete(data) {
    console.log('Game completed:', data);
    // Add your game completion logic here
}

function handleGameReset(data) {
    console.log('Game reset:', data);
    // Add your game reset logic here
}
