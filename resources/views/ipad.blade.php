<x-app-layout>
    <style>
    .weight-button {
      opacity: 0.7;
      transition: opacity 0.2s ease;
    }
    .weight-button.active {
      opacity: 1;
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
                <div class="text-center col-12 mt-5 d-flex justify-content-center align-items-center" style="height:60vh">
                    <div class="d-block">
                        <h2 class="text-center animate-entry heading d-none">Total Pledge</h2>
                        <form method="POST" action="{{route('ipad.store')}}" id="weightForm">
                            @csrf

                            <input type="hidden" name="weight" id="selectedWeight">

                            <div class="d-flex flex-wrap justify-content-center mb-4">
                            @foreach(['4KG', '2KG', '400G', '200G', '85G'] as $weight)
                                <button type="button"
                                        class="col-3 btn-lg mx-3 mb-5 btn btn-danger rounded-pill px-4 py-2 fw-bold weight-button"
                                        data-weight="{{ $weight }}">
                                {{ $weight }}
                                </button>
                            @endforeach
                            </div>

                            <button type="submit" class="custom-btn bg-danger text-white btn-lg">Submit</button>
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
  const buttons = document.querySelectorAll('.weight-button');
  const hiddenInput = document.getElementById('selectedWeight');

  buttons.forEach(button => {
    button.addEventListener('click', () => {
      // Remove active class from all
      buttons.forEach(btn => btn.classList.remove('active'));
      // Add active class to the clicked one
      button.classList.add('active');
      // Set the hidden input value
      hiddenInput.value = button.dataset.weight;
    });
  });

  // Optional: prevent submission if nothing is selected
  document.getElementById('weightForm').addEventListener('submit', function(e) {
    if (!hiddenInput.value) {
      e.preventDefault();
      alert("Please select a weight before submitting.");
    }
  });
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
