// Get Pusher config from window object (set by Blade template)
const pusherConfig = window.PUSHER_CONFIG;

const pusher = new Pusher(pusherConfig.key, {
    cluster: pusherConfig.cluster,
    encrypted: true,
});

// Log connection events
pusher.connection.bind("connected", function () {
    console.log("Pusher connection established successfully!");
});

pusher.connection.bind("disconnected", function () {
    console.log("Pusher connection disconnected");
});

pusher.connection.bind("failed", function () {
    console.log("Pusher connection failed");
});

const channel = pusher.subscribe("live-feed-channel");

// Log when channel subscription is successful
channel.bind("pusher:subscription_succeeded", function () {
    console.log("Successfully subscribed to live-feed-channel");
});

channel.bind("live-feed-event", (data) => {
    console.log("Received live feed event:", data);

    // Handle different actions
    switch (data.action) {
        case "start":
            handleGameStart(data.data);
            break;
        case "update":
            handleGameUpdate(data.data);
            break;
        case "complete":
            handleGameComplete(data.data);
            break;
        case "reset":
            handleGameReset(data.data);
            break;
        default:
            console.log("Unknown action:", data.action);
    }
});

const lobby = document.querySelector(".live-feed-lobby");
const liveGame = document.querySelector(".live-game");
const countDown = document.querySelector(".count-down");

// Game action handlers
function handleGameStart(data) {
    console.log("Game started:", data);
    // Add your game start logic here
    lobby.classList.add("d-none");
    countDown.classList.remove("d-none");

    //count down 3 seconds then go to live game
    setTimeout(() => {
        liveGame.classList.remove("d-none");
        countDown.classList.add("d-none");
    }, 3000);
}

function handleGameUpdate(data) {
    console.log("Game updated:", data);
    // Add your game update logic here
}

function handleGameComplete(data) {
    console.log("Game completed:", data);
    // Add your game completion logic here
}

function handleGameReset(data) {
    console.log("Game reset:", data);
    // Add your game reset logic here
}
