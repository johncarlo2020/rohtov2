<div class="p-5 get-name-container">
    <h1 class="text-center">PLEASE INSERT YOUR NAME</h1>
    <div class="input-container px-5">
        <input type="text" class="form-control rounded-pill text-center" id="name" aria-describedby="nameHelp" placeholder="your name">
        <div id="nameHelp" class="form-text text-center">*Maximum 5 character</div>
    </div>
    <div class="next-button-container text-center">
        <button onclick="addName()" class="next-button"><span>Next</span></button>
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
