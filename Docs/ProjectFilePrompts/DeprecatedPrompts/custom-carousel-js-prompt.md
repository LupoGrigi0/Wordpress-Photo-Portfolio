# Segment Prompt: custom-carousel.js

```
I'm working on a custom WordPress theme for an art portfolio. We're building it in segments.

This segment focuses on: custom-carousel.js

Project context:
- Custom theme with parallax scrolling effects
- Content blocks float over changing background images
- Need a custom image carousel that handles different aspect ratios
- Want smooth transitions between images regardless of aspect ratio
- Need fullscreen navigation capability
- Require autoplay with customizable speed
- Need to handle up to 20 images per carousel
- Must work well on mobile/tablet devices
- Need visual indicator when more images are available
- Should integrate with the dynamic background system

Dependencies:
- Will be enqueued in functions.php
- Will work with HTML generated by carousel.php template
- Will use data provided by carousel-functions.php

Requirements for this file:
1. Create a carousel class that handles:
   - Smooth transitions between images with different aspect ratios
   - Touch/swipe support for mobile
   - Keyboard navigation
   - Fullscreen mode with navigation
   - Auto-play with configurable speed and pause on hover
   - Visual indicators for navigation and image count
   - Lazy loading of images
   - Responsive behavior
2. Add support for updating the background image when a new carousel image is shown
3. Include event hooks for theme customization
4. Optimize for performance with large image collections
5. Include proper error handling for failed image loads
6. Add accessibility features

Please create the complete custom-carousel.js file with detailed comments.
```

This prompt provides guidance for creating the JavaScript that powers the custom carousel functionality you need.
