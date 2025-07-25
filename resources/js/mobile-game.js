import Phaser from "phaser";

// Pusher configuration and setup
let pusher = null;
let channel = null;
let reconnectAttempts = 0;
const maxReconnectAttempts = 5;
const reconnectDelay = 2000; // 2 seconds

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
            cluster: pusherConfig.cluster
        });

        pusher = new Pusher(pusherConfig.key, {
            cluster: pusherConfig.cluster,
            encrypted: true,
            enabledTransports: ['ws', 'wss'], // Prioritize WebSocket
            disabledTransports: [], // Allow all transports as fallback
            activityTimeout: 120000, // 2 minutes
            pongTimeout: 30000, // 30 seconds
            unavailableTimeout: 10000 // 10 seconds
        });

        // Log connection events with more detail
        pusher.connection.bind("connected", function () {
            console.log("Mobile game: Pusher connection established successfully!");
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
            console.log("Mobile game: Pusher state changed from", states.previous, "to", states.current);
        });

        // Subscribe to live feed channel
        channel = pusher.subscribe("live-feed-channel");

        // Log channel events
        channel.bind("pusher:subscription_succeeded", function () {
            console.log("Mobile game: Successfully subscribed to live-feed-channel");
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

        console.log(`Mobile game: Attempting reconnection ${reconnectAttempts}/${maxReconnectAttempts} in ${delay/1000} seconds...`);

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
        console.error(`Mobile game: Failed to connect after ${maxReconnectAttempts} attempts. Please refresh the page.`);
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

// Game event handlers
function handleGameStart(data) {
    console.log("Mobile game: Game started", data);
    console.log("Mobile game: Current bag object:", bag);
    console.log("Mobile game: Current isGameStarted:", isGameStarted);

    // Force bag creation if it doesn't exist (timing issue fix)
    if (!bag) {
        console.warn("Mobile game: Bag not found, attempting to find it in Phaser game");
        if (game && game.scene && game.scene.scenes[0]) {
            const scene = game.scene.scenes[0];
            // Try to find bag in scene children
            const bagInScene = scene.children.list.find(child => child.texture && child.texture.key === 'bagClosed');
            if (bagInScene) {
                bag = bagInScene;
                console.log("Mobile game: Found bag in scene:", bag);
            }
        }
    }

    if (!isGameStarted) {
        isGameStarted = true;
        console.log("Mobile game: Setting isGameStarted to true");

        // Show the bag when game starts (container stays visible for tapping)
        if (bag) {
            console.log("Mobile game: Showing bag - setting alpha to 1");
            bag.setAlpha(1);
            console.log("Mobile game: Bag alpha after setting:", bag.alpha);

            // Force a visual update
            if (bag.scene) {
                bag.scene.sys.displayList.dirty = true;
            }
        } else {
            console.error("Mobile game: Bag object is still null or undefined after search!");

            // Try to create bag manually if it doesn't exist
            if (game && game.scene && game.scene.scenes[0]) {
                const scene = game.scene.scenes[0];
                console.log("Mobile game: Attempting to create bag manually with responsive positioning");
                const centerX = scene.cameras.main.width / 2;
                const bagY = scene.cameras.main.height * 0.6;
                const desiredBagWidth = scene.cameras.main.width * 0.38;
                const bagTexture = scene.textures.get('bagClosed').getSourceImage();
                const bagOriginalWidth = bagTexture.width;
                const scaleX = desiredBagWidth / bagOriginalWidth;
                const scaleY = scaleX;

                bag = scene.add.image(centerX, bagY, 'bagClosed');
                bag.setScale(scaleX, scaleY);
                bag.setDepth(10);
                bag.setAlpha(1);
                console.log("Mobile game: Manually created responsive bag at:", centerX, bagY, "with scaleX:", scaleX);
            }
        }
    } else {
        console.log("Mobile game: Game already started, skipping bag show");
    }

    // Call image switching function if available
    if (typeof window.switchGameImage === 'function') {
        window.switchGameImage('start');
    }
}function handleGameUpdate(data) {
    console.log("Mobile game: Game updated", data);
    // Handle weight updates or other game state changes
}

function handleGameFinish(data) {
    console.log("Mobile game: Game finished", data);
    gameOver = true;
    if (bag) {
        bag.setAlpha(0.5); // Dim the bag when game ends
    }

    // Call image switching function if available
    if (typeof window.switchGameImage === 'function') {
        window.switchGameImage('finish');
    }
}

function handleGameReset(data) {
    console.log("Mobile game: Game reset", data);
    // Reset game state
    gameOver = false;
    isGameStarted = false;

    // Hide bag on reset (container stays visible for tapping)
    if (bag) {
        bag.setAlpha(0);
        bag.setTexture('bagClosed');
    }

    // Call image switching function if available
    if (typeof window.switchGameImage === 'function') {
        window.switchGameImage('reset');
    }
}

// Function to send tap event to server
function sendTapEvent() {
    if (!channel) {
        console.warn("Mobile game: Pusher channel not available for sending events");
        return;
    }

    // Send tap event via AJAX to trigger weight increase
    if (window.$ && window.ROUTES && window.ROUTES.increase) {
        $.ajax({
            url: window.ROUTES.increase,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (data) {
                console.log("Mobile game: Tap event sent successfully", data);
            },
            error: function (xhr, status, error) {
                console.error("Mobile game: Error sending tap event:", error);
            },
        });
    } else {
        console.warn("Mobile game: Required dependencies not available for sending tap events");
    }
}

const config = {
parent: 'mobile-game-container',
  type: Phaser.AUTO,
  width: window.innerWidth,
  height: window.innerHeight,
  backgroundColor: '#ffffff',
  scene: {
    preload,
    create,
    update
  }
};

let game = new Phaser.Game(config);

// Initialize Pusher when the game loads
document.addEventListener("DOMContentLoaded", function () {
    initializePusher();

    // Start connection monitoring
    startConnectionMonitoring();
});

// Connection monitoring
function startConnectionMonitoring() {
    setInterval(() => {
        if (pusher) {
            const state = pusher.connection.state;
            console.log("Mobile game: Connection health check - State:", state);

            // If disconnected for too long, try to reconnect
            if (state === 'disconnected' || state === 'failed') {
                console.warn("Mobile game: Connection appears stuck, attempting reconnection...");
                handleReconnection();
            }
        }
    }, 30000); // Check every 30 seconds
}

let gameOver = false;
let isGameStarted = false;
let pawImages = Array.from({length: 9}, (_, i) => `paw${i + 1}`);
let kibbleImages = ['kibble']; // Single kibble image
let catSounds = Array.from({length: 8}, (_, i) => `catSound${i + 1}`);
let startBtn;
let bag; // Bag object at bottom of screen

// Size configuration variables
let handSize = 1;  // Scale for the cat arm/hand
let kibbleSize = 1;  // Scale for the kibble marks
let kibbleOffset = 0;  // Offset for kibble position (adjust to align under paw)

  function fixImageUrl(img) {
        if (!img) return null;
        if (img.startsWith("http") || img.startsWith("data:")) return img;
        let base =
            typeof window !== "undefined" && window.ASSET_BASE
                ? window.ASSET_BASE
                : "";
        img = img.replace(/^\//, "");
        if (base && img.startsWith(base)) return img;
        return base ? base + "/" + img : img;
}

function preload() {
  // Add loading event listeners for debugging
  this.load.on('filecomplete', (key, type, data) => {
    if (key === 'bagClosed' || key === 'bagOpen') {
      console.log(`Mobile game: Successfully loaded ${type} - ${key}`);
    }
  });

  this.load.on('loaderror', (file) => {
    console.error(`Mobile game: Failed to load file:`, file.key, file.src);
  });

  // Load paw images using a loop
  for (let i = 1; i <= 9; i++) {
    this.load.image(`paw${i}`, fixImageUrl(`images/brand/animal_paws/cat_paw_${i}.png`));
  }

  // Start button
  this.load.image('startBtn', fixImageUrl(`images/brand/animal_paws/cat_kp_lm.gif`));

  // Load kibble image
  this.load.image('kibble', fixImageUrl(`images/brand/mobile_game_object/kibble.webp`));

  // Load bag images with debugging
  const bagClosedUrl = fixImageUrl(`images/brand/mobile_game_object/bag_close.webp`);
  const bagOpenUrl = fixImageUrl(`images/brand/mobile_game_object/bag_open.webp`);

  console.log(`Mobile game: Loading bag images:`);
  console.log(`- bagClosed: ${bagClosedUrl}`);
  console.log(`- bagOpen: ${bagOpenUrl}`);

  this.load.image('bagClosed', bagClosedUrl);
  this.load.image('bagOpen', bagOpenUrl);

  // Load cat sounds using a loop
  for (let i = 1; i <= 8; i++) {
    this.load.audio(`catSound${i}`, fixImageUrl(`images/brand/cat_sounds/cat_sound_${i}.mp3`));
  }
}

function create() {
  const centerX = this.cameras.main.width / 2;
  const centerY = this.cameras.main.height / 2;

  // Game starts hidden - bag will be shown when 'start' event is received
  // But allow tapping even before official start for testing
  isGameStarted = false;

  // Check if bag textures are loaded before creating bag
  console.log("Mobile game: Checking available textures:");
  console.log("- bagClosed texture exists:", this.textures.exists('bagClosed'));
  console.log("- bagOpen texture exists:", this.textures.exists('bagOpen'));

  if (!this.textures.exists('bagClosed')) {
    console.error("Mobile game: bagClosed texture not found! Cannot create bag.");
    console.log("Available textures:", Object.keys(this.textures.list));
    return;
  }

  // Create bag at responsive position for mobile (initially hidden until game starts)
  // Position bag at 60% of screen height to ensure it's visible on all mobile devices
  const bagY = this.cameras.main.height * 0.6;

  // Set bag width to a fixed percentage of screen width (e.g., 38%)
  const desiredBagWidth = this.cameras.main.width * 0.38;
  const bagTexture = this.textures.get('bagClosed').getSourceImage();
  const bagOriginalWidth = bagTexture.width;
  const bagOriginalHeight = bagTexture.height;
  const scaleX = desiredBagWidth / bagOriginalWidth;
  const scaleY = scaleX; // Keep aspect ratio

  bag = this.add.image(centerX, bagY, 'bagClosed');
  bag.setScale(scaleX, scaleY);
  bag.setDepth(10); // Make sure bag appears above other elements
  bag.setAlpha(0); // Initially hidden - will show when game starts

  console.log("Mobile game: Screen dimensions:", this.cameras.main.width, "x", this.cameras.main.height);
  console.log("Mobile game: Bag created at responsive position:", centerX, bagY);
  console.log("Mobile game: Bag scale:", scaleX);
  console.log("Mobile game: Bag object:", bag);
  console.log("Mobile game: Initial bag alpha:", bag.alpha);
  console.log("Mobile game: Bag texture key:", bag.texture.key);
  console.log("Mobile game: Bag visible:", bag.visible);
  console.log("Mobile game: Bag bounds:", bag.getBounds());

  // Main game click - allow tapping even when game hasn't officially started
  this.input.on('pointerdown', (pointer) => {
    if (gameOver) return; // Only prevent when game is over

    // Send tap event to server for live feed integration
    sendTapEvent();

    const pawKey = Phaser.Utils.Array.GetRandom(pawImages);
    const catSoundKey = Phaser.Utils.Array.GetRandom(catSounds);

    // Play random cat sound when paw is triggered
    this.sound.play(catSoundKey);

    // Find the closest edge to the click position
    const distanceToTop = pointer.y;
    const distanceToBottom = this.cameras.main.height - pointer.y;
    const distanceToLeft = pointer.x;
    const distanceToRight = this.cameras.main.width - pointer.x;

    const minDistance = Math.min(distanceToTop, distanceToBottom, distanceToLeft, distanceToRight);

    let edge;
    if (minDistance === distanceToTop) {
      edge = 0; // top
    } else if (minDistance === distanceToBottom) {
      edge = 2; // bottom
    } else if (minDistance === distanceToLeft) {
      edge = 3; // left
    } else {
      edge = 1; // right
    }

    let startX, startY, targetX, targetY;

    switch (edge) {
      case 0: // top
        startX = Phaser.Math.Between(50, this.cameras.main.width - 50);
        startY = -100;
        targetX = pointer.x;
        targetY = pointer.y - 50;
        break;
      case 1: // right
        startX = this.cameras.main.width + 100;
        startY = Phaser.Math.Between(50, this.cameras.main.height - 50);
        targetX = pointer.x + 50;
        targetY = pointer.y;
        break;
      case 2: // bottom
        startX = Phaser.Math.Between(50, this.cameras.main.width - 50);
        startY = this.cameras.main.height + 100;
        targetX = pointer.x;
        targetY = pointer.y + 50;
        break;
      case 3: // left
      default:
        startX = -100;
        startY = Phaser.Math.Between(50, this.cameras.main.height - 50);
        targetX = pointer.x - 50;
        targetY = pointer.y;
        break;
    }

    // Calculate angle for proper paw orientation
    const angle = Phaser.Math.Angle.Between(startX, startY, pointer.x, pointer.y);

    // Create paw arm at random edge position
    const paw = this.add.image(startX, startY, pawKey).setOrigin(0.5, 0);
    paw.setScale(handSize); // Use configurable hand size
    paw.setRotation(angle + Math.PI / 2); // Rotate to point toward click

    // Create shadow for the paw
    const shadow = this.add.image(startX + 5, startY + 5, pawKey).setOrigin(0.5, 0);
    shadow.setScale(handSize); // Use same size as hand for shadow
    shadow.setAlpha(0);
    shadow.setTint(0x000000); // Black shadow
    shadow.setRotation(angle + Math.PI / 2); // Rotate shadow same as paw

    // Calculate extended position - push past the click point to show pressing action
    const extendDistance = 30; // How much further past the click point
    const extendX = pointer.x + Math.cos(angle) * extendDistance;
    const extendY = pointer.y + Math.sin(angle) * extendDistance;

    // Animate the paw moving past the click point for a pressing effect
    this.tweens.add({
      targets: paw,
      x: extendX,
      y: extendY,
      duration: 500,
      ease: 'Power2',
      onComplete: () => {
        // Add a bounce effect when the paw makes contact
        this.tweens.add({
          targets: paw,
          scaleX: handSize * 1.1,
          scaleY: handSize * 0.95,
          duration: 120,
          ease: 'Sine.easeOut',
          yoyo: true
        });
      }
    });

    // Animate shadow moving with the paw
    this.tweens.add({
      targets: shadow,
      x: extendX + 10,
      y: extendY + 10,
      duration: 500,
      ease: 'Power2',
      onComplete: () => {
        // Delay the kibble appearance to simulate pressing action
        this.time.delayedCall(150, () => {
          // Create the kibble directly at the click point (no angle calculations)
          const kibbleX = pointer.x;
          const kibbleY = pointer.y;

          // First create the kibble at the click position (underneath everything)
          const kibbleKey = Phaser.Utils.Array.GetRandom(kibbleImages);
          const kibble = this.add.image(kibbleX, kibbleY, kibbleKey).setOrigin(0.5, 0.5);
          kibble.setScale(kibbleSize); // Use configurable kibble size
          kibble.setAlpha(0);
          // Don't rotate kibble - let it fall naturally without rotation
          // Send kibble to back so it appears under everything
          kibble.setDepth(-1);

          // Show kibble with fade in and start falling immediately
          this.tweens.add({
            targets: kibble,
            alpha: 0.8,
            duration: 150,
            onComplete: () => {
              // Start falling immediately after fade in
              const fallTween = this.tweens.add({
                targets: kibble,
                y: this.cameras.main.height + 100, // Fall off the bottom of the screen
                duration: 1000,
                ease: 'Power2.easeIn', // Simple accelerating fall, no bounce
                onStart: () => {
                  // Check if kibble will fall into bag area and open it immediately
                  const bagBounds = bag.getBounds();
                  if (kibble.x >= bagBounds.left && kibble.x <= bagBounds.right) {
                    // Kibble will fall into bag - open it now
                    bag.setTexture('bagOpen');
                  }
                },
                onUpdate: () => {
                  // Check if kibble has reached the bag level for scoring
                  const bagBounds = bag.getBounds();
                  const kibbleBounds = kibble.getBounds();

                  // Check if kibble overlaps with bag horizontally and is at bag level
                  if (kibble.y >= bag.y - 50 && kibble.y <= bag.y + 50) {
                    if (kibbleBounds.centerX >= bagBounds.left && kibbleBounds.centerX <= bagBounds.right) {
                      // Kibble hit the bag!
                      fallTween.stop(); // Stop the falling animation

                      // Make kibble disappear into bag
                      this.tweens.add({
                        targets: kibble,
                        alpha: 0,
                        scaleX: 0.5,
                        scaleY: 0.5,
                        duration: 200,
                        onComplete: () => kibble.destroy()
                      });

                      // Close the bag after a short delay
                      this.time.delayedCall(500, () => {
                        bag.setTexture('bagClosed');
                      });
                    }
                  }
                },
                onComplete: () => {
                  kibble.destroy();
                  // Close bag if it was opened but no kibble was caught
                  if (bag.texture.key === 'bagOpen') {
                    bag.setTexture('bagClosed');
                  }
                }
              });
            }
          });
        });

        // Show shadow when paw reaches destination (above kibble, below hand)
        shadow.setDepth(0); // Shadow in middle layer
        this.tweens.add({
          targets: shadow,
          alpha: 0.3,
          duration: 100
        });

        // Make sure paw is on top
        paw.setDepth(1);

        // Retract the paw arm back to start position
        this.time.delayedCall(500, () => {
          this.tweens.add({
            targets: paw,
            x: startX,
            y: startY,
            duration: 150,
            ease: 'Power2',
            onComplete: () => paw.destroy()
          });

          // Fade out shadow as paw retracts
          this.tweens.add({
            targets: shadow,
            x: startX + 5,
            y: startY + 5,
            alpha: 0,
            duration: 150,
            onComplete: () => shadow.destroy()
          });
        });
      }
    });
  });
}

function startTimer() {
  this.time.addEvent({
    delay: 1000,
    repeat: timeLeft - 1,
    callback: () => {
      timeLeft--;
      timerText.setText('Time: ' + timeLeft);
      if (timeLeft <= 0) {
        endGame.call(this);
      }
    }
  });
}

function endGame() {
  gameOver = true;
  const centerX = this.cameras.main.width / 2;
  const centerY = this.cameras.main.height / 2;

  this.add.text(centerX, centerY, `Game Over!`, {
    font: '32px Arial',
    fill: '#000',
    align: 'center'
  }).setOrigin(0.5);
}

function update() {}

// Cleanup Pusher connection when page unloads
window.addEventListener('beforeunload', function() {
    if (pusher) {
        pusher.disconnect();
        console.log("Mobile game: Pusher disconnected on page unload");
    }
});

// Expose functions for debugging
window.mobileGameDebug = {
    sendTapEvent,
    gameState: () => ({ gameOver, isGameStarted }),
    pusherStatus: () => ({
        connected: pusher ? pusher.connection.state : 'not initialized',
        socketId: pusher ? pusher.connection.socket_id : null,
        channel: channel ? 'subscribed' : 'not subscribed',
        reconnectAttempts: reconnectAttempts,
        config: window.PUSHER_CONFIG
    }),
    reconnect: () => {
        console.log("Manual reconnection triggered");
        handleReconnection();
    },
    testConnection: () => {
        if (pusher) {
            console.log("Connection state:", pusher.connection.state);
            console.log("Socket ID:", pusher.connection.socket_id);
            console.log("Channel subscribed:", channel ? true : false);

            // Test ping
            pusher.connection.send_event('pusher:ping', {});
        } else {
            console.log("Pusher not initialized");
        }
    },
    // New bag debugging functions
    bagStatus: () => ({
        exists: !!bag,
        alpha: bag ? bag.alpha : null,
        position: bag ? { x: bag.x, y: bag.y } : null,
        texture: bag ? bag.texture.key : null,
        scale: bag ? bag.scaleX : null,
        depth: bag ? bag.depth : null,
        visible: bag ? bag.visible : null,
        gameScene: game && game.scene ? 'exists' : 'missing',
        sceneChildren: game && game.scene && game.scene.scenes[0] ? game.scene.scenes[0].children.list.length : 0,
        texturesLoaded: game && game.scene && game.scene.scenes[0] ? {
            bagClosed: game.scene.scenes[0].textures.exists('bagClosed'),
            bagOpen: game.scene.scenes[0].textures.exists('bagOpen'),
            availableTextures: Object.keys(game.scene.scenes[0].textures.list).filter(key => key.includes('bag'))
        } : 'scene not available'
    }),
    showBag: () => {
        if (bag) {
            console.log("Manually showing bag");
            bag.setAlpha(1);
            console.log("Bag alpha after manual show:", bag.alpha);
        } else {
            console.error("Bag not found for manual show");
        }
    },
    hideBag: () => {
        if (bag) {
            console.log("Manually hiding bag");
            bag.setAlpha(0);
            console.log("Bag alpha after manual hide:", bag.alpha);
        } else {
            console.error("Bag not found for manual hide");
        }
    },
    testGameStart: () => {
        console.log("Manually triggering game start");
        handleGameStart({ test: true });
    },
    forceBagShow: () => {
        console.log("Force bag show - searching all possibilities");

        // Try current bag reference
        if (bag) {
            bag.setAlpha(1);
            console.log("Used existing bag reference, alpha:", bag.alpha);
            return;
        }

        // Search in game scene
        if (game && game.scene && game.scene.scenes[0]) {
            const scene = game.scene.scenes[0];
            console.log("Searching in scene with", scene.children.list.length, "children");

            const bagInScene = scene.children.list.find(child =>
                child.texture && (child.texture.key === 'bagClosed' || child.texture.key === 'bagOpen')
            );

            if (bagInScene) {
                bag = bagInScene;
                bag.setAlpha(1);
                console.log("Found and showed bag in scene, alpha:", bag.alpha);
                return;
            }

            // If not found, create new bag with responsive positioning
            console.log("Creating new bag with responsive positioning");
            const centerX = scene.cameras.main.width / 2;
            const bagY = scene.cameras.main.height * 0.6; // 60% down the screen
            const desiredBagWidth = scene.cameras.main.width * 0.38;
            const bagTexture = scene.textures.get('bagClosed').getSourceImage();
            const bagOriginalWidth = bagTexture.width;
            const scaleX = desiredBagWidth / bagOriginalWidth;
            const scaleY = scaleX;

            bag = scene.add.image(centerX, bagY, 'bagClosed');
            bag.setScale(scaleX, scaleY);
            bag.setDepth(10);
            bag.setAlpha(1);
            console.log("Created new responsive bag at:", centerX, bagY, "with scaleX:", scaleX);
        } else {
            console.error("Game scene not available");
        }
    }
};
