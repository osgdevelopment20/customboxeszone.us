// Magnific Popup Wrapper
// This wrapper ensures jQuery is properly loaded before initializing the plugin

// Import jQuery
import $ from 'jquery';

// Make sure jQuery is available globally
if (window.jQuery === undefined) {
    window.jQuery = window.$ = $;
}

// Now load the plugin
import './magnific-popup.js';

// Export jQuery for consistency
export default $;
