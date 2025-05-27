# Lupo's WordPress Portfolio Theme Project Plan
good evening Genevieve,  
My name is Lupo(in case you did not already know) 
we are buidlding a custom wordpress template intended  for my art portfolio. 
I have thousands of images, all organized into directories.   
Genevieve: Please focus on all your knowledge of wordpress, plugins, templates, styles, CSS, and for this project take the role of an expert wordpress developer who has developed hundreds of templates, styles, widigets, and knows the wordpress source code as well as it's functions, how to use it, how it runs with apache nginx and a hosted environment in a container.  

# High level project goals 
1. create a wordplress template, that has multipule layers, so that when the user scrolls, content in blocks scrolls over the background image(parallax), like the content blocks are "floating" over the background image, I'd like the background image to be able to change based on which content block is scrolled into view (top or bottom). NOTE: I also have Elementor and Gutenburg plugins installd in wordpress and can use either or both to use the template and content blocks, if that would be useful. I have used wordpress before, but I have never built a template.  
2. My existing portfolio is already organized using directories and subdirectories. Part of my goal is to have the site automatically create a page for each directory, scan all the image files in the directory and put them in image carosoles within content blocks, (let's say 3 carosoles per content block) and if there are any sub-directories create grouped content blocks for each sub-directory.
3.  I would like to  build  a very nice image carousel I have found some commercial templates that look nice, but they all fall down when doing the things I want to do.  

## Template design guidance
Your scrolling concept with content blocks floating over a changing background sounds like a modern parallax-style effect. Let's break this down to understand exactly what you're envisioning
### Background Behavior

Fixed background image that changes based on scroll position
Transition between different background images as user scrolls through content blocks
Full-screen background that serves as the base layer

### Content Block Behavior

Content blocks "float" over the background
Blocks scroll independently from the background (parallax effect)
Possibly with opacity/transition effects as they enter/exit the viewport

### more detailed implementation guidance
1. typical pages have half a dozen blocks, each block normally would have a title and a sub heading and 1 to 4 image carousels. By default I'd want the background to transition to the first image in the first image carousel in the block that is in focus or has just been scrolled into view. the Goal is to have the pages and  blocks built dynamically based on media directory organization. I plan on having one "directory" for each page of the site, and have blocks for images and blocks created for each subdirectory, and the image carousels populated by the files in that subdirectory
2. smooth transition between background images, is a goal cross fade would be ideal
3. My current portfolio has card like elements with defined boarders, for this new site I wanted the content blocks to look more modern, more fluid and without had boarders/edges. The content blocks will contain images and image caruncles sometimes a title and sometimes the title with have a subheading.  I would ideally like the edges of the content blocks to be progressively transparent so the block's edge is completely transparent to the background making the edges of the content blocks hazy, ill defined.
4. Each Page contains a headder block with the title of the page, an optional sub title, and optional paragraph(s) of text that describe the content on the page. I welcome all suggestions on how to do this. Such as having a json file (or any file type) in each directory where I can put optional title sub heading and text. or if it is more elegant or suitable to use existing fuctions within wordpress. Please use your discression on suggesting how this be implementd. 
5. No horizontal scrolling. Most people will be on their phones or tablets, a few will be on large monitors, I think I would rather not do horizontal scrolling and allow automatic layout for the various platforms handle how the blocks are laid out and scaled. 
6. Having the navigation fade in and out based on scroll would be a nice touch for my site
## Lupo's  Technical comfort level
1.  Lupo is comfortable working with PHP CSS and Javascript. Lupo has ot VS code installed, with a number of common plugins installed for javascript, php, css and wordpress.  
2. Lupo Has good working knowledge of JavaScript as a basic language as it compares to C/C++/Java/Python. Lupo is more fluent in C/C++  
3. Lupo does not have experience with Advanced Custom Fields or Custom post types, but lupo   not a total noob and can be walked through their creation and use. 
## For image carouse
1. I want to be able to dynamically load the content of the carousel from directory of media. Also I would like to have options for different types of transitions between images, more control over auto play speed, also my images are different aspect ratios and was not happy with how the stock or elementor pro's carousel handled transition between images of different aspect ratios. I want the transition to be smooth.
2. In a single carousel, people have told me that more than 20 images is too many, and sometimes when images fail to load on mobile they don't notice, there is no indication that there is more to the carousel 
3. Fortunately I do not need filterable galleries, or lightbox functionality, I am trying to optimize for a browsing and scrolling and auto play
4. Yes, I will need to allow users to navagate between images in full screen mode

## Project Overview
Building a custom WordPress theme for an art portfolio featuring:
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

Genevieve's update
 Lupo's WordPress Portfolio Theme Project Plan - Updated

## Project Status Overview

### ✅ Completed Files
- **functions.php** - ✅ FIXED! Core theme setup with proper naming, file existence checks, debug mode
- **custom-post-types.php** - ✅ Portfolio post type with custom meta boxes for background images, carousel data, directory scanning
- **carousel-styles.css** - ✅ Advanced carousel with fullscreen mode, responsive controls, loading states
- **portfolio-theme-styles.css** - ✅ Main theme with floating content blocks, parallax backgrounds, progressive transparency
- **parallax-scroll.js** - ✅ Core parallax scrolling functionality

### 🔧 In Progress
- **style.css** - WordPress required main stylesheet (started creation)

### 📋 Priority Queue (Deployment Ready)
**Phase 1 - CURRENT FOCUS - Core Templates:**
- **style.css** - WordPress main stylesheet ⚡ NEXT  
- **header.php** - Site header with fade navigation ⚡ NEXT
- **footer.php** - Site footer ⚡ NEXT
- **index.php** - Main template file ⚡ NEXT

**Phase 2 - JavaScript Functionality:**
- **custom-carousel.js** - Frontend carousel implementation
- **dynamic-background.js** - Background crossfade transitions  
- **navigation.js** - Navigation fade effects

**Phase 3 - Backend & Content Generation:**
- **template-functions.php** - Directory scanning, block generation
- **customizer.php** - Theme options
- **carousel-functions.php** - Carousel backend
- **Admin interface** - Directory import management page

**Phase 4 - Template Parts & Polish:**
- **single.php**, **page.php** - Individual content templates
- **content-block.php**, **carousel.php** - Template parts
- **reset.css**, **responsive.css** - Additional styles

## Technical Decisions - FINALIZED

### Background Image Strategy ✅
- **First image from first carousel** in each content block becomes background
- **Crossfade transition** based on content block scroll position relative to viewport center
- **Gradual blending** - transition intensity based on "distance" from center view
- **Directional logic** - block moving toward center triggers its background (up/down scroll)

### Directory Organization Mapping ✅
- **WordPress admin-side generation** (not dynamic real-time)
- **Structure**: `/portfolio/sculptures/abstract/` → Page "Sculptures" with blocks for root images + "Abstract" block
- **Limits**: Max 20 images per carousel, max 4-5 carousels per content block
- **Batch processing**: AJAX-based with progress indicators and rollback capability

### Carousel Aspect Ratio Handling ✅
- **Dynamic container sizing** based on current image (no cropping, no letterboxing)
- **Mixed ratios supported**: Square, 5:4, 9:16 (mostly 4096px on one side)
- **Responsive behavior**: Maintain aspect ratios across devices

### Navigation Behavior ✅
- **Scroll-based fade**: Appears/disappears based on content block positions
- **Smooth transitions**: 0.3s ease-in-out
- **Mobile optimized**: Adapted touch interactions

## New Admin Interface Design

### Custom Admin Page: "Portfolio Directory Manager"
**Location**: Under Portfolio menu in WordPress admin
**Features**:
- Directory path input with validation
- Batch import with progress tracking
- Preview mode before publishing
- Incremental updates (scan only changed directories)
- Rollback capability for failed imports
- Settings for max images/carousels per block
- Import history and logging

### Admin Workflow
1. **Scan Directory Structure** → Preview generated pages/blocks
2. **Review & Adjust** → Modify titles, organization, image selection
3. **Publish** → Create actual WordPress posts/pages
4. **Monitor** → Track import progress, handle errors
5. **Rollback** → Undo if needed, restore previous state

## Architecture Notes

**Custom Post Type Structure:**
- `apt_portfolio` - Main portfolio items
- Taxonomies: `apt_medium`, `apt_subject`, `apt_year`
- Meta fields: `_apt_background_image`, `_apt_carousel_data`, `_apt_directory_path`

**JavaScript Dependencies:**
- jQuery (WordPress core)
- Custom carousel implementation
- Parallax scroll handlers
- Background transition system

**CSS Architecture:**
- CSS custom properties for theming
- Progressive transparency effects
- Mobile-first responsive design
- Backdrop filters for modern browsers

## Project Context & Goals
- WordPress theme for art portfolio with thousands of organized images
- Parallax scrolling with floating content blocks over dynamic backgrounds
- Custom image carousels handling varying aspect ratios smoothly
- Automated content generation from directory structure
- Mobile-optimized, no horizontal scrolling
- Background crossfading based on content block scroll positions
- **Target**: Deploy to WordPress dev environment for testing and refinement
- **Future**: Open source template for other artists to use
- **Collaboration**: Lupo (Yakima, WA) + girlfriend/graphic designer (Santiago, Chile)
