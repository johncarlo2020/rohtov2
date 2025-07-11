import Phaser from "phaser";
import PlasmaPost2FX from "./PlasmaPost2FX.js";
import WigglePostFX from "./WigglePostFX.js";
// Disable all console logging
// ['log','warn','error'].forEach(method => console[method] = () => {});  // Temporarily enabled for debugging

const config = {
  parent: 'aquarium-container',
  type: Phaser.AUTO,
  width: window.innerWidth,
  height: window.innerHeight,
    backgroundColor: 'rgba(0,0,0,0)',
  render: {
    preserveDrawingBuffer: true,
    transparent: true, // Enable transparency
    contextAttributes: {
      alpha: true, // Allow alpha channel for transparency
      premultipliedAlpha: false,
    },
  },
  scale: {
    mode: Phaser.Scale.RESIZE,
  },
  scene: { preload, create, update },
  pipeline: {
    PlasmaPost2FX,
    WigglePostFX
  }
};

const game = new Phaser.Game(config);

// Pledge data storage
let pledgeData = [];

// Predefined coral positions based on the aquarium layout
const CORAL_POSITIONS = [
  { x: 0.24, y: 0.91, tiltOffsetX: 18, tiltOffsetY: 20, size: 0.50, z: 3, tilt: 0 }, // Left side bottom
  { x: 0.25, y: 0.75, tiltOffsetX: 40, tiltOffsetY: 10, size: 0.30, z: 2, tilt: 10 }, // Left middle rock
  { x: 0.15, y: 0.63, tiltOffsetX: 20, tiltOffsetY: 8, size: 0.30, z: 1, tilt: -10 }, // Left upper rock
  { x: 0.85, y: 0.88, tiltOffsetX: -18, tiltOffsetY: 6, size: 0.50, z: 1, tilt: -15 }, // Right side bottom
  { x: 0.70, y: 0.70, tiltOffsetX: -12, tiltOffsetY: 4, size: 0.27, z: 1, tilt: -20 }, // Right middle rock
  { x: 0.75, y: 0.54, tiltOffsetX: -20, tiltOffsetY: 8, size: 0.30, z: 1, tilt: -30 }, // Right upper rock
];

let currentCoralPositionIndex = 0;

// Limits for objects on screen

const MAX_CORALS = 6;
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

// Tilt position offset configuration
const CORAL_TILT_OFFSET_X = 18; // How much tilt affects X position (pixels)
const CORAL_TILT_OFFSET_Y = 6;  // How much tilt affects Y position (pixels)

function preload() {
  // Add error handling for image loading
  this.load.on('loaderror', function(file) {
    console.error('Failed to load:', file.src);
  });

  this.load.image("stick", "images/brand/coral-seperate/stick.webp");

  // Load bubble sprite sheet for entry animation
  this.load.spritesheet("bubble_anim", "images/brand/bubble_animation.webp", {
    frameWidth: 400,
    frameHeight: 400
  });

  // Load floating name bubble asset
  this.load.image("name_bubble", "images/brand/withMessage.webp");

  // Load tempBubbles images for name bubbles
  for (let i = 1; i <= 1; i++) {
    this.load.image(`tempBubble${i}`, `images/tempBubbles/${i}.png`);
  }

  // Load small bubble overlay for coral effects
  this.load.image("bubble_overlay", "images/brand/bubble_Overlay.webp");

  // Load background image for Phaser canvas
  this.load.image("aquarium_bg", "images/brand/live-feed/bg.webp");

  // Load tempCoral images for initial corals
  for (let i = 1; i <= 1; i++) {
    this.load.image(`tempCoral${i}`, `images/tempCoral/${i}.png`);
  }
}

function create() {
  setupCanvas.call(this);

  // Initialize arrays for tracking objects
  this.corals = [];
  this.nameBubbles = [];

  // Create bubble animation
  this.anims.create({
    key: 'bubble_pop',
    frames: this.anims.generateFrameNumbers('bubble_anim', { start: 0, end: 8 }),
    frameRate: 5,
    repeat: 0
  });

  // Note: Plasma effect disabled to preserve CSS background visibility
  // The underwater background image is handled by CSS
  // this.cameras.main.setPostPipeline(PlasmaPost2FX);
  // --- ENABLE UNDERWATER DISTORTION EFFECT ---
  this.cameras.main.setPostPipeline(PlasmaPost2FX);

  // Bind validateGroupSizes early so initial corals and name bubbles can call it
  this.validateGroupSizes = validateGroupSizes.bind(this);

  preloadCustomPledgeImages(this, () => {
    addCorals.call(this);
    addNameBubbles.call(this);
    addOceanFloorBubbles.call(this);
    addRandomAreaBubbles.call(this);
  });

  // Initialize bubble groups
  this.coralBubblesGroup = this.add.group();
  this.oceanFloorBubblesGroup = this.add.group();
  this.randomAreaBubblesGroup = this.add.group();

    Pusher.logToConsole = true;
    const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        encrypted: true
    });

    const channel = pusher.subscribe('baby-channel');
    channel.bind('baby-event', (data) => {
        console.log('Pusher event received:', data);
        // Accept both data.image and data.img for compatibility
        const BASE_PATH = '';
        function fixImageUrl(img) {
            if (!img) return null;
            if (img.startsWith('http') || img.startsWith('data:')) return img;
            // Use window.ASSET_BASE for relative asset paths
            let base = (typeof window !== 'undefined' && window.ASSET_BASE) ? window.ASSET_BASE : '';
            // Remove any leading slash from img to avoid double slashes
            img = img.replace(/^\//, '');
            // If already starts with base, don't double it
            if (base && img.startsWith(base)) return img;
            return base ? base + '/' + img : img;
        }

        if (data.type === 'coral') {
            const coralId = data.id || Date.now();
            let textureKey = `coral${data.coralId || 1}`;
            let image = data.image || data.img || null;
            image = fixImageUrl(image);
            if (image) {
                if (image.startsWith('data:')) {
                    textureKey = `coral_custom_${coralId}`;
                } else {
                    textureKey = `coral_custom_${coralId}`;
                }
            }
            // Push pledgeData BEFORE spawning
            pledgeData.push({
                id: coralId,
                name: data.name || '',
                coralId: data.coralId || 1,
                type: 'coral',
                image,
                textureKey
            });
            if (image) {
                if (image.startsWith('data:')) {
                    this.textures.addBase64(textureKey, image);
                    spawnSingleCoral.call(this, textureKey, 0.45); // Increased scale
                } else {
                    this.load.image(textureKey, image);
                    this.load.once('complete', () => {
                        spawnSingleCoral.call(this, textureKey, 0.45); // Increased scale
                    });
                    this.load.start();
                }
            } else {
                spawnSingleCoral.call(this, textureKey, 0.45); // Increased scale
            }
        } else if (data.type === 'text') {
            const textId = data.id || Date.now();
            let textureKey = 'name_bubble';
            let image = data.image || data.img || null;
            image = fixImageUrl(image);
            if (image) {
                if (image.startsWith('data:')) {
                    textureKey = `name_bubble_custom_${textId}`;
                } else {
                    textureKey = `name_bubble_custom_${textId}`;
                }
            }
            // Push pledgeData BEFORE spawning
            pledgeData.push({
                id: textId,
                text: data.text || '',
                type: 'text',
                image,
                textureKey
            });
            if (image) {
                if (image.startsWith('data:')) {
                    this.textures.addBase64(textureKey, image);
                    spawnSingleNameBubble.call(this, textureKey, 0.55); // Increased scale
                } else {
                    this.load.image(textureKey, image);
                    this.load.once('complete', () => {
                        spawnSingleNameBubble.call(this, textureKey, 0.55); // Increased scale
                    });
                    this.load.start();
                }
            } else {
                spawnSingleNameBubble.call(this, textureKey, 0.55); // Increased scale
            }
        }
    });

  // Resize listener
  window.addEventListener("resize", () => resizeGame.call(this));
}

// Generic function to remove the oldest item from a group with a fade-out
function removeOldestItem(group, array) {
  console.log(`removeOldestItem called. Group size: ${group.getLength()}, array length: ${array.length}`);


  const children = group.getChildren();
  const oldestItem = children.length > 0 ? children[0] : null;
  if (!oldestItem) {
    console.log(`No items in group to remove (getChildren empty)`);
    return false;
  }

  console.log(`Removing oldest ${oldestItem.objectType}. Group size before: ${group.getLength()}`);

  // For corals, stop bubble generation
  if (oldestItem.objectType === 'coral') {
    oldestItem.isPlanted = false;
    oldestItem.shouldDestroy = true;
    if (oldestItem.bubbleEvents) {
      oldestItem.bubbleEvents.forEach(event => event && !event.hasDispatched && event.remove());
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
          console.log(`Oldest ${oldestItem.objectType} destroyed (visual cleanup complete)`);
        }
        console.log(`Oldest ${oldestItem.objectType} removed from group after fade. Group size after: ${group.getLength()}`);
      }
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
      console.log(`Oldest ${oldestItem.objectType} destroyed (visual cleanup complete)`);
    }
    console.log(`Oldest ${oldestItem.objectType} removed from group immediately. Group size after: ${group.getLength()}`);
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
  console.log('Creating initial corals directly planted...');
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
    tempCoralKeys.push('tempCoral1'); // fallback if only one
  }
  // ...rest of function unchanged...
  const coralPosition = CORAL_POSITIONS[index % CORAL_POSITIONS.length];
  const finalX = coralPosition.x * window.innerWidth + (coralPosition.tiltOffsetX || 0);
  const finalY = coralPosition.y * window.innerHeight + (coralPosition.tiltOffsetY || 0);
  console.log(`Creating initial coral ${index + 1} at position ${finalX}, ${finalY}`);
  let coral;
  const textureKey = tempCoralKeys[index % tempCoralKeys.length];
  // Use CORAL_POSITIONS size property for tempCoral images
  const baseScale = coralPosition.size || 1.4;
  try {
    coral = this.add.sprite(finalX, finalY, textureKey).setScale(0.1); // Start small for grow animation
    coral.baseScale = baseScale;
    coral.baseAlpha = 1.0;
    coral.setPostPipeline('WigglePostFX');
    // Animate scale up to baseScale
    this.tweens.add({
      targets: coral,
      scale: baseScale,
      duration: 1800,
      ease: 'Sine.easeOut'
    });
  } catch (error) {
    console.warn(`Coral image ${textureKey} failed to load, using fallback`);
    const colors = [0xff6b6b, 0x4ecdc4, 0x45b7d1, 0xf9ca24, 0xf0932b, 0xeb4d4b];
    coral = this.add.circle(finalX, finalY, 25, colors[index % colors.length]);
    coral.baseScale = 1.0;
    coral.baseAlpha = 1.0;
  }
  // REMOVE pledgeData assignment for tempCoral initial corals
  // coral.pledgeData = pledge; // No pledge for tempCoral visuals
  coral.objectType = 'coral';
  coral.isPlanted = true;
  coral.phase = 'planted';
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
  if (typeof coralPosition.tilt === 'number' && coral.setRotation) {
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

function spawnSingleCoral(customTextureKey) {
  if (pledgeData.length === 0) return;
  const coralPledges = pledgeData.filter(pledge => pledge.type === 'coral');
  if (coralPledges.length === 0) return;
  console.log('Starting coral spawn sequence...');
  // Use the most recent pledge (the one just pushed)
  const pledge = coralPledges[coralPledges.length - 1];
  // Use custom texture if provided
  const textureKey = customTextureKey || pledge.textureKey || `coral${pledge.coralId}`;
  // Get the final coral position
  const coralPosition = CORAL_POSITIONS[currentCoralPositionIndex % CORAL_POSITIONS.length];
  // Calculate final position with tilt offsets
  const finalX = coralPosition.x * window.innerWidth + (coralPosition.tiltOffsetX || 0);
  const finalY = coralPosition.y * window.innerHeight + (coralPosition.tiltOffsetY || 0);
  currentCoralPositionIndex++;
  const spawnX = -80;
  const spawnY = window.innerHeight * 0.5;
  // --- ENTRY ANIMATION ---
  // Make the entry bubble much larger
  const entryBubbleScale = 0.8; // was 1.0, now much larger
  const bubble = this.add.sprite(spawnX, spawnY, 'bubble_anim', 0).setScale(entryBubbleScale);
  bubble.alpha = 0;
  bubble.setDepth(20);
  this.tweens.add({ targets: bubble, alpha: 1, duration: 500, ease: "Linear" });
  let coral;
  // Use the same scale multiplier as initial corals for consistency
  const scaleMultiplier = 2.8; // Match the visual size of initial corals
  const baseScale = (coralPosition.size || 0.25) * scaleMultiplier;
  try {
    coral = this.add.sprite(spawnX, spawnY, textureKey);
    coral.baseScale = baseScale;
    coral.baseAlpha = 1.0;
    // --- APPLY WIGGLE PIPELINE TO CORAL ---
    coral.setPostPipeline('WigglePostFX');
  } catch (error) {
    console.warn(`Coral image ${textureKey} failed to load, using fallback`);
    const colors = [0xff6b6b, 0x4ecdc4, 0x45b7d1, 0xf9ca24, 0xf0932b, 0xeb4d4b];
    coral = this.add.circle(spawnX, spawnY, 25, colors[pledge.coralId - 1] || 0xff6b6b);
    coral.baseScale = 1.0;
    coral.baseAlpha = 1.0;
  }
  coral.alpha = 0;
  coral.pledgeData = pledge;
  coral.objectType = 'coral';
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

  if (this.coralGroup.getLength() >= MAX_CORALS) {
    if (coral) {
      console.log(`At coral limit (${this.coralGroup.getLength()}/${MAX_CORALS}), removing oldest`);
      removeOldestItem.call(this, this.coralGroup, this.corals);
    }
  }
  this.coralGroup.add(coral);
  this.corals.push(coral);
  console.log(`Added new coral. Group size now: ${this.coralGroup.getLength()}, Array size: ${this.corals.length}, MAX_CORALS: ${MAX_CORALS}`);
  this.validateGroupSizes();
  setTimeout(() => {
    if (coral && !coral.shouldDestroy) {
      this.tweens.add({ targets: coral, alpha: 0.9, duration: 1200, ease: "Linear" });
    }
  }, 200);
  // Entry path
  const controlX = (spawnX + finalX) / 2;
  const controlY = Math.max(spawnY, finalY) + Math.abs(finalY - spawnY) * 0.6 + 100;
  const path = {
    getPoint: (t) => {
      const x = (1 - t) * (1 - t) * spawnX + 2 * (1 - t) * t * controlX + t * t * finalX;
      const y = (1 - t) * (1 - t) * spawnY + 2 * (1 - t) * t * controlY + t * t * finalY;
      return { x, y };
    }
  };
  const duration = 3500;
  let tweenObj = { t: 0 };
  this.tweens.add({
    targets: tweenObj,
    t: 1,
    duration: duration,
    ease: "Sine.easeInOut",
    onUpdate: () => {
      const pos = path.getPoint(tweenObj.t);
      bubble.x = pos.x;
      bubble.y = pos.y;
      coral.x = pos.x;
      coral.y = pos.y;
      // No scale animation for coral during entry
      if (bubble.setScale) {
        const bubbleWobble = entryBubbleScale + Math.sin(tweenObj.t * Math.PI * 3) * 0.08;
        bubble.setScale(bubbleWobble);
      }
    },
    onComplete: () => {
      bubble.play('bubble_pop');
      console.log(`Coral reached final position (${coral.x}, ${coral.y}) - bubble popping`);
      bubble.on('animationcomplete', () => {
        bubble.destroy();
        console.log('Entry bubble destroyed');
      });
      // Set the correct scale or display size ONLY after planting
      if (textureKey.startsWith('coral_custom_')) {
        coral.setDisplaySize(600, 600);
      } else {
        coral.setScale(0.1); // Start small for grow animation
        // Animate scale up to baseScale
        this.tweens.add({
          targets: coral,
          scale: baseScale,
          duration: 1800,
          ease: 'Sine.easeOut'
        });
      }
      // Move coral to final position with tilt offsets
      coral.x = finalX;
      coral.y = finalY;
      coral.originalX = finalX;
      coral.originalY = finalY;
      // Set rotation from CORAL_POSITIONS tilt property (degrees to radians)
      if (typeof coralPosition.tilt === 'number' && coral.setRotation) {
        coral.setRotation(Phaser.Math.DegToRad(coralPosition.tilt));
      }
      coral.isPlanted = true;
      coral.phase = 'planted';
      console.log('Coral planted successfully');
      startCoralBubbles.call(this, coral);
    }
  });
}

function addNameBubbles() {
  console.log('addNameBubbles function called');
  this.nameBubbleGroup = this.add.group();

  // Create initial 6 name bubbles immediately (floating, no entry animation)
  console.log('Creating initial name bubbles directly floating...');
  for (let i = 0; i < MAX_NAME_BUBBLES; i++) {
    createInitialNameBubble.call(this, i);
  }
}

// Function to create initial name bubbles directly in floating positions without entry animation
function createInitialNameBubble(index) {
  if (pledgeData.length === 0) return;

  let pledgesToUse = pledgeData.filter(pledge => pledge.type === 'text');
  if (pledgesToUse.length === 0) {
    console.log('No text pledges found for initial bubbles, using any pledge.');
    pledgesToUse = pledgeData; // Fallback to all pledges
  }
  if (pledgesToUse.length === 0) return; // Still no pledges, exit

  const pledge = Phaser.Utils.Array.GetRandom(pledgesToUse);
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
    tempBubbleKeys.push('tempBubble1'); // fallback if only one
  }
  // Pick key based on index (loop if more than available)
  const tempBubbleKey = tempBubbleKeys[index % tempBubbleKeys.length];

  const x = Phaser.Math.Between(window.innerWidth * 0.1, window.innerWidth * 0.9);
  const y = Phaser.Math.Between(window.innerHeight * 0.1, window.innerHeight * 0.6);

  console.log(`Creating initial name bubble ${index + 1} at position ${x}, ${y}`);

  // Create the name bubble sprite using tempBubbleKey
  let nameBubble;
  try {
    nameBubble = this.add.sprite(x, y, tempBubbleKey).setScale(0.60);
  } catch (error) {
    console.warn('Name bubble image failed to load, using fallback');
    nameBubble = this.add.circle(x, y, 35, 0x87ceeb, 0.7);
  }

  nameBubble.objectType = 'nameBubble';
  nameBubble.pledgeData = pledge;

  // Add floating movement properties with calmer variation
  nameBubble.vx = Phaser.Math.FloatBetween(-0.02, 0.02);
  nameBubble.vy = Phaser.Math.FloatBetween(-0.015, 0.015);
  nameBubble.floatTime = 0;
  nameBubble.floatDirection = Math.random() < 0.5 ? 1 : -1;
  nameBubble.floatRadius = Phaser.Math.Between(10, 20);

  // Check if we need to remove the oldest name bubble BEFORE adding the new one
  let attempts = 0;
  const maxAttempts = 10; // Safety check to prevent infinite loops

  while (this.nameBubbleGroup.getLength() >= MAX_NAME_BUBBLES && attempts < maxAttempts) {
    console.log(`At name bubble limit (${this.nameBubbleGroup.getLength()}/${MAX_NAME_BUBBLES}), removing oldest (attempt ${attempts + 1})`);
    const removed = removeOldestItem.call(this, this.nameBubbleGroup, this.nameBubbles);

    if (!removed) {
      console.log(`Failed to remove name bubble, breaking loop`);
      break;
    }

    attempts++;
  }

  if (attempts >= maxAttempts) {
    console.error(`Maximum name bubble removal attempts reached, something is wrong with group management`);
  }

  // Add to groups
  this.nameBubbleGroup.add(nameBubble);
  this.nameBubbles.push(nameBubble);

  console.log(`Initial name bubble created. Total name bubbles: ${this.nameBubbles.length}`);
}

function spawnSingleNameBubble(customTextureKey) {
  console.log(`Name bubble spawn attempt`);

  console.log('Name bubble spawn proceeding...');
  if (pledgeData.length === 0) {
    console.log('No pledge data available for name bubble');
    return;
  }

  let pledgesToUse = pledgeData.filter(pledge => pledge.type === 'text');
  if (pledgesToUse.length === 0) {
    console.log('No text pledges found, using any random pledge');
    pledgesToUse = pledgeData; // Fallback to all pledges
  }
  if (pledgesToUse.length === 0) return; // Still no pledges, exit

  // Use the most recent pledge (the one just pushed)
  const pledge = pledgesToUse[pledgesToUse.length - 1];
  const textureKey = customTextureKey || pledge.textureKey || 'name_bubble';
  createNameBubble.call(this, pledge, textureKey);
}

function createNameBubble(pledge, textureKey) {
  console.log('Starting name bubble entry...');
  // Entry: randomly from left or right, following a more natural, slower, and lighter path
  const finalX = Phaser.Math.Between(100, window.innerWidth - 100);
  const finalY = Phaser.Math.Between(80, window.innerHeight * 0.4);
  const fromLeft = Math.random() < 0.5;
  const spawnX = fromLeft ? -60 : window.innerWidth + 60;
  const spawnY = window.innerHeight * 0.45 + Phaser.Math.Between(-40, 40); // a bit lower and with some vertical randomness
  // Create name bubble sprite
  let nameBubble;
  try {
    nameBubble = this.add.sprite(spawnX, spawnY, textureKey).setScale(0.38);
    // Set display size to 600x600 for custom uploaded images
    if (textureKey.startsWith('name_bubble_custom_')) {
      nameBubble.setDisplaySize(600, 600);
    }
  } catch (error) {
    console.warn('Name bubble image failed to load, using fallback');
    nameBubble = this.add.circle(spawnX, spawnY, 32, 0x87ceeb, 0.7);
  }
  nameBubble.alpha = 0;
  nameBubble.objectType = 'nameBubble';
  nameBubble.pledgeData = pledge;
  // Remove oldest name bubble if at limit
  let attempts = 0;
  const maxAttempts = 10;
  while (this.nameBubbleGroup.getLength() >= MAX_NAME_BUBBLES && attempts < maxAttempts) {
    const removed = removeOldestItem.call(this, this.nameBubbleGroup, this.nameBubbles);
    if (!removed) break;
    attempts++;
  }
  if (attempts >= maxAttempts) {
    console.error(`Maximum name bubble removal attempts reached, something is wrong with group management`);
  }
  this.nameBubbleGroup.add(nameBubble);
  this.nameBubbles.push(nameBubble);
  this.validateGroupSizes();
  // Floating movement properties
  nameBubble.vx = Phaser.Math.FloatBetween(-0.012, 0.012);
  nameBubble.vy = Phaser.Math.FloatBetween(-0.009, 0.009);
  nameBubble.floatTime = 0;
  nameBubble.floatDirection = Math.random() < 0.5 ? 1 : -1;
  nameBubble.floatRadius = Phaser.Math.Between(8, 16);
  // Natural, lighter, and slower quadratic Bezier path (arched upward, direction-aware)
  const controlX = fromLeft
    ? spawnX + (finalX - spawnX) * 0.45 + Phaser.Math.Between(-30, 30)
    : spawnX - (spawnX - finalX) * 0.45 + Phaser.Math.Between(-30, 30);
  const controlY = spawnY - Phaser.Math.Between(40, 90); // arched upward
  const path = {
    getPoint: (t) => {
      // Add a little horizontal and vertical wobble for more natural feel
      const wobbleX = Math.sin(t * Math.PI * 2.5) * 8 * (1 - t);
      const wobbleY = Math.cos(t * Math.PI * 2.5) * 6 * (1 - t);
      const x = (1 - t) * (1 - t) * spawnX + 2 * (1 - t) * t * controlX + t * t * finalX + wobbleX;
      const y = (1 - t) * (1 - t) * spawnY + 2 * (1 - t) * t * controlY + t * t * finalY + wobbleY;
      return { x, y };
    }
  };
  const duration = Phaser.Math.Between(3800, 4800); // much slower and more variable
  let tweenObj = { t: 0 };
  this.tweens.add({
    targets: tweenObj,
    t: 1,
    duration: duration,
    ease: "Sine.easeInOut",
    onUpdate: () => {
      const pos = path.getPoint(tweenObj.t);
      nameBubble.x = pos.x;
      nameBubble.y = pos.y;
      if (nameBubble.setScale) {
        const scaleWobble = 0.38 + Math.sin(tweenObj.t * Math.PI * 2.2) * 0.012;
        nameBubble.setScale(scaleWobble);
      }
    },
    onStart: () => {
      // Fade in as it starts moving
      this.tweens.add({
        targets: nameBubble,
        alpha: 1,
        duration: 1100,
        ease: "Linear"
      });
    },
    onComplete: () => {
      // No bubble pop, just finish at destination
    }
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
    loop: true
  });

  // Store the event reference for cleanup
  coral.bubbleEvents.push(bubbleEvent);
}

// Function to create a chain of bubbles rising from corals
function createBubbleChain(coral) {
  // Increase the number of bubbles per chain: 8-14
  const numBubblesInChain = Phaser.Math.Between(20, 30); // 8-14 bubbles per chain
  const chainDelay = Phaser.Math.Between(50, 100); // Faster succession for visible chain effect

  console.log(`Creating coral bubble chain: ${numBubblesInChain} bubbles from coral at x=${coral.x}`);

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
  const bubbleX = coral.x + Phaser.Math.Between(-10, 10); // Smaller random offset to keep bubbles closer to coral
  const bubbleY = coral.y - Phaser.Math.Between(20, 40); // Start higher above the coral

  let bubble;
  // Make max size smaller: 0.01 to 0.018
  const randomSize = Phaser.Math.FloatBetween(0.01, 0.018); // Smaller, more subtle bubbles

  try {
    bubble = this.add.sprite(bubbleX, bubbleY, "bubble_overlay").setScale(randomSize);
  } catch (error) {
    // Fallback: smaller circle with random size
    const radius = Phaser.Math.Between(2, 4); // Reduced fallback size
    bubble = this.add.circle(bubbleX, bubbleY, radius, 0x87ceeb, 0.6);
  }

  bubble.alpha = Phaser.Math.FloatBetween(0.4, 0.8); // Increased alpha range for better visibility
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
    }
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
    loop: true
  });
}

// Function to create a chain of bubbles from ocean floor
function createOceanFloorBubbleChain() {
  const numBubblesInChain = Phaser.Math.Between(3, 6); // 3-6 bubbles per chain
  const chainDelay = Phaser.Math.Between(150, 400); // Faster succession for more visible chain effect
  const baseX = Phaser.Math.Between(50, window.innerWidth - 50); // Base position for chain

  console.log(`Creating ocean floor bubble chain: ${numBubblesInChain} bubbles at x=${baseX}`);

  for (let i = 0; i < numBubblesInChain; i++) {
    this.time.delayedCall(i * chainDelay, () => {
      createOceanFloorBubble.call(this, baseX, i);
    });
  }
}

// Function to create random air bubbles from ocean floor
function createOceanFloorBubble(baseX = null, chainIndex = 0) {
  // Use provided baseX for chain, or random position for individual bubbles
  const bubbleX = baseX ? baseX + Phaser.Math.Between(-25, 25) : Phaser.Math.Between(50, window.innerWidth - 50);
  const bubbleY = window.innerHeight - Phaser.Math.Between(20, 60); // Near bottom

  let bubble;
  const randomSize = Phaser.Math.FloatBetween(0.02, 0.06); // Smaller bubbles

  try {
    bubble = this.add.sprite(bubbleX, bubbleY, "bubble_overlay").setScale(randomSize);
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
  const riseHeight = Phaser.Math.Between(window.innerHeight * 0.7, window.innerHeight * 1.1);
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
      const driftX = Math.sin((bubble.floatTime + bubble.phaseOffsetX) * 1.1) * bubble.driftStrength;
      const driftY = Math.cos((bubble.floatTime + bubble.phaseOffsetY) * 0.7) * (bubble.driftStrength * 0.4);

      bubble.x += bubble.vx + driftX;
      bubble.y += bubble.vy + driftY * 0.08;

      // Individual direction changes with varying probabilities
      const changeChance = 0.003 + (Math.sin(bubble.floatTime * 0.2) * 0.002);
      if (Math.random() < changeChance) {
        bubble.vx += Phaser.Math.FloatBetween(-0.006, 0.006);
        bubble.vy += Phaser.Math.FloatBetween(-0.003, 0.003);
        bubble.vx = Phaser.Math.Clamp(bubble.vx, -0.020, 0.020);
        bubble.vy = Phaser.Math.Clamp(bubble.vy, -0.008, 0.008);
      }
    },
    onComplete: () => {
      bubble.destroy();
    }
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
    loop: true
  });
}

// Function to create a chain of bubbles from random areas
function createRandomAreaBubbleChain() {
  const numBubblesInChain = Phaser.Math.Between(2, 5); // 2-5 bubbles per chain
  const chainDelay = Phaser.Math.Between(200, 500); // Faster succession for visible chain effect
  const baseX = Phaser.Math.Between(50, window.innerWidth - 50); // Base position for chain
  const baseY = Phaser.Math.Between(window.innerHeight * 0.4, window.innerHeight - 50);

  console.log(`Creating random area bubble chain: ${numBubblesInChain} bubbles at x=${baseX}, y=${baseY}`);

  for (let i = 0; i < numBubblesInChain; i++) {
    this.time.delayedCall(i * chainDelay, () => {
      createRandomAreaBubble.call(this, baseX, baseY, i);
    });
  }
}

// Function to create bubbles from random areas across the screen
function createRandomAreaBubble(baseX = null, baseY = null, chainIndex = 0) {
  // Use provided base position for chain, or random position for individual bubbles
  const bubbleX = baseX ? baseX + Phaser.Math.Between(-10, 10) : Phaser.Math.Between(50, window.innerWidth - 50);
  const bubbleY = baseY ? baseY + Phaser.Math.Between(-8, 8) : Phaser.Math.Between(window.innerHeight * 0.4, window.innerHeight - 50);

  let bubble;
  // Make bubbles smaller and less transparent
  const randomSize = Phaser.Math.FloatBetween(0.012, 0.025); // Smaller, more subtle bubbles

  try {
    bubble = this.add.sprite(bubbleX, bubbleY, "bubble_overlay").setScale(randomSize);
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
    }
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
      bubble.x += bubble.vx * dt * Phaser.Math.FloatBetween(12, 18) + Math.sin(bubble.floatTime * Phaser.Math.FloatBetween(1.7, 2.3)) * Phaser.Math.FloatBetween(0.2, 0.5);
      bubble.vx += Math.cos(bubble.floatTime * Phaser.Math.FloatBetween(1.2, 1.8)) * Phaser.Math.FloatBetween(0.001, 0.003);
      if (Math.abs(bubble.vx) > Phaser.Math.FloatBetween(0.018, 0.025)) {
        bubble.vx *= 0.9;
      }
    });
  }

  // Update floating name bubbles with calmer movement
  if (this.nameBubbleGroup) {
    const bubbles = this.nameBubbleGroup.getChildren();
    // Subtle collision avoidance for name bubbles
    for (let i = 0; i < bubbles.length; i++) {
      const a = bubbles[i];
      // Bubble-like floating movement
      a.floatTime += dt;
      // Gentle vertical bobbing (main bubble effect, reduced amplitude)
      a.y += Math.sin(a.floatTime * 1.2 + i) * 3.5 * dt;
      // Gentle horizontal drift (reduced amplitude)
      a.x += Math.cos(a.floatTime * 0.7 + i) * 1.5 * dt;
      // Add a little random walk to vx/vy for organic feel
      a.vx += Phaser.Math.FloatBetween(-0.003, 0.003) * dt;
      a.vy += Phaser.Math.FloatBetween(-0.002, 0.002) * dt;
      // Damping to keep velocity under control
      a.vx *= 0.98;
      a.vy *= 0.98;
      // Apply velocity (reduced multipliers)
      a.x += a.vx * dt * 7;
      a.y += a.vy * dt * 5;

      // Subtle repulsion from other bubbles
      for (let j = 0; j < bubbles.length; j++) {
        if (i === j) continue;
        const b = bubbles[j];
        const dx = a.x - b.x;
        const dy = a.y - b.y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        const minDist = 70; // Slightly reduced minimum allowed distance between bubbles
        if (dist < minDist && dist > 0.1) {
          // Softer push away for less aggressive bounce
          const push = (minDist - dist) / minDist * 0.09; // 0.09 is a gentler factor
          a.vx += (dx / dist) * push * dt;
          a.vy += (dy / dist) * push * dt;
        }
      }

      // Keep name bubbles distributed across upper half of the screen only
      if (a.x < 80) {
        a.x = 80;
        a.vx = Math.abs(a.vx);
      }
      if (a.x > window.innerWidth - 80) {
        a.x = window.innerWidth - 80;
        a.vx = -Math.abs(a.vx);
      }
      if (a.y < 50) {
        a.y = 50;
        a.vy = Math.abs(a.vy);
      }
      if (a.y > window.innerHeight * 0.5) {
        a.y = window.innerHeight * 0.5;
        a.vy = -Math.abs(a.vy);
      }
    }
  }

  // Ocean floor and random area bubbles are fully handled by their tween animations
  // No additional update needed since movement is in tween onUpdate callbacks
}

// Validation function to ensure group sizes never exceed limits
function validateGroupSizes() {
  // Safely get group sizes, groups may not be initialized yet
  const coralCount = this.coralGroup ? this.coralGroup.getLength() : 0;
  const nameBubbleCount = this.nameBubbleGroup ? this.nameBubbleGroup.getLength() : 0;

  console.log(`Validating group sizes - Corals: ${coralCount}/${MAX_CORALS}, Name bubbles: ${nameBubbleCount}/${MAX_NAME_BUBBLES}`);

  // Remove only one coral if over the limit (avoid infinite loop due to async removal)
  if (this.coralGroup && this.coralGroup.getLength() > MAX_CORALS) {
    console.warn(`Emergency coral removal! Group size: ${this.coralGroup.getLength()}`);
    removeOldestItem.call(this, this.coralGroup, this.corals);
  }

  // Remove only one name bubble if over the limit (avoid infinite loop due to async removal)
  if (this.nameBubbleGroup && this.nameBubbleGroup.getLength() > MAX_NAME_BUBBLES) {
    console.warn(`Emergency name bubble removal! Group size: ${this.nameBubbleGroup.getLength()}`);
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
  pledgeData.forEach(pledge => {
    if (pledge.image && !pledge.image.startsWith('data:')) {
      const key = pledge.type === 'coral'
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
  scene.load.once('complete', () => {
    callback();
  });
  scene.load.start();
}
