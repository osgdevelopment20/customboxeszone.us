/**
 * Shop-specific JavaScript functionality
 */

// Initialize shop functionality
export default function initShop() {
    // Initialize product filters
    initProductFilters();

    // Initialize product quantity controls
    initQuantityControls();

    // Initialize product image zoom
    initProductZoom();

    // Initialize product variations
    initProductVariations();
}

// Initialize product filters
function initProductFilters() {
    // Initialize price range slider if jQuery UI is available
    if (typeof $.fn.slider !== 'undefined') {
        $('#price-range').slider({
            range: true,
            min: 0,
            max: 1000,
            values: [0, 500],
            slide: function(event, ui) {
                $('#price-min').text('$' + ui.values[0]);
                $('#price-max').text('$' + ui.values[1]);
            }
        });
    }

    // Category filter toggle
    $('.filter-header').on('click', function() {
        $(this).next('.filter-content').slideToggle();
        $(this).toggleClass('active');
    });

    // Mobile filter toggle
    $('.mobile-filter-toggle').on('click', function(e) {
        e.preventDefault();
        $('.shop-filters').toggleClass('active');
    });

    // Apply filters
    $('#apply-filters').on('click', function(e) {
        e.preventDefault();
        // Collect filter values
        const filters = {
            category: $('.category-filter:checked').map(function() { return $(this).val(); }).get(),
            price: $('#price-range').slider('values'),
            // Add other filters as needed
        };

        // You can either submit a form or use AJAX to apply filters
        // For AJAX example:
        /*
        $.ajax({
            url: '/filter-products',
            method: 'GET',
            data: filters,
            success: function(response) {
                $('.products-container').html(response);
            }
        });
        */

        // For form submission:
        const form = $('#filter-form');
        // Add filter values to hidden inputs
        $.each(filters, function(key, value) {
            form.find(`input[name="${key}"]`).val(Array.isArray(value) ? value.join(',') : value);
        });
        form.submit();
    });
}

// Initialize product quantity controls
function initQuantityControls() {
    // Quantity increment/decrement
    $('.quantity-up').on('click', function() {
        const input = $(this).siblings('.qty-input');
        const currentValue = parseInt(input.val());
        input.val(currentValue + 1).trigger('change');
    });

    $('.quantity-down').on('click', function() {
        const input = $(this).siblings('.qty-input');
        const currentValue = parseInt(input.val());
        if (currentValue > 1) {
            input.val(currentValue - 1).trigger('change');
        }
    });

    // Update cart when quantity changes
    $('.qty-input').on('change', function() {
        const productId = $(this).data('product-id');
        const quantity = $(this).val();

        // You can use AJAX to update cart
        /*
        $.ajax({
            url: '/update-cart',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Update cart totals
                updateCartTotals(response);
            }
        });
        */
    });
}

// Initialize product image zoom
function initProductZoom() {
    // Check if elevateZoom is available
    if (typeof $.fn.elevateZoom !== 'undefined') {
        $('.product-image-zoom').elevateZoom({
            zoomType: "inner",
            cursor: "crosshair",
            zoomWindowFadeIn: 500,
            zoomWindowFadeOut: 750
        });
    }

    // Product gallery
    $('.product-gallery-item').on('click', function(e) {
        e.preventDefault();
        const imgSrc = $(this).attr('href');
        const imgLarge = $(this).data('image-large');

        // Update main image
        $('.product-image-zoom')
            .attr('src', imgSrc)
            .attr('data-zoom-image', imgLarge)
            .elevateZoom({
                zoomType: "inner",
                cursor: "crosshair",
                zoomWindowFadeIn: 500,
                zoomWindowFadeOut: 750
            });
    });
}

// Initialize product variations
function initProductVariations() {
    $('.product-variation-select').on('change', function() {
        const variationId = $(this).val();
        const productId = $(this).data('product-id');

        // You can use AJAX to get variation details
        /*
        $.ajax({
            url: `/get-variation/${productId}/${variationId}`,
            method: 'GET',
            success: function(response) {
                // Update price
                $('.product-price').text(response.price);

                // Update stock status
                if (response.in_stock) {
                    $('.stock-status').removeClass('out-of-stock').addClass('in-stock').text('In Stock');
                } else {
                    $('.stock-status').removeClass('in-stock').addClass('out-of-stock').text('Out of Stock');
                }

                // Update product image if needed
                if (response.image) {
                    $('.product-image-zoom')
                        .attr('src', response.image)
                        .attr('data-zoom-image', response.large_image)
                        .elevateZoom({
                            zoomType: "inner",
                            cursor: "crosshair",
                            zoomWindowFadeIn: 500,
                            zoomWindowFadeOut: 750
                        });
                }
            }
        });
        */
    });
}

// Call init function when document is ready
$(document).ready(function() {
    initShop();
});
