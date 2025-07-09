import Phaser from "phaser";

const config = {
  parent: 'aquarium-container',
  type: Phaser.AUTO,
  width: window.innerWidth,
  height: window.innerHeight,
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

// Pledge data storage
let pledgeData = [];

// Adjustable Variables
const SPAWN_DELAY = 3000;
const NAME_BUBBLE_SPAWN_DELAY = 4000; // Different spawn rate for name bubbles
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

function preload() {
  // Add error handling for image loading
  this.load.on('loaderror', function(file) {
    console.error('Failed to load:', file.src);
  });  this.load.image("stick", "images/brand/coral-seperate/stick.webp");

  // Load bubble sprite sheet for entry animation
  this.load.spritesheet("bubble_anim", "images/brand/bubble-spritesheet.png", {
    frameWidth: 64,
    frameHeight: 64
  });

  // Load floating name bubble asset
  this.load.image("name_bubble", "images/brand/name-bubble.png");

  // Load all coral images
  for (let i = 1; i <= 6; i++) {
    this.load.image(`coral${i}`, `images/brand/coral-seperate/${i}.webp`);
  }

  // Fetch pledge data from server
  fetchPledgeData();
}

function create() {
  setupCanvas.call(this);

  // Create bubble animation
  this.anims.create({
    key: 'bubble_pop',
    frames: this.anims.generateFrameNumbers('bubble_anim', { start: 0, end: 7 }),
    frameRate: 12,
    repeat: 0
  });

  addCorals.call(this);
  addNameBubbles.call(this);
  window.addEventListener("resize", () => resizeGame.call(this));
}

function setupCanvas() {
  // No background image - will be handled by Blade template CSS
  // Phaser canvas will be transparent to show the CSS background
  resizeGame.call(this);
}

// Function to fetch pledge data from server
async function fetchPledgeData() {
  // Temporarily use sample data until API endpoint is created
  console.log('Using sample pledge data for testing');
  pledgeData = [
    { id: 1, name: "Alice", coralId: 1, type: "coral" },
    { id: 2, name: "Bob", coralId: 2, type: "coral" },
    { id: 3, name: "Charlie", coralId: 3, type: "coral" },
    { id: 4, text: "Save the Ocean", type: "text" },
    { id: 5, text: "Protect Marine Life", type: "text" },
    { id: 6, name: "Diana", coralId: 4, type: "coral" },
    { id: 7, text: "Ocean Conservation", type: "text" },
    { id: 8, name: "Eve", coralId: 5, type: "coral" },
    { id: 9, name: "Frank", coralId: 6, type: "coral" }
  ];

  // Uncomment this when API endpoint is ready:
  /*
  try {
    const response = await fetch('/api/pledges');
    if (response.ok) {
      pledgeData = await response.json();
      console.log('Loaded pledge data:', pledgeData);
    } else {
      // Fallback to sample data if API is not available
      pledgeData = [
        { id: 1, name: "Alice", coralId: 1, type: "coral" },
        { id: 2, name: "Bob", coralId: 2, type: "coral" },
        { id: 3, name: "Charlie", coralId: 3, type: "coral" },
        { id: 4, name: "Diana", coralId: 4, type: "coral" },
        { id: 5, name: "Eve", coralId: 5, type: "coral" },
        { id: 6, name: "Frank", coralId: 6, type: "coral" }
      ];
    }
  } catch (error) {
    console.log('Failed to fetch pledge data, using sample data:', error);
    // Sample pledge data for testing
    pledgeData = [
      { id: 1, name: "Alice", coralId: 1, type: "coral" },
      { id: 2, name: "Bob", coralId: 2, type: "coral" },
      { id: 3, name: "Charlie", coralId: 3, type: "coral" },
      { id: 4, name: "Diana", coralId: 4, type: "coral" },
      { id: 5, name: "Eve", coralId: 5, type: "coral" },
      { id: 6, name: "Frank", coralId: 6, type: "coral" }
    ];
  }
  */
}

function addCorals() {
  this.coralGroup = this.add.group();
  this.time.addEvent({
    delay: SPAWN_DELAY,
    callback: () => {
      // Get a random pledge from the loaded data (only coral type)
      if (pledgeData.length === 0) return;

      const coralPledges = pledgeData.filter(pledge => pledge.type === 'coral');
      if (coralPledges.length === 0) return;

      const pledge = Phaser.Utils.Array.GetRandom(coralPledges);
      const spawnX = Phaser.Math.Between(100, window.innerWidth - 100);
      const spawnY = 50; // Start from top
      const targetY = window.innerHeight - 120; // Plant at bottom

      // Create bubble sprite for entry animation
      const bubble = this.add.sprite(spawnX, spawnY, 'bubble_anim', 0).setScale(1.5);
      bubble.alpha = 0;
      this.tweens.add({ targets: bubble, alpha: 1, duration: 500, ease: "Linear" });

      // Create coral sprite inside the bubble (initially hidden)
      let coral;
      try {
        coral = this.add.sprite(spawnX, spawnY, `coral${pledge.coralId}`).setScale(0.4);
      } catch (error) {
        console.warn(`Coral image coral${pledge.coralId} failed to load, using fallback`);
        const colors = [0xff6b6b, 0x4ecdc4, 0x45b7d1, 0xf9ca24, 0xf0932b, 0xeb4d4b];
        coral = this.add.circle(spawnX, spawnY, 30, colors[pledge.coralId - 1] || 0xff6b6b);
      }
      coral.alpha = 0; // Hidden initially
      coral.pledgeData = pledge;
      coral.objectType = 'coral';

      // Create stick image (hidden initially)
      let stick;
      try {
        stick = this.add.sprite(spawnX, targetY + 20, "stick").setScale(0.3);
      } catch (error) {
        console.warn('Stick image failed to load, using fallback');
        stick = this.add.rectangle(spawnX, targetY + 20, 4, 40, 0x8b4513);
      }
      stick.setDepth(-1);
      stick.alpha = 0;

      // Animate bubble falling down
      this.tweens.add({
        targets: [bubble, coral],
        y: targetY,
        duration: 2000,
        ease: "Bounce.easeOut",
        onComplete: () => {
          // Pop the bubble and show coral
          bubble.play('bubble_pop');

          // Show coral and stick when bubble pops
          this.tweens.add({
            targets: coral,
            alpha: 1,
            duration: 300,
            ease: "Linear"
          });
          this.tweens.add({
            targets: stick,
            alpha: 1,
            duration: 300,
            ease: "Linear"
          });

          // Remove bubble after animation
          bubble.on('animationcomplete', () => {
            bubble.destroy();
          });

          // Keep coral at bottom (no floating movement)
          coral.isPlanted = true;
        }
      });

      coral.bubble = { stick };
      this.coralGroup.add(coral);
    },
    loop: true,
  });
}

function addNameBubbles() {
  this.nameBubbleGroup = this.add.group();
  this.time.addEvent({
    delay: NAME_BUBBLE_SPAWN_DELAY,
    callback: () => {
      // Get a random pledge from the loaded data (text type for name bubbles)
      if (pledgeData.length === 0) return;

      const textPledges = pledgeData.filter(pledge => pledge.type === 'text');
      if (textPledges.length === 0) {
        // If no text pledges, use any pledge for name display
        const pledge = Phaser.Utils.Array.GetRandom(pledgeData);
        createNameBubble.call(this, pledge);
        return;
      }

      const pledge = Phaser.Utils.Array.GetRandom(textPledges);
      createNameBubble.call(this, pledge);
    },
    loop: true,
  });
}

function createNameBubble(pledge) {
  const spawnX = Phaser.Math.Between(100, window.innerWidth - 100);
  const spawnY = Phaser.Math.Between(50, 200); // Float in upper area

  // Create name bubble sprite
  let nameBubble;
  try {
    nameBubble = this.add.sprite(spawnX, spawnY, "name_bubble").setScale(0.8);
  } catch (error) {
    console.warn('Name bubble image failed to load, using fallback');
    // Create a fallback bubble
    nameBubble = this.add.circle(spawnX, spawnY, 40, 0x87ceeb, 0.7);
  }

  nameBubble.alpha = 0;
  nameBubble.objectType = 'nameBubble';
  nameBubble.pledgeData = pledge;

  // Add floating movement properties
  nameBubble.vx = Phaser.Math.FloatBetween(-0.05, 0.05);
  nameBubble.vy = Phaser.Math.FloatBetween(-0.03, 0.03);
  nameBubble.floatTime = 0;
  nameBubble.floatDirection = Math.random() < 0.5 ? 1 : -1;

  // Add name text on the bubble
  const nameText = this.add.text(spawnX, spawnY, pledge.name || pledge.text || 'Anonymous', {
    font: "14px Arial",
    fill: "#ffffff",
    fontWeight: "bold",
    align: "center"
  }).setOrigin(0.5, 0.5).setDepth(1);

  nameBubble.nameText = nameText;

  // Fade in
  this.tweens.add({
    targets: [nameBubble, nameText],
    alpha: 1,
    duration: 1000,
    ease: "Linear"
  });

  this.nameBubbleGroup.add(nameBubble);
}

function update(time, delta) {
  const dt = delta / 1000;

  // Update planted corals (they don't move)
  this.coralGroup.getChildren().forEach((coral) => {
    // Skip movement for planted corals
    if (coral.isPlanted) {
      return;
    }
  });

  // Update floating name bubbles
  if (this.nameBubbleGroup) {
    this.nameBubbleGroup.getChildren().forEach((nameBubble) => {
      // Gentle floating movement
      nameBubble.x += nameBubble.vx * dt * 20; // Slower speed
      nameBubble.y += nameBubble.vy * dt * 15;

      // Update text position
      if (nameBubble.nameText) {
        nameBubble.nameText.setPosition(nameBubble.x, nameBubble.y);
      }

      // Add gentle floating oscillation
      nameBubble.floatTime += dt;
      nameBubble.vy += Math.sin(nameBubble.floatTime * 1.5) * NAME_BUBBLE_FLOAT_SPEED * nameBubble.floatDirection;
      nameBubble.vx += Math.cos(nameBubble.floatTime * 1.2) * NAME_BUBBLE_FLOAT_SPEED * nameBubble.floatDirection;

      // Keep name bubbles in upper area and on screen
      if (nameBubble.x < 50) {
        nameBubble.x = 50;
        nameBubble.vx = Math.abs(nameBubble.vx);
      }
      if (nameBubble.x > window.innerWidth - 50) {
        nameBubble.x = window.innerWidth - 50;
        nameBubble.vx = -Math.abs(nameBubble.vx);
      }
      if (nameBubble.y < 30) {
        nameBubble.y = 30;
        nameBubble.vy = Math.abs(nameBubble.vy);
      }
      if (nameBubble.y > 250) { // Keep in upper area
        nameBubble.y = 250;
        nameBubble.vy = -Math.abs(nameBubble.vy);
      }
    });
  }
}

function resizeGame() {
  const winWidth = window.innerWidth;
  const winHeight = window.innerHeight;
  this.scale.resize(winWidth, winHeight);
  // No background image to resize - handled by CSS
}
