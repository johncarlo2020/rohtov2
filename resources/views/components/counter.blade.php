<div class="clock d-flex justify-content-center" data-url="{{ route('pledge.counter') }}">dd</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flipclock@0.7.8/compiled/flipclock.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flipclock@0.7.8/compiled/flipclock.min.js"></script>

<script>
    $(document).ready(function () {
      var clock = $('.clock').FlipClock(0, {
        clockFace: 'Counter',
        autoStart: true,
        minimumDigits: 4
      });

      // Get live count from Laravel backend
      $.ajax({
        url: '{{ route('pledge.counter') }}', // adjust to your actual route name
        type: 'GET',
        success: function (res) {
          var target = parseInt(res.count || 0); // fallback to 0 if missing
          var current = 0;

          var interval = setInterval(function () {
            current++;
            clock.setValue(current);
            if (current >= target) {
              clearInterval(interval);
            }
          }, 20);
        },
        error: function () {
          console.error("Failed to fetch counter value.");
        }
      });
    });
  </script>
@endpush
