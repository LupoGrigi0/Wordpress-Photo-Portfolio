# Segment Prompt: template-parts/carousel.php

```
I'm working on a custom WordPress theme for an art portfolio. We're building it in segments.

This segment focuses on: template-parts/carousel.php

Project context:
- Custom theme with parallax scrolling effects
- Content blocks float over changing background images
- Each block contains 1-4 image carousels
- Need to handle different image aspect ratios with smooth transitions
- Need to support fullscreen navigation
- Carousels should have autoplay with configurable speed
- Need visual indicators for navigation and image count
- Must work well on mobile/tablet devices

Dependencies:
- Will be included by content-block.php
- Will use functions from carousel-functions.php
- Will be targeted by custom-carousel.js for functionality
- Will use styling from carousel.css

Requirements for this file:
1. Create a template part that:
   - Renders a carousel container with proper attributes
   - Sets up the necessary structure for images with different aspect ratios
   - Includes navigation controls (prev/next buttons)
   - Shows image count and position indicators
   - Includes fullscreen toggle button
   - Works with the custom carousel JavaScript
2. Include proper data attributes for:
   - Carousel configuration options
   - Image metadata
   - Animation settings
3. Add appropriate WordPress template tags
4. Include responsive image markup
5. Add proper hooks for theme customization
6. Include accessibility attributes and ARIA labels

Please create the complete template-parts/carousel.php file with detailed comments.
```

This prompt provides guidance for creating the template part that will render each image carousel within your portfolio blocks.
