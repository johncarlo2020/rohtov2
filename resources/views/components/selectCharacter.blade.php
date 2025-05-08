<div class="character-selection-container w-100">
    <div class="bubbles-container">
        @for ($i = 1; $i <= 5; $i++)
            <button onclick="selectSkin({{ $i }})" class="character-btn">
                {{-- Assuming image filenames correspond to the loop index, e.g., bubbles/1.webp --}}
                <img class="bubbles" src="{{ asset('images/character/bubbles/' . $i . '.webp') }}"
                    alt="Character {{ $i }}" />
            </button>
        @endfor
    </div>
</div>
