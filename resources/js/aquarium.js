import Phaser from "phaser";
import PlasmaPost2FX from "./PlasmaPost2FX.js";
import WigglePostFX from "./WigglePostFX.js";
// Disable all console logging
// ['log','warn','error'].forEach(method => console[method] = () => {});  // Temporarily enabled for debugging

const config = {
    parent: "aquarium-container",
    type: Phaser.AUTO,
    width: 1080,
    height: 1920,
    backgroundColor: "rgba(0,0,0,0)",
    render: {
        preserveDrawingBuffer: true,
        transparent: true,
        contextAttributes: {
            alpha: true,
            premultipliedAlpha: false,
        },
    },
    scale: {
        mode: Phaser.Scale.NONE,
    },
    physics: {
        default: "arcade",
        arcade: {
            debug: false, // ✅ This shows hitboxes (white outlines)
             debugBodyColor: 0xff0000, // Red boxes
        },
    },
    scene: { preload, create, update },
    pipeline: {
        PlasmaPost2FX,
        WigglePostFX,
    },
};


const game = new Phaser.Game(config);

// Helper to fix asset/image URLs for compatibility everywhere
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

// Configurable mapping from text length to temp bubble scale
const TEMP_BUBBLE_SIZE_MAP = [
    { len: 1, scale: 0.2 },
    { len: 5, scale: 0.18 },
    { len: 10, scale: 0.22 },
    { len: 20, scale: 0.26 },
    { len: 25, scale: 0.3 },
];

function getTempBubbleScale(textLength) {
    if (!TEMP_BUBBLE_SIZE_MAP.length) return 0.15;
    if (textLength <= TEMP_BUBBLE_SIZE_MAP[0].len)
        return TEMP_BUBBLE_SIZE_MAP[0].scale;
    for (let i = 1; i < TEMP_BUBBLE_SIZE_MAP.length; i++) {
        const prev = TEMP_BUBBLE_SIZE_MAP[i - 1];
        const curr = TEMP_BUBBLE_SIZE_MAP[i];
        if (textLength <= curr.len) {
            const t = (textLength - prev.len) / (curr.len - prev.len);
            return prev.scale + t * (curr.scale - prev.scale);
        }
    }
    return TEMP_BUBBLE_SIZE_MAP[TEMP_BUBBLE_SIZE_MAP.length - 1].scale;
}
// Configurable mapping from text length to bubble scale
const NAME_BUBBLE_SIZE_MAP = [
    { len: 1, scale: 0.1 },
    { len: 5, scale: 0.15 },
    { len: 10, scale: 0.2 },
    { len: 20, scale: 0.25 },
    { len: 25, scale: 0.3 },
];

// Helper to get bubble scale for a given text length (interpolates between points)
function getNameBubbleScale(textLength) {
    if (!NAME_BUBBLE_SIZE_MAP.length) return 0.18;
    if (textLength <= NAME_BUBBLE_SIZE_MAP[0].len)
        return NAME_BUBBLE_SIZE_MAP[0].scale;
    for (let i = 1; i < NAME_BUBBLE_SIZE_MAP.length; i++) {
        const prev = NAME_BUBBLE_SIZE_MAP[i - 1];
        const curr = NAME_BUBBLE_SIZE_MAP[i];
        if (textLength <= curr.len) {
            // Linear interpolation between prev and curr
            const t = (textLength - prev.len) / (curr.len - prev.len);
            return prev.scale + t * (curr.scale - prev.scale);
        }
    }
    // If longer than last, use last scale
    return NAME_BUBBLE_SIZE_MAP[NAME_BUBBLE_SIZE_MAP.length - 1].scale;
}

// Pledge data storage
let pledgeData = [];

// Predefined coral positions based on the aquarium layout
const CORAL_POSITIONS = [
    {
        x: 0.2,
        y: 0.91,
        tiltOffsetX: 18,
        tiltOffsetY: 20,
        size: 0.8,
        z: 3,
        tilt: 0,
    }, // Left side bottom
    {
        x: 0.15,
        y: 0.78,
        tiltOffsetX: 40,
        tiltOffsetY: 10,
        size: 0.6,
        z: 2,
        tilt: 10,
    }, // Left middle rock
    {
        x: 0.1,
        y: 0.63,
        tiltOffsetX: 20,
        tiltOffsetY: 8,
        size: 0.4,
        z: 1,
        tilt: -10,
    }, // Left upper rock
    {
        x: 0.8,
        y: 0.5,
        tiltOffsetX: -20,
        tiltOffsetY: 8,
        size: 0.5,
        z: 1,
        tilt: -15,
    }, // Right side bottom
    {
        x: 0.8,
        y: 0.67,
        tiltOffsetX: -12,
        tiltOffsetY: 4,
        size: 0.5,
        z: 1,
        tilt: -15,
    }, // Right middle rock
    // Removed right upper rock (now permanent coral)
];

let currentCoralPositionIndex = 0;

// Limits for objects on screen

const MAX_CORALS = 5;
const MAX_NAME_BUBBLES = 6;

// Adjustable Variables
const SPAWN_DELAY = 9000; // Much slower coral spawning (was 4000)
const NAME_BUBBLE_SPAWN_DELAY = 11000; // Much slower name bubble spawning (was 5000)
const CORAL_SPEED = 8;
const FLOAT_SPEED = 0.05;
const NAME_BUBBLE_FLOAT_SPEED = 0.02; // Slower floating for name bubbles
const COLLISION_PUSH_FORCE = 1.5;
const SPIN_TIME = 2000;
const SPIN_VELOCITY = 360;
const BUBBLE_OFFSET_X = 45;
const BUBBLE_OFFSET_Y = -25;
const BUBBLE_RADIUS = 30;
const MIN_COLLISION_DISTANCE = 80;
const STRETCH_FACTOR = 0.01;
const STRETCH_DURATION = 100;

// Adjustable scale for initial temp bubbles
const TEMP_BUBBLE_SCALE = 0.15; // Change this value to adjust temp bubble size

// Tilt position offset configuration
const CORAL_TILT_OFFSET_X = 18; // How much tilt affects X position (pixels)
const CORAL_TILT_OFFSET_Y = 6; // How much tilt affects Y position (pixels)

function preload() {
    // Helper to fix asset paths (copied from create)
    // ...existing code...
    // Add error handling for image loading
    this.load.on("loaderror", function (file) {
        console.error("Failed to load:", file.src);
    });

    this.load.image("stick", "images/brand/coral-seperate/stick.webp");

    // Load bubble sprite sheet for entry animation
    this.load.spritesheet("bubble_anim", "images/brand/bubble_animation.webp", {
        frameWidth: 400,
        frameHeight: 400,
    });

    // Load floating name bubble asset
    this.load.image("name_bubble", "images/brand/withMessage.webp");

    // Load tempBubbles images for name bubbles
    for (let i = 1; i <= 1; i++) {
        const tempBubblePath = fixImageUrl("images/tempBubbles/" + i + ".png");
        this.load.image(`tempBubble${i}`, tempBubblePath);
    }

    // Load small bubble overlay for coral effects
    this.load.image("bubble_overlay", "images/brand/bubble_Overlay.webp");

    // Load background image for Phaser canvas
    this.load.image("aquarium_bg", "images/brand/live-feed/bg.webp");
    // Load background music
    this.load.audio('ambient', 'audio/ambient-pad-background-music-for-space-or-underwater-adventure-3323 (mp3cut.net).mp3');

    // Load tempCoral images for initial corals
    for (let i = 1; i <= 5; i++) {
        this.load.image(`tempCoral${i}`, `images/tempCoral/${i}.webp`);
    }

    this.load.image("permanentCoral", "images/tempCoral/permanent.webp");
}

function create() {
    setupCanvas.call(this);
    // Play ambient background music
    const bgMusic = this.sound.add('ambient', { loop: true, volume: 0.5 });
    bgMusic.play();
    // Initialize arrays for tracking objects
    this.corals = [];
    this.nameBubbles = [];


    // Set up physics group for name bubbles (physics bounce/collisions disabled)
    this.nameBubbleGroup = this.physics.add.group({
        // disable world bounds and bounce to avoid glitchy sprite collisions
        collideWorldBounds: false,
        bounceX: 0,
        bounceY: 0
    });
    // collision collider removed to rely on custom repulsion logic
    // this.physics.add.collider(this.nameBubbleGroup, this.nameBubbleGroup);

    // Create bubble animation
    this.anims.create({
        key: "bubble_pop",
        frames: this.anims.generateFrameNumbers("bubble_anim", {
            start: 0,
            end: 8,
        }),
        frameRate: 5,
        repeat: 0,
    });

    this.cameras.main.setPostPipeline(PlasmaPost2FX);

    // Bind validateGroupSizes early so initial corals and name bubbles can call it
    this.validateGroupSizes = validateGroupSizes.bind(this);

    preloadCustomPledgeImages(this, () => {
        // Add permanent coral before other corals
        addPermanentCoral.call(this);
        addCorals.call(this);
        addNameBubbles.call(this);
        addOceanFloorBubbles.call(this);
        addRandomAreaBubbles.call(this);
    });
    // Add a permanent coral at a fixed position that is never removed
    function addPermanentCoral() {
        // Position and config as specified
        const coralPosition = {
            x: 0.82,
            y: 0.94,
            tiltOffsetX: -18,
            tiltOffsetY: 6,
            size: 0.8,
            z: 4,
            tilt: -10,
        };
        const aquariumContainer = document.getElementById("aquarium-container");
        const aquariumWidth = aquariumContainer
            ? aquariumContainer.clientWidth
            : window.innerWidth;
        const aquariumHeight = aquariumContainer
            ? aquariumContainer.clientHeight
            : window.innerHeight;
        const finalX =
            coralPosition.x * aquariumWidth + (coralPosition.tiltOffsetX || 0);
        const finalY =
            coralPosition.y * aquariumHeight + (coralPosition.tiltOffsetY || 0);
        let coral;
        const textureKey = "permanentCoral";
        const baseScale = coralPosition.size || 1.4;
        try {
            coral = this.add
                .sprite(finalX, finalY, textureKey)
                .setScale(baseScale);
            coral.baseScale = baseScale;
            coral.baseAlpha = 1.0;
            coral.setPostPipeline("WigglePostFX");
        } catch (error) {
            const colors = [
                0xff6b6b, 0x4ecdc4, 0x45b7d1, 0xf9ca24, 0xf0932b, 0xeb4d4b,
            ];
            coral = this.add.circle(finalX, finalY, 25, colors[1]);
            coral.baseScale = 1.0;
            coral.baseAlpha = 1.0;
        }
        coral.objectType = "permanentCoral";
        coral.isPlanted = true;
        coral.phase = "planted";
        coral.swayTime = Math.random() * Math.PI * 2;
        coral.bobTime = Math.random() * Math.PI * 2;
        coral.originalX = coral.x;
        coral.originalY = coral.y;
        coral.setDepth(coralPosition.z || 5);
        coral.swaySpeed = Phaser.Math.FloatBetween(0.7, 1.1);
        coral.bobSpeed = Phaser.Math.FloatBetween(0.9, 1.2);
        coral.primarySwayAmp = Phaser.Math.FloatBetween(3.5, 5.5);
        coral.secondarySwayFreq = Phaser.Math.FloatBetween(1.5, 2.1);
        coral.secondarySwayAmp = Phaser.Math.FloatBetween(1.0, 2.2);
        coral.tertiarySwayFreq = Phaser.Math.FloatBetween(0.7, 1.1);
        coral.tertiarySwayAmp = Phaser.Math.FloatBetween(1.2, 2.5);
        coral.primaryBobFreq = Phaser.Math.FloatBetween(1.0, 1.2);
        coral.primaryBobAmp = Phaser.Math.FloatBetween(1.0, 2.0);
        coral.secondaryBobFreq = Phaser.Math.FloatBetween(1.5, 2.2);
        coral.secondaryBobAmp = Phaser.Math.FloatBetween(0.5, 1.2);
        coral.tiltFreq = Phaser.Math.FloatBetween(0.7, 1.1);
        coral.tiltAmp = Phaser.Math.FloatBetween(0.07, 0.12);
        coral.scaleFreq = Phaser.Math.FloatBetween(1.1, 1.5);
        coral.scaleAmp = Phaser.Math.FloatBetween(0.015, 0.035);
        coral.alphaFreq = Phaser.Math.FloatBetween(0.6, 0.9);
        coral.alphaAmp = Phaser.Math.FloatBetween(0.08, 0.13);
        coral.tintFreq = Phaser.Math.FloatBetween(0.4, 0.7);
        coral.tintStrength = Phaser.Math.FloatBetween(0.12, 0.18);
        coral.MIN_X = 80;
        coral.MAX_X = window.innerWidth - 80;
        coral.MIN_Y = 50;
        coral.MAX_Y = window.innerHeight * 0.5;
        coral.tiltOffsetX = coralPosition.tiltOffsetX || 0;
        coral.tiltOffsetY = coralPosition.tiltOffsetY || 0;
        if (typeof coralPosition.tilt === "number" && coral.setRotation) {
            coral.setRotation(Phaser.Math.DegToRad(coralPosition.tilt));
        }
        // Not added to coralGroup or corals array, so never removed
        startCoralBubbles.call(this, coral);
    }

    // Initialize bubble groups
    this.coralBubblesGroup = this.add.group();
    this.oceanFloorBubblesGroup = this.add.group();
    this.randomAreaBubblesGroup = this.add.group();

    Pusher.logToConsole = true;
    const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        encrypted: true,
    });

    // Queues for corals and name bubbles
    this.coralQueue = [];
    this.bubbleQueue = [];
    this.isProcessingCoral = false;
    this.isProcessingBubble = false;

    const channel = pusher.subscribe("baby-channel");
    channel.bind("baby-event", (data) => {
        console.log("Pusher event received:", data);
        // Accept both data.image and data.img for compatibility
        const BASE_PATH = "";
        // ...existing code...

        if (data.type === "coral") {
            this.coralQueue.push(data);
            processCoralQueue.call(this);
        } else if (data.type === "text") {
            this.bubbleQueue.push(data);
            processBubbleQueue.call(this);
        }
    });

    // Coral queue processor
    function processCoralQueue() {
        if (this.isProcessingCoral || this.isAnyEntryActive) return;
        if (!this.coralQueue.length) return;
        this.isProcessingCoral = true;
        const data = this.coralQueue.shift();
        const coralId = data.id || Date.now();
        let textureKey = `coral${data.coralId || 1}`;
        let image = data.image || data.img || null;
        image = fixImageUrl(image);
        if (image) {
            textureKey = `coral_custom_${coralId}`;
        }
        // Push pledgeData BEFORE spawning
        pledgeData.push({
            id: coralId,
            name: data.name || "",
            coralId: data.coralId || 1,
            type: "coral",
            image,
            textureKey,
        });
        const finish = () => {
            this.isProcessingCoral = false;
            setTimeout(() => processCoralQueue.call(this), 100); // Process next coral if any
        };
        if (image) {
            if (image.startsWith("data:")) {
                this.textures.addBase64(textureKey, image);
                spawnSingleCoral.call(this, textureKey, 0.45);
                // Wait for animation to finish before next
                waitForCoralEntryFinish.call(this, finish);
            } else {
                this.load.image(textureKey, image);
                this.load.once("complete", () => {
                    spawnSingleCoral.call(this, textureKey, 0.45);
                    waitForCoralEntryFinish.call(this, finish);
                });
                this.load.start();
            }
        } else {
            spawnSingleCoral.call(this, textureKey, 0.45);
            waitForCoralEntryFinish.call(this, finish);
        }
    }

    // Helper to wait for coral entry animation to finish
    function waitForCoralEntryFinish(callback) {
        // Poll until isAnyEntryActive is false, then call callback
        const check = () => {
            if (!this.isAnyEntryActive) {
                callback();
            } else {
                setTimeout(check, 100);
            }
        };
        check();
    }

    // Bubble queue processor
    function processBubbleQueue() {
        if (this.isProcessingBubble) return;
        if (!this.bubbleQueue.length) return;
        this.isProcessingBubble = true;
        const data = this.bubbleQueue.shift();
        const textId = data.id || Date.now();
        let textureKey = "name_bubble";
        let image = data.image || data.img || null;
        image = fixImageUrl(image);
        if (image) {
            textureKey = `name_bubble_custom_${textId}`;
        }
        pledgeData.push({
            id: textId,
            text: data.name || data.text || "",
            type: "text",
            image,
            textureKey,
        });
        const finish = () => {
            this.isProcessingBubble = false;
            setTimeout(() => processBubbleQueue.call(this), 100); // Process next bubble if any
        };
        if (image) {
            if (image.startsWith("data:")) {
                this.textures.addBase64(textureKey, image);
                spawnSingleNameBubble.call(this, textureKey, 0.55);
                waitForBubbleEntryFinish.call(this, finish);
            } else {
                this.load.image(textureKey, image);
                this.load.once("complete", () => {
                    spawnSingleNameBubble.call(this, textureKey, 0.55);
                    waitForBubbleEntryFinish.call(this, finish);
                });
                this.load.start();
            }
        } else {
            spawnSingleNameBubble.call(this, textureKey, 0.55);
            waitForBubbleEntryFinish.call(this, finish);
        }
    }

    // Helper to wait for bubble entry animation to finish
    function waitForBubbleEntryFinish(callback) {
        // Wait for the most recent name bubble to finish entry (isFloating=true)
        const check = () => {
            if (
                this.nameBubbles.length &&
                this.nameBubbles[this.nameBubbles.length - 1].isFloating
            ) {
                callback();
            } else {
                setTimeout(check, 100);
            }
        };
        check();
    }

    // Resize listener
    window.addEventListener("resize", () => resizeGame.call(this));
}

function getSafeFinalPosition(existingBubbles, minDistance = 100, maxTries = 20) {
    let tries = 0;
    let x, y;
    let safe = false;

    while (!safe && tries < maxTries) {
        x = Phaser.Math.Between(100, window.innerWidth - 100);
        y = Phaser.Math.Between(80, window.innerHeight * 0.4);

        safe = true;

        for (let bubble of existingBubbles) {
            const dx = bubble.x - x;
            const dy = bubble.y - y;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (dist < minDistance) {
                safe = false;
                break;
            }
        }

        tries++;
    }

    return { x, y };
}


// Generic function to remove the oldest item from a group with a fade-out
function removeOldestItem(group, array) {
    console.log(
        `removeOldestItem called. Group size: ${group.getLength()}, array length: ${
            array.length
        }`
    );

    const children = group.getChildren();
    const oldestItem = children.length > 0 ? children[0] : null;
    if (!oldestItem) {
        console.log(`No items in group to remove (getChildren empty)`);
        return false;
    }

    console.log(
        `Removing oldest ${
            oldestItem.objectType
        }. Group size before: ${group.getLength()}`
    );

    // For corals, stop bubble generation
    if (oldestItem.objectType === "coral") {
        oldestItem.isPlanted = false;
        oldestItem.shouldDestroy = true;
        if (oldestItem.bubbleEvents) {
            oldestItem.bubbleEvents.forEach(
                (event) => event && !event.hasDispatched && event.remove()
            );
            oldestItem.bubbleEvents = [];
        }
    }

    // Fade out, then remove from group/array and destroy
    if (oldestItem && oldestItem.alpha !== undefined) {
        this.tweens.add({
            targets: oldestItem,
            alpha: 0,
            duration: 500,
            ease: "Linear",
            onComplete: () => {
                // Remove from group and array after fade
                const removeSuccess = group.remove(oldestItem, true, true);
                console.log(`Group.remove() success: ${removeSuccess}`);
                const arrayIndex = array.indexOf(oldestItem);
                if (arrayIndex > -1) {
                    array.splice(arrayIndex, 1);
                    console.log(`Removed from array at index ${arrayIndex}`);
                } else {
                    console.log(`Item not found in array`);
                }
                if (oldestItem && oldestItem.destroy) {
                    oldestItem.destroy();
                    console.log(
                        `Oldest ${oldestItem.objectType} destroyed (visual cleanup complete)`
                    );
                }
                console.log(
                    `Oldest ${
                        oldestItem.objectType
                    } removed from group after fade. Group size after: ${group.getLength()}`
                );
            },
        });
    } else {
        // No alpha property, remove immediately
        const removeSuccess = group.remove(oldestItem, true, true);
        console.log(`Group.remove() success: ${removeSuccess}`);
        const arrayIndex = array.indexOf(oldestItem);
        if (arrayIndex > -1) {
            array.splice(arrayIndex, 1);
            console.log(`Removed from array at index ${arrayIndex}`);
        } else {
            console.log(`Item not found in array`);
        }
        if (oldestItem && oldestItem.destroy) {
            oldestItem.destroy();
            console.log(
                `Oldest ${oldestItem.objectType} destroyed (visual cleanup complete)`
            );
        }
        console.log(
            `Oldest ${
                oldestItem.objectType
            } removed from group immediately. Group size after: ${group.getLength()}`
        );
    }
    return true;
}

function setupCanvas() {
    // No Phaser background image, keep canvas transparent
    if (this.video) {
        this.video.destroy();
        this.video = null;
    }
}

// Function to fetch pledge data from server
function addCorals() {
    this.coralGroup = this.add.group();
    this.isAnyEntryActive = false; // Master flag to prevent ANY entries while something is animating

    // Create initial 6 corals immediately (planted, no entry animation)
    console.log("Creating initial corals directly planted...");
    for (let i = 0; i < MAX_CORALS; i++) {
        createInitialCoral.call(this, i);
    }
}

// Function to create initial corals directly in planted positions without entry animation
function createInitialCoral(index) {
    // Always render tempCoral, even if pledgeData is empty
    // Use tempCoral images for initial corals (support single or multiple)
    const tempCoralKeys = [];
    // Check how many tempCoral images are loaded (assume at least tempCoral1)
    let i = 1;
    while (this.textures.exists(`tempCoral${i}`)) {
        tempCoralKeys.push(`tempCoral${i}`);
        i++;
    }
    if (tempCoralKeys.length === 0) {
        tempCoralKeys.push("tempCoral1"); // fallback if only one
    }
    // ...rest of function unchanged...
    const coralPosition = CORAL_POSITIONS[index % CORAL_POSITIONS.length];
    const aquariumContainer = document.getElementById("aquarium-container");
    const aquariumWidth = aquariumContainer
        ? aquariumContainer.clientWidth
        : window.innerWidth;
    const aquariumHeight = aquariumContainer
        ? aquariumContainer.clientHeight
        : window.innerHeight;
    const finalX =
        coralPosition.x * aquariumWidth + (coralPosition.tiltOffsetX || 0);
    const finalY =
        coralPosition.y * aquariumHeight + (coralPosition.tiltOffsetY || 0);
    console.log(
        `Creating initial coral ${index + 1} at position ${finalX}, ${finalY}`
    );
    let coral;
    const textureKey = tempCoralKeys[index % tempCoralKeys.length];
    // Use CORAL_POSITIONS size property for tempCoral images
    const baseScale = coralPosition.size || 1.4;
    try {
        coral = this.add.sprite(finalX, finalY, textureKey).setScale(0.1); // Start small for grow animation
        coral.baseScale = baseScale;
        coral.baseAlpha = 1.0;
        coral.setPostPipeline("WigglePostFX");
        // Animate scale up to baseScale
        this.tweens.add({
            targets: coral,
            scale: baseScale,
            duration: 1800,
            ease: "Sine.easeOut",
        });
    } catch (error) {
        console.warn(
            `Coral image ${textureKey} failed to load, using fallback`
        );
        const colors = [
            0xff6b6b, 0x4ecdc4, 0x45b7d1, 0xf9ca24, 0xf0932b, 0xeb4d4b,
        ];
        coral = this.add.circle(
            finalX,
            finalY,
            25,
            colors[index % colors.length]
        );
        coral.baseScale = 1.0;
        coral.baseAlpha = 1.0;
    }
    // REMOVE pledgeData assignment for tempCoral initial corals
    // coral.pledgeData = pledge; // No pledge for tempCoral visuals
    coral.objectType = "coral";
    coral.isPlanted = true;
    coral.phase = "planted";
    coral.swayTime = Math.random() * Math.PI * 2;
    coral.bobTime = Math.random() * Math.PI * 2;
    coral.originalX = coral.x;
    coral.originalY = coral.y;
    // Set depth from CORAL_POSITIONS z property
    coral.setDepth(coralPosition.z || 5);
    // Assign fixed random movement parameters for smooth animation
    coral.swaySpeed = Phaser.Math.FloatBetween(0.7, 1.1);
    coral.bobSpeed = Phaser.Math.FloatBetween(0.9, 1.2);
    coral.primarySwayAmp = Phaser.Math.FloatBetween(3.5, 5.5);
    coral.secondarySwayFreq = Phaser.Math.FloatBetween(1.5, 2.1);
    coral.secondarySwayAmp = Phaser.Math.FloatBetween(1.0, 2.2);
    coral.tertiarySwayFreq = Phaser.Math.FloatBetween(0.7, 1.1);
    coral.tertiarySwayAmp = Phaser.Math.FloatBetween(1.2, 2.5);
    coral.primaryBobFreq = Phaser.Math.FloatBetween(1.0, 1.2);
    coral.primaryBobAmp = Phaser.Math.FloatBetween(1.0, 2.0);
    coral.secondaryBobFreq = Phaser.Math.FloatBetween(1.5, 2.2);
    coral.secondaryBobAmp = Phaser.Math.FloatBetween(0.5, 1.2);
    coral.tiltFreq = Phaser.Math.FloatBetween(0.7, 1.1);
    coral.tiltAmp = Phaser.Math.FloatBetween(0.07, 0.12);
    coral.scaleFreq = Phaser.Math.FloatBetween(1.1, 1.5);
    coral.scaleAmp = Phaser.Math.FloatBetween(0.015, 0.035);
    coral.alphaFreq = Phaser.Math.FloatBetween(0.6, 0.9);
    coral.alphaAmp = Phaser.Math.FloatBetween(0.08, 0.13);
    coral.tintFreq = Phaser.Math.FloatBetween(0.4, 0.7);
    coral.tintStrength = Phaser.Math.FloatBetween(0.12, 0.18);
    // Limits
    coral.MIN_X = 80;
    coral.MAX_X = window.innerWidth - 80;
    coral.MIN_Y = 50;
    coral.MAX_Y = window.innerHeight * 0.5;

    coral.tiltOffsetX = coralPosition.tiltOffsetX || 0;
    coral.tiltOffsetY = coralPosition.tiltOffsetY || 0;

    // Set rotation from CORAL_POSITIONS tilt property (degrees to radians)
    if (typeof coralPosition.tilt === "number" && coral.setRotation) {
        coral.setRotation(Phaser.Math.DegToRad(coralPosition.tilt));
    }

    if (this.coralGroup.getLength() >= MAX_CORALS) {
        if (coral) {
            removeOldestItem.call(this, this.coralGroup, this.corals);
        }
    }
    this.coralGroup.add(coral);
    this.corals.push(coral);
    this.validateGroupSizes();
    startCoralBubbles.call(this, coral);
    console.log(`Initial coral created. Total corals: ${this.corals.length}`);
}

// corals entry animation
function spawnSingleCoral(customTextureKey) {
    if (!pledgeData.length) return;

    // Prevent new coral entry if an entry animation is active
    if (this.isAnyEntryActive) {
        console.warn(
            "Coral entry animation in progress, skipping new coral entry."
        );
        return;
    }
    this.isAnyEntryActive = true;

    const coralPledges = pledgeData.filter((p) => p.type === "coral");
    if (!coralPledges.length) {
        this.isAnyEntryActive = false;
        return;
    }

    const pledge = coralPledges[coralPledges.length - 1];
    const slotIndex = currentCoralPositionIndex % CORAL_POSITIONS.length;
    const coralPosition = CORAL_POSITIONS[slotIndex];
    const { finalX, finalY, spawnX, spawnY } = getFinalPosition(coralPosition);

    const textureKey =
        customTextureKey || pledge.textureKey || `coral${pledge.coralId}`;
    const baseScale = coralPosition.size || 0.25;

    const bubble = createBubble.call(
        this,
        spawnX,
        spawnY,
        coralPosition.z || 5
    );
    const coral = createCoral.call(
        this,
        textureKey,
        spawnX,
        spawnY,
        pledge,
        baseScale
    );

    applyCoralAnimationConfig(coral);

    if (this.coralGroup.getLength() >= MAX_CORALS) {
        removeOldestItem.call(this, this.coralGroup, this.corals);
    }

    this.coralGroup.add(coral);
    this.corals.push(coral);
    this.validateGroupSizes();

    setTimeout(() => {
        if (!coral.shouldDestroy) {
            this.tweens.add({
                targets: coral,
                alpha: 0.9,
                duration: 1200,
                ease: "Linear",
            });
        }
    }, 200);

    const path = createBezierPath(spawnX, spawnY, finalX, finalY);
    // Wrap the onComplete logic to reset isAnyEntryActive
    const self = this;
    function onTweenCompleteWrapper(args) {
        if (typeof args.onComplete === "function") args.onComplete();
        self.isAnyEntryActive = false;
    }
    startCoralTween.call(this, {
        coral,
        bubble,
        path,
        finalX,
        finalY,
        baseScale,
        coralPosition,
        textureKey,
        onTweenComplete: function () {
            self.isAnyEntryActive = false;
        },
    });
}

function getFinalPosition(coralPosition) {
    const container = document.getElementById("aquarium-container");
    const width = container?.clientWidth || window.innerWidth;
    const height = container?.clientHeight || window.innerHeight;

    return {
        finalX: coralPosition.x * width + (coralPosition.tiltOffsetX || 0),
        finalY: coralPosition.y * height + (coralPosition.tiltOffsetY || 0),
        spawnX: -80,
        spawnY: height * 0.5,
    };
}

function createBubble(x, y, coralDepth) {
    const bubbleStartScale = 0.55; // much larger bubble size
    const bubble = this.add
        .sprite(x, y, "bubble_anim", 0)
        .setScale(bubbleStartScale)
        .setAlpha(0)
        .setDepth(999); // keep bubble behind coral

    this.tweens.add({
        targets: bubble,
        alpha: 1,
        duration: 500,
        ease: "Linear",
    });
    return bubble;
}

function createCoral(textureKey, x, y, pledge, scale) {
    let coral;
    try {
        coral = this.add.sprite(x, y, textureKey).setScale(0.28); // even larger start scale
        coral.setPostPipeline("WigglePostFX");
    } catch {
        const colors = [
            0xff6b6b, 0x4ecdc4, 0x45b7d1, 0xf9ca24, 0xf0932b, 0xeb4d4b,
        ];
        coral = this.add.circle(
            x,
            y,
            25,
            colors[pledge.coralId - 1] || 0xff6b6b
        );
    }

    Object.assign(coral, {
        baseScale: scale,
        baseAlpha: 1.0,
        pledgeData: pledge,
        objectType: "coral",
        depth: 5,
        alpha: 0,
        isPlanted: false,
        phase: "entry",
    });

    return coral;
}

function applyCoralAnimationConfig(coral) {
    Object.assign(coral, {
        swaySpeed: 0.9,
        bobSpeed: 1.0,
        primarySwayAmp: 4.5,
        secondarySwayFreq: 1.8,
        secondarySwayAmp: 1.6,
        tertiarySwayFreq: 0.9,
        tertiarySwayAmp: 1.8,
        primaryBobFreq: 1.1,
        primaryBobAmp: 1.5,
        secondaryBobFreq: 1.8,
        secondaryBobAmp: 0.8,
        tiltFreq: 0.9,
        tiltAmp: 0.1,
        scaleFreq: 1.3,
        scaleAmp: 0.025,
        alphaFreq: 0.8,
        alphaAmp: 0.1,
        tintFreq: 0.6,
        tintStrength: 0.15,
        MIN_X: 80,
        MAX_X: window.innerWidth - 80,
        MIN_Y: 50,
        MAX_Y: window.innerHeight * 0.5,
    });
}

function createBezierPath(startX, startY, endX, endY) {
    const controlX = (startX + endX) / 2;
    // Reduce downward curve: controlY is closer to the higher of startY/endY, less offset
    const controlY = Math.max(startY, endY) + Math.abs(endY - startY) * 0.25 + 30;

    return {
        getPoint: (t) => ({
            x:
                (1 - t) * (1 - t) * startX +
                2 * (1 - t) * t * controlX +
                t * t * endX,
            y:
                (1 - t) * (1 - t) * startY +
                2 * (1 - t) * t * controlY +
                t * t * endY,
        }),
    };
}

function startCoralTween({
    coral,
    bubble,
    path,
    finalX,
    finalY,
    baseScale,
    coralPosition,
    textureKey,
    onTweenComplete,
}) {
    // New: Two-stage animation - first to center, then to final location
    let tweenObj1 = { t: 0 };
    let tweenObj2 = { t: 0 };
    const bubbleStartScale = 0.55;
    const bubbleEndScale = 0.7;
    const centerX = window.innerWidth / 2;
    const centerY = window.innerHeight / 2;
    // Fix: Use initial spawn position for path1, not coral.x/y (which change during tween)
    const spawnX = coral.x;
    const spawnY = coral.y;
    // Path 1: from spawn to center
    const path1 = {
        getPoint: (t) => {
            const x = (1 - t) * spawnX + t * centerX;
            const y = (1 - t) * spawnY + t * centerY;
            return { x, y };
        },
    };
    // Path 2: from center to final
    const path2 = createBezierPath(centerX, centerY, finalX, finalY);

    // First tween: to center (even slower and smoother)
    this.tweens.add({
        targets: tweenObj1,
        t: 1,
        duration: 5200,
        ease: "Sine.easeInOut",
        onUpdate: () => {
            const pos = path1.getPoint(tweenObj1.t);
            coral.x = pos.x;
            coral.y = pos.y;
            bubble.x = pos.x;
            bubble.y = pos.y;
            const pulse = Math.sin(tweenObj1.t * Math.PI) * 0.5 + 0.5;
            const bubbleScale =
                bubbleStartScale + (bubbleEndScale - bubbleStartScale) * pulse;
            bubble.setScale(bubbleScale);
        },
        onComplete: () => {
            // Second tween: to final location (slower and smoother)
            this.tweens.add({
                targets: tweenObj2,
                t: 1,
                duration: 3200,
                ease: "Sine.easeInOut",
                onUpdate: () => {
                    const pos = path2.getPoint(tweenObj2.t);
                    coral.x = pos.x;
                    coral.y = pos.y;
                    bubble.x = pos.x;
                    bubble.y = pos.y;
                    const pulse = Math.sin(tweenObj2.t * Math.PI) * 0.5 + 0.5;
                    const bubbleScale =
                        bubbleStartScale +
                        (bubbleEndScale - bubbleStartScale) * pulse;
                    bubble.setScale(bubbleScale);
                },
                onComplete: () => {
                    bubble.play("bubble_pop");
                    bubble.on("animationcomplete", () => {
                        bubble.destroy();
                        coral.x = finalX;
                        coral.y = finalY;
                        coral.originalX = finalX;
                        coral.originalY = finalY;
                        if (typeof coralPosition.tilt === "number") {
                            coral.setRotation(
                                Phaser.Math.DegToRad(coralPosition.tilt)
                            );
                        }
                        coral.setDepth(coralPosition.z || 5);
                        coral.isPlanted = true;
                        coral.phase = "planted";
                        if (textureKey.startsWith("coral_custom_")) {
                            const screenWidth = window.innerWidth;
                            const finalSize =
                                screenWidth * (coralPosition.size || 0.25);
                            const startSize = finalSize * 0.28;
                            coral.setDisplaySize(startSize, startSize);
                            coral.alpha = 0.9;
                            this.tweens.add({
                                targets: coral,
                                displayWidth: finalSize,
                                displayHeight: finalSize,
                                duration: 2200,
                                ease: "Elastic.easeOut",
                                onComplete: () => {
                                    coral.setDisplaySize(finalSize, finalSize);
                                    startCoralBubbles.call(this, coral);
                                    currentCoralPositionIndex++;
                                    if (typeof onTweenComplete === "function")
                                        onTweenComplete();
                                },
                            });
                        } else {
                            const startScale = baseScale * 0.28;
                            coral.setScale(startScale);
                            this.tweens.add({
                                targets: coral,
                                scale: baseScale,
                                duration: 1500,
                                ease: "Elastic.easeOut",
                                onComplete: () => {
                                    startCoralBubbles.call(this, coral);
                                    currentCoralPositionIndex++;
                                    if (typeof onTweenComplete === "function")
                                        onTweenComplete();
                                },
                            });
                        }
                    });
                },
            });
        },
    });
}

// edn

function addNameBubbles() {
    console.log("addNameBubbles function called");
    // Initialize physics-enabled group for name bubbles (including temp bubbles)
    this.nameBubbleGroup = this.physics.add.group({
        // Disable physics bounce and world bounds for name bubbles; using custom repulsion instead
        collideWorldBounds: false,
        bounceX: 0,
        bounceY: 0
    });
    // Physics collider disabled to prevent visual glitches when bubbles collide
    // this.physics.add.collider(this.nameBubbleGroup, this.nameBubbleGroup);

    // Create initial 6 name bubbles immediately (floating, no entry animation)
    console.log("Creating initial name bubbles directly floating...");
    for (let i = 0; i < MAX_NAME_BUBBLES; i++) {
        createInitialNameBubble.call(this, i);
    }
}

// Function to create initial name bubbles directly in floating positions without entry animation
function createInitialNameBubble(index) {
    let pledge;
    if (pledgeData.length === 0) {
        // No pledge data, create a placeholder pledge for temp bubble
        pledge = {
            id: `temp_${index}`,
            text: "",
            type: "text",
            image: null,
            textureKey: null,
        };
    } else {
        let pledgesToUse = pledgeData.filter(
            (pledge) => pledge.type === "text"
        );
        if (pledgesToUse.length === 0) {
            console.log(
                "No text pledges found for initial bubbles, using any pledge."
            );
            pledgesToUse = pledgeData; // Fallback to all pledges
        }
        if (pledgesToUse.length === 0) {
            // Still no pledges, fallback to placeholder
            pledge = {
                id: `temp_${index}`,
                text: "",
                type: "text",
                image: null,
                textureKey: null,
            };
        } else {
            pledge = Phaser.Utils.Array.GetRandom(pledgesToUse);
        }
    }
    createSingleInitialNameBubble.call(this, pledge, index);
}

function createSingleInitialNameBubble(pledge, index) {
    // Use preloaded tempBubble keys for each bubble
    // Support single or multiple tempBubble images
    const tempBubbleKeys = [];
    let j = 1;
    while (this.textures.exists(`tempBubble${j}`)) {
        tempBubbleKeys.push(`tempBubble${j}`);
        j++;
    }
    if (tempBubbleKeys.length === 0) {
        tempBubbleKeys.push("tempBubble1"); // fallback if only one
    }
    // Pick a tempBubbleKey (for now, always use the first)
    const tempBubbleKey = tempBubbleKeys[0];

    // Spread bubbles evenly across the width, in the upper 20-35% of the canvas
    const total = MAX_NAME_BUBBLES;
    const margin = 80;
    const spread = (window.innerWidth - 2 * margin) / (total - 1);
    const finalX = margin + index * spread;
    const finalY = Phaser.Math.Between(
        window.innerHeight * 0.2,
        window.innerHeight * 0.35
    );

    // Calculate scale based on pledge text length using config map
    let textLength = pledge.text ? pledge.text.length : 0;
    // Use temp bubble config for temp bubbles, otherwise normal config
    let scale;
    if (pledge.id && pledge.id.toString().startsWith("temp_")) {
        scale = getTempBubbleScale(textLength);
    } else {
        scale = getNameBubbleScale(textLength);
    }

    // Entry: always spawn at middle bottom of the screen
    const spawnX = window.innerWidth / 2;
    const spawnY = window.innerHeight;
    let nameBubble;
    try {
        // Create physics-enabled sprite for temp bubble
        nameBubble = this.physics.add
            .sprite(spawnX, spawnY, tempBubbleKey)
            .setScale(scale);
    } catch (error) {
        // Fallback: circle with physics body
        nameBubble = this.add.circle(
            spawnX,
            spawnY,
            (35 * scale) / 0.6,
            0x87ceeb,
            0.7
        );
        this.physics.add.existing(nameBubble);
        // Configure circular body
        const radius = (35 * scale) / 0.6;
        nameBubble.body.setCircle(radius);
        nameBubble.body.setOffset(-radius, -radius);
    }
    // Common physics behavior for all initial bubbles
    if (nameBubble.body) {
        nameBubble.body.setCollideWorldBounds(true);
        nameBubble.body.setBounce(1, 1);
        // Disable collision during entry animation
        nameBubble.body.enable = false;
    }
    nameBubble.baseScale = scale;
    nameBubble.alpha = 0;
    nameBubble.objectType = "nameBubble";
    nameBubble.pledgeData = pledge;
    nameBubble.isFloating = false;

    // Remove oldest name bubble if at limit
    let attempts = 0;
    const maxAttempts = 10;
    while (
        this.nameBubbleGroup.getLength() >= MAX_NAME_BUBBLES &&
        attempts < maxAttempts
    ) {
        const removed = removeOldestItem.call(
            this,
            this.nameBubbleGroup,
            this.nameBubbles
        );
        if (!removed) break;
        attempts++;
    }
    if (attempts >= maxAttempts) {
        console.error(
            `Maximum name bubble removal attempts reached, something is wrong with group management`
        );
    }
    this.nameBubbleGroup.add(nameBubble);
    this.nameBubbles.push(nameBubble);
    this.validateGroupSizes();

    // Floating movement properties (match dynamic name bubbles)
    nameBubble.baseX = finalX;
    nameBubble.baseY = finalY;
    nameBubble.floatTime = Math.random() * Math.PI * 2;
    nameBubble.floatSpeed = Phaser.Math.FloatBetween(1.2, 2.2);
    nameBubble.floatRadius = Phaser.Math.Between(18, 36);
    nameBubble.floatPhase = Math.random() * Math.PI * 2;

    // Improved bubble-like entry animation: more pronounced wavy path, scale pulse, gentle ease-out
    const controlX =
        spawnX + (finalX - spawnX) * 0.45 + Phaser.Math.Between(-60, 60);
    const controlY = spawnY - Phaser.Math.Between(120, 200);
    const path = {
        getPoint: (t) => {
            // More pronounced horizontal and vertical wobble, like a bubble
            const wobbleX =
                Math.sin(t * Math.PI * 3.2 + Math.sin(t * 8)) *
                18 *
                (1 - t) *
                0.7;
            const wobbleY =
                Math.cos(t * Math.PI * 2.7 + Math.cos(t * 7)) *
                12 *
                (1 - t) *
                0.6;
            const x =
                (1 - t) * (1 - t) * spawnX +
                2 * (1 - t) * t * controlX +
                t * t * finalX +
                wobbleX;
            const y =
                (1 - t) * (1 - t) * spawnY +
                2 * (1 - t) * t * controlY +
                t * t * finalY +
                wobbleY;
            return { x, y };
        },
    };
    let tweenObj = { t: 0 };
    // Slower entry: increase duration
    const entryDuration = Phaser.Math.Between(3200, 4200);
    const baseScale = scale;
    this.tweens.add({
        targets: tweenObj,
        t: 1,
        duration: entryDuration,
        ease: "Cubic.easeOut",
        onUpdate: () => {
            const pos = path.getPoint(tweenObj.t);
            nameBubble.x = pos.x;
            nameBubble.y = pos.y;
            nameBubble.alpha = tweenObj.t;
            // Bubble scale pulse: grows then settles
            if (nameBubble.setScale) {
                const pulse =
                    1 +
                    Math.sin(tweenObj.t * Math.PI) * 0.13 * (1 - tweenObj.t);
                nameBubble.setScale(baseScale * pulse);
            }
        },
        onComplete: () => {
            nameBubble.isFloating = true;
            nameBubble.alpha = 1;
            if (nameBubble.setScale) nameBubble.setScale(baseScale);
            // Enable physics collisions after entry animation
            if (nameBubble.body) nameBubble.body.enable = true;
        },
    });
}

function spawnSingleNameBubble(customTextureKey) {
    console.log(`Name bubble spawn attempt`);

    console.log("Name bubble spawn proceeding...");
    if (pledgeData.length === 0) {
        console.log("No pledge data available for name bubble");
        return;
    }

    let pledgesToUse = pledgeData.filter((pledge) => pledge.type === "text");
    if (pledgesToUse.length === 0) {
        console.log("No text pledges found, using any random pledge");
        pledgesToUse = pledgeData; // Fallback to all pledges
    }
    if (pledgesToUse.length === 0) return; // Still no pledges, exit

    // Use the most recent pledge (the one just pushed)
    const pledge = pledgesToUse[pledgesToUse.length - 1];
    const textureKey = customTextureKey || pledge.textureKey || "name_bubble";
    createNameBubble.call(this, pledge, textureKey);
}

function createNameBubble(pledge, textureKey) {
    console.log("Starting name bubble entry...");

    // Entry: always from the middle bottom of the screen
    const { x: finalX, y: finalY } = getSafeFinalPosition(this.nameBubbles, 120);

    const spawnX = window.innerWidth / 2;
    const spawnY = window.innerHeight;

    let textLength = pledge.text ? pledge.text.length : 0;
    let scale = getNameBubbleScale(textLength);
    let nameBubble;

    try {
        // Try to create physics-enabled sprite
        nameBubble = this.physics.add
            .sprite(spawnX, spawnY, textureKey)
            .setScale(scale);
    } catch (error) {
        // Fallback: create graphics-based circle with physics
        const radius = (35 * scale) / 0.6;
        nameBubble = this.add.circle(spawnX, spawnY, radius, 0x87ceeb, 0.7);
        this.physics.add.existing(nameBubble);

        // Set body to be a circle and fix alignment
        nameBubble.body.setCircle(radius);
        nameBubble.body.setOffset(-radius, -radius);
    }

    // Common physics behavior
    if (nameBubble.body) {
        nameBubble.body.setCollideWorldBounds(true);
        nameBubble.body.setBounce(1, 1);
        nameBubble.body.setVelocity(
            Phaser.Math.Between(-30, 30),
            Phaser.Math.Between(-50, -100)
        );
    }

    nameBubble.baseScale = scale;
    nameBubble.alpha = 0;
    nameBubble.objectType = "nameBubble";
    nameBubble.pledgeData = pledge;
    nameBubble.isFloating = false;

    // Remove oldest if at limit
    let attempts = 0;
    const maxAttempts = 10;
    while (
        this.nameBubbleGroup.getLength() >= MAX_NAME_BUBBLES &&
        attempts < maxAttempts
    ) {
        const removed = removeOldestItem.call(
            this,
            this.nameBubbleGroup,
            this.nameBubbles
        );
        if (!removed) break;
        attempts++;
    }
    if (attempts >= maxAttempts) {
        console.error("Maximum name bubble removal attempts reached");
    }

    // Add to group and track
    this.nameBubbleGroup.add(nameBubble);
    this.nameBubbles.push(nameBubble);
    this.validateGroupSizes();

    // Floating properties
    nameBubble.baseX = finalX;
    nameBubble.baseY = finalY;
    nameBubble.floatTime = Math.random() * Math.PI * 2;
    nameBubble.floatSpeed = Phaser.Math.FloatBetween(1.2, 2.2);
    nameBubble.floatRadius = Phaser.Math.Between(18, 36);
    nameBubble.floatPhase = Math.random() * Math.PI * 2;

    // Entry animation path (bubble-like bezier with wobble)
    const controlX =
        spawnX + (finalX - spawnX) * 0.45 + Phaser.Math.Between(-60, 60);
    const controlY = spawnY - Phaser.Math.Between(120, 200);
    const path = {
        getPoint: (t) => {
            const wobbleX =
                Math.sin(t * Math.PI * 3.2 + Math.sin(t * 8)) *
                18 *
                (1 - t) *
                0.7;
            const wobbleY =
                Math.cos(t * Math.PI * 2.7 + Math.cos(t * 7)) *
                12 *
                (1 - t) *
                0.6;
            const x =
                (1 - t) * (1 - t) * spawnX +
                2 * (1 - t) * t * controlX +
                t * t * finalX +
                wobbleX;
            const y =
                (1 - t) * (1 - t) * spawnY +
                2 * (1 - t) * t * controlY +
                t * t * finalY +
                wobbleY;
            return { x, y };
        },
    };

    let tweenObj = { t: 0 };
    const entryDuration = Phaser.Math.Between(3200, 4200);
    const baseScale = scale;

    this.tweens.add({
        targets: tweenObj,
        t: 1,
        duration: entryDuration,
        ease: "Cubic.easeOut",
        onUpdate: () => {
            const pos = path.getPoint(tweenObj.t);
            nameBubble.x = pos.x;
            nameBubble.y = pos.y;
            nameBubble.alpha = tweenObj.t;

            if (nameBubble.setScale) {
                const pulse =
                    1 +
                    Math.sin(tweenObj.t * Math.PI) * 0.13 * (1 - tweenObj.t);
                nameBubble.setScale(baseScale * pulse);
            }
        },
        onComplete: () => {
            nameBubble.isFloating = true;
            nameBubble.alpha = 1;
            if (nameBubble.setScale) nameBubble.setScale(baseScale);

            // Gentle float velocity after entry
            if (nameBubble.body) {
                nameBubble.body.setVelocity(
                    Phaser.Math.Between(-20, 20),
                    Phaser.Math.Between(-30, -10)
                );
            }
        },
    });
}




// Function to start bubble generation for planted corals
function startCoralBubbles(coral) {
    // Initialize bubble events array if not exists
    if (!coral.bubbleEvents) {
        coral.bubbleEvents = [];
    }

    // Create a single bubble chain generator that creates bubbles in sequence
    const bubbleEvent = this.time.addEvent({
        delay: Phaser.Math.Between(500, 1000), // Initial delay before first bubble
        callback: () => {
            if (coral && coral.isPlanted && !coral.shouldDestroy) {
                createBubbleChain.call(this, coral);
            }
        },
        loop: true,
    });

    // Store the event reference for cleanup
    coral.bubbleEvents.push(bubbleEvent);
}

// Function to create a chain of bubbles rising from corals
function createBubbleChain(coral) {
    // Increase the number of bubbles per chain: 8-14
    const numBubblesInChain = Phaser.Math.Between(20, 30); // 8-14 bubbles per chain
    const chainDelay = Phaser.Math.Between(50, 100); // Faster succession for visible chain effect

    // ...existing code...

    for (let i = 0; i < numBubblesInChain; i++) {
        this.time.delayedCall(i * chainDelay, () => {
            if (coral && coral.isPlanted && !coral.shouldDestroy) {
                createCoralBubble.call(this, coral, i);
            }
        });
    }
}

// Function to create small bubbles rising from corals
function createCoralBubble(coral, chainIndex = 0) {
    // Determine start position for coral bubbles
    const bubbleX = coral.x;
    const bubbleY = coral.y - 30;
    let bubble;
    // Use a fixed bubble size based on parent coral's baseScale (from CORAL_POSITIONS size)
    const coralScale = coral.baseScale || 1.0;
    const fixedSize = 0.014 * coralScale; // Fixed size for all coral bubbles

    try {
        bubble = this.add
            .sprite(bubbleX, bubbleY, "bubble_overlay")
            .setScale(fixedSize);
    } catch (error) {
        // Fallback: fixed size circle
        const radius = 3 * coralScale;
        bubble = this.add.circle(bubbleX, bubbleY, radius, 0x87ceeb, 0.6);
    }

    bubble.alpha = 0.7; // Fixed alpha for all coral bubbles
    bubble.setDepth(15); // Higher depth to ensure bubbles appear above corals and other elements

    // STRAIGHT UP: No side-to-side, just animate straight up
    const riseHeight = Phaser.Math.Between(180, 320); // Longer rise height
    const duration = Phaser.Math.Between(7000, 13000); // Much longer duration (7-13 seconds)

    this.tweens.add({
        targets: bubble,
        y: bubble.y - riseHeight,
        alpha: 0,
        duration: duration,
        ease: "Sine.easeOut",
        // No onUpdate: bubbles go straight up
        onComplete: () => {
            bubble.destroy();
        },
    });

    this.coralBubblesGroup.add(bubble);
}

// Function to add random ocean floor air bubbles
function addOceanFloorBubbles() {
    this.time.addEvent({
        delay: Phaser.Math.Between(3000, 6000), // Less frequent bubble chains
        callback: () => {
            createOceanFloorBubbleChain.call(this);
        },
        loop: true,
    });
}

// Function to create a chain of bubbles from ocean floor
function createOceanFloorBubbleChain() {
    const numBubblesInChain = Phaser.Math.Between(3, 6); // 3-6 bubbles per chain
    const chainDelay = Phaser.Math.Between(150, 400); // Faster succession for more visible chain effect
    const baseX = Phaser.Math.Between(50, window.innerWidth - 50); // Base position for chain

    console.log(
        `Creating ocean floor bubble chain: ${numBubblesInChain} bubbles at x=${baseX}`
    );

    for (let i = 0; i < numBubblesInChain; i++) {
        this.time.delayedCall(i * chainDelay, () => {
            createOceanFloorBubble.call(this, baseX, i);
        });
    }
}

// Function to create random air bubbles from ocean floor
function createOceanFloorBubble(baseX = null, chainIndex = 0) {
    // Use provided baseX for chain, or random position for individual bubbles
    const bubbleX = baseX
        ? baseX + Phaser.Math.Between(-25, 25)
        : Phaser.Math.Between(50, window.innerWidth - 50);
    const bubbleY = window.innerHeight - Phaser.Math.Between(20, 60); // Near bottom

    let bubble;
    const randomSize = Phaser.Math.FloatBetween(0.02, 0.06); // Smaller bubbles

    try {
        bubble = this.add
            .sprite(bubbleX, bubbleY, "bubble_overlay")
            .setScale(randomSize);
    } catch (error) {
        // Fallback: small circle with random size
        const radius = Phaser.Math.Between(1, 4);
        bubble = this.add.circle(bubbleX, bubbleY, radius, 0x87ceeb, 0.7);
    }

    bubble.alpha = Phaser.Math.FloatBetween(0.3, 0.7); // Random transparency
    bubble.setDepth(5); // Behind corals but above background

    // Add unique floating properties for natural movement (more variation)
    bubble.vx = Phaser.Math.FloatBetween(-0.018, 0.018);
    bubble.vy = Phaser.Math.FloatBetween(-0.005, 0.005);
    bubble.floatTime = Math.random() * Math.PI * 4; // Larger random start phase range
    bubble.floatSpeed = Phaser.Math.FloatBetween(0.4, 1.8); // Wider individual float speed range
    bubble.driftStrength = Phaser.Math.FloatBetween(0.2, 1.5); // Wider individual drift strength range
    bubble.phaseOffsetX = Math.random() * Math.PI * 2; // Individual phase offset for X movement
    bubble.phaseOffsetY = Math.random() * Math.PI * 2; // Individual phase offset for Y movement

    // Animate bubble rising up with natural movement
    const riseHeight = Phaser.Math.Between(
        window.innerHeight * 0.7,
        window.innerHeight * 1.1
    );
    const duration = Phaser.Math.Between(8000, 15000); // Much longer duration (8-15 seconds)

    this.tweens.add({
        targets: bubble,
        y: bubbleY - riseHeight,
        alpha: 0,
        duration: duration,
        ease: "Sine.easeOut",
        onUpdate: () => {
            // Add individual natural floating movement with unique phase offsets
            bubble.floatTime += 0.006 * bubble.floatSpeed;
            const driftX =
                Math.sin((bubble.floatTime + bubble.phaseOffsetX) * 1.1) *
                bubble.driftStrength;
            const driftY =
                Math.cos((bubble.floatTime + bubble.phaseOffsetY) * 0.7) *
                (bubble.driftStrength * 0.4);

            bubble.x += bubble.vx + driftX;
            bubble.y += bubble.vy + driftY * 0.08;

            // Individual direction changes with varying probabilities
            const changeChance =
                0.003 + Math.sin(bubble.floatTime * 0.2) * 0.002;
            if (Math.random() < changeChance) {
                bubble.vx += Phaser.Math.FloatBetween(-0.006, 0.006);
                bubble.vy += Phaser.Math.FloatBetween(-0.003, 0.003);
                bubble.vx = Phaser.Math.Clamp(bubble.vx, -0.02, 0.02);
                bubble.vy = Phaser.Math.Clamp(bubble.vy, -0.008, 0.008);
            }
        },
        onComplete: () => {
            bubble.destroy();
        },
    });

    this.oceanFloorBubblesGroup.add(bubble);
}

// Function to add bubbles from random areas like coral bubbles
function addRandomAreaBubbles() {
    this.time.addEvent({
        delay: Phaser.Math.Between(4000, 8000), // Less frequent chains
        callback: () => {
            createRandomAreaBubbleChain.call(this);
        },
        loop: true,
    });
}

// Function to create a chain of bubbles from random areas
function createRandomAreaBubbleChain() {
    const numBubblesInChain = Phaser.Math.Between(2, 5); // 2-5 bubbles per chain
    const chainDelay = Phaser.Math.Between(200, 500); // Faster succession for visible chain effect
    const baseX = Phaser.Math.Between(50, window.innerWidth - 50); // Base position for chain
    const baseY = Phaser.Math.Between(
        window.innerHeight * 0.4,
        window.innerHeight - 50
    );

    console.log(
        `Creating random area bubble chain: ${numBubblesInChain} bubbles at x=${baseX}, y=${baseY}`
    );

    for (let i = 0; i < numBubblesInChain; i++) {
        this.time.delayedCall(i * chainDelay, () => {
            createRandomAreaBubble.call(this, baseX, baseY, i);
        });
    }
}

// Function to create bubbles from random areas across the screen
function createRandomAreaBubble(baseX = null, baseY = null, chainIndex = 0) {
    // Use provided base position for chain, or random position for individual bubbles
    const bubbleX = baseX
        ? baseX + Phaser.Math.Between(-10, 10)
        : Phaser.Math.Between(50, window.innerWidth - 50);
    const bubbleY = baseY
        ? baseY + Phaser.Math.Between(-8, 8)
        : Phaser.Math.Between(
              window.innerHeight * 0.4,
              window.innerHeight - 50
          );

    let bubble;
    // Make bubbles smaller and less transparent
    const randomSize = Phaser.Math.FloatBetween(0.012, 0.025); // Smaller, more subtle bubbles

    try {
        bubble = this.add
            .sprite(bubbleX, bubbleY, "bubble_overlay")
            .setScale(randomSize);
    } catch (error) {
        // Fallback: small circle with random size
        const radius = Phaser.Math.Between(1, 2);
        bubble = this.add.circle(bubbleX, bubbleY, radius, 0x87ceeb, 0.8);
    }

    bubble.alpha = Phaser.Math.FloatBetween(0.7, 0.95); // Less transparent
    bubble.setDepth(8); // Between ocean floor bubbles and coral bubbles

    // STRAIGHT UP: No side-to-side, just animate straight up
    const riseHeight = Phaser.Math.Between(120, 220); // Variable rise height
    const duration = Phaser.Math.Between(6000, 10000); // 6-10 seconds

    this.tweens.add({
        targets: bubble,
        y: bubble.y - riseHeight,
        alpha: 0,
        duration: duration,
        ease: "Sine.easeOut",
        // No onUpdate: bubbles go straight up
        onComplete: () => {
            bubble.destroy();
        },
    });

    this.randomAreaBubblesGroup.add(bubble);
}

function update(time, delta) {
    const dt = delta / 1000;

    // Periodic validation every 10 seconds to ensure limits are maintained
    if (Math.floor(time / 1000) % 10 === 0 && time % 1000 < 50) {
        this.validateGroupSizes();
    }

    // Update corals (floating behavior while falling, stationary when planted)
    this.coralGroup.getChildren().forEach((coral) => {
        if (coral.isPlanted && !coral.shouldDestroy) {
            // --- REMOVE planted coral animation: keep coral at original position and rotation only ---
            coral.x = coral.originalX;
            coral.y = coral.originalY;
            // Do NOT reset rotation here; keep the rotation set at planting
            if (coral.setScale) {
                const baseScale = coral.baseScale || 0.5;
                if (!coral.baseScale) coral.baseScale = baseScale;
                coral.setScale(baseScale);
            }
            if (coral.setAlpha) {
                const baseAlpha = coral.baseAlpha || 1.0;
                if (!coral.baseAlpha) coral.baseAlpha = baseAlpha;
                coral.setAlpha(baseAlpha);
            }
            if (coral.setTint) {
                coral.setTint(0xffffff);
            }
        }
    });

    // --- ENHANCED coral bubble floating ---
    if (this.coralBubblesGroup) {
        this.coralBubblesGroup.getChildren().forEach((bubble) => {
            bubble.floatTime += dt * Phaser.Math.FloatBetween(0.95, 1.1);
            bubble.x +=
                bubble.vx * dt * Phaser.Math.FloatBetween(12, 18) +
                Math.sin(
                    bubble.floatTime * Phaser.Math.FloatBetween(1.7, 2.3)
                ) *
                    Phaser.Math.FloatBetween(0.2, 0.5);
            bubble.vx +=
                Math.cos(
                    bubble.floatTime * Phaser.Math.FloatBetween(1.2, 1.8)
                ) * Phaser.Math.FloatBetween(0.001, 0.003);
            if (Math.abs(bubble.vx) > Phaser.Math.FloatBetween(0.018, 0.025)) {
                bubble.vx *= 0.9;
            }
        });
    }

    // Update floating name bubbles with lively, bubble-like movement
    if (this.nameBubbleGroup) {
        const bubbles = this.nameBubbleGroup.getChildren();
        for (let i = 0; i < bubbles.length; i++) {
            const a = bubbles[i];
            // Only apply floating and clamping if entry animation is done
            if (a.isFloating) {
                // Bubble-like wavy floating (horizontal and vertical)
                a.floatTime += dt * a.floatSpeed;
                a.x =
                    a.baseX +
                    Math.sin(a.floatTime + a.floatPhase) * a.floatRadius;
                a.y =
                    a.baseY +
                    Math.cos(a.floatTime * 0.8 + a.floatPhase) *
                        (a.floatRadius * 0.7);
                // Add gentle random drift if near the top (y < 120)
                if (a.y < 120) {
                    if (!a.driftPhase) a.driftPhase = Math.random() * Math.PI * 2;
                    if (!a.driftSpeed) a.driftSpeed = Phaser.Math.FloatBetween(0.15, 0.35);
                    if (!a.driftRadius) a.driftRadius = Phaser.Math.FloatBetween(12, 28);
                    a.driftPhase += dt * a.driftSpeed;
                    a.x += Math.sin(a.driftPhase) * a.driftRadius * dt * 0.18;
                    a.y += Math.cos(a.driftPhase) * a.driftRadius * dt * 0.09;
                }
                // Always set scale to baseScale (from config)
                if (a.setScale && a.baseScale) {
                    a.setScale(a.baseScale);
                }
                // Keep bubbles in upper part only
                if (a.x < 60) a.x = 60;
                if (a.x > window.innerWidth - 60) a.x = window.innerWidth - 60;
                if (a.y < 30) a.y = 30;
                if (a.y > window.innerHeight * 0.38)
                    a.y = window.innerHeight * 0.38;
            }
        }
    }
    // Prevent name bubbles from overlapping by applying repulsion forces
    preventNameBubbleOverlap.call(this);
}
// Helper to prevent name bubbles from overlapping (entry and floating)
function preventNameBubbleOverlap() {
    const bubbles = this.nameBubbles;
    const SPRING = 0.03; // Lowered for a much gentler spring force
    const DAMPING = 0.92; // Increased for more sluggish, fluid movement
    const REPULSION_RADIUS = 150; // New: Radius for gentle repulsion
    const REPULSION_STRENGTH = 0.005; // New: How strongly they push each other away

    for (let i = 0; i < bubbles.length; i++) {
        const a = bubbles[i];
        // Apply collision to ALL active bubbles (both floating and during entry)
        if (!a.active || a.alpha < 0.1) continue;
        if (!a.vx) a.vx = 0;
        if (!a.vy) a.vy = 0;
        const aRadius = a.displayWidth ? a.displayWidth / 2 : (a.baseScale || 0.2) * 70;

        for (let j = i + 1; j < bubbles.length; j++) {
            const b = bubbles[j];
            if (!b.active || b.alpha < 0.1) continue;
            if (!b.vx) b.vx = 0;
            if (!b.vy) b.vy = 0;
            const bRadius = b.displayWidth ? b.displayWidth / 2 : (b.baseScale || 0.2) * 70;

            const dx = a.x - b.x;
            const dy = a.y - b.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            const minDist = aRadius + bRadius + 12; // Increased buffer slightly

            // Direct collision spring force (gentler)
            if (dist < minDist && dist > 0.1) {
                const overlap = minDist - dist;
                const force = (overlap / minDist) * SPRING;
                const fx = (dx / dist) * force;
                const fy = (dy / dist) * force;
                a.vx += fx;
                a.vy += fy;
                b.vx -= fx;
                b.vy -= fy;
            }
            // Gentle repulsion force to encourage spreading
            else if (dist < REPULSION_RADIUS) {
                const force = (1 - dist / REPULSION_RADIUS) * REPULSION_STRENGTH;
                const fx = (dx / dist) * force;
                const fy = (dy / dist) * force;
                a.vx += fx;
                a.vy += fy;
                b.vx -= fx;
                b.vy -= fy;
            }
        }
    }

    // Apply velocity as persistent base offsets and damping to ALL bubbles
    for (let i = 0; i < bubbles.length; i++) {
        const a = bubbles[i];
        if (!a.active || a.alpha < 0.1) continue;
        a.vx = a.vx || 0;
        a.vy = a.vy || 0;
        // Persistently shift base positions to separate bubbles
        if (a.baseX !== undefined && a.baseY !== undefined) {
            a.baseX += a.vx;
            a.baseY += a.vy;
        } else {
            a.x += a.vx;
            a.y += a.vy;
        }
        // Apply damping
        a.vx *= DAMPING;
        a.vy *= DAMPING;
        // Clamp very small velocities to zero for stability
        if (Math.abs(a.vx) < 0.001) a.vx = 0;
        if (Math.abs(a.vy) < 0.001) a.vy = 0;
    }
}

// Validation function to ensure group sizes never exceed limits
function validateGroupSizes() {
    // Safely get group sizes, groups may not be initialized yet
    const coralCount = this.coralGroup ? this.coralGroup.getLength() : 0;
       const nameBubbleCount = this.nameBubbleGroup
        ? this.nameBubbleGroup.getLength()
        : 0;

    console.log(
        `Validating group sizes - Corals: ${coralCount}/${MAX_CORALS}, Name bubbles: ${nameBubbleCount}/${MAX_NAME_BUBBLES}`
    );

    // Remove only one coral if over the limit (avoid infinite loop due to async removal)
    if (this.coralGroup && this.coralGroup.getLength() > MAX_CORALS) {
        console.warn(
            `Emergency coral removal! Group size: ${this.coralGroup.getLength()}`
        );
        removeOldestItem.call(this, this.coralGroup, this.corals);
    }

    // Remove only one name bubble if over the limit (avoid infinite loop due to async removal)
    if (
        this.nameBubbleGroup &&
        this.nameBubbleGroup.getLength() > MAX_NAME_BUBBLES
    ) {
        console.warn(
            `Emergency name bubble removal! Group size: ${this.nameBubbleGroup.getLength()}`
        );
        removeOldestItem.call(this, this.nameBubbleGroup, this.nameBubbles);
    }
}

function resizeGame() {
    const winWidth = window.innerWidth;
    const winHeight = window.innerHeight;
    this.scale.resize(winWidth, winHeight);
    // No background image to resize - handled by CSS
}

// Preload custom images for pledges
async function preloadCustomPledgeImages(scene, callback) {
    // Find all unique custom image URLs in pledgeData
    const urls = [];
    const keys = [];
    pledgeData.forEach((pledge) => {
        if (pledge.image && !pledge.image.startsWith("data:")) {
            const key =
                pledge.type === "coral"
                    ? `coral_custom_${pledge.id}`
                    : `name_bubble_custom_${pledge.id}`;
            if (!scene.textures.exists(key)) {
                urls.push(pledge.image);
                keys.push(key);
                pledge.textureKey = key;
            }
        }
    });
    if (urls.length === 0) {
        callback();
        return;
    }
    let loaded = 0;
    for (let i = 0; i < urls.length; i++) {
        scene.load.image(keys[i], urls[i]);
    }
    scene.load.once("complete", () => {
        callback();
    });
    scene.load.start();
}
