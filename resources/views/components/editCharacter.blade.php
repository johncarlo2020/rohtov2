<div class="edit-character-container">
    <div class="selected-skin-container">
        <div id="characterName" class="skin-name-container">
        </div>
        <div id="characterEditContainer" class="character-container">
        </div>
    </div>
    <div class="options-container">
        <div class="option with-border">
            <div class="button-container">
                <p class="edit-btn">
                    <span>Hair</span>
                </p>
            </div>
            <div class="items">
                <div class="left-nav">
                    <div class="nav-items">
                        <i class="fa-solid fa-chevron-left"></i>
                        <img src="{{ asset('images/navbacground.webp') }}" alt="Hair 1" />
                    </div>
                </div>
                <div class="slider">
                    @for ($i = 1; $i <= 5; $i++)
                        <button class="item" onclick="selectItem('hair', {{ $i }}, this)">
                            <img src="{{ asset('images/character/choises/hair/' . $i . '.webp') }}" alt="Hair {{ $i }}" />
                        </button>
                    @endfor
                </div>
                <div class="right-nav">
                    <div class="nav-items">
                        <img src="{{ asset('images/navbacground.webp') }}" alt="Hair 1" />
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="devider">
            <img src="{{ asset('images/line.webp') }}" alt="Item 2" />
        </div>
        <div class="option">
            <div class="button-container">
                <p class="edit-btn">
                    <span>Face</span>
                </p>
            </div>
            <div class="items">
                <div class="left-nav">
                    <div class="nav-items">
                        <i class="fa-solid fa-chevron-left"></i>
                        <img src="{{ asset('images/navbacground.webp') }}" alt="Previous" />
                    </div>
                </div>
                <div class="slider">
                    @for ($i = 1; $i <= 5; $i++)
                        <button class="item" onclick="selectItem('face', {{ $i }}, this)">
                            <img src="{{ asset('images/character/choises/face/'.$i.'.webp') }}" alt="Face {{ $i }}" />
                        </button>
                    @endfor
                </div>
                <div class="right-nav">
                    <div class="nav-items">
                        <img src="{{ asset('images/navbacground.webp') }}" alt="Next" />
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="next-button-container text-center mt-5">
        <button onclick="gotoFinishPage()" class="next-button"><span>Done</span></button>
    </div>
</div>


<script>
    document.querySelectorAll('.option .items').forEach(itemsContainer => {
        const leftNav = itemsContainer.querySelector('.left-nav');
        const rightNav = itemsContainer.querySelector('.right-nav');
        const slider = itemsContainer.querySelector('.slider');

        leftNav.addEventListener('click', () => {
            slider.scrollBy({ left: -200, behavior: 'smooth' });
        });
        rightNav.addEventListener('click', () => {
            slider.scrollBy({ left: 200, behavior: 'smooth' });
        });
    });
</script>
