/**
 * Main JavaScript functionality for the website
 */

// Import any required libraries or modules
// import SomeLibrary from 'some-library';

// Initialize main functionality
export default function initMain() {
    // Initialize sliders
    initSliders();

    // Initialize other UI components
    initUIComponents();

    // Initialize scroll effects
    initScrollEffects();
}

// Initialize sliders and carousels
function initSliders() {
    // Check if slick is available
    if (typeof $.fn.slick !== 'undefined') {
        // Hero slider
        $('.hero-slider').slick({
            autoplay: true,
            autoplaySpeed: 5000,
            dots: true,
            arrows: true,
            fade: true,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false
                    }
                }
            ]
        });

        // Product sliders
        $('.product-slider').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            dots: false,
            arrows: true,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
}

// Initialize UI components
function initUIComponents() {
    // Initialize select2 if available
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').select2();
    }

    // Initialize magnific popup if available
    if (typeof $.fn.magnificPopup !== 'undefined') {
        $('.popup-link').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });
    }

    // Initialize tooltips and popovers if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }
}

// Initialize scroll effects
function initScrollEffects() {
    // Initialize WOW.js if available
    if (typeof WOW !== 'undefined') {
        new WOW().init();
    }

    // Smooth scroll for anchor links
    $('a.scroll-to').on('click', function(e) {
        e.preventDefault();
        const target = $(this.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 70
            }, 800);
        }
    });
}

// Call init function when document is ready
$(document).ready(function() {
    initMain();
});
