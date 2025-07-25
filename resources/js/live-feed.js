// Get Pusher config from window object (set by Blade template)
const pusherConfig = window.PUSHER_CONFIG;

// Retry configuration
let retryAttempts = 0;
const maxRetries = 5;
const baseDelay = 2000; // 2 seconds
let pusher = null;
let channel = null;

// Initialize Pusher with retry mechanism
function initializePusher() {
    try {
        pusher = new Pusher(pusherConfig.key, {
            cluster: pusherConfig.cluster,
            encrypted: true,
        });

        // Log connection events
        pusher.connection.bind("connected", function () {
            console.log("Pusher connection established successfully!");
            retryAttempts = 0; // Reset retry counter on successful connection
        });

        pusher.connection.bind("disconnected", function () {
            console.log("Pusher connection disconnected");
        });

        pusher.connection.bind("failed", function () {
            console.log("Pusher connection failed");
            handleConnectionFailure();
        });

        pusher.connection.bind("error", function (error) {
            console.error("Pusher connection error:", error);
            handleConnectionFailure();
        });

        // Subscribe to channel
        channel = pusher.subscribe("live-feed-channel");

        // Log when channel subscription is successful
        channel.bind("pusher:subscription_succeeded", function () {
            console.log("Successfully subscribed to live-feed-channel");
        });

        channel.bind("live-feed-event", (data) => {
            console.log("Received live feed event:", data);

            // Track last event time for debugging stuck states
            window.lastEventTime = new Date();
            console.log(
                "Last event received at:",
                window.lastEventTime.toLocaleTimeString()
            );

            // Handle different actions
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
                case "joined":
                   handleUserJoined(data.data);
                    break;
                default:
                    console.log("Unknown action:", data.action);
            }
        });
    } catch (error) {
        console.error("Error initializing Pusher:", error);
        handleConnectionFailure();
    }
}

// Handle connection failures with retry logic
function handleConnectionFailure() {
    if (retryAttempts < maxRetries) {
        retryAttempts++;
        const delay = baseDelay * Math.pow(2, retryAttempts - 1); // Exponential backoff

        console.log(
            `Pusher connection failed. Retrying in ${
                delay / 1000
            } seconds... (Attempt ${retryAttempts}/${maxRetries})`
        );

        setTimeout(() => {
            console.log(
                `Retry attempt ${retryAttempts}: Reconnecting to Pusher...`
            );

            // Disconnect existing connection if any
            if (pusher) {
                pusher.disconnect();
            }

            // Reinitialize Pusher
            initializePusher();
        }, delay);
    } else {
        console.error(
            `Failed to connect to Pusher after ${maxRetries} attempts. Please refresh the page.`
        );

        // Optional: Show user notification
        showConnectionError();
    }
}

// Show connection error to user
function showConnectionError() {
    // Create or update error notification
    let errorDiv = document.getElementById("pusher-connection-error");
    if (!errorDiv) {
        errorDiv = document.createElement("div");
        errorDiv.id = "pusher-connection-error";
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
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
        Unable to connect to live feed. <br>
        <button onclick="retryConnection()" style="
            background: white;
            color: #dc3545;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            margin-top: 8px;
            cursor: pointer;
        ">Retry Now</button>
    `;
}

// Manual retry function
window.retryConnection = function () {
    console.log("Manual retry requested");
    retryAttempts = 0; // Reset counter for manual retry

    // Hide error notification
    const errorDiv = document.getElementById("pusher-connection-error");
    if (errorDiv) {
        errorDiv.remove();
    }

    // Disconnect and reconnect
    if (pusher) {
        pusher.disconnect();
    }

    initializePusher();
};

// Initialize Pusher on page load
initializePusher();

// Periodic health check for stuck states
setInterval(() => {
    // Check if game has been starting for too long
    if (isGameStarting) {
        console.warn(
            "Health check: Game has been in starting state for extended period"
        );

        // Check if countdown is visible but game hasn't progressed
        if (!countDown.classList.contains("d-none")) {
            const now = new Date();
            const timeSinceLastEvent = window.lastEventTime
                ? (now - window.lastEventTime) / 1000
                : 0;

            console.log(
                `Time since last WebSocket event: ${timeSinceLastEvent} seconds`
            );

            // If no events for 10 seconds and still in countdown, something is wrong
            if (timeSinceLastEvent > 10) {
                console.error(
                    "Health check: No WebSocket events for 10+ seconds, game may be stuck"
                );
                console.log(
                    "Consider using window.forceGameStart() or window.resetGameState() to recover"
                );
            }
        }
    }
}, 5000); // Check every 5 seconds

const lobby = document.querySelector(".live-feed-lobby");
const liveGame = document.querySelector(".live-game");
const countDown = document.querySelector(".count-down");

// Scale pin management
let currentWeight = 0;

// Function to move the scale pin
function moveScalePin(weight) {
    const pin = document.getElementById("scale-pin");
    console.log("moveScalePin called with weight:", weight);
    console.log("Pin element found:", pin);
    console.log("GAME_CONFIG available:", window.GAME_CONFIG);

    if (pin && window.GAME_CONFIG) {
        // Match gameTrigger calculation: 180-degree range (-90deg to +90deg)
        const maxWeight = window.GAME_CONFIG.maxWeight;
        const percentage = Math.min((weight / maxWeight) * 100, 100); // Convert to percentage (0-100)
        const angle = (percentage / 100) * 180 - 90; // -90deg to +90deg for 180deg range

        pin.style.transform = `translateX(-50%) rotate(${angle}deg)`;
        console.log(
            `Scale pin moved to ${weight}kg (${percentage}% = ${angle}°)`
        );
        console.log("Applied transform:", pin.style.transform);

        // Check if maximum weight is reached and trigger confetti
        if (weight >= maxWeight) {
            console.log(
                "Maximum weight reached! Triggering confetti celebration!"
            );
            triggerConfettiCelebration();
        }

        // Update current weight
        currentWeight = weight;
    } else {
        console.error("Cannot move pin - element or config missing:", {
            pin: !!pin,
            config: !!window.GAME_CONFIG,
        });
    }
} // Initialize pin when page loads
document.addEventListener("DOMContentLoaded", function () {
    // Set pin to starting position (-90 degrees for 0kg to match gameTrigger)
    const pin = document.getElementById("scale-pin");
    if (pin) {
        pin.style.transform = "translateX(-50%) rotate(-90deg)";
        console.log(
            "Pin initialized to starting position (-90 degrees for 0kg)"
        );
    }
    // Wait for GAME_CONFIG to be available, then initialize
    setTimeout(() => {
        moveScalePin(0); // Ensure pin is at 0kg position
    }, 100);

    // Initialize kibble system with retry capability
    try {
        initializeKibble();
    } catch (error) {
        console.error(
            "Failed to initialize kibble system on page load:",
            error
        );
        handleKibbleInitFailure();
    }
});

// Game state tracking
let gameStartTimeout = null;
let isGameStarting = false;

// Game action handlers
function handleGameStart(data) {
    console.log("Game started:", data);

    // Prevent multiple start triggers
    if (isGameStarting) {
        console.warn(
            "Game start already in progress, ignoring duplicate trigger"
        );
        return;
    }

    isGameStarting = true;
    console.log("Setting game starting state to true");

    // Clear any existing timeout
    if (gameStartTimeout) {
        clearTimeout(gameStartTimeout);
        console.log("Cleared existing game start timeout");
    }

    // Reset pin to 0 when game starts
    moveScalePin(0);

    lobby.classList.add("d-none");
    countDown.classList.remove("d-none");

    console.log("Starting 3-second countdown to live game");

    // Count down 3 seconds then go to live game with fallback
    gameStartTimeout = setTimeout(() => {
        console.log("Countdown complete, transitioning to live game");

        liveGame.classList.remove("d-none");
        countDown.classList.add("d-none");

        // Reset game starting state
        isGameStarting = false;
        gameStartTimeout = null;

        enableIncreaseOnAdmin();
        console.log("Game transition complete, admin controls enabled");
    }, 3000);

    // Safety fallback in case timeout doesn't work
    setTimeout(() => {
        if (isGameStarting) {
            console.warn(
                "Safety fallback: Game still starting after 5 seconds, forcing transition"
            );
            liveGame.classList.remove("d-none");
            countDown.classList.add("d-none");
            isGameStarting = false;
            gameStartTimeout = null;
            enableIncreaseOnAdmin();
        }
    }, 5000);
}

function handleGameUpdate(data) {
    console.log("Game updated:", data);

    // Update scale pin if weight data is provided
    if (data && data.currentWeight !== undefined) {
        console.log(
            `Updating scale pin from ${currentWeight}kg to ${data.currentWeight}kg`
        );

        // Trigger falling kibble when weight increases
        if (data.currentWeight > currentWeight) {
            // Use kibble count from WebSocket data if available, otherwise default to random
            const kibbleCount =
                data.kibbleCount || Math.floor(Math.random() * 4) + 3;
            triggerKibbleFall(kibbleCount);
        }

        moveScalePin(data.currentWeight);

        // Optional: Add visual feedback for weight updates
        const pin = document.getElementById("scale-pin");
        if (pin) {
            // Add a brief glow effect to show the pin moved
            pin.style.filter = "drop-shadow(0 0 10px rgba(255, 255, 0, 0.8))";
            setTimeout(() => {
                pin.style.filter = "none";
            }, 500);
        }
    } else {
        console.warn(
            "Game update received but no currentWeight data found:",
            data
        );
    }
}

// User join queue system
let userJoinQueue = [];
let isProcessingQueue = false;
const MAX_VISIBLE_USERS = 11; // Match your PHP limit

function handleUserJoined(data) {
    console.log("User joined:", data);

    // Add to queue for processing
    userJoinQueue.push(data);
    console.log("Added to queue. Queue length:", userJoinQueue.length);

    // Process queue if not already processing
    if (!isProcessingQueue) {
        processUserQueue();
    }
}

async function processUserQueue() {
    if (userJoinQueue.length === 0 || isProcessingQueue) {
        return;
    }

    isProcessingQueue = true;
    console.log("Starting queue processing...");

    while (userJoinQueue.length > 0) {
        const data = userJoinQueue.shift(); // Get first item from queue
        await addUserToList(data); // Process user addition

        // Small delay between processing to ensure smooth animations
        await new Promise(resolve => setTimeout(resolve, 600));
    }

    isProcessingQueue = false;
    console.log("Queue processing complete");
}

async function addUserToList(data) {
    console.log("Processing user:", data.user?.fname);

    // Update player count
    if (data.totalUsers) {
        const playerCountElement = document.getElementById('player-count');
        if (playerCountElement) {
            console.log("Updating player count to:", data.totalUsers);
            playerCountElement.textContent = data.totalUsers;

            // Trigger count animation
            animatePlayerCount();
        }
    }

    // Add new user to the list
    if (data.user) {
        const userContainer = document.querySelector('.user-container');
        if (!userContainer) {
            console.error("User container not found!");
            return;
        }

        // Check if we need to remove the oldest user first
        const currentUsers = userContainer.querySelectorAll('.user-item');
        if (currentUsers.length >= MAX_VISIBLE_USERS) {
            console.log("Max users reached, removing oldest user");
            await removeOldestUser(userContainer);
        }

        // Create and add new user
        const userItem = createUserElement(data.user);

        // Add floating hearts effect before adding user to DOM
        // We'll use a temporary position for hearts based on container bottom
        const containerRect = userContainer.getBoundingClientRect();
        const heartX = containerRect.left + containerRect.width / 2;
        const heartY = containerRect.bottom;
        createFloatingHeartsAtPosition(heartX, heartY);

        userContainer.appendChild(userItem);

        // Animate new user in
        await animateUserIn(userItem);

        console.log("User added successfully:", data.user.fname);
    }
}

async function removeOldestUser(container) {
    const oldestUser = container.querySelector('.user-item');
    if (!oldestUser) return;

    const userName = oldestUser.querySelector('.username')?.textContent || 'Unknown';
    console.log("Fading out oldest user:", userName);

    // Fade out animation
    oldestUser.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    oldestUser.style.opacity = '0';
    oldestUser.style.transform = 'translateX(-20px)'; // Slide left while fading

    // Wait for animation to complete, then remove
    return new Promise(resolve => {
        setTimeout(() => {
            if (oldestUser.parentNode) {
                oldestUser.remove();
                console.log("Oldest user removed:", userName);
            }
            resolve();
        }, 600); // Match transition duration
    });
}

function createUserElement(user) {
    // Create user item container
    const userItem = document.createElement('div');
    userItem.className = 'user-item';

    // Create avatar image
    const avatarImg = document.createElement('img');
    avatarImg.src = `${window.ASSET_BASE}/images/avatarCats/02_cat0${user.avatar_id}.webp`;
    avatarImg.alt = 'Avatar';
    avatarImg.className = 'avatar';

    // Create username text
    const usernameText = document.createElement('p');
    usernameText.className = 'username-text';
    usernameText.innerHTML = `<span class="username">${user.fname}</span> <span class="joined-text">Joined</span>`;

    // Add elements to user item
    userItem.appendChild(avatarImg);
    userItem.appendChild(usernameText);

    // Set initial state for animation (hidden)
    userItem.style.opacity = '0';
    userItem.style.transform = 'translateY(20px)';

    return userItem;
}

async function animateUserIn(userItem) {
    // Force a reflow to ensure initial styles are applied
    userItem.offsetHeight;

    // Apply transition and animate in
    userItem.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
    userItem.style.opacity = '1';
    userItem.style.transform = 'translateY(0)';

    // Wait for animation to complete
    return new Promise(resolve => {
        setTimeout(() => {
            resolve();
        }, 800); // Match transition duration
    });
}

// Create floating hearts animation when user joins
function createFloatingHearts(userElement) {
    // Get the position of the user element
    const rect = userElement.getBoundingClientRect();
    const centerX = rect.left + rect.width / 2;
    const centerY = rect.top + rect.height / 2;

    createFloatingHeartsAtPosition(centerX, centerY);
}

// Create floating hearts at specific coordinates
function createFloatingHeartsAtPosition(centerX, centerY) {
    // Create multiple hearts for a nice effect
    const heartCount = 5;

    for (let i = 0; i < heartCount; i++) {
        setTimeout(() => {
            createSingleHeart(centerX, centerY);
        }, i * 200); // Stagger hearts every 200ms
    }
}

function createSingleHeart(startX, startY) {
    const heart = document.createElement('div');
    heart.className = 'floating-heart';
    heart.innerHTML = '💖'; // Heart emoji

    // Random slight offset for variety
    const offsetX = (Math.random() - 0.5) * 60; // ±30px horizontal spread
    const offsetY = (Math.random() - 0.5) * 40; // ±20px vertical spread

    heart.style.cssText = `
        position: fixed;
        left: ${startX + offsetX}px;
        top: ${startY + offsetY}px;
        font-size: 24px;
        pointer-events: none;
        z-index: 1000;
        animation: floatUpHeart 3s ease-out forwards;
        transform: translateX(-50%) translateY(-50%);
    `;

    document.body.appendChild(heart);

    // Remove heart after animation completes
    setTimeout(() => {
        if (heart.parentNode) {
            heart.parentNode.removeChild(heart);
        }
    }, 3000);
}

// Animate player count when it updates
function animatePlayerCount() {
    const playerCountContainer = document.querySelector('.player-count');
    if (!playerCountContainer) return;

    // Remove any existing animation class
    playerCountContainer.classList.remove('count-updated');

    // Force a reflow to ensure the class removal takes effect
    playerCountContainer.offsetHeight;

    // Add the animation class
    playerCountContainer.classList.add('count-updated');

    // Remove the class after animation completes
    setTimeout(() => {
        playerCountContainer.classList.remove('count-updated');
    }, 600); // Match animation duration
}

const finishDiv = document.querySelector(".finish");

function handleGameComplete(data) {
    console.log("Game completed:", data);

    // Show completion animation or effects
    const pin = document.getElementById("scale-pin");
    if (pin) {
        // Flash the pin with green glow for completion
        pin.style.filter = "drop-shadow(0 0 15px #28a745)";
        setTimeout(() => {
            pin.style.filter = "none"; // Reset filter
        }, 2000);
    }

    // Optional: Return to lobby after completion
    setTimeout(() => {
        finishDiv.classList.add("d-none");
        liveGame.classList.add("d-none");
        lobby.classList.remove("d-none");
        countDown.classList.add("d-none");
        moveScalePin(0); // Reset pin to 0
    }, 5000);
}

function handleGameFinish(data) {
    console.log("Game finished:", data);

    // Check if there are still kibbles falling
    if (activeKibbles > 0) {
        console.log(`Waiting for ${activeKibbles} kibbles to finish falling before proceeding with game finish`);
        window.pendingGameFinish = data; // Store the data for later

        // Safety timeout - proceed anyway after 5 seconds even if kibbles haven't finished
        setTimeout(() => {
            if (window.pendingGameFinish) {
                console.warn("Safety timeout: Proceeding with game finish despite pending kibbles");
                proceedWithGameFinish(window.pendingGameFinish);
                window.pendingGameFinish = null;
                activeKibbles = 0; // Reset counter to prevent issues
            }
        }, 5000);

        return; // Don't proceed yet
    }

    // No kibbles falling, proceed immediately
    proceedWithGameFinish(data);
}

// Separate function to handle the actual game finish logic
function proceedWithGameFinish(data) {
    console.log("Proceeding with game finish:", data);

    // Trigger confetti celebration
    triggerConfettiCelebration();

    liveGame.classList.add("d-none");
    finishDiv.classList.remove("d-none");

    // Reset pin to 0 position
    moveScalePin(0);
}

function handleGameReset(data) {
    location.reload();
}

// Manual recovery functions for debugging
window.forceGameStart = function () {
    console.log("Manual force game start triggered");
    isGameStarting = false; // Reset state
    if (gameStartTimeout) {
        clearTimeout(gameStartTimeout);
        gameStartTimeout = null;
    }

    // Force transition to live game
    lobby.classList.add("d-none");
    countDown.classList.add("d-none");
    liveGame.classList.remove("d-none");
    enableIncreaseOnAdmin();
    console.log("Forced game start complete");
};

window.resetGameState = function () {
    console.log("Manual game state reset triggered");

    // Reset all states
    isGameStarting = false;
    if (gameStartTimeout) {
        clearTimeout(gameStartTimeout);
        gameStartTimeout = null;
    }

    // Reset to lobby
    liveGame.classList.add("d-none");
    countDown.classList.add("d-none");
    lobby.classList.remove("d-none");
    moveScalePin(0);

    console.log("Game state manually reset to lobby");
};

window.checkGameState = function () {
    const state = {
        isGameStarting: isGameStarting,
        hasGameTimeout: !!gameStartTimeout,
        lobbyVisible: !lobby.classList.contains("d-none"),
        countDownVisible: !countDown.classList.contains("d-none"),
        liveGameVisible: !liveGame.classList.contains("d-none"),
        currentWeight: currentWeight,
    };

    console.log("Current game state:", state);
    return state;
};

// Debug function to test pin movement manually
window.testPinMovement = function (weight) {
    console.log("Testing pin movement with weight:", weight);
    moveScalePin(weight);
};

// Debug function to test different weight values
window.testPinRange = function () {
    const weights = [0, 1, 2, 3, 4];
    let index = 0;

    const testNext = () => {
        if (index < weights.length) {
            console.log(`Testing weight: ${weights[index]}kg`);
            moveScalePin(weights[index]);
            index++;
            setTimeout(testNext, 1000);
        } else {
            console.log("Pin range test completed");
            moveScalePin(0); // Reset to 0
        }
    };

    testNext();
};

// Falling kibble animation with tracking
let activeKibbles = 0; // Track number of kibbles currently falling

function createFallingKibble(x, y) {
    activeKibbles++; // Increment active kibble count
    console.log(`Creating kibble. Active kibbles: ${activeKibbles}`);

    const kibble = document.createElement("div");
    kibble.className = "falling-kibble";
    kibble.style.cssText = `
        position: fixed;
        width: 30px;
        height: 30px;
        background-image: url('${window.ASSET_BASE}/images/brand/kibble.webp');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        left: ${x - 15}px;
        top: ${y - 15}px;
        z-index: 33;
        pointer-events: none;
        animation: fallAndSpin 2s ease-in forwards;
    `;

    document.body.appendChild(kibble);

    // Remove kibble after animation and decrement counter
    setTimeout(() => {
        if (kibble.parentNode) {
            kibble.parentNode.removeChild(kibble);
        }
        activeKibbles--; // Decrement active kibble count
        console.log(`Kibble removed. Active kibbles: ${activeKibbles}`);

        // Check if this was the last kibble and game is waiting to finish
        if (activeKibbles === 0 && window.pendingGameFinish) {
            console.log("All kibbles have fallen, proceeding with game finish");
            proceedWithGameFinish(window.pendingGameFinish);
            window.pendingGameFinish = null;
        }
    }, 2000);
}

// Trigger kibble fall from random positions at top of screen
function triggerKibbleFall(kibbleCount = null) {
    // Only trigger in live game mode
    if (!liveGame.classList.contains("d-none")) {
        const screenWidth = window.innerWidth;
        const screenHeight = window.innerHeight;

        // Target the bag area (center of screen where the dispenser/bag is)
        const bagCenterX = screenWidth / 2;
        const bagCenterY = screenHeight * 0.4; // Approximate bag position
        const bagWidth = screenWidth * 0.1; // Bag area width (10% of screen) - decreased from 20%

        // Use provided kibble count or default to random 3-6
        const finalKibbleCount =
            kibbleCount !== null
                ? kibbleCount
                : Math.floor(Math.random() * 4) + 3;

        console.log(`Dropping ${finalKibbleCount} kibbles into the bag!`);

        for (let i = 0; i < finalKibbleCount; i++) {
            setTimeout(() => {
                // Random X position within bag area
                const randomOffset = (Math.random() - 0.5) * bagWidth;
                const targetX = bagCenterX + randomOffset;

                // Start from top of screen but target the bag
                const startY = -30; // Start above screen

                createFallingKibble(targetX, startY);
            }, i * 150); // Stagger the kibbles every 150ms
        }

        console.log(`${finalKibbleCount} kibbles falling into the bag!`);
    }
}

// Add kibble falling animation CSS and floating hearts CSS
function addKibbleStyles() {
    if (!document.getElementById("kibble-styles")) {
        const style = document.createElement("style");
        style.id = "kibble-styles";
        style.textContent = `
            @keyframes fallAndSpin {
                0% {
                    transform: translateY(0) rotate(0deg);
                    opacity: 1;
                }
                100% {
                    transform: translateY(100vh) rotate(720deg);
                    opacity: 0;
                }
            }

            @keyframes floatUpHeart {
                0% {
                    transform: translateX(-50%) translateY(-50%) scale(0.5);
                    opacity: 0;
                }
                20% {
                    transform: translateX(-50%) translateY(-50%) scale(1.2);
                    opacity: 1;
                }
                100% {
                    transform: translateX(-50%) translateY(-250px) scale(0.8);
                    opacity: 0;
                }
            }

            .falling-kibble {
                transition: none;
            }

            .floating-heart {
                transition: none;
                user-select: none;
                animation-timing-function: ease-out;
            }
        `;
        document.head.appendChild(style);
    }
}

// Kibble initialization retry configuration
let kibbleRetryAttempts = 0;
const maxKibbleRetries = 3;
const kibbleRetryDelay = 1000; // 1 second

// Initialize animation system with retry mechanism
function initializeKibble() {
    try {
        addKibbleStyles();

        // Verify that the styles were added successfully
        const stylesElement = document.getElementById("kibble-styles");
        if (!stylesElement) {
            throw new Error("Failed to add animation styles to DOM");
        }

        console.log(
            "Animation system initialized successfully - kibble and hearts will animate properly"
        );
        kibbleRetryAttempts = 0; // Reset counter on success
    } catch (error) {
        console.error("Error initializing kibble system:", error);
        handleKibbleInitFailure();
    }
}

// Handle kibble initialization failures with retry logic
function handleKibbleInitFailure() {
    if (kibbleRetryAttempts < maxKibbleRetries) {
        kibbleRetryAttempts++;
        const delay = kibbleRetryDelay * kibbleRetryAttempts; // Linear backoff

        console.log(
            `Kibble initialization failed. Retrying in ${
                delay / 1000
            } seconds... (Attempt ${kibbleRetryAttempts}/${maxKibbleRetries})`
        );

        setTimeout(() => {
            console.log(
                `Kibble retry attempt ${kibbleRetryAttempts}: Reinitializing kibble system...`
            );
            initializeKibble();
        }, delay);
    } else {
        console.error(
            `Failed to initialize kibble system after ${maxKibbleRetries} attempts. Kibble animations may not work properly.`
        );

        // Show user notification for kibble initialization failure
        showKibbleError();
    }
}

// Show kibble initialization error to user
function showKibbleError() {
    // Create error notification for kibble failure
    let errorDiv = document.getElementById("kibble-init-error");
    if (!errorDiv) {
        errorDiv = document.createElement("div");
        errorDiv.id = "kibble-init-error";
        errorDiv.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            background: #ffc107;
            color: #212529;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 9998;
            font-family: Arial, sans-serif;
            font-size: 14px;
            max-width: 300px;
        `;
        document.body.appendChild(errorDiv);
    }

    errorDiv.innerHTML = `
        <strong>Animation Issue</strong><br>
        Kibble animations may not work properly. <br>
        <button onclick="retryKibbleInit()" style="
            background: #212529;
            color: #ffc107;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            margin-top: 8px;
            cursor: pointer;
        ">Retry Animation</button>
    `;
}

// Manual kibble retry function
window.retryKibbleInit = function () {
    console.log("Manual kibble initialization retry requested");
    kibbleRetryAttempts = 0; // Reset counter for manual retry

    // Hide error notification
    const errorDiv = document.getElementById("kibble-init-error");
    if (errorDiv) {
        errorDiv.remove();
    }

    initializeKibble();
};

// Confetti celebration function
function triggerConfettiCelebration() {
    // Check if confetti library is available
    if (typeof confetti === "undefined") {
        console.warn(
            "Confetti library not loaded. Cannot trigger celebration."
        );
        return;
    }

    console.log(
        "🎉 Starting confetti celebration for reaching maximum weight!"
    );

    // Main confetti burst from center
    confetti({
        particleCount: 600,
        spread: 70,
        origin: { y: 0.6 },
    });

    // Side bursts for more dramatic effect
    setTimeout(() => {
        confetti({
            particleCount: 100,
            angle: 60,
            spread: 55,
            origin: { x: 0 },
        });
    }, 200);

    setTimeout(() => {
        confetti({
            particleCount: 50,
            angle: 120,
            spread: 55,
            origin: { x: 1 },
        });
    }, 400);

    // Golden confetti rain
    setTimeout(() => {
        confetti({
            particleCount: 200,
            spread: 100,
            origin: { y: 0.2 },
            colors: ["#FFD700", "#FFA500", "#FF6347", "#32CD32", "#1E90FF"],
        });
    }, 600);

    // Final burst with stars
    setTimeout(() => {
        confetti({
            particleCount: 150,
            spread: 360,
            ticks: 100,
            gravity: 0.6,
            decay: 0.96,
            startVelocity: 20,
            shapes: ["star"],
            colors: [
                "#FFE400",
                "#FFBD00",
                "#E89611",
                "#E89611",
                "#FFCA6C",
                "#FDFFB8",
            ],
        });
    }, 800);

    console.log("🎊 Confetti celebration complete!");
}

function enableIncreaseOnAdmin() {
    console.log("Enabling increase on admin");
    // Emit event to enable increase
    $.ajax({
        url: window.ROUTES.start,
        type: "GET",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            if (data.success) {
                console.log("Increase enabled successfully:", data.message);
            } else {
                console.error("Failed to enable increase:", data.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error enabling increase:", error);
        },
    });
}
