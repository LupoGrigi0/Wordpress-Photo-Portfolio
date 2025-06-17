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
        this.portfolioItems = document.querySelectorAll('.content-block');
        this.observer = new MutationObserver(this.handleMutations.bind(this));
        this.observerConfig = {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        };
        
        // Initialize observation
        this.init();
    }

    init() {
        // Observe each portfolio item
        this.portfolioItems.forEach(item => {
            this.observer.observe(item, this.observerConfig);
        });

        // Initial positioning
        this.updateSpacing();

        // Handle window resize
        window.addEventListener('resize', () => {
            requestAnimationFrame(this.updateSpacing.bind(this));
        });
    }    constructor() {
        this.portfolioItems = document.querySelectorAll('.content-block');
        this.observer = new MutationObserver(this.handleMutations.bind(this));
        this.observerConfig = {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        };
        
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

        const viewportHeight = window.innerHeight;
        
        this.portfolioItems.forEach((item, index) => {
            // Use CSS variables for consistent spacing
            item.style.setProperty('--item-spacing', `${Math.floor(viewportHeight * 0.3)}px`);
            
            // Add classes instead of direct styles
            item.classList.add('portfolio-item-spaced');
            
            if (index === 0) {
                item.classList.add('portfolio-item-first');
            }
            
            if (index === this.portfolioItems.length - 1) {
                item.classList.add('portfolio-item-last');
            }
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new PortfolioSpacing();
});
