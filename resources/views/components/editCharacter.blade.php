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
                        <img src="{{ asset('images/navbacground.png') }}" alt="Hair 1" />
                    </div>
                </div>
                <div class="slider">
                    <button class="item" onclick="selectItem('hair', 1, this)">
                        <img src="{{ asset('images/character/hair/1.png') }}" alt="Hair 1" />
                    </button>
                    <button class="item" onclick="selectItem('hair', 2, this)">
                        <img src="{{ asset('images/character/hair/2.png') }}" alt="Hair 2" />
                    </button>
                    <button class="item" onclick="selectItem('hair', 1, this)">
                        <img src="{{ asset('images/character/hair/1.png') }}" alt="Hair 1" />
                    </button>
                    <button class="item" onclick="selectItem('hair', 2, this)">
                        <img src="{{ asset('images/character/hair/2.png') }}" alt="Hair 2" />
                    </button>
                </div>
                <div class="right-nav">
                    <div class="nav-items">
                        <img src="{{ asset('images/navbacground.png') }}" alt="Hair 1" />
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="devider">
            <img src="{{ asset('images/line.png') }}" alt="Item 2" />
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
                        <img src="{{ asset('images/navbacground.png') }}" alt="Previous" />
                    </div>
                </div>
                <div class="slider">
                    <button class="item" onclick="selectItem('face', 1, this)">
                        <img src="{{ asset('images/character/face/1.png') }}" alt="Face 1" />
                    </button>
                    <button class="item" onclick="selectItem('face', 2, this)">
                        <img src="{{ asset('images/character/face/2.png') }}" alt="Face 2" />
                    </button>
                    <button class="item" onclick="selectItem('face', 3, this)">
                        <img src="{{ asset('images/character/face/1.png') }}" alt="Face 3" />
                    </button>
                    <button class="item" onclick="selectItem('face', 4, this)">
                        <img src="{{ asset('images/character/face/2.png') }}" alt="Face 4" />
                    </button>
                </div>
                <div class="right-nav">
                    <div class="nav-items">
                        <img src="{{ asset('images/navbacground.png') }}" alt="Next" />
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="next-button-container text-center mt-5">
        <button onclick="addName()" class="next-button"><span>Done</span></button>
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
