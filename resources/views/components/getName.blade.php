<div class="p-5 get-name-container">
    <h1 class="text-center">PLEASE INSERT YOUR NAME</h1>
    <div class="input-container px-5">
        <input type="text" class="form-control rounded-pill text-center" id="name" maxlength="5" aria-describedby="nameHelp" placeholder="your name">
        <div id="nameHelp" class="form-text text-center mt-2">*Maximum 5 character</div>
    </div>
    <div class="next-button-container text-center">
        <button onclick="addName()" class="next-button"><span>Next</span></button>
    </div>
</div>

<script>
    const nameInput = document.getElementById('name');

    // Add an event listener to limit the input to 5 characters
    nameInput.addEventListener('input', function() {
        if (this.value.length > 5) {
            this.value = this.value.slice(0, 5);
        }
    });

</script>
