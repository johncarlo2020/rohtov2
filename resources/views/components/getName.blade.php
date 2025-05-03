<div class="p-5 select-name-container">
    <h1 class="text-center">PLEASE INSERT YOUR NAME</h1>
    <div class="mb-3">
        <input type="text" class="form-control rounded-pill text-center" id="name" aria-describedby="nameHelp">
        {{-- <div id="nameHelp" class="form-text">Please enter a valid name</div> --}}
    </div>
    <div class="text-center bottom-text-welcome col-12 mt-5">
        <button onclick="addName()" class="home-btn welcome-sign-btn btn rounded-pill"><span>Start</span></button>
    </div>
</div>

<script>
    const nameInput = document.getElementById('name');
    // add checker for name input if not empty
    function addName() {
        selectedCharacter.name = nameInput.value;
        // Check if the name is empty
        if (selectedCharacter.name.trim() === '') {
            return;
        }
        selectedCharacter.name = nameInput.value;
        nextStep();
    }
</script>
