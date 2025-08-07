<div class="tick " id="ticker1" data-credits="false" data-value="00,000,000.00" data-did-init="handleTickInit2">
    <div data-value-mapping="money"
         data-credits="false"
         data-layout="horizontal fit"
         data-transform="
           -> split
           -> delay(rtl, 100, 150)
         "
         >
      <div class="d-block">
        <div>
        <span class="text-dark" style="font-size:48px;"><strong>TOTAL PLEDGE</strong></span>
      </div>
      <div class="d-flex">
        <div class="container-repeater">
          <span data-repeat="true">
            <span id="pledge" data-view="flip"></span>
          </span>
        </div>
        <span class="unit-label text-dark d-flex align-items-center ms-4" style="font-size:70px;" ><strong>g</strong></span>
      </div>
      </div>
    </div>
  </div>

<div class="tick" id="ticker2" data-credits="false" data-value="01,000,000.00" data-did-init="handleTickInit">
    <div data-value-mapping="money"
         data-credits="false"
         data-layout="horizontal fit"
         data-transform="
           -> split
           -> delay(rtl, 100, 150)
         "

         >
      <div class="d-block mt-5">
        <div class="mt-5 mb-4" style="line-height:0";>
        <span id="contrib-text" class="text-dark" style="font-size:90px;"><strong>YOUR CONTRIBUTION <br>FROM YOUR PURCHASE</strong></span>
      </div>
      <div class="d-flex">
        <div class="container-repeater">
          <span data-repeat="true">
            <span id="percentage" data-view="flip"></span>
          </span>
        </div>
        <span class="unit-label text-dark d-flex align-items-center ms-4" style="font-size:90px;" ><strong>g</strong></span>
      </div>
      </div>
    </div>
  </div>
<audio id="flip-sound" src="{{ asset('assets/sound/flipcard.mp3') }}" preload="auto"></audio>

@push('styles')
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flipclock@0.7.8/compiled/flipclock.css" /> -->
<link rel="stylesheet" id="pagestyle" href="{{ asset('assets/css/flip.css') }}"  />
<style>
#percentage span,
#pledge span
{
color:rgb(252, 0, 0);

border-radius:0 !important;
}

span#pledge,
span#percentage
{
  margin: 1px;
}

.tick-flip-panel {
    background-color: #fff;
}

/* iPad and Safari specific fixes for decimal display */
@media screen and (-webkit-min-device-pixel-ratio: 1) {
  .tick [data-view="flip"] {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeSpeed;
  }

  /* Ensure decimal points are visible */
  .tick span {
    font-feature-settings: "tnum" 1;
    font-variant-numeric: tabular-nums;
  }
}

/* 1920x1080 */

.container-repeater {
    padding: 15px 28px;
    background: #fc0000;
    border-radius: 20px;
}

div#ticker1 {
    position: absolute;
    top: 5%;
    left: 50%;
    transform: translateX(-50%);
    /* background-color: #fc0000; */
    border-radius: 20px;
    /* padding: 20px; */
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 150px;
    color: white;
    white-space: nowrap;
    font-size:100px;
}

div#ticker2 {
    position: absolute;
    top: 30%;
    left: 50%;
    transform: translateX(-50%);
    /* background-color: #fc0000; */
    border-radius: 20px;
    /* padding: 20px; */
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 150px;
    color: white;
    white-space: nowrap;
}



</style>

@endpush

@push('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/flipclock@0.7.8/compiled/flipclock.min.js"></script> -->
<script src="{{ asset('assets/js/plugins/flip.js') }}"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>

  let currentCounterValue = null;
  let currentPercentageValue = 100000;
  let canPlaySound = false;
  const flipAudio = document.getElementById('flip-sound');

  // Enable sound on first user interaction
  function enableFlipSound() {
      canPlaySound = true;
      window.removeEventListener('click', enableFlipSound);
      window.removeEventListener('keydown', enableFlipSound);
      window.removeEventListener('touchstart', enableFlipSound);
  }
  window.addEventListener('click', enableFlipSound);
  window.addEventListener('keydown', enableFlipSound);
  window.addEventListener('touchstart', enableFlipSound);

  function playFlipSound() {
      if (canPlaySound && flipAudio) {
          flipAudio.currentTime = 0;
          flipAudio.play().catch(err => {
              console.warn("Audio play failed:", err);
          });
      }
  }

     function formatToMoneyString(value) {
      // Convert value to fixed 2 decimal string
      let [whole, decimal] = parseFloat(value).toFixed(2).split('.');

      // Pad with leading zeros to ensure exactly 6 digits for 000,000 format
      whole = whole.padStart(6, '0'); // e.g., "000425"

      // Format into 000,000 pattern
      const formattedWhole = whole.slice(0, 3) + ',' + whole.slice(3);
      return `${formattedWhole}.${decimal}`;
    }

    function formatToMoneyString2(value) {
      // Convert value to fixed 2 decimal string
      let [whole, decimal] = parseFloat(value).toFixed(2).split('.');

      // Pad with leading zeros to ensure at least 8 digits
      whole = whole.padStart(8, '0'); // e.g., "00100400"

      // Format into 00,000,000
      const formattedWhole = whole.replace(/(\d{2})(\d{3})(\d{3})/, '$1,$2,$3');

      return `${formattedWhole}.${decimal}`;
    }



function handleTickInit(tick) {
    window._tickInstance = tick;
    // Set initial value for the pledge counter
    const initialFormatted = formatToMoneyString(0);
    tick.value = initialFormatted;
    fetchAndUpdateData();
}

function handleTickInit2(tick) {
    window._tickInstance2 = tick;
    // Set initial value for the percentage counter with the base amount
    const initialFormatted = formatToMoneyString2(currentPercentageValue);
    tick.value = initialFormatted;
    fetchAndUpdateData();
}

function fetchAndUpdateData() {
    fetch(`{{ route('pledge.counter') }}`)
        .then(res => res.json())
        .then(data => {
            console.log('Fetched data:', data); // Debug log
            const countChanged = updateCounter(data.count);
            const percentageChanged = updatePercentage(data.percentage);

            if (countChanged || percentageChanged) {
                playFlipSound();
            }
        })
        .catch(err => console.error("Fetch error:", err));
}

function updateCounter(count) {
    if (typeof count === 'undefined') return false;

    const value = parseFloat(count);
    if (!isNaN(value) && value !== currentCounterValue) {
        currentCounterValue = value;
        const formatted = formatToMoneyString(value);
        if (window._tickInstance) {
            window._tickInstance.value = formatted;
        }
        return true;
    }
    return false;
}

function updatePercentage(percentage) {
    if (typeof percentage === 'undefined') return false;

    const value = parseFloat(percentage);
    if (!isNaN(value)) {
        const newValue = currentPercentageValue + value;
        const formatted = formatToMoneyString2(newValue);
        console.log('Percentage update - input:', percentage, 'parsed:', value, 'newValue:', newValue, 'formatted:', formatted);

        if (window._tickInstance2) {
            window._tickInstance2.value = formatted;
        }

        return true;
    }

    return false;
}

// function updateCounter(count) {
//     if (typeof count === 'undefined') return;

//     const value = parseInt(count, 10);
//     if (!isNaN(value) && value !== currentCounterValue) {
//         currentCounterValue = value;
//         const formatted = formatToMoneyString(value);
//         if (window._tickInstance) {
//             window._tickInstance.value = formatted;
//         }
//     }
// }

// function updatePercentage(percentage) {
//     if (typeof percentage === 'undefined') return;

//     const value = parseInt(percentage, 10);
//     if (!isNaN(value) && value !== currentPercentageValue) {
//         currentPercentageValue = value;
//         const formatted = formatToMoneyString2(value);
//         console.log(formatted);
//         if (window._tickInstance2) {
//             window._tickInstance2.value = formatted;
//         }
//     }
// }

    Pusher.logToConsole = false;

    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    const channel = pusher.subscribe('live-feed-channel');

    channel.bind('live-feed-event', function (data) {
        console.log('LiveFeedEvent received:', data);
        fetchAndUpdateData();
        // fetchAndUpdatePercentage();
    });

</script>




<!-- <script>
    var clock;
    $(document).ready(function () {

      // console.log("Document is ready, initializing FlipClock...");
      // clock = $('.clock').FlipClock(0, {
      //   clockFace: 'Counter',
      //   autoStart: false, // Do not start automatically
      //   minimumDigits: 4
      // });
      fetchAndAnimateCounter();
    });
    // Expose a function to set the counter value manually
    function setCounterValue(value) {
      if (clock) {
        clock.setValue(value);
      }
    }
    // Fetch and animate the counter value from backend
    function fetchAndAnimateCounter() {
      $.ajax({
        url: '{{ route('pledge.counter') }}',
        type: 'GET',
        success: function (res) {
            if (res && res.count) {
                setCounterValue(res.count);
                clock.start(); // Start the clock after setting the value
            } else {
                console.error("Invalid response from server:", res);
            }
        },
        error: function () {
          console.error("Failed to fetch counter value.");
        }
      });
    }

    // Pusher real-time update
    Pusher.logToConsole = false;
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });
    const channel = pusher.subscribe('baby-channel');
    channel.bind('baby-event', function(data) {
        fetchAndAnimateCounter();
    });
</script> -->
@endpush
