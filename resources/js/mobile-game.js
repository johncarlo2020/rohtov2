// Pusher configuration and setup
let pusher = null;
let channel = null;
let reconnectAttempts = 0;
const maxReconnectAttempts = 5;
const reconnectDelay = 2000; // 2 seconds
const mobileTapHere = document.querySelector(".mobile-tap-here");

// Initialize Pusher connection
function initializePusher() {
    try {
        const pusherConfig = window.PUSHER_CONFIG;
        if (!pusherConfig) {
            console.warn("Pusher config not found in mobile game");
            return;
        }

        console.log("Mobile game: Initializing Pusher with config:", {
            key: pusherConfig.key,
            cluster: pusherConfig.cluster,
        });

        pusher = new Pusher(pusherConfig.key, {
            cluster: pusherConfig.cluster,
            encrypted: true,
            enabledTransports: ["ws", "wss"], // Prioritize WebSocket
            disabledTransports: [], // Allow all transports as fallback
            activityTimeout: 120000, // 2 minutes
            pongTimeout: 30000, // 30 seconds
            unavailableTimeout: 10000, // 10 seconds
        });

        // Log connection events with more detail
        pusher.connection.bind("connected", function () {
            console.log(
                "Mobile game: Pusher connection established successfully!",
            );
            console.log("Connection state:", pusher.connection.state);
            console.log("Socket ID:", pusher.connection.socket_id);
            reconnectAttempts = 0; // Reset on successful connection
        });

        pusher.connection.bind("connecting", function () {
            console.log("Mobile game: Pusher connecting...");
        });

        pusher.connection.bind("disconnected", function () {
            console.warn("Mobile game: Pusher connection disconnected");
            console.log("Reconnect attempts:", reconnectAttempts);
            handleReconnection();
        });

        pusher.connection.bind("failed", function () {
            console.error("Mobile game: Pusher connection failed");
            handleReconnection();
        });

        pusher.connection.bind("error", function (error) {
            console.error("Mobile game: Pusher connection error:", error);
        });

        pusher.connection.bind("state_change", function (states) {
            console.log(
                "Mobile game: Pusher state changed from",
                states.previous,
                "to",
                states.current,
            );
        });

        // Subscribe to live feed channel
        channel = pusher.subscribe("live-feed-channel");

        // Log channel events
        channel.bind("pusher:subscription_succeeded", function () {
            console.log(
                "Mobile game: Successfully subscribed to live-feed-channel",
            );
        });

        channel.bind("pusher:subscription_error", function (error) {
            console.error("Mobile game: Channel subscription error:", error);
        });

        // Handle game events
        channel.bind("live-feed-event", (data) => {
            console.log("Mobile game received event:", data);
            handleGameEvent(data);
        });

        console.log("Mobile game: Pusher initialized successfully");
    } catch (error) {
        console.error("Mobile game: Error initializing Pusher:", error);
        handleReconnection();
    }
}

// Handle reconnection attempts
function handleReconnection() {
    if (reconnectAttempts < maxReconnectAttempts) {
        reconnectAttempts++;
        const delay = reconnectDelay * Math.pow(2, reconnectAttempts - 1); // Exponential backoff

        console.log(
            `Mobile game: Attempting reconnection ${reconnectAttempts}/${maxReconnectAttempts} in ${delay / 1000} seconds...`,
        );

        setTimeout(() => {
            console.log("Mobile game: Reconnecting to Pusher...");

            // Disconnect existing connection if any
            if (pusher) {
                pusher.disconnect();
            }

            // Reinitialize Pusher
            initializePusher();
        }, delay);
    } else {
        console.error(
            `Mobile game: Failed to connect after ${maxReconnectAttempts} attempts. Please refresh the page.`,
        );
        showConnectionError();
    }
}

// Show connection error to user
function showConnectionError() {
    // Create error notification
    let errorDiv = document.getElementById("mobile-pusher-error");
    if (!errorDiv) {
        errorDiv = document.createElement("div");
        errorDiv.id = "mobile-pusher-error";
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            background: #dc3545;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 9999;
            font-family: Arial, sans-serif;
            font-size: 14px;
            max-width: 300px;
        `;
        document.body.appendChild(errorDiv);
    }

    errorDiv.innerHTML = `
        <strong>Connection Lost</strong><br>
        Unable to connect to game server. <br>
        <button onclick="retryMobileConnection()" style="
            background: white;
            color: #dc3545;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            margin-top: 8px;
            cursor: pointer;
        ">Retry Connection</button>
    `;
}

// Manual retry function
window.retryMobileConnection = function () {
    console.log("Mobile game: Manual retry requested");
    reconnectAttempts = 0; // Reset counter for manual retry

    // Hide error notification
    const errorDiv = document.getElementById("mobile-pusher-error");
    if (errorDiv) {
        errorDiv.remove();
    }

    // Disconnect and reconnect
    if (pusher) {
        pusher.disconnect();
    }

    initializePusher();
};

// Handle incoming game events
function handleGameEvent(data) {
    switch (data.action) {
        case "start":
            handleGameStart(data.data);
            break;
        case "update":
            handleGameUpdate(data.data);
            break;
        case "finish":
            handleGameFinish(data.data);
            break;
        case "reset":
            handleGameReset(data.data);
            break;
        default:
            console.log("Mobile game: Unknown action:", data.action);
    }
}

const lobby = document.querySelector(".mobile-lobby");
const countDown = document.querySelector(".mobile-countdown");
const tapGame = document.querySelector(".mobile-tap-game");
const finish = document.querySelector(".mobile-finish");
const productEl = document.getElementById("mobileProduct");

let isGameStarted = false;
let gameOver = false;
let currentWeight = 0;

// Game event handlers
function handleGameStart(data) {
    console.log("Mobile game: Game started", data);
    isGameStarted = true;
    gameOver = false;
    currentWeight = 0;

    lobby.classList.add("d-none");
    finish.classList.add("d-none");
    tapGame.classList.add("d-none");
    countDown.classList.remove("d-none");

    startCountdownSequence(() => {
        countDown.classList.add("d-none");
        tapGame.classList.remove("d-none");
    });
}

function handleGameUpdate(data) {
    console.log("Mobile game: Game updated", data);
    // Admin's Increase button should only affect the live-feed display, not this screen
    if (
        data &&
        data.currentWeight !== undefined &&
        data.currentWeight > currentWeight
    ) {
        currentWeight = data.currentWeight;
    }
}

let endmusicPLayed = false;

function handleGameFinish(data) {
    console.log("Mobile game: Game finished", data);
    gameOver = true;

    setTimeout(() => {
        tapGame.classList.add("d-none");
        finish.classList.remove("d-none");
    }, 900);
}

function playFinishMusic() {
    if (endmusicPLayed) return;
    endmusicPLayed = true;
    const audio = new Audio(`${window.ASSET_BASE}/sounds/finish.mp3`);
    audio.play().catch((error) => {
        console.warn("❌ Could not play finish music:", error);
    });
}

function handleGameReset(data) {
    console.log("Mobile game: Game reset", data);
    location.reload();
}

// Countdown sequence (3, 2, 1) - same pattern as the live-feed screen
function startCountdownSequence(onComplete) {
    const nums = ["m-countdown-3", "m-countdown-2", "m-countdown-1"];
    nums.forEach((id) =>
        document.getElementById(id)?.classList.remove("active"),
    );

    nums.forEach((id, index) => {
        setTimeout(() => {
            nums.forEach((otherId) =>
                document.getElementById(otherId)?.classList.remove("active"),
            );
            document.getElementById(id)?.classList.add("active");
        }, index * 1000);
    });

    setTimeout(() => {
        document.getElementById("m-countdown-1")?.classList.remove("active");
        if (typeof onComplete === "function") onComplete();
    }, nums.length * 1000);
}

// Ingredient objects thrown up onto the product, matching the live-feed visual
function triggerFallingObjects(count = 1, isBig = false) {
    if (!tapGame || tapGame.classList.contains("d-none")) return;

    for (let i = 0; i < count; i++) {
        setTimeout(() => createFallingObject(isBig), i * 150);
    }
}

function createFallingObject(isBig) {
    if (!productEl) return;

    const objectFile = isBig
        ? "big2.png"
        : `${Math.floor(Math.random() * 4) + 1}.png`;

    const size = isBig ? 170 : 110;

    const productRect = productEl.getBoundingClientRect();

    const originX = productRect.left + productRect.width / 2;
    const originY = productRect.top + productRect.height * 0.15;

    const targetX = originX + (Math.random() - 0.5) * productRect.width * 0.8;

    const targetY = -size;

    const deltaX = targetX - originX;
    const deltaY = targetY - originY;

    const obj = document.createElement("div");

    Object.assign(obj.style, {
        position: "fixed",
        width: `${size}px`,
        height: `${size}px`,

        // Set position only once
        left: `${originX - size / 2}px`,
        top: `${originY - size / 2}px`,

        backgroundImage: `url('${window.ASSET_BASE}/images/brand/falling_objects/${objectFile}')`,
        backgroundSize: "contain",
        backgroundRepeat: "no-repeat",
        backgroundPosition: "center",

        zIndex: "10",
        pointerEvents: "none",

        opacity: "1",

        // GPU-friendly animation
        transform: "translate3d(0, 0, 0) scale(0.5)",
        transformOrigin: "center",

        willChange: "transform, opacity",

        transition: "transform 800ms ease-in, opacity 250ms ease-in 550ms",
    });

    document.body.appendChild(obj);

    // Let browser commit the initial state without forced reflow
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            obj.style.transform = `translate3d(${deltaX}px, ${deltaY}px, 0) scale(1.1)`;

            obj.style.opacity = "0";
        });
    });

    obj.addEventListener(
        "transitionend",
        () => {
            obj.remove();
        },
        { once: true },
    );
}

// Light effect burst shown centered on the product where the object comes from
function showLightEffect(x, y) {
    if (!productEl) return;

    // Subtle flashlight-style beam rising from the lid, no image asset needed
    // Anchored inside the product's own container (not <body>) and inserted behind it in the
    // DOM so its lower z-index always keeps it under the can artwork instead of covering it
    const container = productEl.parentElement;
    const containerRect = container.getBoundingClientRect();

    const burst = document.createElement("div");
    burst.className = "mobile-light-burst";
    burst.style.left = `${x - containerRect.left}px`;
    burst.style.top = `${y - containerRect.top}px`;
    burst.innerHTML =
        '<span class="mobile-light-beam"></span><span class="mobile-light-core"></span>';
    container.insertBefore(burst, productEl);
    setTimeout(() => burst.remove(), 900);
}

const tapSounds = [
    `${window.ASSET_BASE}/sounds/mobile/tapCollections/1.mp3`,
    `${window.ASSET_BASE}/sounds/mobile/tapCollections/2.mp3`,
    `${window.ASSET_BASE}/sounds/mobile/tapCollections/3.mp3`,
];

// Bounce feedback + burst an object out of the product when the player taps the "TAP ME" screen
if (tapGame) {
    tapGame.addEventListener("click", () => {
        if (gameOver || !productEl) return;
        productEl.style.transition = "transform 150ms ease-out";
        productEl.style.transform = "scale(1.06)";
        setTimeout(() => {
            productEl.style.transform = "scale(1)";
        }, 150);

        //add lightning effect on product when tap is triggered

        // play random tap music from /sounds/mobile/tapCollections

        const randomTapSound = new Audio(
            tapSounds[Math.floor(Math.random() * tapSounds.length)],
        );

        randomTapSound.play().catch((error) => {
            console.warn("❌ Could not play tap sound:", error);
        });

        createFallingObject(false);
    });
}

// Inject the light effect styles/keyframes

// Initialize Pusher when the page loads
document.addEventListener("DOMContentLoaded", function () {
    initializePusher();
    startConnectionMonitoring();
});

// Connection monitoring
function startConnectionMonitoring() {
    setInterval(() => {
        if (pusher) {
            const state = pusher.connection.state;
            if (state === "disconnected" || state === "failed") {
                console.warn(
                    "Mobile game: Connection appears stuck, attempting reconnection...",
                );
                handleReconnection();
            }
        }
    }, 30000);
}

// Cleanup Pusher connection when page unloads
window.addEventListener("beforeunload", function () {
    if (pusher) {
        pusher.disconnect();
        console.log("Mobile game: Pusher disconnected on page unload");
    }
});

// Expose functions for debugging
window.mobileGameDebug = {
    gameState: () => ({ gameOver, isGameStarted, currentWeight }),
    pusherStatus: () => ({
        connected: pusher ? pusher.connection.state : "not initialized",
        socketId: pusher ? pusher.connection.socket_id : null,
        channel: channel ? "subscribed" : "not subscribed",
        reconnectAttempts: reconnectAttempts,
        config: window.PUSHER_CONFIG,
    }),
    reconnect: () => {
        console.log("Manual reconnection triggered");
        handleReconnection();
    },
    testGameStart: () => handleGameStart({ test: true }),
    testFallingObjects: (count = 1, isBig = false) =>
        triggerFallingObjects(count, isBig),
};
