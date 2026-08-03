import Phaser from 'phaser';

const fragmentShader = `
precision mediump float;
uniform float     time;
uniform sampler2D uMainSampler;
varying vec2      outTexCoord;

void main(void) {
    // Extremely subtle, almost imperceptible waves
    float wave = sin(outTexCoord.y * 40.0 + time * 2.0) * 0.002;
    wave += sin(outTexCoord.y * 80.0 + time * 3.0) * 0.001;
    vec2 uv = outTexCoord;
    uv.x += wave;
    gl_FragColor = texture2D(uMainSampler, uv);
}
`;

export default class WigglePostFX extends Phaser.Renderer.WebGL.Pipelines.PostFXPipeline {
    constructor(game) {
        super({
            game,
            renderTarget: true,
            fragShader: fragmentShader
        });
    }
    onPreRender() {
        this.set1f('time', this.game.loop.time / 1000);
    }
}
