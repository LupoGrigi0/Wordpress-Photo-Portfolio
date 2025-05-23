## this snippit to be pre-pended to each files individual prompt to provide extra context.
## Project Overview
Building a custom WordPress theme for an art portfolio featuring:
- Parallax scrolling with floating content blocks
- Background images that change based on scroll position
- Custom image carousel for displaying artwork with varying aspect ratios
- Dynamic block generation based on media directory structure

## Project Organization

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
4. **parallax-scroll.js** - Core JavaScript for the parallax scrolling effect

#### Stylesheet Files
1. **reset.css** - CSS reset/normalize
2. **responsive.css** - Responsive design rules
3. **carousel.css** - Carousel-specific styles
4. **portfolio-theme-style.css** - Main theme stylesheet (partial)

`Lupos-portfolio-theme/`  
`├── assets/`  
`│   ├── css/`  
`│   ├── js/`  
`│   └── images/`  
`├── inc/`  
`│   ├── custom-post-types.php`  
`│   ├── template-functions.php`  
`│   ├── customizer.php`  
`│   └── carousel-functions.php`  
`├── template-parts/`  
`│   ├── content-block.php`  
`│   └── carousel.php`  
`├── functions.php`  
`├── header.php`  
`├── footer.php`  
`├── index.php`  
`├── single.php`  
`├── page.php`  
`├── style.css`  
`└── screenshot.png`

Any file that that this file depends on, first ask if the file has already been created, if so, Lupo will upload it for Genieve to edit, if not Genevieve will create the file and fill it with it's dependancies. 