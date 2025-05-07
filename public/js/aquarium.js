import Phaser from "phaser";
import PlasmaPost2FX from "./PlasmaPost2FX";

const config = {
  parent: 'aquarium-container',
  type: Phaser.AUTO,
  width: window.innerWidth,
  height: window.innerHeight,
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

const game = new Phaser.Game(config);
const fishNames = ["Nemo", "Bub", "Fin", "Splash", "Marl", "Corl"];

// Adjustable Variables
const SPAWN_DELAY = 3000;
const FISH_SPEED = 8;
const FLOAT_SPEED = 0.05;
const COLLISION_PUSH_FORCE = 1.5;
const SPIN_TIME = 2000;
const SPIN_VELOCITY = 360;
const BUBBLE_OFFSET_X = 45;
const BUBBLE_OFFSET_Y = -25;
const BUBBLE_RADIUS = 30;
const MIN_COLLISION_DISTANCE = 80;
const STRETCH_FACTOR = 0.01;
const STRETCH_DURATION = 100;

function preload() {
  this.load.video("aquarium", "/images/hadalabobabies/aquarium.mp4");
  this.load.image("fish", "/src/images/drawing (2).png");
}

function create() {
    this.cameras.main.setPostPipeline(PlasmaPost2FX);
  setupCanvas.call(this);
  addFish.call(this);
  window.addEventListener("resize", () => resizeGame.call(this));
}

function setupCanvas() {
  this.video = this.add.video(0, 0, "aquarium").setOrigin(0, 0);
  resizeGame.call(this);
  this.video.play(true);
}

function addFish() {
  this.fishGroup = this.add.group();
  this.time.addEvent({
    delay: SPAWN_DELAY,
    callback: () => {
      const spawnX = Phaser.Math.Between(50, window.innerWidth - 50);
      const spawnY = Phaser.Math.Between(50, window.innerHeight - 50);
      const fish = this.add.sprite(spawnX, spawnY, "fish").setScale(0.3);
      fish.alpha = 0;
      this.tweens.add({ targets: fish, alpha: 1, duration: 1000, ease: "Linear" });

      fish.vx = Phaser.Math.FloatBetween(-0.1, 0.1);
      fish.vy = Phaser.Math.FloatBetween(-0.1, 0.1);
      fish.floatTime = 0;
      fish.floatDirection = Math.random() < 0.5 ? 1 : -1;
      fish.spinTime = 0;
      fish.angularVelocity = 0;

      const name = Phaser.Utils.Array.GetRandom(fishNames);
      const bubble = this.add.circle(spawnX + BUBBLE_OFFSET_X, spawnY + BUBBLE_OFFSET_Y, BUBBLE_RADIUS, 0x87ceeb, 0.5);
      const text = this.add.text(spawnX + BUBBLE_OFFSET_X, spawnY + BUBBLE_OFFSET_Y, name, {
        font: "18px Arial",
        fill: "#ffffff",
      }).setOrigin(0.5, 0.5).setDepth(1);

      fish.bubble = { bubble, text };

      this.fishGroup.add(fish);
    },
    loop: true,
  });
}

function update(time, delta) {
  const dt = delta / 1000;
  this.fishGroup.getChildren().forEach((fish) => {
    fish.x += fish.vx * dt * FISH_SPEED;
    fish.y += fish.vy * dt * FISH_SPEED;
    fish.bubble.bubble.setPosition(fish.x + BUBBLE_OFFSET_X, fish.y + BUBBLE_OFFSET_Y);
    fish.bubble.text.setPosition(fish.x + BUBBLE_OFFSET_X, fish.y + BUBBLE_OFFSET_Y);

    fish.floatTime += dt;
    fish.vy += Math.sin(fish.floatTime * 2) * FLOAT_SPEED * fish.floatDirection;
    fish.vx += Math.cos(fish.floatTime * 2) * FLOAT_SPEED * fish.floatDirection;

    // **Squash & Stretch Based on Movement**
    let speed = Math.sqrt(fish.vx * fish.vx + fish.vy * fish.vy);
    let stretchX = 1 + speed * STRETCH_FACTOR;
    let stretchY = 1 / stretchX;

    this.tweens.add({
      targets: fish,
      scaleX: stretchX * 0.3,
      scaleY: stretchY * 0.3,
      duration: STRETCH_DURATION,
      ease: "Sine.easeInOut",
    });

    // **Prevent fish from going off-screen**
    if (fish.x < 50) {
      fish.x = 50;
      fish.vx = Math.abs(fish.vx);
    }
    if (fish.x > window.innerWidth - 50) {
      fish.x = window.innerWidth - 50;
      fish.vx = -Math.abs(fish.vx);
    }
    if (fish.y < 50) {
      fish.y = 50;
      fish.vy = Math.abs(fish.vy);
    }
    if (fish.y > window.innerHeight - 50) {
      fish.y = window.innerHeight - 50;
      fish.vy = -Math.abs(fish.vy);
    }

    // **Collision Handling**
    this.fishGroup.getChildren().forEach((otherFish) => {
      if (fish !== otherFish) {
        const dist = Phaser.Math.Distance.Between(fish.x, fish.y, otherFish.x, otherFish.y);

        if (dist < MIN_COLLISION_DISTANCE) {
          const angle = Phaser.Math.Angle.Between(fish.x, fish.y, otherFish.x, otherFish.y);
          const pushForce = COLLISION_PUSH_FORCE * (1 - dist / MIN_COLLISION_DISTANCE);
          const repelX = Math.cos(angle) * pushForce;
          const repelY = Math.sin(angle) * pushForce;

          // Apply repulsion force
          fish.vx -= repelX;
          fish.vy -= repelY;
          otherFish.vx += repelX;
          otherFish.vy += repelY;

          // **Squash effect on collision**
          this.tweens.add({
            targets: fish,
            scaleX: 0.25,
            scaleY: 0.35,
            duration: 100,
            yoyo: true,
            ease: "Sine.easeInOut",
          });

          // **Spin Effect**
          fish.spinTime = SPIN_TIME;
          fish.angularVelocity = SPIN_VELOCITY * (Math.random() > 0.5 ? 1 : -1);
          otherFish.spinTime = SPIN_TIME;
          otherFish.angularVelocity = -SPIN_VELOCITY * (Math.random() > 0.5 ? 1 : -1);
        }
      }
    });

    // **Handle spin effect after collision**
    if (fish.spinTime > 0) {
      fish.spinTime -= delta;
      fish.angle += fish.angularVelocity * dt;
      if (fish.spinTime <= 0) {
        fish.spinTime = 0;
        fish.angularVelocity = 0;
      }
    }
  });
}

function resizeGame() {
  const winWidth = window.innerWidth;
  const winHeight = window.innerHeight;
  this.scale.resize(winWidth, winHeight);
  const origWidth = 1920;
  const origHeight = 1080;
  const scaleFactor = Math.max(winWidth / origWidth, winHeight / origHeight);
  this.video.setDisplaySize(origWidth * scaleFactor, origHeight * scaleFactor);
}
