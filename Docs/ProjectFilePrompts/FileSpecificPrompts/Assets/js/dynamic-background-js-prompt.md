# Segment Prompt: dynamic-background.js

```
I'm working on a custom WordPress theme for an art portfolio. We're building it in segments.

This segment focuses on: dynamic-background.js

Project context:
- Custom theme with parallax scrolling effects
- Content blocks float over changing background images
- Background images change based on which content block is in view
- Need smooth cross-fade transitions between background images
- Should respond to scroll position
- Must work well on mobile/tablet devices
- Background images will typically be the first image from each block's carousel

Dependencies:
- Will be enqueued in functions.php
- Works alongside parallax-scroll.js
- Will interact with custom-carousel.js
- Will use data from portfolio custom post types

Requirements for this file:
1. Create a system that:
   - Tracks which content block is currently in view
   - Preloads background images for smooth transitions
   - Performs smooth cross-fade transitions between backgrounds
   - Handles scroll events efficiently (debounced/throttled)
   - Manages aspect ratio differences between background images
   - Works with dynamic content loading
2. Include options for:
   - Transition speed customization
   - Background positioning options (cover, contain, etc.)
   - Fallback image when no block is in primary view
3. Implement performance optimizations:
   - Image lazy loading
   - Low-resolution placeholders while loading
   - Efficient DOM manipulation
4. Add proper event handling for window resize
5. Include accessibility considerations

Please create the complete dynamic-background.js file with detailed comments.
```

This prompt provides guidance for creating the JavaScript that will handle the dynamic background changes based on scroll position.
