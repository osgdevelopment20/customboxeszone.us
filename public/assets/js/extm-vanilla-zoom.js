/*
    Vanilla JavaScript Extended Mag(nify) - Zoom on Hover (Right Side)
    Based on jQuery Extended Mag by Caleb O'Leary (extm.js)
    Converted to vanilla JavaScript for integration with custom product slider
*/

class ExtmZoom {
    constructor(element, userOptions = {}) {
        this.defaultOptions = {
            zoomElement: null,
            imageSrc: element.getAttribute('src'),
            squareOverlay: false,
            position: 'overlay', // Always overlay
            rightPad: 0,
            lazy: false,
            zoomLevel: 1,
            zoomSize: false,
            loadingText: false,
            loadingImage: false
        };
        this.options = Object.assign({}, this.defaultOptions, userOptions);
        this.imageElement = element;
        this.initialized = false;
        // Always initialize immediately for visible images
        if (this.imageElement.width > 10 && this.imageElement.height > 10) {
            this.init();
        } else {
            this.imageElement.addEventListener('load', () => this.init(), { once: true });
        }
    }

    init() {
        if (this.initialized) return;
        this.initialized = true;
        const smallWidth = this.imageElement.offsetWidth;
        const smallHeight = this.imageElement.offsetHeight;
        const rect = this.imageElement.getBoundingClientRect();
        const offset = {
            top: rect.top + window.scrollY,
            left: rect.left + window.scrollX
        };
        // Create zoom element as overlay
        if (!this.options.zoomElement) {
            this.zoomElement = document.createElement('div');
            this.zoomElement.style.overflow = 'hidden';
            this.zoomElement.style.pointerEvents = 'none';
            this.zoomElement.style.height = `${smallHeight}px`;
            this.zoomElement.style.width = `${smallWidth}px`;
            this.zoomElement.className = 'extm';
            // Always overlay
            document.body.appendChild(this.zoomElement);
            this.zoomElement.style.position = 'absolute';
            this.zoomElement.style.top = `${offset.top}px`;
            this.zoomElement.style.left = `${offset.left}px`;
        } else {
            this.zoomElement = this.options.zoomElement;
        }
        // Create full size image
        this.fullSizeImage = document.createElement('img');
        this.fullSizeImage.style.position = 'relative';
        this.fullSizeImage.style.maxWidth = 'none';
        if (this.options.zoomSize) {
            this.fullSizeImage.style.width = `${this.options.zoomSize}px`;
        } else if (this.options.zoomLevel !== 1) {
            this.fullSizeImage.style.width = `${100 * this.options.zoomLevel}%`;
        }
        if (this.options.imageSrc) {
            this.fullSizeImage.src = this.options.imageSrc;
        } else {
            this.fullSizeImage.src = this.imageElement.src;
        }
        this.zoomElement.appendChild(this.fullSizeImage);
        // Hide zoom element initially
        this.zoomElement.style.visibility = 'hidden';
        // Set up event handlers
        this.fullSizeImage.addEventListener('load', () => {
            this.setupEvents(smallWidth, smallHeight);
        });
    }

    setupEvents(smallWidth, smallHeight) {
        const zoomElement = this.zoomElement;
        const fullSizeImage = this.fullSizeImage;
        const imageElement = this.imageElement;
        // Mouse enter
        imageElement.addEventListener('mouseenter', () => {
            const rect = imageElement.getBoundingClientRect();
            const offset = {
                top: rect.top + window.scrollY,
                left: rect.left + window.scrollX
            };
            // Always overlay
            zoomElement.style.top = `${offset.top}px`;
            zoomElement.style.left = `${offset.left}px`;
            zoomElement.style.visibility = 'visible';
        });
        // Mouse leave
        imageElement.addEventListener('mouseleave', () => {
            zoomElement.style.visibility = 'hidden';
        });
        // Mouse move
        imageElement.addEventListener('mousemove', (event) => {
            const rect = imageElement.getBoundingClientRect();
            const offset = {
                top: rect.top + window.scrollY,
                left: rect.left + window.scrollX
            };
            const fullSizeWidth = fullSizeImage.offsetWidth;
            const fullSizeHeight = fullSizeImage.offsetHeight;
            const wRatio = fullSizeWidth / smallWidth;
            const hRatio = fullSizeHeight / smallHeight;
            const wDifference = 0 - (fullSizeWidth - zoomElement.offsetWidth);
            const hDifference = 0 - (fullSizeHeight - zoomElement.offsetHeight);
            let setTop = smallHeight / 2 - (event.pageY - offset.top) * hRatio;
            setTop = Math.max(setTop, hDifference);
            setTop = Math.min(setTop, 0);
            let setLeft = smallWidth / 2 - (event.pageX - offset.left) * wRatio;
            setLeft = Math.max(setLeft, wDifference);
            setLeft = Math.min(setLeft, 0);
            fullSizeImage.style.top = `${setTop}px`;
            fullSizeImage.style.left = `${setLeft}px`;
        });
    }

    destroy() {
        if (this.zoomElement) {
            this.zoomElement.remove();
        }
    }
    updateImage(newUrl) {
        if (this.fullSizeImage) {
            this.fullSizeImage.src = newUrl;
        }
    }
}
// Helper function to initialize ExtmZoom on only the first visible element
function extmZoomVisible(selector, options = {}) {
    // Only select the first visible element
    const elements = Array.from(document.querySelectorAll(selector));
    const visible = elements.find(el => el.offsetParent !== null && getComputedStyle(el).display !== 'none');
    const instances = [];
    if (visible) {
        instances.push(new ExtmZoom(visible, options));
    }
    return instances;
}

// Helper function to destroy all zoom overlays and initialize for the currently visible image
let extmCurrentZoomInstances = [];
function extmZoomForVisibleImage(selector, options = {}) {
    // Destroy previous overlays
    if (extmCurrentZoomInstances && Array.isArray(extmCurrentZoomInstances)) {
        extmCurrentZoomInstances.forEach(instance => instance.destroy());
    }
    extmCurrentZoomInstances = [];
    // Only select the first visible element
    const elements = Array.from(document.querySelectorAll(selector));
    const visible = elements.find(el => el.offsetParent !== null && getComputedStyle(el).display !== 'none');
    if (visible) {
        extmCurrentZoomInstances.push(new ExtmZoom(visible, options));
    }
}
