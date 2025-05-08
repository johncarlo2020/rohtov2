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
    // spawn initial mix of five tempCharacter variants
    const tempKeys = ['tempCharacter','tempCharacter2','tempCharacter3','tempCharacter4','tempCharacter5'];
    for (let i = 0; i < tempKeys.length; i++) {
        const spriteKey = tempKeys[i % tempKeys.length];
        addFish.call(this, { spriteKey, frameWidth: 200, frameHeight: 200 });
    }
    this.time.addEvent({
        delay: 1000,
        loop: true,
        callback: () => {
            if (this.entities.getLength() < 5) {
                // randomly choose among five temp variants
                const randIndex = Phaser.Math.Between(0, tempKeys.length - 1);
                const key = tempKeys[randIndex];
                addFish.call(this, { spriteKey: key, frameWidth: 200, frameHeight: 200 });
            }
        }
    });
    // on real fish added, remove one tempCharacter to keep total constant
    this.events.on('fishAdded', reduceTempCharacters, this);
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
    // determine texture key: use dynamic key for uploaded sprite sheets
    const baseKey = spriteKey || 'fish';
    const key = spriteUrl ? `${baseKey}_dyn` : baseKey;
    const spawnFish = () => {
        // determine random in-frame target position
        const targetX = Phaser.Math.Between(50, window.innerWidth - 50);
        const targetY = Phaser.Math.Between(50, window.innerHeight - 50);
        // choose a random side to spawn from (0:left,1:right,2:top,3:bottom)
        let startX, startY;
        const side = Phaser.Math.Between(0, 3);
        const scaleFactor = spriteKey === 'fish' ? FISH_SCALE : 0.8;
        switch (side) {
            case 0: // left
                startX = -frameWidth * scaleFactor;
                startY = Phaser.Math.Between(50, window.innerHeight - 50);
                break;
            case 1: // right
                startX = window.innerWidth + frameWidth * scaleFactor;
                startY = Phaser.Math.Between(50, window.innerHeight - 50);
                break;
            case 2: // top
                startY = -frameHeight * scaleFactor;
                startX = Phaser.Math.Between(50, window.innerWidth - 50);
                break;
            default: // bottom
                startY = window.innerHeight + frameHeight * scaleFactor;
                startX = Phaser.Math.Between(50, window.innerWidth - 50);
                break;
        }
        // always use a physics-enabled sprite for fish
        const fish = this.physics.add.sprite(startX, startY, key)
            .setScale(scaleFactor);
        // play animation based on key
        if (spriteUrl) {
            // dynamic baby sprite sheet: create unique animation key if needed
            const animKey = `${key}_dyn`;
            if (!this.anims.exists(animKey)) {
                this.anims.create({
                    key: animKey,
                    frames: this.anims.generateFrameNumbers(key, { start: 0, end: FISH_FRAME_COUNT - 1 }),
                    frameRate: FRAME_RATE_NORMAL,
                    repeat: -1
                });
            }
            fish.play(animKey);
        } else if (key === 'fish') {
            fish.play('fishSwim');
        } else if (key === 'tempCharacter') {
            fish.play('idle');
        } else if (key === 'tempCharacter2') {
            fish.play('idle2');
        } else if (key === 'tempCharacter3') {
            fish.play('idle3');
        } else if (key === 'tempCharacter4') {
            fish.play('idle4');
        } else if (key === 'tempCharacter5') {
            fish.play('idle5');
        }
        // entry tween: move from off-screen start to in-frame target and fade in
        fish.alpha = 0;
        this.tweens.add({
            targets: fish,
            x: targetX,
            y: targetY,
            alpha: 1,
            duration: 1000,
            ease: 'Cubic.easeOut'
        });
        // scale pop animation: scale up then back to normal
        this.tweens.add({
            targets: fish,
            scale: scaleFactor * 1.2,
            duration: 300,
            ease: 'Back.easeOut',
            yoyo: true
        });
        // movement properties
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
        if (name) {
            this.events.emit('fishAdded', fish);
        }
        if (typeof callback === 'function') callback(fish);
        return fish;
    };
    // dynamically load asset if needed
    if (!this.textures.exists(key)) {
        // load uploaded sprite sheet for dynamic fish
        this.load.spritesheet(key, spriteUrl, { frameWidth, frameHeight });
        this.load.once('complete', spawnFish, this);
        this.load.start();
        return;
    }
    // texture exists: spawn immediately
    return spawnFish();
}

// spawn a single temp character at random position
function spawnTempChar() {
    const x = Phaser.Math.Between(50, window.innerWidth - 50);
    const y = Phaser.Math.Between(50, window.innerHeight - 50);
    const temp = this.physics.add.sprite(x, y, 'tempCharacter').setOrigin(0.5).setScale(0.3);
    temp.play('idle');
    // manual movement properties for consistent collision
    temp.vx = Phaser.Math.FloatBetween(-0.05, 0.05);
    temp.vy = Phaser.Math.FloatBetween(-0.05, 0.05);
    temp.floatTime = 0;
    temp.floatDirection = Math.random() < 0.5 ? 1 : -1;
    this.entities.add(temp);
    // enable arcade physics bounce and collision for manual temp spawn
    temp.body.setCollideWorldBounds(true);
    temp.body.setBounce(1);
    temp.body.setVelocity(temp.vx * TEMP_SPEED, temp.vy * TEMP_SPEED);
    return temp;
}

// remove one temp character from screen
function reduceTempCharacters() {
    if (this.entities.getLength() > 0) {
        // remove any tempCharacter variant
        const temp = this.entities.getChildren().find(c => c.texture.key.startsWith('tempCharacter'));
        if (temp) { this.entities.remove(temp, true, true); }
    }
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
