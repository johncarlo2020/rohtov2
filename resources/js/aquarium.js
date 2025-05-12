import Phaser from "phaser";
import PlasmaPost2FX from "./PlasmaPost2FX";

const config = {
    parent: "aquarium-container",
    type: Phaser.AUTO,
    width: window.innerWidth,
    height: window.innerHeight,
    physics: {
        default: 'arcade',
        arcade: { gravity: { y: 0 }, debug: false } // Changed to false
    },
    pipeline: { PlasmaPost2FX },
    render: {
        preserveDrawingBuffer: true,
        contextAttributes: {
            alpha: true,
            premultipliedAlpha: false,
        },
    },
    scale: {
        mode: Phaser.Scale.NONE,
    },
    scene: { preload, create, update },
};

const ASSET = window.ASSET_BASE;

const game = new Phaser.Game(config);
const fishNames = ["Nemo", "Bub", "Fin", "Splash", "Marl", "Corl"];

// Adjustable Variables
const SPAWN_DELAY = 3000;
const FISH_SPEED = 80;
const TEMP_SPEED = 100;
const FLOAT_SPEED = 0.04;
const FLOAT_FREQUENCY = 3; // oscillation frequency multiplier
const COLLISION_PUSH_FORCE = 1.5;
// Frame rate presets for animations
const FRAME_RATE_SLOW = 6;
const FRAME_RATE_NORMAL = 3;
const FRAME_RATE_FAST = 10;
const FRAME_RATE_SUPERFAST = 16;
const SPIN_TIME = 2000;
const SPIN_VELOCITY = 360;
const BUBBLE_OFFSET_X = 60; // Increased from 45
const BUBBLE_OFFSET_Y = -60; // Decreased from -25
const BUBBLE_RADIUS = 70; // Increased from 30
const MIN_COLLISION_DISTANCE = 80;
const STRETCH_FACTOR = 0.01;
// New Movement Dynamics Constants
const DART_CHANCE_PER_SECOND = 0.15; // 15% chance per second for a fish to consider darting
const DART_DURATION = 0.7;          // Dart lasts for 0.7 seconds
const DART_SPEED_MULTIPLIER = 2.8;  // Darts are 2.8x faster than normal base speed
const DART_COOLDOWN_MIN = 4.0;      // Minimum cooldown between darts (seconds)
const DART_COOLDOWN_MAX = 10.0;     // Maximum cooldown between darts (seconds)
const MAX_NORMAL_SPEED_FACTOR = 1.3;// Max speed during normal floating (1.3x base speed)

// Entry Bubble Animation Constants
const ENTRY_BUBBLE_SPRITESHEET_KEY = 'entryBubbleSheet';
const ENTRY_BUBBLE_ANIM_KEY = 'entryBubblePopAnim';
const ENTRY_BUBBLE_FRAME_WIDTH = 400;
const ENTRY_BUBBLE_FRAME_HEIGHT = 400;
const ENTRY_BUBBLE_FRAME_COUNT = 9;
const ENTRY_BUBBLE_ANIM_FRAMERATE = 10; // FPS, results in 3 second animation (30 frames / 10 FPS) - SLOWED DOWN from 20

// fish spritesheet settings
const FISH_FRAME_WIDTH = 300;
const FISH_FRAME_HEIGHT = 300;
// actual fish spritesheet has 7 frames
const FISH_FRAME_COUNT = 6;
// add a scale constant for fish size
const FISH_SCALE = 0.8; // scale for fish sprites (Increased from 0.4)

// tempCharacter spritesheet settings
const TEMP_CHAR_FRAME_WIDTH = 400;
const TEMP_CHAR_FRAME_HEIGHT = 400;
const TEMP_CHAR_SCALE = 0.6; // scale for temp characters (Previously hardcoded 0.8)
// tempCharacter spritesheet frame count (25000px width ÷ 200px frame = 125 frames)
const TEMP_FRAME_COUNT = 125;

const MAX_TOTAL_CHARACTERS = 15;
const NUM_DEFAULT_TEMP_CHARACTERS = 5;

function preload() {
    this.load.video("aquarium", `${ASSET}/images/hadalabobabies/Aqua HL v2.mp4`);
    this.load.image("bubble", `${ASSET}/images/bubble.webp`);

    // Load the entry bubble spritesheet
    this.load.spritesheet(
        ENTRY_BUBBLE_SPRITESHEET_KEY,
        `${ASSET}/images/bubble_animation.webp`,
        { frameWidth: ENTRY_BUBBLE_FRAME_WIDTH, frameHeight: ENTRY_BUBBLE_FRAME_HEIGHT, endFrame: ENTRY_BUBBLE_FRAME_COUNT - 1 }
    );

    // load fish as spritesheet instead of static image
    this.load.spritesheet(
        "fish",
        `${ASSET}/images/drawing (2).webp`,
        { frameWidth: FISH_FRAME_WIDTH, frameHeight: FISH_FRAME_HEIGHT, endFrame: FISH_FRAME_COUNT - 1 }
    );
    // load tempCharacter sheet as 200×200 frames (TEMP_FRAME_COUNT frames)
    this.load.spritesheet(
        "tempCharacter",
        `${ASSET}/images/defaultBabies/1.webp`,
        { frameWidth: TEMP_CHAR_FRAME_WIDTH, frameHeight: TEMP_CHAR_FRAME_HEIGHT, endFrame: TEMP_FRAME_COUNT - 1 }
    );
    // load second temp character variant
    this.load.spritesheet(
        "tempCharacter2",
        `${ASSET}/images/defaultBabies/2.webp`,
        { frameWidth: TEMP_CHAR_FRAME_WIDTH, frameHeight: TEMP_CHAR_FRAME_HEIGHT, endFrame: TEMP_FRAME_COUNT - 1 }
    );
    // load additional temp character variants (3-5)
    this.load.spritesheet("tempCharacter3", `${ASSET}/images/defaultBabies/3.webp`, { frameWidth: TEMP_CHAR_FRAME_WIDTH, frameHeight: TEMP_CHAR_FRAME_HEIGHT, endFrame: TEMP_FRAME_COUNT - 1 });
    this.load.spritesheet("tempCharacter4", `${ASSET}/images/defaultBabies/4.webp`, { frameWidth: TEMP_CHAR_FRAME_WIDTH, frameHeight: TEMP_CHAR_FRAME_HEIGHT, endFrame: TEMP_FRAME_COUNT - 1 });
    this.load.spritesheet("tempCharacter5", `${ASSET}/images/defaultBabies/5.webp`, { frameWidth: TEMP_CHAR_FRAME_WIDTH, frameHeight: TEMP_CHAR_FRAME_HEIGHT, endFrame: TEMP_FRAME_COUNT - 1 });
}

function create() {
    this.cameras.main.setPostPipeline(PlasmaPost2FX);
    setupCanvas.call(this);
    window.addEventListener("resize", () => resizeGame.call(this));

    this.fishQueue = []; // Initialize the fish queue
    this.isRealFishEntering = false; // Flag to track if a 'real' fish is in its entry animation

    loadAquariumState.call(this); // Load saved state

    // Pusher subscription inside scene to ensure correct context
    Pusher.logToConsole = true;
    const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        encrypted: true
    });
    const channel = pusher.subscribe('baby-channel');
    channel.bind('baby-event', (data) => {
        const fishData = {
            spriteKey: 'fish', // Pusher events are for 'real' fish
            spriteUrl: `${ASSET}/${data.img}`,
            frameWidth: FISH_FRAME_WIDTH,
            frameHeight: FISH_FRAME_HEIGHT,
            name: data.name,
            type: data.type,
            // Indicate that this fish is new and should perform entry animation
            isNewFromPusher: true
        };
        this.fishQueue.push(fishData);
        saveAquariumState.call(this); // Save queue
        // console.log(`Fish '${data.name}' added to queue. Queue size: ${this.fishQueue.length}`);
        tryProcessFishQueue.call(this);
        // console.log(data.img); // Original console log, kept for reference
    });

    // idle animation for tempCharacter
    this.anims.create({
        key: "idle",
        frames: this.anims.generateFrameNumbers("tempCharacter", { start: 0, end: TEMP_FRAME_COUNT - 1 }),
        frameRate: FRAME_RATE_FAST,
        repeat: -1
    });
    // idle animation for second tempCharacter variant
    this.anims.create({
        key: "idle2",
        frames: this.anims.generateFrameNumbers("tempCharacter2", { start: 0, end: TEMP_FRAME_COUNT - 1 }),
        frameRate: FRAME_RATE_FAST,
        repeat: -1
    });
    // idle animations for variants 3-5
    this.anims.create({ key: "idle3", frames: this.anims.generateFrameNumbers("tempCharacter3", { start: 0, end: TEMP_FRAME_COUNT - 1 }), frameRate: FRAME_RATE_FAST, repeat: -1 });
    this.anims.create({ key: "idle4", frames: this.anims.generateFrameNumbers("tempCharacter4", { start: 0, end: TEMP_FRAME_COUNT - 1 }), frameRate: FRAME_RATE_FAST, repeat: -1 });
    this.anims.create({ key: "idle5", frames: this.anims.generateFrameNumbers("tempCharacter5", { start: 0, end: TEMP_FRAME_COUNT - 1 }), frameRate: FRAME_RATE_FAST, repeat: -1 });

    // Create the entry bubble animation
    this.anims.create({
        key: ENTRY_BUBBLE_ANIM_KEY,
        frames: this.anims.generateFrameNumbers(ENTRY_BUBBLE_SPRITESHEET_KEY, { start: 0, end: ENTRY_BUBBLE_FRAME_COUNT - 1 }),
        frameRate: ENTRY_BUBBLE_ANIM_FRAMERATE,
        repeat: 0 // Play once
    });

    // unify all characters (temps & real fish) into one physics group
    this.entities = this.physics.add.group();
    // spawn initial mix of NUM_DEFAULT_TEMP_CHARACTERS tempCharacter variants
    const tempKeys = ['tempCharacter','tempCharacter2','tempCharacter3','tempCharacter4','tempCharacter5'];
    for (let i = 0; i < NUM_DEFAULT_TEMP_CHARACTERS; i++) {
        const spriteKey = tempKeys[i % tempKeys.length]; // Ensures different variants if NUM_DEFAULT_TEMP_CHARACTERS <= tempKeys.length
        addFish.call(this, { spriteKey, frameWidth: TEMP_CHAR_FRAME_WIDTH, frameHeight: TEMP_CHAR_FRAME_HEIGHT }); // Corrected frame dimensions
    }

    // Timer to ensure NUM_DEFAULT_TEMP_CHARACTERS are present, up to MAX_TOTAL_CHARACTERS
    this.time.addEvent({
        delay: 3000, // Check periodically
        loop: true,
        callback: () => {
            const currentTempCharacters = this.entities.getChildren().filter(entity =>
                entity.texture.key.startsWith('tempCharacter')
            );
            const numCurrentTemps = currentTempCharacters.length;

            if (numCurrentTemps < NUM_DEFAULT_TEMP_CHARACTERS && this.entities.getLength() < MAX_TOTAL_CHARACTERS) {
                const tempKeyToAdd = tempKeys[Phaser.Math.Between(0, tempKeys.length - 1)];
                console.log(`Replenishing temp character. Current temps: ${numCurrentTemps}, Total: ${this.entities.getLength()}`);
                addFish.call(this, { spriteKey: tempKeyToAdd, frameWidth: TEMP_CHAR_FRAME_WIDTH, frameHeight: TEMP_CHAR_FRAME_HEIGHT }); // Corrected frame dimensions
            }
        }
    });

    // collision among all entities
    this.physics.add.collider(this.entities, this.entities, (entity1, entity2) => {
        // Check if the entities are active and have bodies before processing collision
        if (entity1 && entity1.active && entity1.body && entity2 && entity2.active && entity2.body) {
            // Randomly choose one entity to spin
            /* if (Phaser.Math.Between(0, 1) === 0) {
                handleCollisionSpin(entity1, this);
            } else {
                handleCollisionSpin(entity2, this);
            } */

            // Custom push logic for stronger collisions
            const body1 = entity1.body;
            const body2 = entity2.body;

            const dx = entity2.x - entity1.x;
            const dy = entity2.y - entity1.y;
            let distance = Math.sqrt(dx * dx + dy * dy);

            let normalX, normalY;

            if (distance === 0) {
                // Bodies are at the exact same position. Apply a random push direction.
                const randomAngle = Phaser.Math.FloatBetween(0, 2 * Math.PI);
                normalX = Math.cos(randomAngle);
                normalY = Math.sin(randomAngle);
            } else {
                normalX = dx / distance;
                normalY = dy / distance;
            }

            // COLLISION_PUSH_FORCE (1.5) is used as a multiplier for a base push velocity.
            const BASE_PUSH_VELOCITY = 20; // px/s. Adjust this value if needed.
            const pushImpulse = COLLISION_PUSH_FORCE * BASE_PUSH_VELOCITY; // e.g., 1.5 * 20 = 30 px/s

            // Apply the push to the velocities, ensuring bodies are movable
            if (body1.moves) {
                body1.velocity.x -= normalX * pushImpulse;
                body1.velocity.y -= normalY * pushImpulse;
            }
            if (body2.moves) {
                body2.velocity.x += normalX * pushImpulse;
                body2.velocity.y += normalY * pushImpulse;
            }
        }
    }, null, this);

    // add character count display
    // this.countText = this.add.text(10, 10, '', { font: '20px Arial', fill: '#D9D9D9' }).setDepth(10);

    // Initial call to process queue if needed (e.g., for items added before Pusher connects or from localStorage)
    tryProcessFishQueue.call(this);
}

function setupCanvas() {
    this.video = this.add.video(0, 0, "aquarium").setOrigin(0, 0);
    resizeGame.call(this);
    this.video.play(true);
}

// spawn a fish with dynamic sprite and optional name
// Modified signature to include onFishSpawnedCallback (original callback) and onEntryCompleteCallback
function addFish({ spriteKey, spriteUrl, frameWidth, frameHeight, name = null, type = null, isNewFromPusher = false, savedState = null } = {}, onFishSpawnedCallback, onEntryCompleteCallback) {
    if (!this.entities) { this.entities = this.add.group(); }

    let effectiveTextureKey;
    let effectiveAnimationKey;
    const isDynamicSprite = !!spriteUrl;

    if (isDynamicSprite) {
        // For dynamically loaded sprites (e.g., from Pusher)
        // Create a unique texture key from the spriteUrl to avoid caching issues.
        // Using a simplified approach by taking the last part of the URL (filename) and sanitizing it.
        const filename = spriteUrl.substring(spriteUrl.lastIndexOf('/') + 1).replace(/[^a-zA-Z0-9_.-]/g, '_');
        effectiveTextureKey = `dyn_${spriteKey}_${filename}`; // e.g., dyn_fish_unique_sprite.webp
        effectiveAnimationKey = `${effectiveTextureKey}_anim`; // e.g., dyn_fish_unique_sprite.webp_anim
    } else {
        // For preloaded sprites (default 'fish' or 'tempCharacter' variants)
        effectiveTextureKey = spriteKey; // e.g., 'fish', 'tempCharacter', 'tempCharacter2'
        // Determine animationKey based on existing conventions for preloaded sprites
        if (effectiveTextureKey === 'fish') {
            effectiveAnimationKey = 'fishSwim';
        } else if (effectiveTextureKey === 'tempCharacter') {
            effectiveAnimationKey = 'idle';
        } else if (effectiveTextureKey === 'tempCharacter2') {
            effectiveAnimationKey = 'idle2';
        } else if (effectiveTextureKey === 'tempCharacter3') {
            effectiveAnimationKey = 'idle3';
        } else if (effectiveTextureKey === 'tempCharacter4') {
            effectiveAnimationKey = 'idle4';
        } else if (effectiveTextureKey === 'tempCharacter5') {
            effectiveAnimationKey = 'idle5';
        } else {
            console.warn(`Unknown spriteKey for preloaded animation: ${effectiveTextureKey}`);
            effectiveAnimationKey = `${effectiveTextureKey}_default_anim`; // Fallback
        }
    }

    // This inner function contains the core logic for creating and setting up the fish/character.
    // It uses `effectiveTextureKey` and `effectiveAnimationKey` for assets,
    // but the original `spriteKey` (from addFish args) for behavioral decisions.
    const spawnFishEntity = () => {
        // Max character handling (uses original spriteKey to differentiate fish vs temp)
        if (!savedState && this.entities.getLength() >= MAX_TOTAL_CHARACTERS) { // Don't apply for rehydrated fish
            if (spriteKey === 'fish' || isDynamicSprite) { // Check original intent or if it's a dynamic fish
                // Find the oldest "fish" (first in the group that is a fish)
                const oldestFish = this.entities.getChildren().find(entity =>
                    entity.texture.key === 'fish' || entity.texture.key.startsWith('dyn_fish_') // Corrected: 'dyn_fish_'
                );
                if (oldestFish) {
                    console.log(`Max capacity (${MAX_TOTAL_CHARACTERS}) reached. Removing oldest fish ('${oldestFish.texture.key}') to make space for new fish.`);
                    if (oldestFish.bubble) {
                        oldestFish.bubble.bubble.destroy();
                        oldestFish.bubble.text.destroy();
                    }
                    this.entities.remove(oldestFish, true, true); // Remove from group and destroy
                    saveAquariumState.call(this); // Save state after removal
                } else {
                    // Max capacity reached, and all are tempCharacters (or no fish to remove)
                    console.log(`Max capacity (${MAX_TOTAL_CHARACTERS}) reached, but no removable fish found. New fish not added.`);
                    if (typeof onFishSpawnedCallback === 'function') onFishSpawnedCallback(null);
                    if (typeof onEntryCompleteCallback === 'function') onEntryCompleteCallback(null); // Ensure queue proceeds
                    return null; // Do not add the new fish
                }
            } else { // Trying to add a tempCharacter when at max capacity
                console.log(`Max capacity (${MAX_TOTAL_CHARACTERS}) reached. New temp character not added.`);
                if (typeof onFishSpawnedCallback === 'function') onFishSpawnedCallback(null);
                if (typeof onEntryCompleteCallback === 'function') onEntryCompleteCallback(null); // Ensure queue proceeds
                return null; // Do not add new temp character
            }
        }

        const scaleFactor = (spriteKey === 'fish') ? FISH_SCALE : TEMP_CHAR_SCALE; // Use new TEMP_CHAR_SCALE
        let startX, startY, targetX, targetY, initialActualScale, initialAngle;

        if (savedState) {
            initialActualScale = savedState.scale;
            initialAngle = savedState.angle;
            startX = savedState.x;
            startY = savedState.y;
            // No targetX/Y for saved fish, they are already placed.
        } else if (spriteKey === 'fish') { // Entry animation style based on original spriteKey
            const fishEntryScaleMultiplier = 1.5;
            initialActualScale = scaleFactor * fishEntryScaleMultiplier;
            initialAngle = Phaser.Math.Between(-15, 15); // Slight random tilt

            // Starting position for 'fish': Randomly from the right, slightly off-screen
            startX = window.innerWidth + (frameWidth * initialActualScale / 2);
            // Adjust Y to be random within a margin
            const marginY = (frameHeight * initialActualScale / 2) + 50; // Ensure fish is fully visible vertically
            startY = Phaser.Math.Between(marginY, window.innerHeight - marginY);


            // Target position for 'fish': Center of the screen
            targetX = window.innerWidth / 2;
            targetY = window.innerHeight / 2;
        } else { // For tempCharacters and any other non-'fish' entities
            initialActualScale = scaleFactor; // Start at their normal scale
            initialAngle = Phaser.Math.Between(-15, 15); // Slight random tilt for natural entry

            // Random off-screen start for temp characters
            const edge = Phaser.Math.Between(0, 3); // 0: top, 1: right, 2: bottom, 3: left
            // Calculate buffer based on the larger dimension of the sprite to ensure it's off-screen
            const spriteDimension = frameWidth > frameHeight ? frameWidth : frameHeight;
            const buffer = (spriteDimension * initialActualScale / 2) + 30; // 30px extra margin

            if (edge === 0) { // Top
                startX = Phaser.Math.Between(0, window.innerWidth);
                startY = -buffer;
            } else if (edge === 1) { // Right
                startX = window.innerWidth + buffer;
                startY = Phaser.Math.Between(0, window.innerHeight);
            } else if (edge === 2) { // Bottom
                startX = Phaser.Math.Between(0, window.innerWidth);
                startY = window.innerHeight + buffer;
            } else { // Left
                startX = -buffer;
                startY = Phaser.Math.Between(0, window.innerHeight);
            }

            // Random on-screen target for temp characters
            const margin = 100; // Keep them away from screen edges
            targetX = Phaser.Math.Between(margin, window.innerWidth - margin);
            targetY = Phaser.Math.Between(margin, window.innerHeight - margin);
        }

        const fish = this.physics.add.sprite(startX, startY, effectiveTextureKey); // Use unique texture key

        if (savedState) {
            fish.setAlpha(1); // Saved fish are already visible
            fish.setScale(savedState.scale);
            fish.setAngle(savedState.angle);
            fish.flipX = savedState.flipX;
            // Restore dynamic properties
            fish.baseSpeedMultiplier = savedState.baseSpeedMultiplier;
            fish.personalFloatSpeed = savedState.personalFloatSpeed;
            fish.personalFloatFrequency = savedState.personalFloatFrequency;
            fish.isDarting = savedState.isDarting;
            fish.dartTimer = savedState.dartTimer;
            fish.dartCooldown = savedState.dartCooldown;
            fish.floatTime = savedState.floatTime;
            fish.floatDirection = savedState.floatDirection;

            if (savedState.velocityX && savedState.velocityY) {
                 fish.body.setVelocity(savedState.velocityX, savedState.velocityY);
            }

        } else if (spriteKey === 'fish') {
            fish.setAlpha(0); // Real fish start transparent for their fade-in entry
        } else {
            fish.setAlpha(1); // Temp characters start fully opaque
        }

        fish.setScale(initialActualScale);
        fish.setAngle(initialAngle);
        fish.isSpinning = false; // Initialize isSpinning flag

        // Movement dynamics properties (only if not from savedState)
        if (!savedState) {
            fish.baseSpeedMultiplier = Phaser.Math.FloatBetween(0.85, 1.15); // Slight variation in base speed
            fish.personalFloatSpeed = FLOAT_SPEED * Phaser.Math.FloatBetween(0.7, 1.3);
            fish.personalFloatFrequency = FLOAT_FREQUENCY * Phaser.Math.FloatBetween(0.8, 1.2);
            fish.isDarting = false;
            fish.dartTimer = 0;
            fish.dartCooldown = Phaser.Math.FloatBetween(1.0, DART_COOLDOWN_MAX / 2);
            fish.floatTime = 0;
            fish.floatDirection = Math.random() < 0.5 ? 1 : -1;
            fish.isPerformingEntryAnimation = false; // Initialize the flag
        }

        // Adjust physics body size and offset to better match visible sprite
        // frameWidth and frameHeight are arguments to addFish, representing the original sprite frame dimensions
        let bodyWidthPercent = 0.7;  // Default percentage of original frame dimension for body size
        let bodyHeightPercent = 0.7;

        if (spriteKey === 'fish') {
            // Fish sprites might be wider than tall and have more transparent area
            bodyWidthPercent = 0.50; // Body will be 50% of the frame's width
            bodyHeightPercent = 0.30; // Body will be 30% of the frame's height
        } else { // For tempCharacters
            // Assuming tempCharacters are more uniform
            bodyWidthPercent = 0.60; // Body will be 60% of the frame's width
            bodyHeightPercent = 0.60; // Body will be 60% of the frame's height
        }

        const newBodyWidth = frameWidth * bodyWidthPercent;
        const newBodyHeight = frameHeight * bodyHeightPercent;

        // Calculate offsets to center the new smaller body within the original (unscaled) frame
        const offsetX = (frameWidth - newBodyWidth) / 2;
        const offsetY = (frameHeight - newBodyHeight) / 2;

        fish.body.setSize(newBodyWidth, newBodyHeight);
        fish.body.setOffset(offsetX, offsetY);

        // play animation based on key
        // For dynamic sprites, animation is created in the loading block if texture is new.
        // For preloaded, it should exist.
        if (this.anims.exists(effectiveAnimationKey)) {
            fish.play(effectiveAnimationKey);
        } else {
            // This block primarily handles the case where a dynamic sprite's animation needs creation
            // if spawnFishEntity is called when the texture already exists but anim doesn't (e.g. after a reload)
            if (isDynamicSprite) {
                this.anims.create({
                    key: effectiveAnimationKey,
                    frames: this.anims.generateFrameNumbers(effectiveTextureKey, { start: 0, end: FISH_FRAME_COUNT - 1 }),
                    frameRate: FRAME_RATE_NORMAL,
                    repeat: -1
                });
                fish.play(effectiveAnimationKey);
            } else {
                console.error(`Animation ${effectiveAnimationKey} not found for preloaded sprite ${effectiveTextureKey}`);
            }
        }

        // Apply tweens based on original spriteKey (only if not from savedState or if it's a new fish from Pusher)
        if (!savedState && (isNewFromPusher || spriteKey !== 'fish')) { // Apply entry for new pusher fish or temp chars
            if (spriteKey === 'fish') {
                this.isRealFishEntering = true; // Set flag for 'real' fish entry
                fish.isPerformingEntryAnimation = true;
                fish.body.enable = false; // Disable physics body during tween-controlled entry

                // Ensure fish is rendered on top of the bubble
                fish.setDepth(11); // Higher depth for fish

                // Create the entry bubble
                const entryBubble = this.add.sprite(fish.x, fish.y, ENTRY_BUBBLE_SPRITESHEET_KEY);
                entryBubble.setDepth(10); // Lower depth for bubble
                entryBubble.setAlpha(0.8); // DEBUG: Make it slightly transparent & ensure alpha > 0
                entryBubble.setFrame(0); // Show the first frame (intact bubble)

                // Define intermediate points for dynamic movement
                const screenCenterX = window.innerWidth / 2;
                const screenCenterY = window.innerHeight / 2;
                const wanderRange = 120; // How far to wander from the center
                // initialActualScale is the starting scale (e.g., scaleFactor * 1.5)
                // scaleFactor is the final target scale for the fish

                // Stage 1 target: move towards a random point near center, partially scale down
                const intermediateX1 = Phaser.Math.Between(screenCenterX - wanderRange, screenCenterX + wanderRange);
                const intermediateY1 = Phaser.Math.Between(screenCenterY - wanderRange / 1.5, screenCenterY + wanderRange / 1.5);
                const scaleStage1 = (initialActualScale + scaleFactor) / 2; // Midway scale

                // Stage 2 target: move to another random point near center, scale to final size
                const intermediateX2 = Phaser.Math.Between(screenCenterX - wanderRange / 1.5, screenCenterX + wanderRange / 1.5);
                const intermediateY2 = Phaser.Math.Between(screenCenterY - wanderRange, screenCenterY + wanderRange);

                const entryDurationStage1 = 3000; // Duration for the first part of the movement
                const entryDurationStage2 = 5000; // Duration for the second part of the movement
                const bubbleStaticHoldDuration = 5000; // Total time fish moves before bubble pop sequence starts
                                                    // This is separate from the tweens' duration.
                                                    // The bubble pop animation itself takes 3s (30frames / 10fps)

                // Tween for Stage 1 movement
                this.tweens.add({
                    targets: fish,
                    x: intermediateX1,
                    y: intermediateY1,
                    alpha: 1, // Fade in during the first stage
                    scale: scaleStage1,
                    angle: Phaser.Math.Between(-12, 12), // Initial turn
                    duration: entryDurationStage1,
                    ease: 'Sine.InOut',
                    onUpdate: () => {
                        if (entryBubble.active) {
                            entryBubble.setPosition(fish.x, fish.y);
                            entryBubble.setScale(fish.scale * 1.05); // Bubble scales with fish
                        }
                    },
                    onComplete: () => {
                        // Tween for Stage 2 movement
                        this.tweens.add({
                            targets: fish,
                            x: intermediateX2,
                            y: intermediateY2,
                            scale: scaleFactor, // Arrive at final scale
                            angle: 0, // Straighten out
                            duration: entryDurationStage2,
                            ease: 'Sine.InOut',
                            onUpdate: () => {
                                if (entryBubble.active) {
                                    entryBubble.setPosition(fish.x, fish.y);
                                    entryBubble.setScale(fish.scale * 1.05);
                                }
                            },
                            onComplete: () => {
                                // Fish movement is complete. Now hold the static bubble, then play pop animation.
                                // The total movement time is entryDurationStage1 + entryDurationStage2.
                                // The bubble pop animation is triggered by a timer that starts when fish is created.
                                // The original delayedCall for bubble pop is 3000ms. We need to ensure it fires *after* these movements.
                                // Let's adjust the bubble pop trigger logic slightly.
                                // The bubble pop animation will be triggered by the entryBubble.on('animationcomplete') of a *dummy* short animation
                                // or simply by the main delayedCall if we ensure its timing is correct.

                                // The existing delayedCall for bubble pop is: this.time.delayedCall(3000, ...)
                                // This should remain, as it dictates when the *pop sequence starts* relative to bubble creation.
                                // The fish movement tweens (1.5s + 1.5s = 3s) will happen concurrently with this timer.
                                // So, the bubble will show its first frame for 3s, during which the fish moves, then the pop animation plays.
                            }
                        });
                    }
                });

                // This delayedCall initiates the bubble pop animation sequence after 3 seconds
                // The fish completes its 2-stage movement within these 3 seconds.
                this.time.delayedCall(bubbleStaticHoldDuration, () => {
                    if (entryBubble.active) {
                        entryBubble.play(ENTRY_BUBBLE_ANIM_KEY);
                    }
                }, [], this);

                // This onComplete is for the BUBBLE's pop animation
                entryBubble.on('animationcomplete', () => {
                    if (entryBubble.active) {
                        entryBubble.destroy();
                    }

                    // Fish finalization logic, happens after bubble pops
                    if (fish.active) {
                        fish.body.enable = true;
                        const baseSpeed = FISH_SPEED * fish.baseSpeedMultiplier;
                        const initialBodyAngle = Phaser.Math.Angle.Random();
                        fish.body.setVelocity(Math.cos(initialBodyAngle) * baseSpeed, Math.sin(initialBodyAngle) * baseSpeed);
                        fish.isPerformingEntryAnimation = false;
                    }

                    this.isRealFishEntering = false;
                    saveAquariumState.call(this);
                    tryProcessFishQueue.call(this);

                    if (typeof onEntryCompleteCallback === 'function') {
                        onEntryCompleteCallback(fish.active ? fish : null);
                    }

                    if (fish.active && name && name.trim().length > 0 && type === 'dj') {
                        if (!fish.bubble && !savedState) {
                            createNameBubble.call(this, fish, name);
                            saveAquariumState.call(this);
                        }
                    }
                });
                // NOTE: The original single large tween for fish entry is now replaced by the two-stage tween above.
            } else { // For tempCharacters (original spriteKey starts with 'tempCharacter')
                // Entry tween: move from off-screen start to in-frame target, and adjust angle (alpha removed)
                const movementTween = this.tweens.add({
                    targets: fish,
                    x: targetX,
                    y: targetY,
                    // alpha: 1, // REMOVED - Temp characters no longer fade in via this tween
                    angle: 0, // Straighten out as it arrives
                    duration: 3000, // Gentler arrival duration
                    ease: 'Sine.InOut' // Smoother easing for movement and angle
                });

                // Scale pop animation: scale up then back to normal - made more subtle
                this.tweens.add({
                    targets: fish,
                    scale: initialActualScale * 1.2, // More pronounced pop (Increased from 1.1)
                    duration: 700, // Slightly longer duration for the pop (Increased from 500)
                    ease: 'Sine.easeOut', // Smoother, less bouncy pop
                    yoyo: true,
                    delay: 2300 // Start pop as movement nears completion (Adjusted from 1500 due to increased movement duration)
                });

                if (typeof onEntryCompleteCallback === 'function') {
                    if (movementTween) {
                        movementTween.on('complete', () => {
                            // It's possible the scale pop tween is still running.
                            // A small delay can help ensure the entity is in a more stable state.
                            this.time.delayedCall(200, () => { // Small delay for other tweens to settle
                               if (fish && fish.active) onEntryCompleteCallback(fish);
                               else onEntryCompleteCallback(null); // Pass null if fish became inactive
                               saveAquariumState.call(this); // Save state after entry
                            });
                        });
                    } else {
                        // Fallback if movementTween somehow isn't defined (should not happen)
                        // Call after the expected duration of movement + pop
                        this.time.delayedCall(3000 + 700, () => {
                            if (fish && fish.active) onEntryCompleteCallback(fish);
                            else onEntryCompleteCallback(null);
                            saveAquariumState.call(this); // Save state after entry
                        });
                    }
                }
            }
        } else if (savedState) { // Fish loaded from storage, no full entry animation
             fish.isPerformingEntryAnimation = false; // Ensure flag is false for restored fish
             if (typeof onEntryCompleteCallback === 'function') {
                onEntryCompleteCallback(fish); // Call immediately
            }
        }

        // movement properties (based on original spriteKey) - only if not from savedState and no velocity set yet
        if (!savedState && (!fish.body || (fish.body.velocity.x === 0 && fish.body.velocity.y === 0))) {
            if (spriteKey === 'fish') {
                fish.vx = Phaser.Math.FloatBetween(-FISH_SPEED, FISH_SPEED); // These are legacy, physics body velocity is prime
                fish.vy = Phaser.Math.FloatBetween(-FISH_SPEED, FISH_SPEED);
            } else {
                fish.vx = Phaser.Math.FloatBetween(-TEMP_SPEED, TEMP_SPEED);
                fish.vy = Phaser.Math.FloatBetween(-TEMP_SPEED, TEMP_SPEED);
            }
        }
        if (!savedState) { // Only set for new fish
            fish.floatTime = 0;
            fish.floatDirection = Math.random() < 0.5 ? 1 : -1;
        }
        // no spin effect, keep horizontal orientation
        // attach bubble only if name provided and type is 'dj'
        if (name && name.trim().length > 0 && type === 'dj') {
            // Replace circle with the loaded bubble image
            const bubbleImage = this.add.image(fish.x + BUBBLE_OFFSET_X, fish.y + BUBBLE_OFFSET_Y, 'bubble');
            bubbleImage.setScale(0.5);
            // It might be necessary to scale the bubble image if its original size is not appropriate
            // bubbleImage.setScale(0.5); // Example: scale to 50%
            // Adjust BUBBLE_RADIUS or use image dimensions for text centering if needed

            const textObj = this.add.text(bubbleImage.x, bubbleImage.y, name.trim(), {   fontSize: '25px',
                fontFamily: 'Arial',
                fontStyle: 'bold',
                fill: '#14477A' })
                .setOrigin(0.5).setDepth(1);
            fish.bubble = { bubble: bubbleImage, text: textObj }; // Store image as bubble
        }
        this.entities.add(fish);
        // enable arcade physics bounce and collision
        fish.body.setCollideWorldBounds(true);
        fish.body.setBounce(0.92); // Increased bounce for stronger push (was 0.85, then 0.6)

        // apply initial velocity from vx/vy, now incorporating baseSpeedMultiplier (only if not from savedState and no velocity set)
        if (!savedState && fish.body && (fish.body.velocity.x === 0 && fish.body.velocity.y === 0) && !fish.isPerformingEntryAnimation) {
            const baseSpeed = (spriteKey === 'fish' ? FISH_SPEED : TEMP_SPEED) * fish.baseSpeedMultiplier;
            const initialBodyAngle = Phaser.Math.Angle.Random(); // Renamed to avoid conflict with fish.angle
            fish.body.setVelocity(Math.cos(initialBodyAngle) * baseSpeed, Math.sin(initialBodyAngle) * baseSpeed);
        }

        if (name && spriteKey === 'fish') { // Emit event if it's a named fish (typically dynamic ones)
            this.events.emit('fishAdded', fish);
        }
        if (typeof onFishSpawnedCallback === 'function') onFishSpawnedCallback(fish);
        if (!savedState) saveAquariumState.call(this); // Save state when a new fish is fully added
        return fish;
    };
    // dynamically load asset if needed
    if (isDynamicSprite && !this.textures.exists(effectiveTextureKey)) {
        // load uploaded sprite sheet for dynamic fish
        this.load.spritesheet(effectiveTextureKey, spriteUrl, { frameWidth, frameHeight });
        this.load.once('complete', () => {
            // Create animation for the newly loaded dynamic spritesheet
            if (!this.anims.exists(effectiveAnimationKey)) {
                this.anims.create({
                    key: effectiveAnimationKey,
                    frames: this.anims.generateFrameNumbers(effectiveTextureKey, { start: 0, end: FISH_FRAME_COUNT - 1 }),
                    frameRate: FRAME_RATE_NORMAL,
                    repeat: -1
                });
            }
            spawnFishEntity(); // Call the main spawning logic, which will use the callbacks passed to addFish
        }, this);
        this.load.start();
        return; // Async path, function returns undefined here
    }
    // texture exists (preloaded, or dynamic and already loaded from a previous call/session cache):
    // or if it's a preloaded sprite (isDynamicSprite is false)
    if (!isDynamicSprite && !this.textures.exists(effectiveTextureKey)){
        console.error(`Error: Preloaded texture ${effectiveTextureKey} for ${spriteKey} not found.`);
        if (typeof onFishSpawnedCallback === 'function') onFishSpawnedCallback(null);
        if (typeof onEntryCompleteCallback === 'function') {
            onEntryCompleteCallback(null); // Ensure queue proceeds
            saveAquariumState.call(this); // Save queue state even if fish failed
        }
        return null;
    }

    // If it's a dynamic texture that already exists, its animation might also need to be created
    // if it wasn't (e.g. texture cached by browser but JS state lost on reload, then anims re-registered)
    if (isDynamicSprite && !this.anims.exists(effectiveAnimationKey)) {
        this.anims.create({
            key: effectiveAnimationKey,
            frames: this.anims.generateFrameNumbers(effectiveTextureKey, { start: 0, end: FISH_FRAME_COUNT - 1 }),
            frameRate: FRAME_RATE_NORMAL,
            repeat: -1
        });
    }
    return spawnFishEntity(); // Synchronous path
}

// helper: move sprite by velocity and bounce off edges
function handleMovement(sprite, dt) {
    sprite.x += sprite.vx * dt;
    sprite.y += sprite.vy * dt;
    const min = 50, maxX = window.innerWidth - 50, maxY = window.innerHeight - 50;
    if (sprite.x < min) { sprite.x = min; sprite.vx = Math.abs(sprite.vx); }
    if (sprite.x > maxX) { sprite.x = maxX; sprite.vx = -Math.abs(sprite.vx); }
    if (sprite.y < min) { sprite.y = min; sprite.vy = Math.abs(sprite.vy); }
    if (sprite.y > maxY) { sprite.y = maxY; sprite.vy = -Math.abs(sprite.vy); }
}

function update(time, delta) {
    const dt = delta / 1000; // delta is in ms, convert to seconds for timers and rates
    const moveFactor = dt; // use direct dt since vx/vy are in pixels/sec

    const numEntities = this.entities ? this.entities.getLength() : 0;
    // this.countText.setText(`Count: ${numEntities}`);

    // update entities group only when there are entities
    if (this.entities && this.entities.getChildren().length > 0) {
        this.entities.getChildren().forEach((entity) => {
            // update bubble position if exists
            if (entity.bubble) {
                // Update position for both bubble image and text
                entity.bubble.bubble.setPosition(
                    entity.x + BUBBLE_OFFSET_X,
                    entity.y + BUBBLE_OFFSET_Y
                );
                entity.bubble.text.setPosition(
                    entity.x + BUBBLE_OFFSET_X, // Text is centered on the bubble image
                    entity.y + BUBBLE_OFFSET_Y
                );
            }
            // New movement logic
            if (entity.isDarting) {
                entity.dartTimer -= dt;
                if (entity.dartTimer <= 0) {
                    entity.isDarting = false;
                    // Restore a base cruising speed after darting
                    const baseSpeedAfterDart = (entity.texture.key.startsWith('dyn_fish_') || entity.texture.key === 'fish' ? FISH_SPEED : TEMP_SPEED) * entity.baseSpeedMultiplier;
                    if (entity.body.velocity.lengthSq() > 0) { // lengthSq for efficiency
                        entity.body.velocity.normalize().scale(baseSpeedAfterDart);
                    } else {
                        const newAngle = Phaser.Math.Angle.Random();
                        entity.body.velocity.setTo(Math.cos(newAngle) * baseSpeedAfterDart, Math.sin(newAngle) * baseSpeedAfterDart);
                    }
                }
                // Velocity for darting is already set. Physics engine handles movement.
            } else { // Not currently darting
                entity.dartCooldown -= dt;
                // Check if it's time to consider darting (and not currently spinning from a collision)
                if (entity.dartCooldown <= 0 && !entity.isSpinning && Math.random() < DART_CHANCE_PER_SECOND * dt) {
                    entity.isDarting = true;
                    entity.dartTimer = DART_DURATION;
                    entity.dartCooldown = Phaser.Math.FloatBetween(DART_COOLDOWN_MIN, DART_COOLDOWN_MAX);

                    const dartAngle = Phaser.Math.Angle.Random(); // Could also use entity.body.velocity.angle() to dart forward
                    const dartSpeedValue = (entity.texture.key.startsWith('dyn_fish_') || entity.texture.key === 'fish' ? FISH_SPEED : TEMP_SPEED) * entity.baseSpeedMultiplier * DART_SPEED_MULTIPLIER;
                    entity.body.velocity.setTo(Math.cos(dartAngle) * dartSpeedValue, Math.sin(dartAngle) * dartSpeedValue);
                } else if (!entity.isSpinning) { // Apply normal floating only if not darting and not spinning
                    entity.floatTime += dt; // floatTime is an existing property
                    const floatX = Math.cos(entity.floatTime * entity.personalFloatFrequency) * entity.personalFloatSpeed * entity.floatDirection;
                    const floatY = Math.sin(entity.floatTime * entity.personalFloatFrequency) * entity.personalFloatSpeed * entity.floatDirection;

                    entity.body.velocity.x += floatX;
                    entity.body.velocity.y += floatY;

                    // Clamp velocity to a max normal speed
                    const maxNormalSpeed = (entity.texture.key.startsWith('dyn_fish_') || entity.texture.key === 'fish' ? FISH_SPEED : TEMP_SPEED) * entity.baseSpeedMultiplier * MAX_NORMAL_SPEED_FACTOR;
                    if (entity.body.velocity.lengthSq() > maxNormalSpeed * maxNormalSpeed) {
                        entity.body.velocity.normalize().scale(maxNormalSpeed);
                    }
                }
            }

            // flip sprite based on current velocity (ensure this is after all velocity modifications)
            if (!entity.isSpinning) { // Don't flip if spinning, let the spin animation control orientation
                 entity.flipX = entity.body.velocity.x < 0;
            }
        });
    }
}

function resizeGame() {
    const winWidth = window.innerWidth;
    const winHeight = window.innerHeight;
    this.scale.resize(winWidth, winHeight);
    const origWidth = 1920;
    const origHeight = 1080;
    const scaleFactor = Math.max(winWidth / origWidth, winHeight / origHeight);
    this.video.setDisplaySize(
        origWidth * scaleFactor,
        origHeight * scaleFactor
    );
}

function handleCollisionSpin(entity, scene) {
    if (!entity || !entity.active || entity.isSpinning) return; // Don't spin if already spinning or inactive

    entity.isSpinning = true;

    const isRealFish = entity.texture.key === 'fish' || entity.texture.key.startsWith('dyn_fish_');
    if (isRealFish && entity.alpha < 1) {
        // Stop any tweens that might be controlling the alpha property.
        scene.tweens.killTweensOf(entity, ['alpha']);
        // Set alpha to 1 to ensure the fish is fully visible.
        entity.setAlpha(1);
        entity.scale = FISH_SCALE;
    }

    scene.tweens.killTweensOf(entity, ['angle']); // Stop any ongoing angle tweens

    const spinAmount = Phaser.Math.Between(120, 240) * (Math.random() < 0.5 ? 1 : -1); // Spin amount
    const currentAngle = entity.angle;

    scene.tweens.add({
        targets: entity,
        angle: currentAngle + spinAmount,
        duration: 600, // Duration of the spin (Increased from 300ms)
        ease: 'Power1',
        onComplete: () => {
            if (entity && entity.active) { // Check if entity still exists and is active
                scene.tweens.add({
                    targets: entity,
                    angle: 0, // Rotate back to normal orientation
                    duration: 1800, // Slower recovery duration
                    ease: 'Sine.Out',
                    onComplete: () => {
                        if (entity && entity.active) {
                            entity.isSpinning = false;
                        }
                    }
                });
            } else {
                // Entity might have been destroyed during the spin
                if (entity) {
                    entity.isSpinning = false;
                }
            }
        }
    });
}

// New function to process the fish queue
function tryProcessFishQueue() {
    if (!this.fishQueue || this.fishQueue.length === 0) {
        // console.log("Queue is empty or not initialized.");
        return;
    }

    if (!this.entities) {
        // console.log("Entities group not initialized yet, cannot process queue.");
        return;
    }

    const nextFishData = this.fishQueue[0]; // Peek at the next fish
    const isNextFishRealType = nextFishData.spriteKey === 'fish' || !!nextFishData.spriteUrl;
    const isAquariumFull = this.entities.getLength() >= MAX_TOTAL_CHARACTERS;

    // Scenario 1: Aquarium is NOT full
    if (!isAquariumFull) {
        if (!isNextFishRealType) { // Next is a TEMP fish
            // Spawn the TEMP fish (temp fish don't care about isRealFishEntering flag)
            const fishDataToSpawn = this.fishQueue.shift(); // Dequeue
            // console.log(`Spawning TEMP fish '${fishDataToSpawn.spriteKey}' (aquarium not full). Queue: ${this.fishQueue.length}`);
            addFish.call(this,
                fishDataToSpawn,
                null, // onFishSpawnedCallback
                (spawnedFish) => { // onEntryCompleteCallback
                    tryProcessFishQueue.call(this); // Attempt to process next in queue
                }
            );
        } else { // Next is a REAL fish
            if (!this.isRealFishEntering) { // And no other real fish is currently entering
                // Spawn the REAL fish
                const fishDataToSpawn = this.fishQueue.shift(); // Dequeue
                // console.log(`Spawning REAL fish '${fishDataToSpawn.name}' (aquarium not full, no other real fish entering). Queue: ${this.fishQueue.length}`);
                addFish.call(this,
                    fishDataToSpawn,
                    null, // onFishSpawnedCallback
                    (spawnedFish) => { // onEntryCompleteCallback
                        // isRealFishEntering is handled by addFish's animation onComplete
                        tryProcessFishQueue.call(this); // Attempt to process next in queue
                    }
                );
            } else {
                // HOLD: Aquarium not full, next is REAL, but another REAL fish is currently entering.
                // console.log(`Holding queue (not full): Next is REAL ('${nextFishData.name}'), but a REAL fish is already entering. Queue: ${this.fishQueue.length}`);
            }
        }
    }
    // Scenario 2: Aquarium IS full
    else { // isAquariumFull is true
        if (!isNextFishRealType) { // Next is a TEMP fish
            // Discard the TEMP fish (aquarium is full, temp fish don't replace)
            const discardedTempFish = this.fishQueue.shift(); // Dequeue and discard
            saveAquariumState.call(this); // Save queue after discarding
            // console.log(`Aquarium full. Discarding TEMP fish from queue: ${discardedTempFish.spriteKey}. Queue: ${this.fishQueue.length}`);
            tryProcessFishQueue.call(this); // Attempt to process the next item immediately
        } else { // Next is a REAL fish
            if (!this.isRealFishEntering) { // And no other real fish is currently entering
                // Attempt to spawn the REAL fish (addFish will handle replacement logic)
                const fishDataToSpawn = this.fishQueue.shift(); // Dequeue
                // console.log(`Spawning REAL fish '${fishDataToSpawn.name}' (aquarium full, attempting replacement, no other real fish entering). Queue: ${this.fishQueue.length}`);
                addFish.call(this,
                    fishDataToSpawn,
                    null, // onFishSpawnedCallback
                    (spawnedFish) => { // onEntryCompleteCallback
                        // isRealFishEntering is handled by addFish's animation onComplete
                        tryProcessFishQueue.call(this); // Attempt to process next in queue
                    }
                );
            } else {
                // HOLD: Aquarium full, next is REAL, but another REAL fish is currently entering.
                // console.log(`Holding queue (full): Next is REAL ('${nextFishData.name}'), but a REAL fish is already entering. Queue: ${this.fishQueue.length}`);
            }
        }
    }
}

// Local Storage Functions
const AQUARIUM_STATE_KEY = 'aquariumState';

function saveAquariumState() {
    if (!this.entities) return;

    const simplifiedEntities = this.entities.getChildren().map(entity => {
        if (!entity || !entity.texture) return null; // Skip if entity is invalid
        return {
            spriteKey: entity.texture.key.startsWith('dyn_') ? (entity.texture.key.includes('_fish_') ? 'fish' : 'unknown_dyn') : entity.texture.key, // Store original intent
            spriteUrl: entity.texture.key.startsWith('dyn_') ? entity.texture.url : null, // Assuming texture.url holds it for dynamic ones
            frameWidth: entity.frame ? entity.frame.cutWidth : (entity.texture.key === 'fish' || entity.texture.key.startsWith('dyn_fish_') ? FISH_FRAME_WIDTH : TEMP_CHAR_FRAME_WIDTH),
            frameHeight: entity.frame ? entity.frame.cutHeight : (entity.texture.key === 'fish' || entity.texture.key.startsWith('dyn_fish_') ? FISH_FRAME_HEIGHT : TEMP_CHAR_FRAME_HEIGHT),
            name: entity.bubble ? entity.bubble.text.text : null,
            type: entity.bubble ? (entity.bubble.text.text.includes("DJ") ? "dj" : null) : null, // This is a guess, adjust if type is stored elsewhere
            x: entity.x,
            y: entity.y,
            scale: entity.scale,
            angle: entity.angle,
            flipX: entity.flipX,
            velocityX: entity.body ? entity.body.velocity.x : 0,
            velocityY: entity.body ? entity.body.velocity.y : 0,
            // Movement dynamics
            baseSpeedMultiplier: entity.baseSpeedMultiplier,
            personalFloatSpeed: entity.personalFloatSpeed,
            personalFloatFrequency: entity.personalFloatFrequency,
            isDarting: entity.isDarting,
            dartTimer: entity.dartTimer,
            dartCooldown: entity.dartCooldown,
            floatTime: entity.floatTime,
            floatDirection: entity.floatDirection,
            // Store effectiveTextureKey to help with reloading dynamic sprites
            effectiveTextureKey: entity.texture.key
        };
    }).filter(e => e !== null);

    const state = {
        fishQueue: this.fishQueue,
        entities: simplifiedEntities
    };

    try {
        localStorage.setItem(AQUARIUM_STATE_KEY, JSON.stringify(state));
        // console.log('Aquarium state saved.');
    } catch (e) {
        console.error('Failed to save aquarium state:', e);
    }
}

function loadAquariumState() {
    try {
        const savedStateString = localStorage.getItem(AQUARIUM_STATE_KEY);
        if (savedStateString) {
            const savedState = JSON.parse(savedStateString);
            if (savedState.fishQueue) {
                this.fishQueue = savedState.fishQueue;
                // console.log('Fish queue loaded:', this.fishQueue.length);
            }
            if (savedState.entities && this.entities) {
                // console.log('Loading entities from storage:', savedState.entities.length);
                savedState.entities.forEach(entityData => {
                    if (entityData) {
                        // Need to ensure texture is available before creating sprite
                        const isDynamic = !!entityData.spriteUrl;
                        if (isDynamic && !this.textures.exists(entityData.effectiveTextureKey)) {
                            this.load.spritesheet(entityData.effectiveTextureKey, entityData.spriteUrl, { frameWidth: entityData.frameWidth, frameHeight: entityData.frameHeight });
                            this.load.once('complete', () => {
                                if (!this.anims.exists(`${entityData.effectiveTextureKey}_anim`)) {
                                     this.anims.create({
                                        key: `${entityData.effectiveTextureKey}_anim`,
                                        frames: this.anims.generateFrameNumbers(entityData.effectiveTextureKey, { start: 0, end: (entityData.spriteKey === 'fish' ? FISH_FRAME_COUNT : TEMP_FRAME_COUNT) - 1 }),
                                        frameRate: FRAME_RATE_NORMAL,
                                        repeat: -1
                                    });
                                }
                                addFish.call(this, { ...entityData, savedState: entityData }, null, null);
                            });
                            this.load.start();
                        } else {
                             // For preloaded or already loaded dynamic textures
                            addFish.call(this, { ...entityData, savedState: entityData }, null, null);
                        }
                    }
                });
            }
        }
    } catch (e) {
        console.error('Failed to load aquarium state:', e);
        this.fishQueue = []; // Reset queue on error
    }
}
