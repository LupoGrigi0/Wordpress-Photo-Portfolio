## this snippit to be pre-pended to each files individual prompt to provide extra context.
## Project Overview
Hello Genievieve, 
We are  working on building a custom WordPress theme for an art portfolio. We're building it in segments. It's major functionality features:
- Parallax scrolling with floating content blocks
- Background images that change based on scroll position
- Custom image carousel for displaying artwork with varying aspect ratios
- Dynamic block generation based on media directory structure

## The project is organized like this

#### Core Theme Files
1. **functions.php** - Theme setup, enqueuing scripts/styles, features
2. **header.php** - Site header with navigation that fades in/out
3. **footer.php** - Site footer
4. **index.php** - Main template file
5. **single.php** - Single post display template
6. **page.php** - Static page display template
7. **style.css** - Core style for portfolio

#### Include Files
1. **custom-post-types.php** - Portfolio custom post type and taxonomy setup. 
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
3. **carousel-styles.css** - Carousel-specific styles
4. **portfolio-theme-style.css** - Main theme stylesheet 

The directory structure is laid out like this

`Lupos-portfolio-theme/`  
`├── assets/`  
`│   ├── css/` 
`│   |    ├── reset.css.css`  
`│   |    ├── responsive.css.css`  
`│   |    ├── carousel-styles.css`       **Status: Created, draft 1 complete**
`│   |    └── portfolio-theme-style.css` **Status: Created, draft 1 complete** 
`│   ├── js/`  
`│   |   ├── custom-carousel.js`  
`│   |   ├── dynamic-background.js`  
`│   |   ├── navigation.js`  
`│   |   └── parallax-scroll.js`         **Status: Created, draft 1 complete**
`│   └── images/`  
`├── inc/`  
`│   ├── custom-post-types.php`          **Status: Created, draft 1 complete**
`│   ├── template-functions.php`  
`│   ├── customizer.php`  
`│   └── carousel-functions.php`  
`├── template-parts/`  
`│   ├── content-block.php`  
`│   └── carousel.php`  
`├── functions.php`                      **Status: Created, draft 1 complete MANY ERRORS due to undefined functions and @package ArtPortfolioTheme**
`├── header.php`  
`├── footer.php`  
`├── index.php`  
`├── single.php`  
`├── page.php`  
`├── style.css`  
`└── screenshot.png`

### Other Notes from Genevieve:

### **Custom Post Types Strategy**

Since you want to organize your content based on your media directory structure, I recommend creating a custom post type for your portfolio items along with custom taxonomies for categorization.

Let's set up a system where:

1. Each "block" is a custom post type called "Portfolio Item"  
2. You can categorize these into different sections/pages  
3. You'll use custom fields to store the carousel images

### **Dynamic Block Generation**

For the dynamic block generation based on your directory structure, we'll need to:

1. Create a custom import tool that scans your directories  
2. Generate portfolio items based on folder structure  
3. Associate images with their respective carousel blocks

For This conversation:
<INSERT SPECIFIC FILE PROMPT HERE>

Any file that that this file depends on, first ask if the file has already been created, if so, Lupo will upload it for Genieve to edit, if not Genevieve will create the file and fill it with it's dependancies. 