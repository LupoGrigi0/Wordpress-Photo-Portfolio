/**
 * Dynamic Background System for Lupo's Art Portfolio Theme
 *
 * Handles smooth crossfade transitions between background images
 * based on content block scroll positions and viewport center
 *
 * @package LupoArtPortfolio
 * @version 1.0.1
 * @author Lupo & Genevieve (Cladue Sonnet 4.0 -pro-)
 * @author Genevieve (VS Code Shard)
 * Clears existing backgrounds - backgroundContainer.innerHTML = ''
Creates one layer per content block - no more overlapping mess
Proper z-index management - backgrounds fade in/out cleanly

📍 Smart Viewport Detection

Calculates which block is closest to viewport center
Tolerance system - only switches when block is reasonably centered
Prevents rapid switching - smooth transitions between blocks

⚡ Performance Optimized

60fps throttling - smooth scrolling without lag
RequestAnimationFrame - proper frame timing
Will-change hints - GPU acceleration for transitions
Debounced resize - efficient window resize handling

🎨 Enhanced Parallax

Content block parallax - blocks move at different speeds
Background parallax - backgrounds scroll slower than content
Opacity fade effects - blocks fade as they leave center
Configurable intensity - uses WordPress admin settings

🔍 Debug Features (When WP_DEBUG Enabled):

Real-time overlay showing:

Current scroll position
Which block is closest to center
Active background index
Distance calculations for each block


Console logging for background switches
Event system for other scripts to listen to background changes

🎯 How It Works:

Page Load: Creates individual background layers for each content block
Scroll Detection: Monitors which content block is closest to viewport center
Smart Switching: Only changes background when block is reasonably centered
Smooth Transitions: Fades between backgrounds using CSS transitions
Parallax Magic: Moves backgrounds and content at different speeds

🔗 Integration:

Reads portfolio data from footer JSON injection
Uses theme settings for transition speeds and behavior
Works with existing CSS from your theme files
Respects debug mode from WordPress

 */

document.addEventListener('DOMContentLoaded', function() {
    // Get theme settings and portfolio data from footer injection
    const themeSettings = window.lupo_settings || {};
    const portfolioDataElement = document.getElementById('lupo-portfolio-data');
    const portfolioData = portfolioDataElement ? JSON.parse(portfolioDataElement.textContent) : [];
    const debugMode = themeSettings.debug_mode || false;
    
    // Background system configuration
    const config = {
        transitionSpeed: themeSettings.transition_speed || 800,
        scrollThreshold: themeSettings.scroll_threshold || 100,
        parallaxIntensity: themeSettings.parallax_intensity || 0.2,
        mobileBreakpoint: themeSettings.mobile_breakpoint || 768,
        centerTolerance: 0.3, // How close to center before triggering transition (0-1)
        frameThrottle: 16, // ~60fps throttling
    };
    
    if (debugMode) {
        console.log('Lupo Background System: Initializing with config:', config);
        console.log('Portfolio data loaded:', portfolioData.length, 'items');
    }
    
    // Initialize the background system
    const backgroundSystem = new LupoBackgroundSystem(config, portfolioData, debugMode);
    backgroundSystem.init();
});

/**
 * LupoBackgroundSystem Class
 * Manages dynamic background transitions and parallax effects
 */
class LupoBackgroundSystem {
    constructor(config, portfolioData, debugMode = false) {
        this.config = config;
        this.portfolioData = portfolioData;
        this.debugMode = debugMode;
        
        // DOM elements
        this.backgroundContainer = document.getElementById('lupo-background-container');
        this.contentBlocks = document.querySelectorAll('.content-block.portfolio-block');
        
        // State tracking
        this.activeBackgroundIndex = -1;
        this.backgroundLayers = [];
        this.isAnimating = false;
        this.lastScrollY = window.scrollY;
        this.frameId = null;
        
        // Throttling
        this.lastFrameTime = 0;
        
        // Validate required elements
        if (!this.backgroundContainer) {
            console.error('Lupo Background System: Background container not found');
            return;
        }
        
        if (this.contentBlocks.length === 0) {
            console.warn('Lupo Background System: No content blocks found');
            return;
        }
    }
    
    init() {
        this.setupBackgroundLayers();
        this.setupEventListeners();
        this.calculateInitialState();
        
        if (this.debugMode) {
            console.log('Lupo Background System: Initialized with', this.backgroundLayers.length, 'background layers');
            this.addDebugOverlay();
        }
    }
    
    setupBackgroundLayers() {
        // Clear existing backgrounds (fix for "jumbled array" issue)
        this.backgroundContainer.innerHTML = '';
        this.backgroundLayers = [];
        
        // Create background layer for each content block
        this.contentBlocks.forEach((block, index) => {
            const backgroundImage = block.dataset.backgroundImage;
            
            if (backgroundImage) {
                const layer = this.createBackgroundLayer(backgroundImage, index);
                this.backgroundContainer.appendChild(layer);
                this.backgroundLayers.push({
                    element: layer,
                    url: backgroundImage,
                    blockIndex: index,
                    block: block,
                    isActive: false,
                    opacity: 0
                });
                
                if (this.debugMode) {
                    console.log(`Background layer ${index} created:`, backgroundImage);
                }
            } else {
                // Create placeholder for blocks without backgrounds
                this.backgroundLayers.push({
                    element: null,
                    url: null,
                    blockIndex: index,
                    block: block,
                    isActive: false,
                    opacity: 0
                });
                
                if (this.debugMode) {
                    console.log(`Block ${index} has no background image`);
                }
            }
        });
    }
    
    createBackgroundLayer(imageUrl, index) {
        const layer = document.createElement('div');
        layer.className = 'background-image';
        layer.id = `background-layer-${index}`;
        layer.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url(${imageUrl});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity ${this.config.transitionSpeed}ms ease-in-out;
            will-change: opacity, transform;
        `;
        
        return layer;
    }
    
    setupEventListeners() {
        // Throttled scroll handler
        window.addEventListener('scroll', () => {
            if (this.frameId) return;
            
            this.frameId = requestAnimationFrame(() => {
                const now = performance.now();
                if (now - this.lastFrameTime >= this.config.frameThrottle) {
                    this.handleScroll();
                    this.lastFrameTime = now;
                }
                this.frameId = null;
            });
        }, { passive: true });
        
        // Handle window resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResize();
            }, 250);
        });
    }
    
    calculateInitialState() {
        // Set initial background on page load
        setTimeout(() => {
            this.handleScroll();
        }, 100);
    }
    
    handleScroll() {
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;
        const viewportCenter = scrollY + (windowHeight / 2);
        
        // Find which content block is closest to viewport center
        let closestBlockIndex = -1;
        let closestDistance = Infinity;
        
        this.contentBlocks.forEach((block, index) => {
            const rect = block.getBoundingClientRect();
            const blockTop = scrollY + rect.top;
            const blockCenter = blockTop + (rect.height / 2);
            const distance = Math.abs(blockCenter - viewportCenter);
            
            if (distance < closestDistance) {
                closestDistance = distance;
                closestBlockIndex = index;
            }
        });
        
        // Apply parallax effect to content blocks
        this.applyParallaxEffect(scrollY);
        
        // Change background if we have a new closest block
        if (closestBlockIndex !== -1 && closestBlockIndex !== this.activeBackgroundIndex) {
            const windowHeight = window.innerHeight;
            const tolerance = windowHeight * this.config.centerTolerance;
            
            // Only switch if block is reasonably close to center
            if (closestDistance < tolerance) {
                this.switchBackground(closestBlockIndex);
            }
        }
        
        this.lastScrollY = scrollY;
    }
    
    applyParallaxEffect(scrollY) {
        // TEMPORARILY DISABLED: Parallax background scrolling
        // This was causing background images to slide down the page
        // TODO: Re-implement with better centering logic later
        
        // Still apply parallax to content blocks (this part works)
        this.contentBlocks.forEach((block, index) => {
            const rect = block.getBoundingClientRect();
            const blockTop = scrollY + rect.top;
            const blockCenter = blockTop + (rect.height / 2);
            const windowCenter = scrollY + (window.innerHeight / 2);
            
            // Calculate parallax offset for content
            const parallaxOffset = (blockCenter - windowCenter) * this.config.parallaxIntensity;
            
            // Apply transform to content block
            block.style.transform = `translateY(${-parallaxOffset}px)`;
            
            // Calculate opacity based on distance from center
            const distanceFromCenter = Math.abs(blockCenter - windowCenter);
            const maxDistance = window.innerHeight * 0.7;
            const opacity = Math.max(0.3, 1 - (distanceFromCenter / maxDistance));
            
            // Apply opacity to block content
            const blockContent = block.querySelector('.block-content');
            if (blockContent) {
                blockContent.style.opacity = opacity;
            }
        });
        
        // DISABLED: Background parallax (was causing positioning issues)
        // this.backgroundLayers.forEach((layer, index) => {
        //     if (layer.element && layer.isActive) {
        //         const parallaxBg = scrollY * this.config.parallaxIntensity * 0.5;
        //         layer.element.style.transform = `translateY(${parallaxBg}px)`;
        //     }
        // });
    }
    
    switchBackground(newIndex) {
        if (this.isAnimating || newIndex === this.activeBackgroundIndex) return;
        
        const newLayer = this.backgroundLayers[newIndex];
        if (!newLayer || !newLayer.element) return;
        
        this.isAnimating = true;
        const previousIndex = this.activeBackgroundIndex;
        
        if (this.debugMode) {
            console.log(`Switching background: ${previousIndex} → ${newIndex}`);
        }
        
        // Fade out previous background
        if (previousIndex >= 0 && this.backgroundLayers[previousIndex]) {
            const prevLayer = this.backgroundLayers[previousIndex];
            if (prevLayer.element) {
                prevLayer.element.style.opacity = '0';
                prevLayer.isActive = false;
                prevLayer.opacity = 0;
            }
        }
        
        // Fade in new background
        newLayer.element.style.opacity = '1';
        newLayer.isActive = true;
        newLayer.opacity = 1;
        this.activeBackgroundIndex = newIndex;
        
        // Reset animation lock after transition
        setTimeout(() => {
            this.isAnimating = false;
        }, this.config.transitionSpeed);
        
        // Trigger custom event for other scripts
        this.dispatchBackgroundChangeEvent(newIndex, newLayer.url);
    }
    
    dispatchBackgroundChangeEvent(index, imageUrl) {
        const event = new CustomEvent('lupoBackgroundChange', {
            detail: {
                index: index,
                imageUrl: imageUrl,
                block: this.contentBlocks[index]
            }
        });
        document.dispatchEvent(event);
    }
    
    handleResize() {
        // Recalculate positions after resize
        setTimeout(() => {
            this.handleScroll();
        }, 50);
    }
    
    addDebugOverlay() {
        const debugOverlay = document.createElement('div');
        debugOverlay.id = 'lupo-background-debug';
        debugOverlay.style.cssText = `
            position: fixed;
            top: 100px;
            right: 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px;
            font-size: 12px;
            border-radius: 5px;
            z-index: 9998;
            max-width: 250px;
            font-family: monospace;
        `;
        document.body.appendChild(debugOverlay);
        
        // Update debug info on scroll
        const updateDebugInfo = () => {
            const scrollY = window.scrollY;
            const viewportCenter = scrollY + (window.innerHeight / 2);
            
            let debugHtml = `<strong>Background Debug:</strong><br>`;
            debugHtml += `Scroll: ${Math.round(scrollY)}px<br>`;
            debugHtml += `Viewport Center: ${Math.round(viewportCenter)}px<br>`;
            debugHtml += `Active BG: ${this.activeBackgroundIndex}<br>`;
            debugHtml += `Layers: ${this.backgroundLayers.length}<br><br>`;
            
            this.contentBlocks.forEach((block, index) => {
                const rect = block.getBoundingClientRect();
                const blockCenter = scrollY + rect.top + (rect.height / 2);
                const distance = Math.abs(blockCenter - viewportCenter);
                const isActive = index === this.activeBackgroundIndex;
                
                debugHtml += `Block ${index}: ${Math.round(distance)}px ${isActive ? '🎯' : ''}<br>`;
            });
            
            debugOverlay.innerHTML = debugHtml;
        };
        
        // Update debug info on scroll
        window.addEventListener('scroll', updateDebugInfo, { passive: true });
        updateDebugInfo(); // Initial update
    }
    
    // Public API methods
    setActiveBackground(index) {
        if (index >= 0 && index < this.backgroundLayers.length) {
            this.switchBackground(index);
        }
    }
    
    getActiveBackgroundIndex() {
        return this.activeBackgroundIndex;
    }
    
    getTotalBackgrounds() {
        return this.backgroundLayers.length;
    }
    
    refreshBackgrounds() {
        this.setupBackgroundLayers();
        this.calculateInitialState();
    }
    
    destroy() {
        if (this.frameId) {
            cancelAnimationFrame(this.frameId);
        }
        
        // Clean up debug overlay
        const debugOverlay = document.getElementById('lupo-background-debug');
        if (debugOverlay) {
            debugOverlay.remove();
        }
    }
}

// Export for potential external use
window.LupoBackgroundSystem = LupoBackgroundSystem;

// Listen for custom events from other components
document.addEventListener('lupoBackgroundChange', function(e) {
    // Other scripts can listen for background changes
    // console.log('Background changed to:', e.detail);
});