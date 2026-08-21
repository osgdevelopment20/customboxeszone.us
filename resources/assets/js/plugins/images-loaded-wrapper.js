// ImagesLoaded Wrapper
// This wrapper ensures proper loading of the imagesloaded library

// Use the jQuery that's already loaded via CDN
const $ = window.jQuery || window.$;

// Make sure jQuery is available before proceeding
if (!$) {
    console.error('jQuery is not available. Make sure it is properly loaded via CDN.');
}

// Import the packaged version of imagesloaded from node_modules
// This version includes all dependencies and doesn't rely on require
import 'imagesloaded/imagesloaded.pkgd.min.js';

// Export jQuery for consistency
export default $;
