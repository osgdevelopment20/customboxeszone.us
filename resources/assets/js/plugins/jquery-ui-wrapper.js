// jQuery UI Wrapper
// This wrapper ensures jQuery is properly loaded before initializing the plugin

// Use the jQuery that's already loaded via CDN
const $ = window.jQuery || window.$;

// Make sure jQuery is available before proceeding
if (!$) {
    console.error('jQuery is not available. Make sure it is properly loaded via CDN.');
}

// Now load the plugin
import './jquery-ui.js';

// Export jQuery for consistency
export default $;
