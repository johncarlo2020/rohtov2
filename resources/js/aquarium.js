import Phaser from "phaser";
import PlasmaPost2FX from "./PlasmaPost2FX.js";
// Disable all console logging
// ['log','warn','error'].forEach(method => console[method] = () => {});  // Temporarily enabled for debugging

const config = {
  parent: 'aquarium-container',
  type: Phaser.AUTO,
  width: window.innerWidth,
  height: window.innerHeight,
  backgroundColor: 'transparent',
  render: {
    preserveDrawingBuffer: true,
    transparent: true,
    contextAttributes: {
      alpha: true,
      premultipliedAlpha: false,
    },
  },
  scale: {
    mode: Phaser.Scale.RESIZE,
  },
  scene: { preload, create, update },
  pipeline: {
    PlasmaPost2FX
  }
};

const game = new Phaser.Game(config);

// Pledge data storage
let pledgeData = [];

// Predefined coral positions based on the aquarium layout
const CORAL_POSITIONS = [
  { x: 0.15, y: 0.85 }, // Left side bottom
  { x: 0.25, y: 0.75 }, // Left middle rock
  { x: 0.12, y: 0.65 }, // Left upper rock
  { x: 0.75, y: 0.85 }, // Right side bottom
  { x: 0.85, y: 0.75 }, // Right middle rock
  { x: 0.88, y: 0.65 }, // Right upper rock
  { x: 0.45, y: 0.90 }, // Center bottom
  { x: 0.35, y: 0.80 }, // Center left
  { x: 0.65, y: 0.80 }  // Center right
];

let currentCoralPositionIndex = 0;

// Limits for objects on screen

const MAX_CORALS = 6;
const MAX_NAME_BUBBLES = 6;

// Adjustable Variables
const SPAWN_DELAY = 4000; // Slower coral spawning (was 1500)
const NAME_BUBBLE_SPAWN_DELAY = 5000; // Slower name bubble spawning (was 2000)
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
  });

  this.load.image("stick", "images/brand/coral-seperate/stick.webp");

  // Load bubble sprite sheet for entry animation
  this.load.spritesheet("bubble_anim", "images/brand/bubble_animation.webp", {
    frameWidth: 400,
    frameHeight: 400
  });

  // Load floating name bubble asset
  this.load.image("name_bubble", "images/brand/withMessage.webp");

  // Load small bubble overlay for coral effects
  this.load.image("bubble_overlay", "images/brand/bubble_Overlay.webp");

  // Load all coral images
  for (let i = 1; i <= 6; i++) {
    this.load.image(`coral${i}`, `images/brand/coral/${i}.webp`);
  }

  // Fetch pledge data from server
  fetchPledgeData();
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

  // Bind validateGroupSizes early so initial corals and name bubbles can call it
  this.validateGroupSizes = validateGroupSizes.bind(this);

  addCorals.call(this);
  addNameBubbles.call(this);
  addOceanFloorBubbles.call(this);
  addRandomAreaBubbles.call(this);

  // Initialize bubble groups
  this.coralBubblesGroup = this.add.group();
  this.oceanFloorBubblesGroup = this.add.group();
  this.randomAreaBubblesGroup = this.add.group();

  // Simulation hooks for WebSocket testing
  // Spawn a coral via WebSocket message simulation
  window.simulateAddCoral = () => {
    console.log('Simulating coral add via WebSocket');
    spawnSingleCoral.call(this);
  };
  // Spawn a name bubble via WebSocket message simulation
  window.simulateAddNameBubble = () => {
    console.log('Simulating name bubble add via WebSocket');
    spawnSingleNameBubble.call(this);
  };
  // Composite simulation: spawn both coral and name bubble
  window.simulateAddBoth = () => {
    window.simulateAddCoral();
    window.simulateAddNameBubble();
  };
  // Debug function to check and reset entry flag
  window.checkEntryFlag = () => {
    console.log(`Entry flag status: ${this.isAnyEntryActive}`);
    return this.isAnyEntryActive;
  };
  window.resetEntryFlag = () => {
    console.log('Manually resetting entry flag');
    this.isAnyEntryActive = false;
  };

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
  console.log('Pledge data loaded:', pledgeData.length, 'items');
  console.log('Text pledges available:', pledgeData.filter(p => p.type === 'text').length);

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
  this.isAnyEntryActive = false; // Master flag to prevent ANY entries while something is animating

      // Create initial 6 corals immediately (planted, no entry animation)
  console.log('Creating initial corals directly planted...');
  for (let i = 0; i < MAX_CORALS; i++) {
    createInitialCoral.call(this, i);
  }

  // Then continue with regular spawning
  this.time.addEvent({
    delay: SPAWN_DELAY,
    callback: () => {
      spawnSingleCoral.call(this);
    },
    loop: true,
  });
}

// Function to create initial corals directly in planted positions without entry animation
function createInitialCoral(index) {
  // Get a random pledge from the loaded data (only coral type)
  if (pledgeData.length === 0) return;

  const coralPledges = pledgeData.filter(pledge => pledge.type === 'coral');
  if (coralPledges.length === 0) return;

  const pledge = Phaser.Utils.Array.GetRandom(coralPledges);

  // Get the predefined coral position
  const coralPosition = CORAL_POSITIONS[index % CORAL_POSITIONS.length];
  const finalX = coralPosition.x * window.innerWidth;
  const finalY = coralPosition.y * window.innerHeight;

  console.log(`Creating initial coral ${index + 1} at position ${finalX}, ${finalY}`);

  // Create coral directly at final position
  let coral;
  try {
    coral = this.add.sprite(finalX, finalY, `coral${pledge.coralId}`).setScale(0.25);
    coral.baseScale = 0.25;
    coral.baseAlpha = 1.0;
  } catch (error) {
    console.warn(`Coral image coral${pledge.coralId} failed to load, using fallback`);
    const colors = [0xff6b6b, 0x4ecdc4, 0x45b7d1, 0xf9ca24, 0xf0932b, 0xeb4d4b];
    coral = this.add.circle(finalX, finalY, 25, colors[pledge.coralId - 1] || 0xff6b6b);
    coral.setDepth(5);
    coral.baseScale = 1.0;
    coral.baseAlpha = 1.0;
  }

  // Set coral properties
  coral.pledgeData = pledge;
  coral.objectType = 'coral';
  coral.isPlanted = true;
  coral.phase = 'planted';
  coral.swayTime = Math.random() * Math.PI * 2;
  coral.bobTime = Math.random() * Math.PI * 2;
  coral.originalX = coral.x;
  coral.originalY = coral.y;
  coral.setDepth(5);

  // Only remove if we are definitely adding a new coral
  if (this.coralGroup.getLength() >= MAX_CORALS) {
    // Remove only if we have a valid coral to add
    if (coral) {
      removeOldestItem.call(this, this.coralGroup, this.corals);
    }
  }

  // Add to groups
  this.coralGroup.add(coral);
  this.corals.push(coral);

  // Validate group sizes after addition
  this.validateGroupSizes();

  // Start bubble generation for this coral
  startCoralBubbles.call(this, coral);

  console.log(`Initial coral created. Total corals: ${this.corals.length}`);
}

function spawnSingleCoral() {
  // Get a random pledge from the loaded data (only coral type)
  if (pledgeData.length === 0) return;

  const coralPledges = pledgeData.filter(pledge => pledge.type === 'coral');
  if (coralPledges.length === 0) return;

  console.log('Starting coral spawn sequence...');

  const pledge = Phaser.Utils.Array.GetRandom(coralPledges);

  // Get the final coral position immediately
  const coralPosition = CORAL_POSITIONS[currentCoralPositionIndex % CORAL_POSITIONS.length];
  const finalX = coralPosition.x * window.innerWidth;
  const finalY = coralPosition.y * window.innerHeight;
  currentCoralPositionIndex++;

  // Decide entry direction: left, right, or center
  const entryTypeRand = Math.random();
  let entryType;
  if (entryTypeRand < 0.33) {
    entryType = 'left';
  } else if (entryTypeRand < 0.66) {
    entryType = 'right';
  } else {
    entryType = 'center';
  }

  let spawnX, spawnY, midY;
  midY = window.innerHeight * 0.5;
  if (entryType === 'left') {
    spawnX = -80;
    spawnY = midY;
  } else if (entryType === 'right') {
    spawnX = window.innerWidth + 80;
    spawnY = midY;
  } else {
    // Center entry: from bottom center, like a bubble rising
    spawnX = finalX + Phaser.Math.Between(-40, 40);
    spawnY = window.innerHeight + 100;
  }

  // Create bubble sprite for entry animation
  const bubble = this.add.sprite(spawnX, spawnY, 'bubble_anim', 0).setScale(1.0);
  bubble.alpha = 0;
  bubble.setDepth(20);
  this.tweens.add({ targets: bubble, alpha: 1, duration: 500, ease: "Linear" });

  // Create coral sprite inside the bubble
  let coral;
  try {
    coral = this.add.sprite(spawnX, spawnY, `coral${pledge.coralId}`).setScale(0.25);
    coral.baseScale = 0.25;
    coral.baseAlpha = 1.0;
  } catch (error) {
    console.warn(`Coral image coral${pledge.coralId} failed to load, using fallback`);
    const colors = [0xff6b6b, 0x4ecdc4, 0x45b7d1, 0xf9ca24, 0xf0932b, 0xeb4d4b];
    coral = this.add.circle(spawnX, spawnY, 25, colors[pledge.coralId - 1] || 0xff6b6b);
    coral.setDepth(5);
    coral.baseScale = 1.0;
    coral.baseAlpha = 1.0;
  }
  coral.alpha = 0;
  coral.pledgeData = pledge;
  coral.objectType = 'coral';
  coral.setDepth(5);

  // Remove only one oldest coral if at the limit (never in a loop)
  if (this.coralGroup.getLength() >= MAX_CORALS) {
    // Remove only if we have a valid coral to add
    if (coral) {
      console.log(`At coral limit (${this.coralGroup.getLength()}/${MAX_CORALS}), removing oldest`);
      removeOldestItem.call(this, this.coralGroup, this.corals);
    }
  }

  // Add coral to group after ensuring we have space
  this.coralGroup.add(coral);
  this.corals.push(coral);
  console.log(`Added new coral. Group size now: ${this.coralGroup.getLength()}, Array size: ${this.corals.length}, MAX_CORALS: ${MAX_CORALS}`);

  // Validate group sizes after addition
  this.validateGroupSizes();

  // Show coral with bubble with a slower fade-in
  setTimeout(() => {
    if (coral && !coral.shouldDestroy) {
      console.log('Making coral visible in bubble');
      this.tweens.add({ targets: coral, alpha: 0.9, duration: 1200, ease: "Linear" });
    }
  }, 200);

  // Bubble-like entry path
  // For left/right: from left/right middle, curve down to final position
  // For center: from bottom center, wobbly upward
  let path = { getPoint: null };
  if (entryType === 'left' || entryType === 'right') {
    // Quadratic Bezier: from side middle, curve down to final position
    // Control point is horizontally between start and end, vertically below the midpoint
    const controlX = (spawnX + finalX) / 2;
    const controlY = Math.max(spawnY, finalY) + Math.abs(finalY - spawnY) * 0.6 + 100;
    path.getPoint = (t) => {
      // Quadratic Bezier interpolation
      const x = (1 - t) * (1 - t) * spawnX + 2 * (1 - t) * t * controlX + t * t * finalX;
      const y = (1 - t) * (1 - t) * spawnY + 2 * (1 - t) * t * controlY + t * t * finalY;
      return { x, y };
    };
  } else {
    // Center: wobbly upward path (sinusoidal)
    const wobbleAmplitude = Phaser.Math.Between(30, 60);
    const wobbleFrequency = Phaser.Math.FloatBetween(1.5, 2.5);
    path.getPoint = (t) => {
      // t: 0 (start) to 1 (end)
      const x = spawnX + Math.sin(t * Math.PI * wobbleFrequency) * wobbleAmplitude * (1 - t);
      const y = spawnY - (spawnY - finalY) * t;
      return { x, y };
    };
  }

  // Animate along the path
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
      // Add a little wobble/scale for realism
      if (coral.setScale) {
        const scaleWobble = 0.25 + Math.sin(tweenObj.t * Math.PI * 4) * 0.01;
        coral.setScale(scaleWobble);
      }
      if (bubble.setScale) {
        const bubbleWobble = 1.0 + Math.sin(tweenObj.t * Math.PI * 3) * 0.04;
        bubble.setScale(bubbleWobble);
      }
    },
    onComplete: () => {
      // Pop the bubble immediately
      bubble.play('bubble_pop');
      console.log(`Coral reached final position (${coral.x}, ${coral.y}) - bubble popping`);

      // Remove bubble after animation
      bubble.on('animationcomplete', () => {
        bubble.destroy();
        console.log('Entry bubble destroyed');
      });

      // Plant the coral immediately
      coral.isPlanted = true;
      coral.phase = 'planted';
      coral.swayTime = Math.random() * Math.PI * 2;
      coral.bobTime = Math.random() * Math.PI * 2;
      coral.originalX = coral.x;
      coral.originalY = coral.y;

      console.log('Coral planted successfully');

      // Start bubble generation for this coral
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

  // Start regular spawning timer immediately
  console.log('Name bubble spawning timer started');
  this.time.addEvent({
    delay: NAME_BUBBLE_SPAWN_DELAY,
    callback: () => {
      spawnSingleNameBubble.call(this);
    },
    loop: true,
  });
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
  // Create name bubble directly in floating area
  const x = Phaser.Math.Between(window.innerWidth * 0.1, window.innerWidth * 0.9);
  const y = Phaser.Math.Between(window.innerHeight * 0.1, window.innerHeight * 0.6);

  console.log(`Creating initial name bubble ${index + 1} at position ${x}, ${y}`);

  // Create the name bubble sprite
  let nameBubble;
  try {
    nameBubble = this.add.sprite(x, y, "name_bubble").setScale(0.45);
  } catch (error) {
    console.warn('Name bubble image failed to load, using fallback');
    // Create a fallback bubble
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

function spawnSingleNameBubble() {
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

  const pledge = Phaser.Utils.Array.GetRandom(pledgesToUse);
  createNameBubble.call(this, pledge);
}

function createNameBubble(pledge) {
  console.log('Starting name bubble entry...');

  const spawnX = Phaser.Math.Between(100, window.innerWidth - 100);
  const spawnY = Phaser.Math.Between(80, window.innerHeight * 0.4); // Restrict to upper portion only

  // Create name bubble sprite
  let nameBubble;
  try {
    nameBubble = this.add.sprite(spawnX, spawnY, "name_bubble").setScale(0.45);
  } catch (error) {
    console.warn('Name bubble image failed to load, using fallback');
    // Create a fallback bubble
    nameBubble = this.add.circle(spawnX, spawnY, 35, 0x87ceeb, 0.7);
  }

  nameBubble.alpha = 0;
  nameBubble.objectType = 'nameBubble';
  nameBubble.pledgeData = pledge;

  // Remove oldest name bubble if at limit - this now works correctly
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

  // Add to group after ensuring we have space
  this.nameBubbleGroup.add(nameBubble);
  this.nameBubbles.push(nameBubble);
  console.log(`Added new name bubble. Group size now: ${this.nameBubbleGroup.getLength()}, Array size: ${this.nameBubbles.length}, MAX_NAME_BUBBLES: ${MAX_NAME_BUBBLES}`);

  // Validate group sizes after addition
  this.validateGroupSizes();

  // Add floating movement properties with calmer variation
  nameBubble.vx = Phaser.Math.FloatBetween(-0.02, 0.02);
  nameBubble.vy = Phaser.Math.FloatBetween(-0.015, 0.015);
  nameBubble.floatTime = 0;
  nameBubble.floatDirection = Math.random() < 0.5 ? 1 : -1;
  nameBubble.floatRadius = Phaser.Math.Between(10, 20);

  // Slower fade in for name bubble
  this.tweens.add({
    targets: nameBubble,
    alpha: 1,
    duration: 1200,
    ease: "Linear",
    onComplete: () => {
      console.log('Name bubble entry complete');
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
    delay: Phaser.Math.Between(1000, 3000), // Initial delay before first bubble
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
  const numBubblesInChain = Phaser.Math.Between(4, 8); // 4-8 bubbles per chain
  const chainDelay = Phaser.Math.Between(100, 300); // Faster succession for visible chain effect

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
  const randomSize = Phaser.Math.FloatBetween(0.01, 0.03); // Reduced bubble size for more subtle effect

  try {
    bubble = this.add.sprite(bubbleX, bubbleY, "bubble_overlay").setScale(randomSize);
  } catch (error) {
    // Fallback: smaller circle with random size
    const radius = Phaser.Math.Between(2, 6); // Reduced fallback size for more subtle bubbles
    bubble = this.add.circle(bubbleX, bubbleY, radius, 0x87ceeb, 0.6);
  }

  bubble.alpha = Phaser.Math.FloatBetween(0.4, 0.8); // Increased alpha range for better visibility
  bubble.setDepth(15); // Higher depth to ensure bubbles appear above corals and other elements

  // Add unique floating properties for each bubble (more variation)
  bubble.vx = Phaser.Math.FloatBetween(-0.015, 0.015);
  bubble.vy = Phaser.Math.FloatBetween(-0.004, 0.004);
  bubble.floatTime = Math.random() * Math.PI * 4; // Larger random start phase range
  bubble.floatSpeed = Phaser.Math.FloatBetween(0.5, 2.0); // Wider individual float speed range
  bubble.driftStrength = Phaser.Math.FloatBetween(0.1, 1.2); // Wider individual drift strength range
  bubble.phaseOffsetX = Math.random() * Math.PI * 2; // Individual phase offset for X movement
  bubble.phaseOffsetY = Math.random() * Math.PI * 2; // Individual phase offset for Y movement

  // Animate bubble rising up with random properties
  const riseHeight = Phaser.Math.Between(100, 200); // Random rise height
  const duration = Phaser.Math.Between(2000, 4500); // Random duration

  this.tweens.add({
    targets: bubble,
    y: bubble.y - riseHeight,
    alpha: 0,
    duration: duration,
    ease: "Sine.easeOut",
    onUpdate: () => {
      // Add individual natural floating movement with unique phase offsets
      bubble.floatTime += 0.008 * bubble.floatSpeed;
      const driftX = Math.sin((bubble.floatTime + bubble.phaseOffsetX) * 1.1) * bubble.driftStrength;
      const driftY = Math.cos((bubble.floatTime + bubble.phaseOffsetY) * 0.7) * (bubble.driftStrength * 0.4);

      bubble.x += bubble.vx + driftX;
      bubble.y += bubble.vy + driftY * 0.08;

      // Individual direction changes with different probabilities per bubble
      const changeChance = 0.004 + (Math.sin(bubble.floatTime * 0.3) * 0.002);
      if (Math.random() < changeChance) {
        bubble.vx += Phaser.Math.FloatBetween(-0.005, 0.005);
        bubble.vy += Phaser.Math.FloatBetween(-0.002, 0.002);
        bubble.vx = Phaser.Math.Clamp(bubble.vx, -0.020, 0.020);
        bubble.vy = Phaser.Math.Clamp(bubble.vy, -0.008, 0.008);
      }
    },
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
  const bubbleX = baseX ? baseX + Phaser.Math.Between(-20, 20) : Phaser.Math.Between(50, window.innerWidth - 50);
  const bubbleY = baseY ? baseY + Phaser.Math.Between(-15, 15) : Phaser.Math.Between(window.innerHeight * 0.4, window.innerHeight - 50);

  let bubble;
  const randomSize = Phaser.Math.FloatBetween(0.015, 0.05); // Smaller bubbles

  try {
    bubble = this.add.sprite(bubbleX, bubbleY, "bubble_overlay").setScale(randomSize);
  } catch (error) {
    // Fallback: small circle with random size
    const radius = Phaser.Math.Between(1, 3);
    bubble = this.add.circle(bubbleX, bubbleY, radius, 0x87ceeb, 0.6);
  }

  bubble.alpha = Phaser.Math.FloatBetween(0.3, 0.7); // Random transparency
  bubble.setDepth(8); // Between ocean floor bubbles and coral bubbles

  // Add unique floating properties (more variation)
  bubble.vx = Phaser.Math.FloatBetween(-0.015, 0.015);
  bubble.vy = Phaser.Math.FloatBetween(-0.004, 0.004);
  bubble.floatTime = Math.random() * Math.PI * 4; // Larger random start phase range
  bubble.floatSpeed = Phaser.Math.FloatBetween(0.5, 1.8); // Wider individual float speed range
  bubble.driftStrength = Phaser.Math.FloatBetween(0.2, 1.3); // Wider individual drift strength range
  bubble.phaseOffsetX = Math.random() * Math.PI * 2; // Individual phase offset for X movement
  bubble.phaseOffsetY = Math.random() * Math.PI * 2; // Individual phase offset for Y movement

  // Animate bubble rising up with longer duration
  const riseHeight = Phaser.Math.Between(120, 250); // Variable rise height
  const duration = Phaser.Math.Between(6000, 12000); // Much longer duration (6-12 seconds)

  this.tweens.add({
    targets: bubble,
    y: bubble.y - riseHeight,
    alpha: 0,
    duration: duration,
    ease: "Sine.easeOut",
    onUpdate: () => {
      // Add individual natural floating movement with unique phase offsets
      bubble.floatTime += 0.007 * bubble.floatSpeed;
      const driftX = Math.sin((bubble.floatTime + bubble.phaseOffsetX) * 1.4) * bubble.driftStrength;
      const driftY = Math.cos((bubble.floatTime + bubble.phaseOffsetY) * 0.8) * (bubble.driftStrength * 0.3);

      bubble.x += bubble.vx + driftX;
      bubble.y += bubble.vy + driftY * 0.05;

      // Individual direction changes with varying probabilities
      const changeChance = 0.004 + (Math.sin(bubble.floatTime * 0.15) * 0.003);
      if (Math.random() < changeChance) {
        bubble.vx += Phaser.Math.FloatBetween(-0.004, 0.004);
        bubble.vy += Phaser.Math.FloatBetween(-0.002, 0.002);
        bubble.vx = Phaser.Math.Clamp(bubble.vx, -0.018, 0.018);
        bubble.vy = Phaser.Math.Clamp(bubble.vy, -0.006, 0.006);
      }
    },
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
      // Enhanced underwater coral swaying animation - made more visible
      coral.swayTime += dt * 0.8; // Increased speed for more noticeable movement
      coral.bobTime = coral.bobTime || 0;
      coral.bobTime += dt * 1.0; // Increased bobbing speed

      // Multi-layered swaying motion - increased amplitudes for visibility
      const primarySway = Math.sin(coral.swayTime) * 4.0; // Increased from 1.2 to 4.0
      const secondarySway = Math.sin(coral.swayTime * 1.7) * 1.5; // Increased from 0.4 to 1.5
      const tertiarySway = Math.cos(coral.swayTime * 0.8) * 2.0; // Increased from 0.6 to 2.0

      // Vertical bobbing motion (like underwater current) - increased for visibility
      const primaryBob = Math.cos(coral.bobTime * 1.1) * 1.5; // Increased from 0.3 to 1.5
      const secondaryBob = Math.sin(coral.bobTime * 1.8) * 0.8; // Increased from 0.15 to 0.8

      // More noticeable rotation/tilt
      const tilt = Math.sin(coral.swayTime * 0.9) * 0.08; // Increased from 0.02 to 0.08 radians (~4.5 degrees)

      // Apply all movements relative to original position
      coral.x = coral.originalX + primarySway + secondarySway + tertiarySway;
      coral.y = coral.originalY + primaryBob + secondaryBob;

      // Apply rotation if coral is a sprite (not circle fallback)
      if (coral.setRotation) {
        coral.setRotation(tilt);
      }

      // Add subtle scale variation to enhance visibility of movement
      if (coral.setScale) {
        const baseScale = coral.baseScale || 0.5; // Store original scale if not stored
        if (!coral.baseScale) coral.baseScale = baseScale;
        const scaleVariation = 1 + Math.sin(coral.swayTime * 1.3) * 0.02; // ±2% scale variation for more subtle effect
        coral.setScale(baseScale * scaleVariation);
      }

      // Add subtle alpha variation to enhance visibility
      if (coral.setAlpha) {
        const baseAlpha = coral.baseAlpha || 1.0; // Store original alpha if not stored
        if (!coral.baseAlpha) coral.baseAlpha = baseAlpha;
        const alphaVariation = 1 + Math.sin(coral.bobTime * 0.7) * 0.1; // ±10% alpha variation
        coral.setAlpha(Math.max(0.7, baseAlpha * alphaVariation)); // Ensure minimum visibility
      }

      // Add subtle tint variation to enhance movement visibility
      if (coral.setTint) {
        const tintPhase = coral.swayTime * 0.5;
        const tintStrength = 0.15; // Subtle tint variation
        const r = 1 + Math.sin(tintPhase) * tintStrength;
        const g = 1 + Math.sin(tintPhase + Math.PI / 3) * tintStrength;
        const b = 1 + Math.sin(tintPhase + (2 * Math.PI) / 3) * tintStrength;

        // Convert to 0-255 range and create tint
        const tintR = Math.floor(Math.max(0, Math.min(255, r * 255)));
        const tintG = Math.floor(Math.max(0, Math.min(255, g * 255)));
        const tintB = Math.floor(Math.max(0, Math.min(255, b * 255)));
        const tintColor = (tintR << 16) | (tintG << 8) | tintB;

        coral.setTint(tintColor);
      }

      // Debug logging to confirm movement is being applied
      if (Math.floor(time / 1000) % 3 === 0 && coral.swayTime % (Math.PI * 2) < 0.1) {
        console.log(`Coral at original (${coral.originalX}, ${coral.originalY}) now at (${coral.x.toFixed(1)}, ${coral.y.toFixed(1)}) - sway: ${primarySway.toFixed(1)}, bob: ${primaryBob.toFixed(1)}, tilt: ${(tilt * 180 / Math.PI).toFixed(1)}°`);
      }
    }
    // Note: All animation phases are now handled by tweens, no additional movement needed
  });

  // Update coral bubbles
  if (this.coralBubblesGroup) {
    this.coralBubblesGroup.getChildren().forEach((bubble) => {
      // Add gentle floating movement to rising bubbles
      bubble.floatTime += dt;
      bubble.x += bubble.vx * dt * 15 + Math.sin(bubble.floatTime * 2) * 0.3;

      // Slight horizontal drift
      bubble.vx += Math.cos(bubble.floatTime * 1.5) * 0.002;

      // Keep bubbles from drifting too far
      if (Math.abs(bubble.vx) > 0.02) {
        bubble.vx *= 0.9;
      }
    });
  }

  // Ocean floor and random area bubbles have their movement handled in tween onUpdate
  // No additional update logic needed here as movement is in the tween animations

  // Update floating name bubbles with calmer movement
  if (this.nameBubbleGroup) {
    this.nameBubbleGroup.getChildren().forEach((nameBubble) => {
      // Much calmer floating movement
      nameBubble.x += nameBubble.vx * dt * 8; // Reduced from 25 to 8
      nameBubble.y += nameBubble.vy * dt * 6; // Reduced from 20 to 6

      // Add gentle floating oscillation with smaller radius
      nameBubble.floatTime += dt * 0.5; // Slower time progression
      const floatSpeed = 0.6; // Much slower float speed
      nameBubble.vy += Math.sin(nameBubble.floatTime * floatSpeed) * NAME_BUBBLE_FLOAT_SPEED * 0.3 * nameBubble.floatDirection; // Reduced intensity
      nameBubble.vx += Math.cos(nameBubble.floatTime * floatSpeed * 0.8) * NAME_BUBBLE_FLOAT_SPEED * 0.3 * nameBubble.floatDirection; // Reduced intensity

      // Add gentle circular motion
      const circleX = Math.sin(nameBubble.floatTime * 0.3) * nameBubble.floatRadius * dt * 0.5; // Much gentler
      const circleY = Math.cos(nameBubble.floatTime * 0.4) * nameBubble.floatRadius * dt * 0.5; // Much gentler
      nameBubble.x += circleX;
      nameBubble.y += circleY;

      // Keep name bubbles distributed across upper half of the screen only
      if (nameBubble.x < 80) {
        nameBubble.x = 80;
        nameBubble.vx = Math.abs(nameBubble.vx);
      }
      if (nameBubble.x > window.innerWidth - 80) {
        nameBubble.x = window.innerWidth - 80;
        nameBubble.vx = -Math.abs(nameBubble.vx);
      }
      if (nameBubble.y < 50) {
        nameBubble.y = 50;
        nameBubble.vy = Math.abs(nameBubble.vy);
      }
      if (nameBubble.y > window.innerHeight * 0.5) { // Restrict to upper half only
        nameBubble.y = window.innerHeight * 0.5;
        nameBubble.vy = -Math.abs(nameBubble.vy);
      }
    });
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
