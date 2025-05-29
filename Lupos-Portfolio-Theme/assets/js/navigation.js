/**
 * Enhanced Navigation System for Lupo's Art Portfolio Theme
 *
 * Implements scroll-direction-based fade logic, mouse proximity triggers,
 * and mobile-optimized navigation behavior
 *
 * @package LupoArtPortfolio
 * @author Lupo & Genevieve (Cladue Sonnet 4.0 -pro-)
 * @version 1.0.0
 * 
 * Scroll Direction Logic (Your Original Concept!)

Scrolling UP → Navigation fades IN ✨
Scrolling DOWN → Navigation fades OUT ✨
Smooth direction detection - uses history tracking to avoid jitter
Smart sensitivity - only responds to meaningful scroll movements

🎯 Enhanced Behavior ("¿Por qué no los dos?" Philosophy)

At very top → Hidden (clean first impression)
Mouse near top → Shows (even when scrolling down)
Mobile menu open → Always visible (practical override)
Focus events → Shows for accessibility

📱 Mobile Optimized

Touch-friendly hamburger menu
Prevents body scroll when menu is open
Escape key closes mobile menu
Click outside closes mobile menu
Resize handling - closes menu when switching to desktop

⚡ Performance Excellence

60fps throttling - smooth scroll detection
Direction smoothing - tracks recent scroll history
Debounced events - efficient event handling
RequestAnimationFrame - proper timing

🔍 Debug Features (When WP_DEBUG Enabled):
Real-time overlay showing:

Current scroll position and direction
Navigation visibility state
Mouse proximity status
Mobile menu state
Scroll direction history

🎯 How It Works:

Scroll Detection: Monitors scroll direction with smoothing algorithm
Direction Logic: UP = show, DOWN = hide (your original vision!)
Smart Overrides: Mouse proximity and mobile menu keep it visible when needed
Smooth Transitions: CSS-based fades with configurable timing
Mobile Excellence: Touch-optimized menu with proper state management

🔗 Integration Points:

Uses theme settings from WordPress admin (when customizer is built)
Respects debug mode for development feedback
Works with existing header.php markup
Public API for other scripts to control navigation
Custom events ready for background system integration
 * 
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get theme settings from footer injection
    const themeSettings = window.lupo_settings || {};
    const debugMode = themeSettings.debug_mode || false;
    
    // Navigation configuration
    const config = {
        scrollThreshold: themeSettings.scroll_threshold || 50,
        fadeDelay: themeSettings.nav_fade_delay || 3000,
        mobileBreakpoint: themeSettings.mobile_breakpoint || 768,
        mouseProximityZone: 100, // pixels from top to trigger on mouse movement
        scrollDirectionSensitivity: 10, // minimum scroll distance to detect direction
        transitionSpeed: 300, // milliseconds for fade transitions
        debounceDelay: 16, // ~60fps throttling
    };
    
    if (debugMode) {
        console.log('Lupo Navigation: Initializing with config:', config);
    }
    
    // Initialize the navigation system
    const navigationSystem = new LupoNavigationSystem(config, debugMode);
    navigationSystem.init();
});

/**
 * LupoNavigationSystem Class
 * Manages enhanced navigation behavior with scroll direction detection
 */
class LupoNavigationSystem {
    constructor(config, debugMode = false) {
        this.config = config;
        this.debugMode = debugMode;
        
        // DOM elements
        this.navigation = document.getElementById('lupo-main-navigation');
        this.mobileToggle = document.querySelector('.mobile-nav-toggle');
        this.primaryMenu = document.getElementById('primary-menu');
        
        // State tracking
        this.lastScrollY = window.scrollY;
        this.scrollDirection = 'none';
        this.isVisible = false;
        this.fadeTimeout = null;
        this.frameId = null;
        this.lastFrameTime = 0;
        this.isMobileMenuOpen = false;
        this.isMouseNearTop = false;
        
        // Scroll direction detection
        this.scrollHistory = [];
        this.scrollHistoryLength = 3; // frames to track for direction smoothing
        
        // Validate required elements
        if (!this.navigation) {
            console.error('Lupo Navigation: Navigation element not found');
            return;
        }
    }
    
    init() {
        this.setupEventListeners();
        this.setupMobileNavigation();
        this.calculateInitialState();
        
        if (this.debugMode) {
            console.log('Lupo Navigation: Initialized');
            this.addDebugOverlay();
        }
    }
    
    setupEventListeners() {
        // Throttled scroll handler with direction detection
        window.addEventListener('scroll', () => {
            if (this.frameId) return;
            
            this.frameId = requestAnimationFrame(() => {
                const now = performance.now();
                if (now - this.lastFrameTime >= this.config.debounceDelay) {
                    this.handleScroll();
                    this.lastFrameTime = now;
                }
                this.frameId = null;
            });
        }, { passive: true });
        
        // Mouse movement detection for proximity triggers
        document.addEventListener('mousemove', (e) => {
            this.handleMouseMovement(e);
        }, { passive: true });
        
        // Touch start for mobile (prevent mouse events on touch devices)
        document.addEventListener('touchstart', () => {
            this.isMouseNearTop = false;
        }, { passive: true });
        
        // Handle focus events for accessibility
        this.navigation.addEventListener('focus', () => {
            this.showNavigation('focus');
        }, true);
        
        this.navigation.addEventListener('blur', () => {
            if (!this.isMobileMenuOpen) {
                this.scheduleHide();
            }
        }, true);
        
        // Window resize handler
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                this.handleResize();
            }, 250);
        });
        
        // Visibility change (tab switching)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.clearFadeTimeout();
            }
        });
    }
    
    handleScroll() {
        const currentScrollY = window.scrollY;
        const scrollDelta = currentScrollY - this.lastScrollY;
        
        // Update scroll direction with smoothing
        this.updateScrollDirection(scrollDelta);
        
        // Apply scroll-direction-based logic
        this.applyScrollDirectionLogic(currentScrollY, scrollDelta);
        
        this.lastScrollY = currentScrollY;
        
        if (this.debugMode) {
            this.updateDebugInfo();
        }
    }
    
    updateScrollDirection(scrollDelta) {
        // Only track significant scroll movements
        if (Math.abs(scrollDelta) < this.config.scrollDirectionSensitivity) {
            return;
        }
        
        // Add to history
        this.scrollHistory.push(scrollDelta > 0 ? 'down' : 'up');
        
        // Keep history length manageable
        if (this.scrollHistory.length > this.scrollHistoryLength) {
            this.scrollHistory.shift();
        }
        
        // Determine dominant direction from recent history
        const upCount = this.scrollHistory.filter(dir => dir === 'up').length;
        const downCount = this.scrollHistory.filter(dir => dir === 'down').length;
        
        if (upCount > downCount) {
            this.scrollDirection = 'up';
        } else if (downCount > upCount) {
            this.scrollDirection = 'down';
        }
        // If equal, keep previous direction
    }
    
    applyScrollDirectionLogic(scrollY, scrollDelta) {
        const isAtTop = scrollY <= this.config.scrollThreshold;
        const isScrollingUp = this.scrollDirection === 'up';
        const isScrollingDown = this.scrollDirection === 'down';
        
        // At the very top - always hide unless mouse is near top or mobile menu is open
        if (isAtTop) {
            if (this.isMouseNearTop || this.isMobileMenuOpen) {
                this.showNavigation('top-mouse');
            } else {
                this.hideNavigation('at-top');
            }
            return;
        }
        
        // Scroll direction based logic (your original vision!)
        if (isScrollingUp) {
            // Scrolling up - show navigation
            this.showNavigation('scroll-up');
        } else if (isScrollingDown) {
            // Scrolling down - hide navigation (unless mobile menu is open)
            if (!this.isMobileMenuOpen) {
                this.hideNavigation('scroll-down');
            }
        }
        
        // Mouse proximity override
        if (this.isMouseNearTop) {
            this.showNavigation('mouse-proximity');
        }
    }
    
    handleMouseMovement(e) {
        const wasNearTop = this.isMouseNearTop;
        this.isMouseNearTop = e.clientY < this.config.mouseProximityZone;
        
        // Only trigger if state changed
        if (this.isMouseNearTop && !wasNearTop) {
            this.showNavigation('mouse-enter');
        } else if (!this.isMouseNearTop && wasNearTop) {
            // Don't immediately hide on mouse leave, let scroll logic handle it
            this.scheduleHide();
        }
    }
    
    showNavigation(reason = 'unknown') {
        if (this.isVisible) return;
        
        this.isVisible = true;
        this.navigation.classList.add('visible');
        this.clearFadeTimeout();
        
        if (this.debugMode) {
            console.log(`Navigation shown: ${reason}`);
        }
    }
    
    hideNavigation(reason = 'unknown') {
        if (!this.isVisible) return;
        
        this.isVisible = false;
        this.navigation.classList.remove('visible');
        this.clearFadeTimeout();
        
        if (this.debugMode) {
            console.log(`Navigation hidden: ${reason}`);
        }
    }
    
    scheduleHide() {
        if (this.isMobileMenuOpen) return;
        
        this.clearFadeTimeout();
        this.fadeTimeout = setTimeout(() => {
            if (!this.isMouseNearTop && !this.isMobileMenuOpen) {
                this.hideNavigation('timeout');
            }
        }, this.config.fadeDelay);
    }
    
    clearFadeTimeout() {
        if (this.fadeTimeout) {
            clearTimeout(this.fadeTimeout);
            this.fadeTimeout = null;
        }
    }
    
    setupMobileNavigation() {
        if (!this.mobileToggle || !this.primaryMenu) return;
        
        this.mobileToggle.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggleMobileMenu();
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (this.isMobileMenuOpen && !this.navigation.contains(e.target)) {
                this.closeMobileMenu();
            }
        });
        
        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isMobileMenuOpen) {
                this.closeMobileMenu();
            }
        });
    }
    
    toggleMobileMenu() {
        if (this.isMobileMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }
    
    openMobileMenu() {
        this.isMobileMenuOpen = true;
        this.mobileToggle.setAttribute('aria-expanded', 'true');
        this.mobileToggle.classList.add('active');
        this.primaryMenu.classList.add('mobile-open');
        document.body.style.overflow = 'hidden';
        
        // Ensure navigation is visible when mobile menu is open
        this.showNavigation('mobile-menu-open');
        
        if (this.debugMode) {
            console.log('Mobile menu opened');
        }
    }
    
    closeMobileMenu() {
        this.isMobileMenuOpen = false;
        this.mobileToggle.setAttribute('aria-expanded', 'false');
        this.mobileToggle.classList.remove('active');
        this.primaryMenu.classList.remove('mobile-open');
        document.body.style.overflow = '';
        
        // Let normal scroll logic handle visibility after mobile menu closes
        setTimeout(() => {
            if (!this.isMouseNearTop && window.scrollY > this.config.scrollThreshold) {
                this.scheduleHide();
            }
        }, 100);
        
        if (this.debugMode) {
            console.log('Mobile menu closed');
        }
    }
    
    calculateInitialState() {
        const initialScrollY = window.scrollY;
        
        if (initialScrollY <= this.config.scrollThreshold) {
            this.hideNavigation('initial-at-top');
        } else {
            this.showNavigation('initial-scrolled');
            this.scheduleHide();
        }
    }
    
    handleResize() {
        const isMobile = window.innerWidth <= this.config.mobileBreakpoint;
        
        // Close mobile menu on resize to desktop
        if (!isMobile && this.isMobileMenuOpen) {
            this.closeMobileMenu();
        }
        
        // Recalculate state
        setTimeout(() => {
            this.calculateInitialState();
        }, 50);
    }
    
    addDebugOverlay() {
        const debugOverlay = document.createElement('div');
        debugOverlay.id = 'lupo-navigation-debug';
        debugOverlay.style.cssText = `
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px;
            font-size: 12px;
            border-radius: 5px;
            z-index: 9999;
            max-width: 200px;
            font-family: monospace;
        `;
        document.body.appendChild(debugOverlay);
        this.debugOverlay = debugOverlay;
        this.updateDebugInfo();
    }
    
    updateDebugInfo() {
        if (!this.debugOverlay) return;
        
        const scrollY = Math.round(window.scrollY);
        const mouseY = this.isMouseNearTop ? '< 100px' : '> 100px';
        
        let debugHtml = `<strong>Navigation Debug:</strong><br>`;
        debugHtml += `Scroll: ${scrollY}px<br>`;
        debugHtml += `Direction: ${this.scrollDirection}<br>`;
        debugHtml += `Visible: ${this.isVisible ? '✅' : '❌'}<br>`;
        debugHtml += `Mouse: ${mouseY}<br>`;
        debugHtml += `Mobile Menu: ${this.isMobileMenuOpen ? '📱' : '❌'}<br>`;
        debugHtml += `History: [${this.scrollHistory.join(', ')}]<br>`;
        
        this.debugOverlay.innerHTML = debugHtml;
    }
    
    // Public API methods
    show() {
        this.showNavigation('api-call');
    }
    
    hide() {
        this.hideNavigation('api-call');
    }
    
    toggle() {
        if (this.isVisible) {
            this.hide();
        } else {
            this.show();
        }
    }
    
    isNavigationVisible() {
        return this.isVisible;
    }
    
    isMobileMenuVisible() {
        return this.isMobileMenuOpen;
    }
    
    destroy() {
        this.clearFadeTimeout();
        
        if (this.frameId) {
            cancelAnimationFrame(this.frameId);
        }
        
        if (this.debugOverlay) {
            this.debugOverlay.remove();
        }
        
        // Reset mobile menu state
        if (this.isMobileMenuOpen) {
            this.closeMobileMenu();
        }
    }
}

// Export for potential external use
window.LupoNavigationSystem = LupoNavigationSystem;

// Custom events for integration with other systems
document.addEventListener('lupoBackgroundChange', function(e) {
    // Could show navigation briefly when background changes
    // Uncomment if desired:
    // const nav = window.lupoNavigationSystem;
    // if (nav) nav.show();
});