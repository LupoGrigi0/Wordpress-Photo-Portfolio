/**
 * Advanced Image Carousel for Lupo's Art Portfolio Theme
 *
 * Handles dynamic container sizing for mixed aspect ratios,
 * smooth transitions, touch controls, and fullscreen mode
 *
 * @package LupoArtPortfolio
 * @version 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get theme settings from footer injection
    const themeSettings = window.lupo_settings || {};
    const debugMode = themeSettings.debug_mode || false;
    
    // Carousel configuration
    const config = {
        transitionSpeed: themeSettings.carousel_transition_speed || 400,
        autoplaySpeed: themeSettings.carousel_speed || 5000,
        autoplayEnabled: themeSettings.carousel_autoplay !== false,
        touchThreshold: 50, // pixels for swipe detection
        resizeDebounce: 250, // milliseconds
    };
    
    if (debugMode) {
        console.log('Lupo Carousel: Initializing with config:', config);
    }
    
    // Initialize all carousels
    const carousels = document.querySelectorAll('.advanced-carousel');
    const carouselInstances = [];
    
    carousels.forEach(function(carouselElement, index) {
        const instance = new LupoCarousel(carouselElement, config, index);
        carouselInstances.push(instance);
    });
    
    // Handle window resize for all carousels
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            carouselInstances.forEach(instance => instance.handleResize());
        }, config.resizeDebounce);
    });
    
    // Global fullscreen exit handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            carouselInstances.forEach(instance => instance.exitFullscreen());
        }
    });
});

/**
 * LupoCarousel Class
 * Handles individual carousel functionality
 */
class LupoCarousel {
    constructor(element, config, index) {
        this.element = element;
        this.config = config;
        this.index = index;
        this.currentSlide = 0;
        this.isAnimating = false;
        this.autoplayInterval = null;
        this.touchStartX = 0;
        this.touchStartY = 0;
        this.isFullscreen = false;
        
        // Get carousel elements
        this.inner = element.querySelector('.carousel-inner');
        this.slides = element.querySelectorAll('.carousel-slide');
        this.indicators = element.querySelectorAll('.carousel-indicator');
        this.prevButton = element.querySelector('.carousel-control.prev');
        this.nextButton = element.querySelector('.carousel-control.next');
        this.fullscreenButton = element.querySelector('.carousel-fullscreen');
        this.progressBar = element.querySelector('.carousel-progress-inner');
        
        // Validate required elements
        if (!this.inner || this.slides.length === 0) {
            console.warn('Lupo Carousel: Invalid carousel structure, skipping initialization');
            return;
        }
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.calculateDimensions();
        this.setupAutoplay();
        this.showSlide(0);
        
        if (this.config.debugMode) {
            console.log(`Lupo Carousel ${this.index}: Initialized with ${this.slides.length} slides`);
        }
    }
    
    setupEventListeners() {
        // Navigation buttons
        if (this.prevButton) {
            this.prevButton.addEventListener('click', () => this.prevSlide());
        }
        
        if (this.nextButton) {
            this.nextButton.addEventListener('click', () => this.nextSlide());
        }
        
        // Indicators
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.showSlide(index));
        });
        
        // Fullscreen button
        if (this.fullscreenButton) {
            this.fullscreenButton.addEventListener('click', () => this.toggleFullscreen());
        }
        
        // Touch events for swipe
        this.element.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: true });
        this.element.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: false });
        this.element.addEventListener('touchend', (e) => this.handleTouchEnd(e), { passive: true });
        
        // Mouse events for drag (desktop)
        this.element.addEventListener('mousedown', (e) => this.handleMouseStart(e));
        
        // Pause autoplay on hover
        this.element.addEventListener('mouseenter', () => this.pauseAutoplay());
        this.element.addEventListener('mouseleave', () => this.resumeAutoplay());
        
        // Handle focus for accessibility
        this.element.addEventListener('focus', () => this.pauseAutoplay(), true);
        this.element.addEventListener('blur', () => this.resumeAutoplay(), true);
    }
    
    calculateDimensions() {
        if (this.slides.length === 0) return;
        
        // Get current slide image
        const currentImg = this.slides[this.currentSlide].querySelector('img');
        if (!currentImg) return;
        
        // Wait for image to load if not loaded
        if (!currentImg.complete) {
            currentImg.addEventListener('load', () => this.updateCarouselDimensions());
            return;
        }
        
        this.updateCarouselDimensions();
    }
    
    updateCarouselDimensions() {
        const currentImg = this.slides[this.currentSlide].querySelector('img');
        if (!currentImg) return;
        
        const containerWidth = this.element.offsetWidth;
        const imgWidth = currentImg.naturalWidth || currentImg.width;
        const imgHeight = currentImg.naturalHeight || currentImg.height;
        
        if (imgWidth && imgHeight) {
            // Calculate aspect ratio
            const aspectRatio = imgHeight / imgWidth;
            const newHeight = containerWidth * aspectRatio;
            
            // Set carousel height to match current image aspect ratio
            this.inner.style.height = `${newHeight}px`;
            
            // Ensure all slides match this height
            this.slides.forEach(slide => {
                slide.style.height = `${newHeight}px`;
            });
            
            if (this.config.debugMode) {
                console.log(`Lupo Carousel ${this.index}: Updated dimensions - ${containerWidth}x${newHeight} (AR: ${aspectRatio.toFixed(3)})`);
            }
        }
    }
    
    showSlide(index, direction = 'next') {
        if (this.isAnimating || index === this.currentSlide) return;
        
        this.isAnimating = true;
        const previousSlide = this.currentSlide;
        this.currentSlide = index;
        
        // Update active states
        this.slides.forEach((slide, i) => {
            slide.classList.remove('active', 'prev', 'next');
            if (i === index) {
                slide.classList.add('active');
            }
        });
        
        this.indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
        
        // Animate transition with dynamic sizing
        this.animateSlideTransition(previousSlide, index, direction);
        
        // Update progress bar
        this.updateProgressBar();
        
        // Calculate new dimensions for the active slide
        setTimeout(() => {
            this.calculateDimensions();
            this.isAnimating = false;
        }, this.config.transitionSpeed);
    }
    
    animateSlideTransition(fromIndex, toIndex, direction) {
        const fromSlide = this.slides[fromIndex];
        const toSlide = this.slides[toIndex];
        
        if (!fromSlide || !toSlide) return;
        
        // Set initial positions
        const translateValue = direction === 'next' ? '100%' : '-100%';
        toSlide.style.transform = `translateX(${translateValue})`;
        toSlide.style.opacity = '1';
        toSlide.style.display = 'block';
        
        // Force reflow
        toSlide.offsetHeight;
        
        // Animate both slides
        fromSlide.style.transition = `transform ${this.config.transitionSpeed}ms ease-in-out, opacity ${this.config.transitionSpeed}ms ease-in-out`;
        toSlide.style.transition = `transform ${this.config.transitionSpeed}ms ease-in-out, opacity ${this.config.transitionSpeed}ms ease-in-out`;
        
        const oppositeTranslate = direction === 'next' ? '-100%' : '100%';
        fromSlide.style.transform = `translateX(${oppositeTranslate})`;
        fromSlide.style.opacity = '0';
        toSlide.style.transform = 'translateX(0)';
        
        // Clean up after animation
        setTimeout(() => {
            this.slides.forEach(slide => {
                if (slide !== toSlide) {
                    slide.style.display = 'none';
                }
                slide.style.transition = '';
                slide.style.transform = '';
                slide.style.opacity = '';
            });
            toSlide.style.display = 'block';
        }, this.config.transitionSpeed);
    }
    
    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.showSlide(nextIndex, 'next');
    }
    
    prevSlide() {
        const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.showSlide(prevIndex, 'prev');
    }
    
    setupAutoplay() {
        if (!this.config.autoplayEnabled || this.slides.length <= 1) return;
        
        this.startAutoplay();
    }
    
    startAutoplay() {
        this.stopAutoplay();
        this.autoplayInterval = setInterval(() => {
            this.nextSlide();
        }, this.config.autoplaySpeed);
    }
    
    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }
    
    pauseAutoplay() {
        this.stopAutoplay();
    }
    
    resumeAutoplay() {
        if (this.config.autoplayEnabled && this.slides.length > 1) {
            this.startAutoplay();
        }
    }
    
    updateProgressBar() {
        if (!this.progressBar) return;
        
        const progress = ((this.currentSlide + 1) / this.slides.length) * 100;
        this.progressBar.style.width = `${progress}%`;
    }
    
    // Touch/Swipe handlers
    handleTouchStart(e) {
        if (e.touches.length !== 1) return;
        
        this.touchStartX = e.touches[0].clientX;
        this.touchStartY = e.touches[0].clientY;
        this.pauseAutoplay();
    }
    
    handleTouchMove(e) {
        if (e.touches.length !== 1) return;
        
        // Prevent default scrolling for horizontal swipes
        const deltaX = Math.abs(e.touches[0].clientX - this.touchStartX);
        const deltaY = Math.abs(e.touches[0].clientY - this.touchStartY);
        
        if (deltaX > deltaY) {
            e.preventDefault();
        }
    }
    
    handleTouchEnd(e) {
        if (e.changedTouches.length !== 1) return;
        
        const deltaX = e.changedTouches[0].clientX - this.touchStartX;
        const deltaY = Math.abs(e.changedTouches[0].clientY - this.touchStartY);
        
        // Only handle horizontal swipes
        if (Math.abs(deltaX) > this.config.touchThreshold && Math.abs(deltaX) > deltaY) {
            if (deltaX > 0) {
                this.prevSlide();
            } else {
                this.nextSlide();
            }
        }
        
        this.resumeAutoplay();
    }
    
    // Mouse drag handlers (for desktop)
    handleMouseStart(e) {
        if (e.button !== 0) return; // Only left mouse button
        
        this.touchStartX = e.clientX;
        this.pauseAutoplay();
        
        const handleMouseMove = (moveEvent) => {
            moveEvent.preventDefault();
        };
        
        const handleMouseUp = (upEvent) => {
            const deltaX = upEvent.clientX - this.touchStartX;
            
            if (Math.abs(deltaX) > this.config.touchThreshold) {
                if (deltaX > 0) {
                    this.prevSlide();
                } else {
                    this.nextSlide();
                }
            }
            
            this.resumeAutoplay();
            document.removeEventListener('mousemove', handleMouseMove);
            document.removeEventListener('mouseup', handleMouseUp);
        };
        
        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', handleMouseUp);
    }
    
    // Fullscreen functionality
    toggleFullscreen() {
        if (this.isFullscreen) {
            this.exitFullscreen();
        } else {
            this.enterFullscreen();
        }
    }
    
    enterFullscreen() {
        this.element.classList.add('fullscreen');
        document.body.style.overflow = 'hidden';
        this.isFullscreen = true;
        
        // Recalculate dimensions for fullscreen
        setTimeout(() => {
            this.calculateDimensions();
        }, 50);
        
        if (this.config.debugMode) {
            console.log(`Lupo Carousel ${this.index}: Entered fullscreen`);
        }
    }
    
    exitFullscreen() {
        if (!this.isFullscreen) return;
        
        this.element.classList.remove('fullscreen');
        document.body.style.overflow = '';
        this.isFullscreen = false;
        
        // Recalculate dimensions for normal view
        setTimeout(() => {
            this.calculateDimensions();
        }, 50);
        
        if (this.config.debugMode) {
            console.log(`Lupo Carousel ${this.index}: Exited fullscreen`);
        }
    }
    
    // Handle window resize
    handleResize() {
        this.calculateDimensions();
    }
    
    // Public API methods
    goToSlide(index) {
        if (index >= 0 && index < this.slides.length) {
            this.showSlide(index);
        }
    }
    
    getCurrentSlide() {
        return this.currentSlide;
    }
    
    getTotalSlides() {
        return this.slides.length;
    }
    
    destroy() {
        this.stopAutoplay();
        this.exitFullscreen();
        // Remove event listeners would go here if needed
    }
}

// Export for potential external use
window.LupoCarousel = LupoCarousel;