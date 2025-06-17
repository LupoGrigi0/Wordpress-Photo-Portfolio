# Fix #1: Disable Problematic Background Parallax Scrolling

## Problem Description
The background images are sliding down the page instead of staying centered. This is caused by conflicting behaviors:
- CSS sets `background-position: center` 
- JavaScript parallax applies `transform: translateY()` 
- Result: Background moves below viewport, creating black space at top

## Goal
Temporarily disable the parallax scrolling effect while keeping the crossfade functionality working. This will:
- ✅ Keep backgrounds centered and properly sized
- ✅ Maintain smooth crossfade transitions between portfolio items  
- ✅ Eliminate black space at top of page
- ✅ Create stable base for other fixes

## Solution Approach
Comment out the problematic parallax transform logic in the background system while preserving the crossfade mechanism.

## File to Edit
`assets/js/dynamic-background.js`

## Specific Changes Needed

### Step 1: Locate the `applyParallaxEffect` function
Find this function (around line 140-180):

```javascript
applyParallaxEffect(scrollY) {
    this.contentBlocks.forEach((block, index) => {
        // ... parallax logic here
    });
    
    // Apply parallax to background layers
    this.backgroundLayers.forEach((layer, index) => {
        if (layer.element && layer.isActive) {
            const parallaxBg = scrollY * this.config.parallaxIntensity * 0.5;
            layer.element.style.transform = `translateY(${parallaxBg}px)`;
        }
    });
}
```

### Step 2: Disable the function temporarily
Replace the ENTIRE function with this version:

```javascript
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
```

## How to Test the Fix

### Before Fix:
- Background images slide down page as you scroll
- Black space visible at top of viewport
- Images appear "cropped" (like the crow woman's head being cut off)

### After Fix:
- Background images stay centered in viewport
- No black space at top
- Full background images visible and properly positioned
- Crossfade between different portfolio items still works
- Content blocks still have subtle parallax movement (this is good)

## Success Criteria
1. ✅ No black space at top of page
2. ✅ Background images stay centered while scrolling  
3. ✅ Crossfade transitions still work when scrolling between portfolio items
4. ✅ Content blocks still have gentle movement (just not backgrounds)

## Notes for Future
This is a temporary fix. Later we can re-implement background parallax with:
- Better centering calculations
- Admin toggle for enable/disable  
- Separate intensity controls for content vs background

## Commit Message Suggestion
```
fix: temporarily disable background parallax to fix positioning

- Backgrounds now stay centered instead of sliding down page
- Preserves crossfade functionality between portfolio items
- Eliminates black space at top of viewport
- Maintains content block parallax effects
```