import Phaser from "phaser";
import PlasmaPost2FX from "./PlasmaPost2FX";

const config = {
    parent: "aquarium-container",
    type: Phaser.AUTO,
    width: window.innerWidth,
    height: window.innerHeight,
    physics: {
        default: 'arcade',
        arcade: { gravity: { y: 0 }, debug: false }
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
const BUBBLE_OFFSET_X = 45;
const BUBBLE_OFFSET_Y = -25;
const BUBBLE_RADIUS = 30;
const MIN_COLLISION_DISTANCE = 80;
const STRETCH_FACTOR = 0.01;
const STRETCH_DURATION = 100;
// fish spritesheet settings
const FISH_FRAME_WIDTH = 800;
const FISH_FRAME_HEIGHT = 800;
// actual fish spritesheet has 7 frames
const FISH_FRAME_COUNT = 6;
// add a scale constant for fish size
const FISH_SCALE = 0.4; // scale for fish sprites
// tempCharacter spritesheet frame count (25000px width ÷ 200px frame = 125 frames)
const TEMP_FRAME_COUNT = 125;

const MAX_TOTAL_CHARACTERS = 10;
const NUM_DEFAULT_TEMP_CHARACTERS = 5;

function preload() {
    this.load.video("aquarium", `${ASSET}/images/hadalabobabies/Aqua HL v2.mp4`);
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
        { frameWidth: 400, frameHeight: 400, endFrame: TEMP_FRAME_COUNT - 1 }
    );
    // load second temp character variant
    this.load.spritesheet(
        "tempCharacter2",
        `${ASSET}/images/defaultBabies/2.webp`,
        { frameWidth: 400, frameHeight: 400, endFrame: TEMP_FRAME_COUNT - 1 }
    );
    // load additional temp character variants (3-5)
    this.load.spritesheet("tempCharacter3", `${ASSET}/images/defaultBabies/3.webp`, { frameWidth: 400, frameHeight: 400, endFrame: TEMP_FRAME_COUNT - 1 });
    this.load.spritesheet("tempCharacter4", `${ASSET}/images/defaultBabies/4.webp`, { frameWidth: 400, frameHeight: 400, endFrame: TEMP_FRAME_COUNT - 1 });
    this.load.spritesheet("tempCharacter5", `${ASSET}/images/defaultBabies/5.webp`, { frameWidth: 400, frameHeight: 400, endFrame: TEMP_FRAME_COUNT - 1 });
}

function create() {
    this.cameras.main.setPostPipeline(PlasmaPost2FX);
    setupCanvas.call(this);
    window.addEventListener("resize", () => resizeGame.call(this));

    // Pusher subscription inside scene to ensure correct context
    Pusher.logToConsole = true;
    const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        encrypted: true
    });
    const channel = pusher.subscribe('baby-channel');
    channel.bind('baby-event', (data) => {
        const imgUrl = `${ASSET}/${data.img}`;
        addFish.call(this, { spriteKey: 'fish', spriteUrl: imgUrl, frameWidth: FISH_FRAME_WIDTH, frameHeight: FISH_FRAME_HEIGHT, name: data.name });

        console.log(data.img);
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

    // unify all characters (temps & real fish) into one physics group
    this.entities = this.physics.add.group();
    // spawn initial mix of NUM_DEFAULT_TEMP_CHARACTERS tempCharacter variants
    const tempKeys = ['tempCharacter','tempCharacter2','tempCharacter3','tempCharacter4','tempCharacter5'];
    for (let i = 0; i < NUM_DEFAULT_TEMP_CHARACTERS; i++) {
        const spriteKey = tempKeys[i % tempKeys.length]; // Ensures different variants if NUM_DEFAULT_TEMP_CHARACTERS <= tempKeys.length
        addFish.call(this, { spriteKey, frameWidth: 200, frameHeight: 200 });
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
                addFish.call(this, { spriteKey: tempKeyToAdd, frameWidth: 200, frameHeight: 200 });
            }
        }
    });

    // collision among all entities
    this.physics.add.collider(this.entities, this.entities);

    // add character count display
    this.countText = this.add.text(10, 10, '', { font: '20px Arial', fill: '#ffffff' }).setDepth(10);
}

function setupCanvas() {
    this.video = this.add.video(0, 0, "aquarium").setOrigin(0, 0);
    resizeGame.call(this);
    this.video.play(true);
}

// spawn a fish with dynamic sprite and optional name
function addFish({ spriteKey, spriteUrl, frameWidth, frameHeight, name = null } = {}, callback) {
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
        if (this.entities.getLength() >= MAX_TOTAL_CHARACTERS) {
            if (spriteKey === 'fish' || isDynamicSprite) { // Check original intent or if it's a dynamic fish
                // Find the oldest "fish" (first in the group that is a fish)
                const oldestFish = this.entities.getChildren().find(entity =>
                    entity.texture.key === 'fish' || entity.texture.key.startsWith('fish_dyn')
                );
                if (oldestFish) {
                    console.log(`Max capacity (${MAX_TOTAL_CHARACTERS}) reached. Removing oldest fish ('${oldestFish.texture.key}') to make space for new fish.`);
                    this.entities.remove(oldestFish, true, true); // Remove from group and destroy
                } else {
                    // Max capacity reached, and all are tempCharacters (or no fish to remove)
                    console.log(`Max capacity (${MAX_TOTAL_CHARACTERS}) reached, but no removable fish found. New fish not added.`);
                    if (typeof callback === 'function') callback(null);
                    return null; // Do not add the new fish
                }
            } else { // Trying to add a tempCharacter when at max capacity
                console.log(`Max capacity (${MAX_TOTAL_CHARACTERS}) reached. New temp character not added.`);
                if (typeof callback === 'function') callback(null);
                return null; // Do not add new temp character
            }
        }

        const scaleFactor = (spriteKey === 'fish') ? FISH_SCALE : 0.8; // Behavior based on original spriteKey
        let startX, startY, targetX, targetY, initialActualScale, initialAngle;

        if (spriteKey === 'fish') { // Entry animation style based on original spriteKey
            const fishEntryScaleMultiplier = 1.5;
            initialActualScale = scaleFactor * fishEntryScaleMultiplier;
            initialAngle = Phaser.Math.Between(-15, 15); // Slight random tilt

            // Starting position for 'fish': Middle-right, slightly off-screen
            startX = window.innerWidth + (frameWidth * initialActualScale / 2); // Use actual initial scale for offset
            startY = window.innerHeight / 2; // Vertical middle

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
        fish.setAlpha(0);
        fish.setScale(initialActualScale);
        fish.setAngle(initialAngle);

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

        // Apply tweens based on original spriteKey
        if (spriteKey === 'fish') {
            // Main entry tween for 'fish' type entities
            this.tweens.add({
                targets: fish,
                x: targetX,
                y: targetY,
                alpha: 1,                               // Fade in
                scale: scaleFactor,                     // Animate to its final intended scale (downsizing)
                angle: 0,                               // Straighten out from the initial tilt
                duration: 3000,                         // Duration for the entry
                ease: 'Cubic.InOut',                    // Smooth acceleration and deceleration
                onComplete: () => {
                    // Optional: a very subtle "settle" animation once arrived
                    if (fish && fish.active) {
                        this.tweens.add({
                            targets: fish,
                            scale: scaleFactor * 1.03, // Very subtle bounce
                            duration: 300,
                            ease: 'Sine.easeInOut',
                            yoyo: true
                        });
                    }
                }
            });
        } else { // For tempCharacters (original spriteKey starts with 'tempCharacter')
            // Entry tween: move from off-screen start to in-frame target, fade in, and adjust angle
            this.tweens.add({
                targets: fish,
                x: targetX,
                y: targetY,
                alpha: 1,
                angle: 0, // Straighten out as it arrives
                duration: 2000, // Gentler arrival duration
                ease: 'Sine.InOut' // Smoother easing for movement, alpha, and angle
            });

            // Scale pop animation: scale up then back to normal - made more subtle
            this.tweens.add({
                targets: fish,
                scale: initialActualScale * 1.1, // Subtle pop (10% bigger from its starting/final scale)
                duration: 500, // Slightly longer duration for the pop
                ease: 'Sine.easeOut', // Smoother, less bouncy pop
                yoyo: true,
                delay: 1500 // Start pop as movement nears completion
            });
        }

        // movement properties (based on original spriteKey)
        if (spriteKey === 'fish') {
            fish.vx = Phaser.Math.FloatBetween(-FISH_SPEED, FISH_SPEED);
            fish.vy = Phaser.Math.FloatBetween(-FISH_SPEED, FISH_SPEED);
        } else {
            fish.vx = Phaser.Math.FloatBetween(-TEMP_SPEED, TEMP_SPEED);
            fish.vy = Phaser.Math.FloatBetween(-TEMP_SPEED, TEMP_SPEED);
        }
        fish.floatTime = 0;
        fish.floatDirection = Math.random() < 0.5 ? 1 : -1;
        // no spin effect, keep horizontal orientation
        // attach bubble only if name provided
        if (name && name.trim().length > 0) {
            // const bubble = this.add.circle(x + BUBBLE_OFFSET_X, y + BUBBLE_OFFSET_Y, BUBBLE_RADIUS, 0x87ceeb, 0.5);
            // const textObj = this.add.text(x + BUBBLE_OFFSET_X, y + BUBBLE_OFFSET_Y, name.trim(), { font: '18px Arial', fill: '#ffffff' })
            //     .setOrigin(0.5).setDepth(1);
            // fish.bubble = { bubble, text: textObj };
        }
        this.entities.add(fish);
        // enable arcade physics bounce and collision
        fish.body.setCollideWorldBounds(true);
        fish.body.setBounce(1);
        // apply initial velocity from vx/vy
        fish.body.setVelocity(fish.vx, fish.vy);
        if (name && spriteKey === 'fish') { // Emit event if it's a named fish (typically dynamic ones)
            this.events.emit('fishAdded', fish);
        }
        if (typeof callback === 'function') callback(fish);
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
            spawnFishEntity(); // Call the main spawning logic
        }, this);
        this.load.start();
        return; // Async path, function returns undefined here
    }
    // texture exists (preloaded, or dynamic and already loaded from a previous call/session cache):
    // or if it's a preloaded sprite (isDynamicSprite is false)
    if (!isDynamicSprite && !this.textures.exists(effectiveTextureKey)){
        console.error(`Error: Preloaded texture ${effectiveTextureKey} for ${spriteKey} not found.`);
        if (typeof callback === 'function') callback(null);
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
    const dt = delta / 1000;
    const moveFactor = dt; // use direct dt since vx/vy are in pixels/sec

    const numEntities = this.entities ? this.entities.getLength() : 0;
    this.countText.setText(`Count: ${numEntities}`);

    // update entities group only when there are entities
    if (this.entities && this.entities.getChildren().length > 0) {
        this.entities.getChildren().forEach((entity) => {
            // update bubble position if exists
            if (entity.bubble) {
                entity.bubble.bubble.setPosition(
                    entity.x + BUBBLE_OFFSET_X,
                    entity.y + BUBBLE_OFFSET_Y
                );
                entity.bubble.text.setPosition(
                    entity.x + BUBBLE_OFFSET_X,
                    entity.y + BUBBLE_OFFSET_Y
                );
            }
            // apply float oscillation velocity on top of physics
            entity.floatTime += dt;
            const floatX = Math.cos(entity.floatTime * FLOAT_FREQUENCY) * FLOAT_SPEED * entity.floatDirection;
            const floatY = Math.sin(entity.floatTime * FLOAT_FREQUENCY) * FLOAT_SPEED * entity.floatDirection;
            entity.body.velocity.x += floatX;
            entity.body.velocity.y += floatY;
            // flip sprite based on current velocity
            entity.flipX = entity.body.velocity.x < 0;
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
