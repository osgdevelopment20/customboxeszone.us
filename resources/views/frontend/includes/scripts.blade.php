<!-- All scripts loaded with defer to prevent render-blocking -->

<script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}" defer></script>
<script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}" defer></script>
<!-- Removed unused heavy plugins: jquery-ui, perfect-scrollbar, imagesLoaded, scrollUp, theiaSticky -->
<script src="{{ asset('assets/js/main.js') }}" defer></script>


<!-- Search functionality optimized with vanilla JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBox = document.getElementById('search-box');
        const searchList = document.getElementById('search-list');
        const searchForm = document.getElementById('search-form-submit');

        if (searchBox) {
            searchBox.addEventListener('keyup', function() {
                const query = this.value;

                if (query === '') {
                    if (searchList) searchList.style.display = 'none';
                    return;
                }

                if (query !== '') {
                    // Use fetch API instead of jQuery ajax
                    fetch("{{route('search')}}" + "?query=" + encodeURIComponent(query), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (searchList) {
                            searchList.style.display = 'block';
                            searchList.innerHTML = data;
                            // Fade in effect with CSS transition
                            searchList.style.opacity = '0';
                            setTimeout(() => {
                                searchList.style.opacity = '1';
                            }, 10);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        }

        // Event delegation for dropdown item clicks
        document.addEventListener('click', function(e) {
            if (e.target && e.target.matches('a.dropdown-item')) {
                if (searchBox) searchBox.value = e.target.textContent;

                if (searchList) {
                    // Fade out effect with CSS transition
                    searchList.style.opacity = '0';
                    setTimeout(() => {
                        searchList.style.display = 'none';
                    }, 300);
                }

                if (searchForm) searchForm.submit();
            }
        });
    });
</script>

<!-- Add CSS for fade transitions and custom product slider -->
<style>
#search-list{transition:opacity 0.3s ease}

/* Custom Product Image Slider Styles */
.product-image-slider {
    position: relative;
    overflow: hidden;
    margin-bottom: 10px;
}

/*.product-image-slider figure {*/
/*    margin: 0;*/
/*    transition: opacity 0.3s ease;*/
/*}*/

.product-image-slider img {
    width: 100%;
    height: auto;
    cursor: crosshair;
    border-radius: 10px;
}

.slider-nav-thumbnails {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

.slider-nav-thumbnails div {
    width: calc(25% - 10px);
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.3s ease;
}

.slider-nav-thumbnails div.active {
    opacity: 1;
}

.slider-nav-thumbnails img {
    width: 100%;
    height: auto;
    border-radius: 10px;
}

.slider-nav-thumbnails div.active img {
    border: 2px solid #3BB77E;
    box-sizing: border-box;
}

.slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.slider-prev {
    left: 10px;
}

.slider-next {
    right: 10px;
}

.zoom-container {
    border-radius: 10px;
    cursor: crosshair;
    background-color: transparent;
}

@media (max-width: 991px) {
    .slider-nav-thumbnails div {
        width: calc(33.333% - 10px);
    }
}

@media (max-width: 575px) {
    .slider-nav-thumbnails div {
        width: calc(33.333% - 10px);
    }
}
</style>

<!-- Custom Hero Slider implemented in vanilla JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
</script>

<!-- News Ticker implemented in vanilla JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
</script>
