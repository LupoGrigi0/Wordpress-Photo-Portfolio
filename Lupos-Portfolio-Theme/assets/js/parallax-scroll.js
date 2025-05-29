/**
 * Parallax Scrolling with Dynamic Background Changes
 * 
 * This script handles:
 * 1. Parallax effect for content blocks
 * 2. Background image transitions based on scroll position
 * 3. Navigation fade in/out
 * @package LupoArtPortfolio
 * @version 1.0.0
 * @author Lupo & Genevieve (Cladue Sonnet 4.0 -pro-)
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const contentBlocks = document.querySelectorAll('.content-block');
    const backgroundContainer = document.querySelector('.background-container');
    const siteNavigation = document.querySelector('.site-navigation');
    
    // Variables for scroll tracking
    let lastScrollTop = 0;
    let ticking = false;
    let activeBlockIndex = 0;
    
    // Create background elements for each block
    contentBlocks.forEach((block, index) => {
        // Get background image from data attribute
        const bgImage = block.dataset.backgroundImage;
        
        if (bgImage) {
            // Create background div
            const bgDiv = document.createElement('div');
            bgDiv.classList.add('background-image');
            bgDiv.id = `background-${index}`;
            bgDiv.style.backgroundImage = `url(${bgImage})`;
            bgDiv.style.opacity = index === 0 ? 1 : 0;
            
            // Add to background container
            backgroundContainer.appendChild(bgDiv);
        }
    });
    
    // Handle scroll events
    window.addEventListener('scroll', function() {
        lastScrollTop = window.scrollY;
        
        if (!ticking) {
            window.requestAnimationFrame(function() {
                handleScroll(lastScrollTop);
                ticking = false;
            });
            
            ticking = true;
        }
    });
    
    // Handle navigation visibility
    let navTimeout;
    window.addEventListener('scroll', function() {
        // Show navigation
        siteNavigation.classList.add('visible');
        
        // Clear previous timeout
        clearTimeout(navTimeout);
        
        // Set timeout to hide navigation after 2 seconds of no scrolling
        navTimeout = setTimeout(function() {
            siteNavigation.classList.remove('visible');
        }, 2000);
    });
    
    // Initial trigger to set correct state
    handleScroll(window.scrollY);
    
    // Function to handle scroll updates
    function handleScroll(scrollPos) {
        // Update content block positions for parallax effect
        contentBlocks.forEach(block => {
            const blockTop = block.getBoundingClientRect().top;
            const blockCenter = blockTop + (block.offsetHeight / 2);
            const windowCenter = window.innerHeight / 2;
            
            // Calculate parallax offset
            const parallaxOffset = (blockCenter - windowCenter) * 0.2;
            block.style.transform = `translateY(${-parallaxOffset}px)`;
            
            // Add a slight rotation for more dynamic movement
            const rotationFactor = parallaxOffset * 0.01;
            block.style.transform += ` rotate(${rotationFactor}deg)`;
            
            // Calculate opacity based on position (fade in/out as blocks enter/leave viewport)
            const distanceFromCenter = Math.abs(blockCenter - windowCenter);
            const maxDistance = window.innerHeight * 0.7;
            const opacity = 1 - Math.min(1, distanceFromCenter / maxDistance);
            
            // Apply opacity to the block content
            const blockContent = block.querySelector('.block-content');
            if (blockContent) {
                blockContent.style.opacity = Math.max(0.2, opacity);
            }
        });
        
        // Determine which block is currently in focus
        let newActiveBlockIndex = 0;
        let closestDistance = Infinity;
        
        contentBlocks.forEach((block, index) => {
            const blockTop = block.getBoundingClientRect().top;
            const blockCenter = blockTop + (block.offsetHeight / 2);
            const windowCenter = window.innerHeight / 2;
            const distance = Math.abs(blockCenter - windowCenter);
            
            if (distance < closestDistance) {
                closestDistance = distance;
                newActiveBlockIndex = index;
            }
        });
        
        // Change background if active block changed
        if (newActiveBlockIndex !== activeBlockIndex) {
            // Transition to new background
            const currentBg = document.getElementById(`background-${activeBlockIndex}`);
            const newBg = document.getElementById(`background-${newActiveBlockIndex}`);
            
            if (currentBg && newBg) {
                // Fade out current background
                fadeOut(currentBg, 500);
                
                // Fade in new background
                fadeIn(newBg, 500);
            }
            
            activeBlockIndex = newActiveBlockIndex;
        }
    }
    
    // Helper functions for smooth transitions
    function fadeIn(element, duration) {
        let opacity = 0;
        element.style.opacity = opacity;
        
        const interval = 10;
        const delta = interval / duration;
        
        const fadeInInterval = setInterval(function() {
            opacity += delta;
            element.style.opacity = Math.min(opacity, 1);
            
            if (opacity >= 1) {
                clearInterval(fadeInInterval);
            }
        }, interval);
    }
    
    function fadeOut(element, duration) {
        let opacity = 1;
        element.style.opacity = opacity;
        
        const interval = 10;
        const delta = interval / duration;
        
        const fadeOutInterval = setInterval(function() {
            opacity -= delta;
            element.style.opacity = Math.max(opacity, 0);
            
            if (opacity <= 0) {
                clearInterval(fadeOutInterval);
            }
        }, interval);
    }
});