/**
 * Dynamic portfolio item spacing
 * Adjusts spacing after carousel content loads
 * 
 * @package Lupos-Portfolio-Theme
 * Version: 1.0.0
 * Co-authored-by: Genevieve (VS Code Shard)
 */

class PortfolioSpacing {
    constructor() {
        // Core properties
        this.portfolioItems = document.querySelectorAll('.content-block');
        this.carousels = document.querySelectorAll('.advanced-carousel');
        this.isInitialized = false;
        
        // Observer setup
        this.observer = new MutationObserver(this.handleMutations.bind(this));
        this.observerConfig = {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class', 'src']
        };
        
        // Debounce handlers
        this.updateTimeout = null;
        this.resizeTimeout = null;
        this.isUpdating = false;
        
        // Wait for carousels to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initialize());
        } else {
            this.initialize();
        }
        
        // Initialize observation
        this.init();
    }

    initialize() {
        if (this.isInitialized) return;
        this.isInitialized = true;

        // Ensure carousels are ready before calculating spacing
        const checkCarousels = setInterval(() => {
            const readyCarousels = Array.from(this.carousels)
                .filter(carousel => carousel.offsetHeight > 0);

            if (readyCarousels.length === this.carousels.length) {
                clearInterval(checkCarousels);
                this.init();
            }
        }, 100);

        // Timeout after 5 seconds to prevent infinite waiting
        setTimeout(() => {
            clearInterval(checkCarousels);
            if (!this.isInitialized) {
                console.warn('Some carousels may not be fully initialized');
                this.init();
            }
        }, 5000);
    }    init() {
        if (this.observersAttached) return; // Prevent recursive initialization
        this.observersAttached = true;

        // First, do the initial spacing calculation
        this.updateSpacing();

        // Then set up observers for future changes
        this.portfolioItems.forEach(item => {
            this.observer.observe(item, this.observerConfig);
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (this.resizeTimeout) {
                clearTimeout(this.resizeTimeout);
            }
            this.resizeTimeout = setTimeout(() => {
                this.updateSpacing();
            }, 100);
        });
        
        // Debounce handler
        this.updateTimeout = null;
        this.isUpdating = false;
        
        // Initialize observation
        this.init();
    }

    handleMutations(mutations) {
        // Clear any pending timeout
        if (this.updateTimeout) {
            clearTimeout(this.updateTimeout);
        }

        // If we're already in an update, don't queue another one
        if (this.isUpdating) {
            return;
        }

        // Debounce the update
        this.updateTimeout = setTimeout(() => {
            const shouldUpdate = mutations.some(mutation => {
                // Check if carousel content changed
                return mutation.target.closest('.carousel-container') ||
                       mutation.target.closest('.block-content');
            });

            if (shouldUpdate) {
                this.isUpdating = true;
                requestAnimationFrame(() => {
                    this.updateSpacing();
                    // Wait a bit before allowing more updates
                    setTimeout(() => {
                        this.isUpdating = false;
                    }, 100);
                });
            }
        }, 250); // Wait for 250ms of no changes before updating
    }    updateSpacing() {
        // Early exit if document is hidden (tab not active)
        if (document.hidden) return;

        // Get reference carousel height
        const carousel = document.querySelector('.advanced-carousel');
        if (!carousel) return;
          const carouselHeight = Math.max(carousel.offsetHeight, 300); // Use actual height or min-height
        const spacingHeight = Math.floor(carouselHeight * 0.5); // Half of carousel height
        
        this.portfolioItems.forEach((item, index) => {
            // Reset any existing styles
            item.style.margin = '0';
            
            // Calculate margins based on position
            if (index === 0) {
                // First item: one-third carousel height from top
                item.style.marginTop = `${spacingHeight}px`;
            } else {
                // Other items: one-third carousel height between them
                item.style.marginTop = `${spacingHeight}px`;
            }
            
            if (index === this.portfolioItems.length - 1) {
                // Last item: one-third carousel height at bottom
                item.style.marginBottom = `${spacingHeight}px`;
            }
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new PortfolioSpacing();
});
