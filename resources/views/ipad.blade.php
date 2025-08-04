<x-app-layout>
    <style>
    h2#total-weight
    {
        width: 25vw;
    }
    .weight-button {
      opacity: 0.7;
      transition: opacity 0.2s ease;
    }
    .weight-button.active {
      opacity: 1;
    }

    .total-display {
      font-size: 2rem;
      margin-bottom: 20px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      /* max-width: 400px; */
      margin: auto;
    }

    .weight-btn {
      border: 2px solid #e74c3c;
      border-radius: 10px;
      padding: 38px;
      font-weight: bold;
      font-size:20px;
      color: #e74c3c;
      cursor: pointer;
      position: relative;
      transition: background 0.2s;
    }

    .weight-btn.active {
      background: #e74c3c;
      color: #fff;
    }

    .counter {
        position: absolute;
        bottom: 5px;
        left: 5px;
        width: 28px;
        height: 28px;
        background: white;
        color: #dc3545;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .remove-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #fff;
        border: 2px solid #e74c3c;
        border-radius: 50%;
        color: #e74c3c;
        width: 30px;
        height: 30px;
        line-height: 21px;
        font-size: 20px;
        cursor: pointer;
    }

    .submit-btn {
      background: #e74c3c;
      color: white;
      padding: 15px 40px;
      margin-top: 30px;
      font-size: 1.2rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }

    .heading{
        font-size:36px;
        font-weight:bold;
    }

  </style>
    <div class="antialiased ipad-background h-100 ipad-page">
        <div class="py-3 container-fluid main-content">
            <div class="row">
                <div class="col-12 d-flex justify-content-center align-items-center animate-entry">
                    <div class="branding w-25 d-flex justify-conten-center">
                        <img class="logo" src="{{ asset('images/brand/logo.webp') }}" alt="Brand Logo" />
                    </div>
                </div>
                <h1 class="text-center animate-entry heading text-dark mt-4">BUYER'S VOLUME</h1>
                <div class="text-center col-12 mt-5 d-flex flex-column justify-content-center align-items-center">
                    <div class="d-block">
                        <form method="POST" action="{{route('ipad.store')}}" id="weightForm">
                            @csrf

                            <input type="hidden" name="weight" id="selectedWeight">

                            <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                            <!-- Placeholder Icon (Replace src if needed) -->
                            <img src="{{ asset('images/brand/rc_scale_img.png') }}" alt="scale" style="width: 30px; height: 30px;" />

                            <!-- Total Display -->
                            <h2 class="m-0 fw-bold text-dark" id="total-weight">0</h2>

                            <!-- G Box -->
                            <span class="bg-danger text-white rounded-2 px-3 py-1 fw-bold">G</span>
                            </div>

                            <div class="grid" id="weight-grid">
                                <!-- Buttons will be added dynamically -->
                            </div>

                            <button type="submit" class="submit-btn" style="width:570px;">SUBMIT</button>

                            <!-- <button type="submit" class="custom-btn bg-danger text-white submit-btn">SUBMIT</button> -->
                        </form>
                        <!-- <div class="col mb-3 animate-entry delay-2">
                            <a href="{{ route('ipad.info') }}" class="custom-btn custom-btn-secondary">START</a>
                        </div> -->
                    </div>
                </div>
            </div>
            <x-footer />
        </div>

        <!-- Thank You Modal -->
        <div class="modal modal-sm fade" id="thankYouModal" tabindex="-1" aria-labelledby="thankYouModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-5">
              <div class="modal-body">
              <!-- <h5 class="modal-title w-100 text-dark" id="thankYouModalLabel">Thank You!</h5> -->
                <p class="fs-5 text-dark">Thank you for pledging!</p>
              </div>
              <div class="justify-content-center">
                <a href="{{ route('ipad.index') }}" class="custom-btn bg-danger text-white">Submit</a>
              </div>
            </div>
          </div>
        </div>
    </div>
<script>
    const weights = [
      '4Kg', '2Kg', '1.5Kg', '1.2Kg',
      '800g', '400g', '300g', '85g'
    ];

    const grid = document.getElementById('weight-grid');
    const totalDisplay = document.getElementById('total-weight');
    const selectedWeightInput = document.getElementById('selectedWeight');

    const state = {};

    const toGrams = (val) => {
      const isKg = val.toLowerCase().includes('kg');
      return parseFloat(val) * (isKg ? 1000 : 1);
    };

    const updateTotal = () => {
      let total = 0;
      for (let key in state) {
        total += toGrams(key) * state[key];
      }
      totalDisplay.textContent = total.toLocaleString();
      selectedWeightInput.value = total;
    };

    const renderButtons = () => {
      grid.innerHTML = '';
      weights.forEach(weight => {
        const btn = document.createElement('div');
        btn.className = 'weight-btn';
        btn.textContent = weight;

        if (state[weight]) {
          btn.classList.add('active');

          const count = document.createElement('div');
          count.className = 'counter';
          count.textContent = state[weight];
          btn.appendChild(count);

          const remove = document.createElement('div');
          remove.className = 'remove-btn';
          remove.textContent = '–';
          remove.onclick = (e) => {
            e.stopPropagation();
            if (state[weight] > 1) {
              state[weight]--;
            } else {
              delete state[weight];
            }
            renderButtons();
            updateTotal();
          };
          btn.appendChild(remove);
        }

        btn.onclick = () => {
          state[weight] = (state[weight] || 0) + 1;
          renderButtons();
          updateTotal();
        };

        grid.appendChild(btn);
      });
    };

    renderButtons();
  </script>

@if(session('success'))
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const modal = new bootstrap.Modal(document.getElementById('thankYouModal'));
    modal.show();
  });
</script>
@endif
</x-app-layout>
