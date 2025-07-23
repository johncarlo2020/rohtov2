import Phaser from "phaser";

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
  // Load paw images using a loop
  for (let i = 1; i <= 9; i++) {
    this.load.image(`paw${i}`, fixImageUrl(`images/brand/animal_paws/cat_paw_${i}.png`));
  }

  // Start button
  this.load.image('startBtn', fixImageUrl(`images/brand/animal_paws/cat_kp_lm.gif`));

  // Load kibble image
  this.load.image('kibble', fixImageUrl(`images/brand/mobile_game_object/kibble.webp`));

  // Load bag images
  this.load.image('bagClosed', fixImageUrl(`images/brand/mobile_game_object/bag_close.webp`));
  this.load.image('bagOpen', fixImageUrl(`images/brand/mobile_game_object/bag_open.webp`));

  // Load cat sounds using a loop
  for (let i = 1; i <= 8; i++) {
    this.load.audio(`catSound${i}`, fixImageUrl(`images/brand/cat_sounds/cat_sound_${i}.mp3`));
  }
}

function create() {
  const centerX = this.cameras.main.width / 2;
  const centerY = this.cameras.main.height / 2;

  // Start button
  startBtn = this.add.image(centerX, centerY, 'startBtn').setInteractive();
  startBtn.setScale(0.8);

  // Create bag at bottom of screen (initially closed)
  bag = this.add.image(centerX, this.cameras.main.height - 250, 'bagClosed');
  bag.setScale(0.7); // Reduced size and moved higher to prevent cutting
  bag.setDepth(10); // Make sure bag appears above other elements

  startBtn.on('pointerdown', () => {
    startBtn.destroy(); // Remove start button
    isGameStarted = true;
  });

  // Main game click
  this.input.on('pointerdown', (pointer) => {
    if (!isGameStarted || gameOver) return;

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
