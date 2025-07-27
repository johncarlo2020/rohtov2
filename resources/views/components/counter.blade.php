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

<div class="tick" data-value="00,000.000" data-did-init="handleTickInit">
    <div data-value-mapping="money"
         data-credits="false"
         data-layout="horizontal fit"
         data-transform="
           -> split
           -> delay(rtl, 100, 150)
         ">
      <span data-repeat="true">
        <span data-view="flip"></span>
      </span>
    </div>
  </div>

@push('styles')
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flipclock@0.7.8/compiled/flipclock.css" /> -->
<link rel="stylesheet" id="pagestyle" href="{{ asset('assets/css/flip.css') }}"  />

@endpush

@push('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/flipclock@0.7.8/compiled/flipclock.min.js"></script> -->
 <script src="{{ asset('assets/js/plugins/flip.js') }}"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>
    function formatToMoneyString(value) {
        // Pad to 8 digits (e.g. 3008 → "00003008")
        const str = value.toString().padStart(8, '0');
        const whole = str.slice(0, 5);    // first 5 digits
        const decimal = str.slice(5);     // last 3 digits
        const formattedWhole = whole.replace(/(\d{2})(\d{3})/, '$1,$2');
        return `${formattedWhole}.${decimal}`;
    }

    function handleTickInit(tick) {
        // Save tick globally so Pusher can access it
        window._tickInstance = tick;

        // Optional: fetch once initially
        fetchAndUpdateCounter();
    }

    function fetchAndUpdateCounter() {
        fetch(`{{ route('pledge.counter') }}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.count !== undefined) {
                    const value = parseInt(data.count, 10);
                    if (!isNaN(value)) {
                        const formatted = formatToMoneyString(value);
                        if (window._tickInstance) {
                            window._tickInstance.value = `${formatted}`; // note: wrapped in single quotes
                        }
                    }
                } else {
                    console.error("Invalid data:", data);
                }
            })
            .catch(err => console.error("Fetch error:", err));
    }

    // Initialize Pusher
    Pusher.logToConsole = false;

    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    const channel = pusher.subscribe('live-feed-channel');
    channel.bind('live-feed-channel', function (data) {
        // Whenever the event is triggered, update the counter
        fetchAndUpdateCounter();
    });
</script>

<!-- <script>
    // Format number like 3008 → 00,003.008
    function formatToMoneyString(value) {
      const str = value.toString().padStart(8, '0'); // e.g., "003008"
      const whole = str.slice(0, 5);  // "003"
      const decimal = str.slice(5);  // "008"
      const formattedWhole = whole.replace(/(\d{2})(\d{3})/, '$1,$2'); // "003" → "00,003"
      return `${formattedWhole}.${decimal}`; // No $
    }

    function handleTickInit(tick) {
      Tick.data.poll(
        '{{ route('pledge.counter') }}', // Your Laravel route
        function (response) {
          try {
            const data = JSON.parse(response);
            const value = parseInt(data.count, 10);
            if (!isNaN(value)) {
              const formatted = formatToMoneyString(value); // e.g., "00,003.008"
              console.log(formatted);
              tick.value = `${formatted}`; // Must be wrapped in single quotes
            }
          } catch (e) {
            console.error('Invalid server response:', response);
          }
        },
      );
    }
  </script> -->


<!-- <script>
    function handleTickInit(tick) {
    // Polling your Laravel route every 5 seconds
    Tick.data.poll(
        '{{ route('pledge.counter') }}',
        function (response) {
            try {
                // Parse JSON response
                const data = JSON.parse(response);
                const value = parseFloat(data.count);

                if (!isNaN(value)) {
                    tick.value = value;
                } else {
                    console.warn('Invalid number in response:', data.count);
                }

            } catch (e) {
                console.error('Failed to parse response:', response);
            }
        },
        5000
    );
}
</script> -->
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
