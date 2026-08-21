(function ($) {
    "use strict";
    // Removed preloader and onload modal to improve FCP/LCP
    /*-----------------
        Menu Stick
    -----------------*/
    var header = $('.sticky-bar');
    var win = $(window);
    win.on('scroll', function() {
        var scroll = win.scrollTop();
        if (scroll < 200) {
            header.removeClass('stick');
            $('.header-style-2 .categori-dropdown-active-large').removeClass('open');
            $('.header-style-2 .categori-button-active').removeClass('open');
        } else {
            header.addClass('stick');
        }
    });


    /*====== SidebarSearch ======*/
    function sidebarSearch() {
        var searchTrigger = $('.search-active'),
            endTriggersearch = $('.search-close'),
            container = $('.main-search-active');

        searchTrigger.on('click', function(e) {
            e.preventDefault();
            container.addClass('search-visible');
        });

        endTriggersearch.on('click', function() {
            container.removeClass('search-visible');
        });

    }
    sidebarSearch();

     /*====== Sidebar menu Active ======*/
    function mobileHeaderActive() {
        var navbarTrigger = $('.burger-icon'),
            endTrigger = $('.mobile-menu-close'),
            container = $('.mobile-header-active'),
            wrapper4 = $('body');

        wrapper4.prepend('<div class="body-overlay-1"></div>');

        navbarTrigger.on('click', function(e) {
            e.preventDefault();
            container.addClass('sidebar-visible');
            wrapper4.addClass('mobile-menu-active');
        });

        endTrigger.on('click', function() {
            container.removeClass('sidebar-visible');
            wrapper4.removeClass('mobile-menu-active');
        });

        $('.body-overlay-1').on('click', function() {
            container.removeClass('sidebar-visible');
            wrapper4.removeClass('mobile-menu-active');
        });
    }
    mobileHeaderActive();


   /*---------------------
        Mobile menu active
    ------------------------ */
    var $offCanvasNav = $('.mobile-menu'),
        $offCanvasNavSubMenu = $offCanvasNav.find('.dropdown');

    /*Add Toggle Button With Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.parent().prepend('<span class="menu-expand"><i class="fi-rs-angle-small-down"></i></span>');

    /*Close Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.slideUp();

    /*Category Sub Menu Toggle*/
    $offCanvasNav.on('click', 'li a, li .menu-expand', function(e) {
        var $this = $(this);
        if ( ($this.parent().attr('class').match(/\b(menu-item-has-children|has-children|has-sub-menu)\b/)) && ($this.attr('href') === '#' || $this.hasClass('menu-expand')) ) {
            e.preventDefault();
            if ($this.siblings('ul:visible').length){
                $this.parent('li').removeClass('active');
                $this.siblings('ul').slideUp();
            } else {
                $this.parent('li').addClass('active');
                $this.closest('li').siblings('li').removeClass('active').find('li').removeClass('active');
                $this.closest('li').siblings('li').find('ul:visible').slideUp();
                $this.siblings('ul').slideDown();
            }
        }
    });


})(jQuery);

/* News Ticker implemented in vanilla JavaScript */
    document.addEventListener('livewire:load', function() {
    const newsFlash = document.getElementById('news-flash');
    if (!newsFlash) return;

    const newsList = newsFlash.querySelector('ul');
    if (!newsList) return;

    const newsItems = newsList.querySelectorAll('li');
    if (newsItems.length <= 1) return;

    // Configuration
    const config = {
    speed: 500,      // Animation speed in milliseconds
    pause: 3000,     // Delay between animations in milliseconds
    animation: 'fade', // Animation type
    mousePause: false, // Whether to pause on mouse hover
    showItems: 1     // Number of items to show at once
};

    // Set up the container styles
    newsFlash.style.overflow = 'hidden';
    newsList.style.position = 'relative';
    newsList.style.display = 'block';
    newsList.style.margin = '0';
    newsList.style.padding = '0';

    // Hide all items except the first one
    let currentIndex = 0;
    newsItems.forEach((item, index) => {
    item.style.display = index === 0 ? 'block' : 'none';
    item.style.margin = '0';
    item.style.padding = '0';
    item.style.opacity = index === 0 ? '1' : '0';
    item.style.transition = `opacity ${config.speed}ms ease`;
});

    // Function to animate to the next item
    function showNextItem() {
    const prevIndex = currentIndex;
    currentIndex = (currentIndex + 1) % newsItems.length;

    // Fade out current item
    newsItems[prevIndex].style.opacity = '0';

    // After fade out, hide current and show next
    setTimeout(() => {
    newsItems[prevIndex].style.display = 'none';
    newsItems[currentIndex].style.display = 'block';

    // Trigger reflow to ensure transition works
    void newsItems[currentIndex].offsetWidth;

    // Fade in next item
    newsItems[currentIndex].style.opacity = '1';
}, config.speed);
}

    // Start the animation loop
    let interval = setInterval(showNextItem, config.pause + config.speed);

    // Add mouse pause functionality if enabled
    if (config.mousePause) {
    newsFlash.addEventListener('mouseenter', () => clearInterval(interval));
    newsFlash.addEventListener('mouseleave', () => {
    clearInterval(interval);
    interval = setInterval(showNextItem, config.pause + config.speed);
});
}
});
/* END News Ticker implemented in vanilla JavaScript*/


/*  Custom Testimonial Slider implemented in vanilla JavaScript*/
document.addEventListener('livewire:load', function() {
    // Custom implementation for hero-slider-1
    const heroSlider = document.querySelector('.hero-slider-1');
    if (!heroSlider) return;

    const slides = heroSlider.querySelectorAll('.single-hero-slider');
    if (slides.length <= 1) return;

    // Configuration
    const config = {
        speed: 500,      // Fade transition speed in milliseconds
        pause: 5000,     // Delay between slides in milliseconds
        autoplay: true   // Whether to automatically advance slides
    };

    // Add necessary styles for fade transition and navigation dots
    const style = document.createElement('style');
    style.textContent = `

           .slider-1-height-2 {
            height: 200px!important;
              }
            .hero-slider-1 {
                position: relative;
                overflow: hidden;
            }
            .single-hero-slider {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                opacity: 0;
                transition: opacity ${config.speed}ms ease;
                z-index: 1;
            }
            .single-hero-slider.active {
                opacity: 1;
                position: relative;
                z-index: 2;
            }
            .slider-dots {
                display: flex;
                justify-content: center;
                margin-top: 10px;
                position: relative;
                z-index: 3;
            }
            .slider-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background-color: #ccc;
                margin: 0 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            .slider-dot.active {
                background-color: #3BB77E;
            }
        `;
    document.head.appendChild(style);

    // Initialize: hide all slides except the first one
    let currentIndex = 0;
    slides.forEach((slide, index) => {
        if (index === 0) {
            slide.classList.add('active');
        } else {
            slide.classList.remove('active');
        }
    });

    // Create navigation dots
    const dotsContainer = document.createElement('div');
    dotsContainer.className = 'slider-dots';

    // Add dots for each slide
    for (let i = 0; i < slides.length; i++) {
        const dot = document.createElement('div');
        dot.className = 'slider-dot';
        if (i === 0) {
            dot.classList.add('active');
        }

        // Add click event to navigate to the corresponding slide
        dot.addEventListener('click', function() {
            // Clear autoplay interval when user interacts with dots
            if (interval) {
                clearInterval(interval);
            }
            showSlide(i);
            // Restart autoplay after user interaction
            if (config.autoplay) {
                interval = setInterval(nextSlide, config.pause);
            }
        });

        dotsContainer.appendChild(dot);
    }

    // Append dots container after the slider
    heroSlider.appendChild(dotsContainer);

    // Function to show a specific slide
    function showSlide(index) {
        // Hide current slide
        slides[currentIndex].classList.remove('active');
        dotsContainer.children[currentIndex].classList.remove('active');

        // Update current index
        currentIndex = index;

        // Show new slide
        slides[currentIndex].classList.add('active');
        dotsContainer.children[currentIndex].classList.add('active');
    }

    // Function to advance to the next slide
    function nextSlide() {
        const newIndex = (currentIndex + 1) % slides.length;
        showSlide(newIndex);
    }

    // Start autoplay if enabled
    let interval;
    if (config.autoplay) {
        interval = setInterval(nextSlide, config.pause);
    }
});

