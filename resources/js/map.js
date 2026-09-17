const mapPage = document.getElementById("map-page");
const mapAssets = mapPage.dataset;

let pledgeModalInstance; // Instance for the pledge modal (exampleModal)
let dateModalInstance; // Instance for the date modal




function showDateModal() {
    const modalElement = document.getElementById('date');
    if (!modalElement) return;

    const qrDiv = modalElement.querySelector('.qr');
    if (qrDiv) {
        qrDiv.innerHTML = ''; // Clear previous QR code if any


        new QRCode(qrDiv, {
            text: userSpecificUrl,
            width: 128, // You can adjust the size as needed
            height: 128,
            correctLevel: QRCode.CorrectLevel.H // Error correction level
        });
    }

    if (!dateModalInstance) { // Initialize if not already done for the date modal
        dateModalInstance = new bootstrap.Modal(modalElement);
    }
    dateModalInstance.show();
}

// Define showModal in the global scope so it can be accessed by the onclick attribute
function showModal() {
    const modalElement = document.getElementById('exampleModal');
    if (!modalElement) return;

    if (!pledgeModalInstance) { // Initialize if not already done for the pledge modal
        pledgeModalInstance = new bootstrap.Modal(modalElement);
    }

    // Get elements every time modal is shown to ensure fresh state
    const chooseDivLocal = modalElement.querySelector('#choose');
    const selectedDivLocal = modalElement.querySelector('#selected');
    const selectedAnswerImgLocal = modalElement.querySelector('#selected-answer-img');
    const pledgeYesRadioLocal = modalElement.querySelector('#pledgeYes');
    const pledgeNoRadioLocal = modalElement.querySelector('#pledgeNo');

    // if (pledgeYesRadioLocal) pledgeYesRadioLocal.checked = false;
    // if (pledgeNoRadioLocal) pledgeNoRadioLocal.checked = false;
    // if (selectedAnswerImgLocal) selectedAnswerImgLocal.src = ''; // Clear previous image
    // }
    // // pledgeModalInstance.show(); // Corrected: use the specific instance

    const storedPledge = localStorage.getItem('userPledgeChoice');

    if (storedPledge) {
        if (selectedAnswerImgLocal) {
            selectedAnswerImgLocal.src = storedPledge === 'yes' ? mapAssets.yesImage :
                mapAssets.noImage;
        }
        if (chooseDivLocal) chooseDivLocal.classList.add('d-none');
        if (selectedDivLocal) selectedDivLocal.classList.remove('d-none');
    } else {
        if (chooseDivLocal) chooseDivLocal.classList.remove('d-none');
        if (selectedDivLocal) selectedDivLocal.classList.add('d-none');
        if (pledgeYesRadioLocal) pledgeYesRadioLocal.checked = false;
        if (pledgeNoRadioLocal) pledgeNoRadioLocal.checked = false;
        if (selectedAnswerImgLocal) selectedAnswerImgLocal.src = ''; // Clear previous image
    }
    pledgeModalInstance.show(); // Use the correct instance to show
}

function initializeMap() {
    document.querySelector('[data-show-date-modal]')?.addEventListener('click', showDateModal);
    document.getElementById('pledge-image')?.addEventListener('click', showModal);
    const confirmVisitButton = document.getElementById('confirmVisitButton');
    const chooseDiv = document.getElementById('choose'); // Used by confirmVisitButton listener
    const selectedDiv = document.getElementById('selected'); // Used by confirmVisitButton listener
    const selectedAnswerImg = document.getElementById(
        'selected-answer-img'); // Used by confirmVisitButton listener
    const pledgeYesRadio = document.getElementById('pledgeYes');
    const pledgeNoRadio = document.getElementById('pledgeNo');
    const pledgeImage = document.getElementById(
        'pledge-image'); // The main clickable image outside the modal

    function applyStoredPledge() {
        const storedPledge = localStorage.getItem('userPledgeChoice');
        if (pledgeImage) { // Ensure pledgeImage element exists
            if (storedPledge === 'yes') {
                pledgeImage.src = mapAssets.pledgeImage;
            } else { // Covers 'no' or null/undefined, reverting to default
                pledgeImage.src = mapAssets.defaultPledgeImage;
            }
        }
    }

    applyStoredPledge(); // Apply on page load

    // Ensure the modal instance is created if not already by showModal (e.g. if other scripts need it)
    // This is somewhat redundant if showModal is the only entry point, but safe.
    const exampleModalElement = document.getElementById('exampleModal');
    if (exampleModalElement && !pledgeModalInstance) {
        pledgeModalInstance = new bootstrap.Modal(exampleModalElement);
    }
    const dateModalElement = document.getElementById('date');
    if (dateModalElement && !dateModalInstance) {
        dateModalInstance = new bootstrap.Modal(dateModalElement);
    }

    if (confirmVisitButton) {
        confirmVisitButton.addEventListener('click', function() {
            let choiceValue = '';
            let modalImgSrc = ''; // Image for inside the modal

            if (pledgeYesRadio && pledgeYesRadio.checked) {
                choiceValue = 'yes';
                modalImgSrc = mapAssets.yesImage;
                if (pledgeImage) {
                    pledgeImage.src =
                        mapAssets.pledgeImage; // Update main page image
                }
            } else if (pledgeNoRadio && pledgeNoRadio.checked) {
                choiceValue = 'no';
                modalImgSrc = mapAssets.noImage;
                if (pledgeImage) {
                    pledgeImage.src =
                        mapAssets.defaultPledgeImage; // Revert main page image
                }
            } else {
                // Optionally handle the case where neither is selected, e.g., show an alert
                console.log('No pledge option selected.');
                return;
            }

            if (selectedAnswerImg) {
                selectedAnswerImg.src = modalImgSrc; // Set the image inside the modal
            }

            localStorage.setItem('userPledgeChoice', choiceValue);

            // Switch views inside the modal
            if (chooseDiv) {
                chooseDiv.classList.add('d-none');
            }
            if (selectedDiv) {
                selectedDiv.classList.remove('d-none');
            }
        });
    }

    // Remove the fade-in class after the animation ends
    const fadeInElements = document.querySelectorAll('.fade-in');
    fadeInElements.forEach(element => {
        element.addEventListener('animationend', () => {
            element.classList.remove('fade-in');
        });
    });

    // Ensure breathing animation continues after fade-in
    const nextStationHelpers = document.querySelectorAll('.next-station-helper');
    nextStationHelpers.forEach(helper => {
        helper.addEventListener('animationend', (event) => {
            if (event.animationName === 'fadeIn') {
                helper.classList.remove('fade-in');
            }
        });
    });

    // The event listener for the 'close' button (id="close") has been removed earlier
    // as it relies on data-bs-dismiss="modal".

    // Example of how to clear the pledge for testing (you can adapt this to a button or other event)
    // document.getElementById('someClearButton').addEventListener('click', function() {
    //     localStorage.removeItem('userPledgeChoice');
    //     applyStoredPledge(); // Re-apply default state to pledgeImage
    //     alert('Pledge cleared from local storage.');
    // });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializeMap, { once: true });
} else {
    initializeMap();
}

