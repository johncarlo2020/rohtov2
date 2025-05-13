@props([
    'availableDates' => [],
    'inputName' => 'date',
    'selectedDateDisplayId' => 'selectedDateText',
    'initiallySelectedValue' => ''
])

<div class="date-picker">
    <h2 class="heading-text text-center mb-2">Date selected: <span id="{{ $selectedDateDisplayId }}"></span></h2>
    <div class="date-grid-container">
        @foreach ($availableDates as $index => $dateInfo)
            <div class="date-button-item">
                <input type="radio"
                       id="{{ $inputName . '-' . $index }}"
                       name="{{ $inputName }}"
                       value="{{ $dateInfo['value'] }}"
                       class="date-radio-input"
                       @if($dateInfo['value'] == $initiallySelectedValue && !$dateInfo['disabled']) checked @endif
                       @if($dateInfo['disabled']) disabled @endif
                       required>
                <label for="{{ $inputName . '-' . $index }}"
                       class="date-radio-label @if($dateInfo['disabled']) disabled @endif">
                    {{ $dateInfo['display'] }}
                </label>
            </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRadioInputs = document.querySelectorAll('input[name="{{ $inputName }}"].date-radio-input');
    const selectedDateTextElement = document.getElementById('{{ $selectedDateDisplayId }}');

    function updateSelectedDateDisplay(value) {
        const selectedRadio = Array.from(dateRadioInputs).find(input => input.value === value);
        if (selectedRadio) {
            const label = document.querySelector('label[for="' + selectedRadio.id + '"]');
            if (label && selectedDateTextElement) {
                selectedDateTextElement.textContent = label.textContent;
            }
        } else if (selectedDateTextElement) {
            const firstAvailable = Array.from(dateRadioInputs).find(input => !input.disabled);
            if(firstAvailable){
                 const label = document.querySelector('label[for="' + firstAvailable.id + '"]');
                 if (label && selectedDateTextElement) {
                    selectedDateTextElement.textContent = label.textContent;
                    if(!document.querySelector('input[name="{{ $inputName }}"].date-radio-input:checked')){
                        firstAvailable.checked = true; // Check it only if nothing else is checked
                    }
                }
            } else {
                 selectedDateTextElement.textContent = 'N/A';
            }
        }
    }

    const initiallyCheckedInput = document.querySelector('input[name="{{ $inputName }}"].date-radio-input:checked');
    if (initiallyCheckedInput) {
        updateSelectedDateDisplay(initiallyCheckedInput.value);
    } else {
        const firstEnabledInput = Array.from(dateRadioInputs).find(input => !input.disabled);
        if (firstEnabledInput) {
            firstEnabledInput.checked = true;
            updateSelectedDateDisplay(firstEnabledInput.value);
        } else if (selectedDateTextElement) {
             selectedDateTextElement.textContent = 'No dates available';
        }
    }

    dateRadioInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                updateSelectedDateDisplay(this.value);
            }
        });
    });
});
</script>
