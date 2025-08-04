<!-- <div class="clock d-flex justify-content-center" data-url="{{ route('pledge.counter') }}">dd</div> -->

<!-- <div class="tick" data-value="" data-did-init="handleTickInit">

    <div data-value-mapping="indexes" data-layout="horizontal fit" data-transform="arrive(.2) -&gt; round -&gt; split -&gt; delay(rtl, 100, 150)">

        <span class="tick-text-inline">$</span>

        <span data-view="flip">1</span>
        <span data-view="flip">2</span>

        <span class="tick-text-inline text-dark">,</span>

        <span data-view="flip">8</span>
        <span data-view="flip">5</span>
        <span data-view="flip">0</span>

        <span class="tick-text-inline text-dark">.</span>
        <span data-view="flip">0</span>
        <span data-view="flip">0</span>
        <span data-view="flip">0</span>

    </div>

</div> -->

<div class="tick " id="ticker1" data-credits="false" data-value="0,000.000" data-did-init="handleTickInit2">
    <div data-value-mapping="money"
         data-credits="false"
         data-layout="horizontal fit"
         data-transform="
           -> split
           -> delay(rtl, 100, 150)
         "
         style="
         background-color:#fc0000;
         padding:20px;
         border-radius:20px;
         "
         >
      <div class="container-repeater">
        <span data-repeat="true">
          <span id="pledge" data-view="flip"></span>
        </span>
      </div>
      <!-- <span class="unit-label" style="color:white; font-size:130px; margin-left:10px; text-dark">g</span> -->
    </div>
  </div>

<div class="tick" id="ticker2" data-credits="false" data-value="0,000.000" data-did-init="handleTickInit">
    <div data-value-mapping="money"
         data-credits="false"
         data-layout="horizontal fit"
         data-transform="
           -> split
           -> delay(rtl, 100, 150)
         "
         style="
         background-color:#fc0000;
         padding:20px;
         border-radius:20px;
         "
         >
      <span data-repeat="true">
        <span id="percentage" data-view="flip"></span>
      </span>
    </div>
  </div>

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

/* 1920x1080 */

div#ticker1 {
    position: absolute;
    top: 25%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #fc0000;
    border-radius: 20px;
    /* padding: 20px; */
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 150px;
    color: white;
    white-space: nowrap;
    font-size:130px;
}

div#ticker2 {
    position: absolute;
    top: 64%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #fc0000;
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
     function formatToMoneyString(value) {
      const str = value.toString().padStart(7, '0'); // e.g., "003008"
      const whole = str.slice(0, 4);  // "003"
      const decimal = str.slice(4);  // "008"
      const formattedWhole = whole.replace(/(\d)(\d{3})/, '$1,$2')  ; // "003" → "00,003"
      return `${formattedWhole}.${decimal}`; // No $
    }

    
let currentCounterValue = null;
let currentPercentageValue = null;

function handleTickInit(tick) {
    window._tickInstance = tick;
    fetchAndUpdateData();
}

function handleTickInit2(tick) {
    window._tickInstance2 = tick;
    fetchAndUpdateData();
}

function fetchAndUpdateData() {
    fetch(`{{ route('pledge.counter') }}`)
        .then(res => res.json())
        .then(data => {
            updateCounter(data.count);
            updatePercentage(data.percentage);
        })
        .catch(err => console.error("Fetch error:", err));
}

function updateCounter(count) {
    if (typeof count === 'undefined') return;

    const value = parseInt(count, 10);
    if (!isNaN(value) && value !== currentCounterValue) {
        currentCounterValue = value;
        const formatted = formatToMoneyString(value);
        if (window._tickInstance) {
            window._tickInstance.value = formatted;
        }
    }
}

function updatePercentage(percentage) {
    if (typeof percentage === 'undefined') return;

    const value = parseInt(percentage, 10);
    if (!isNaN(value) && value !== currentPercentageValue) {
        currentPercentageValue = value;
        const formatted = formatToMoneyString(value);
        if (window._tickInstance2) {
            window._tickInstance2.value = formatted;
        }
    }
}

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
