# WordPress Portfolio Theme Project Plan

## Project Overview
Building a custom WordPress theme for an art portfolio featuring:
- Parallax scrolling with floating content blocks
- Background images that change based on scroll position
- Custom image carousel for displaying artwork with varying aspect ratios
- Dynamic block generation based on media directory structure

## Project Organization

### Completed Files
1. **parallax-scroll.js** - Core JavaScript for the parallax scrolling effect
2. **portfolio-theme-style.css** - Main theme stylesheet (partial)

### Files To Be Completed

#### Core Theme Files
1. **functions.php** - Theme setup, enqueuing scripts/styles, features
2. **header.php** - Site header with navigation that fades in/out
3. **footer.php** - Site footer
4. **index.php** - Main template file
5. **single.php** - Single post display template
6. **page.php** - Static page display template

#### Include Files
1. **custom-post-types.php** - Portfolio custom post type and taxonomy setup
2. **template-functions.php** - Custom template functions
3. **customizer.php** - Theme customization options
4. **carousel-functions.php** - Custom carousel functionality

#### Template Parts
1. **content-block.php** - Template for portfolio content blocks
2. **carousel.php** - Template for image carousel

#### JavaScript Files
1. **custom-carousel.js** - Custom carousel implementation
2. **dynamic-background.js** - Background image transition functionality
3. **navigation.js** - Navigation fade in/out on scroll

#### Stylesheet Files
1. **reset.css** - CSS reset/normalize
2. **responsive.css** - Responsive design rules
3. **carousel.css** - Carousel-specific styles

## Segment Prompts Format

For each file, we'll use the following prompt template:

```
I'm working on a custom WordPress theme for an art portfolio. We're building it in segments.

This segment focuses on: [FILE NAME]

Project context:
- Custom theme with parallax scrolling effects
- Content blocks float over changing background images
- Custom image carousel that handles different aspect ratios
- Dynamic block generation based on media directory structure

Dependencies:
[List any functions, variables, classes, or selectors this file depends on]

Requirements for this file:
[Specific requirements for this file]

Please create the complete [FILE NAME] with detailed comments.
```

## Next Segment: custom-post-types.php

This will be our first full segment to implement, as it's fundamental to the theme's structure.
