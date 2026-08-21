// Isotope Wrapper
// This wrapper ensures proper loading of the isotope library

// Use the jQuery that's already loaded via CDN
const $ = window.jQuery || window.$;

// Make sure jQuery is available before proceeding
if (!$) {
    console.error('jQuery is not available. Make sure it is properly loaded via CDN.');
}

// Import isotope modules individually to avoid require statements
// This approach allows Vite to properly resolve the dependencies
import 'isotope-layout/js/isotope';
import 'isotope-layout/js/item';
import 'isotope-layout/js/layout-mode';
import 'isotope-layout/js/layout-modes/masonry';
import 'isotope-layout/js/layout-modes/fit-rows';
import 'isotope-layout/js/layout-modes/vertical';

// Export jQuery for consistency
export default $;
