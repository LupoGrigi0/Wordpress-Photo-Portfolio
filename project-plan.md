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

# Lupo's WordPress Portfolio Theme Project Plan

## Project Overview
Building a custom WordPress theme for Lupo's art portfolio featuring:
- **Parallax scrolling** with floating content blocks over changing backgrounds
- **Dynamic content generation** based on media directory structure  
- **Custom image carousels** optimized for varying aspect ratios
- **Progressive transparency** edges on content blocks for modern, fluid look
- **Mobile-first responsive design** with no horizontal scrolling

## High Level Goals

### 1. Parallax Template Design
- Content blocks "float" over background images with parallax effect
- Background images change based on which content block is in viewport
- Smooth crossfade transitions between background images
- Navigation that fades in/out on scroll
- Progressive transparency on block edges (hazy, undefined borders)

### 2. Dynamic Content Generation
- Automatically create pages for each media directory
- Generate content blocks for subdirectories  
- Populate image carousels with files from each subdirectory
- Support for optional titles, subtitles, and descriptions per directory

### 3. Custom Image Carousel
- Handle varying aspect ratios smoothly
- Multiple transition types and autoplay speed control
- Optimized for mobile browsing and scrolling
- Visual indicators for carousel progress/loading
- Full-screen navigation capability

## Technical Architecture
*** NOTE: We are using Wordpress Version 6.8.1 ***

### Core Theme Structure
```
Lupos-portfolio-theme/
├── assets/
│   ├── css/
│   │   ├── reset.css
│   │   ├── responsive.css  
│   │   ├── carousel-styles.css       [Deployed, tested]
│   │   └── portfolio-theme-style.css [Deployed, tested]
│   ├── js/
│   │   ├── custom-carousel.js        [Deployed, tested]
│   │   ├── dynamic-background.js     [Deployed, tested]
│   │   ├── navigation.js            [Deployed, tested]
│   │   └── parallax-scroll.js       [Deployed, tested]
│   └── images/
├── inc/
│   ├── custom-post-types.php        [Deployed, tested - v 1.5]
│   ├── template-functions.php       [IN PROGRESS]
│   ├── customizer.php
│   └── carousel-functions.php
├── template-parts/
│   ├── content-block.php
│   └── carousel.php
├── functions.php                    [Deployed, tested]
├── header.php                       [Deployed, tested]
├── footer.php                       [Deployed, tested - v 1.5]
├── index.php                        [Deployed, tested]
├── single.php
├── page.php
├── style.css                        [Deployed, tested]
└── screenshot.png
```
Excample of the directory structure for the actual portfolio files and directories
```
wp-content/uploads/
├── portfolio/                    ← YOUR ROOT PORTFOLIO DIRECTORY
│   ├── sculptures/              ← Creates "Sculptures" page
│   │   ├── abstract/            ← Content block: "Abstract"
│   │   │   ├── piece001.jpg
│   │   │   ├── piece002.jpg
│   │   │   └── ...
│   │   └── realistic/           ← Content block: "Realistic"  
│   │       ├── bust001.jpg
│   │       └── ...
│   ├── paintings/               ← Creates "Paintings" page
│   │   ├── landscapes/          ← Content block: "Landscapes"
│   │   └── portraits/           ← Content block: "Portraits"
│   └── mixed-media/             ← Creates "Mixed Media" page
│       ├── installation001.jpg
│       └── ...
```


## Content Organization Strategy

### Custom Post Types
- **Portfolio Item** custom post type for content blocks
- Custom taxonomies for categorization/pages
- Custom fields for carousel images and metadata
- Import tool to scan directories and generate posts

### Directory Mapping
- One directory = One page
- Subdirectories = Content blocks within page  
- Images in subdirectory = Carousel content
- Optional JSON/text files for titles and descriptions

## Design Specifications

### Content Blocks
- **Header Block**: Page title, optional subtitle, optional description paragraphs
- **Standard Blocks**: Title, subtitle (optional), 1-4 image carousels
- **Progressive Transparency**: Edges fade to completely transparent
- **Modern Fluid Design**: No hard borders or defined edges
- **Responsive Layout**: Automatic scaling for mobile/tablet/desktop

### Background Behavior  
- Fixed background that changes on scroll
- Transitions to first image of focused content block's first carousel
- Smooth crossfade transitions between images
- Full-screen coverage

### Navigation
- Fade in/out based on scroll position
- Mobile-optimized
- Clean, minimal design

## Technical Requirements

### Browser Support
- Mobile-first responsive design
- Touch-friendly carousel controls
- Smooth scrolling performance on mobile devices
- Progressive enhancement for desktop features

### Performance Optimization
- Lazy loading for carousel images
- Optimized image sizes for different viewports
- Minimal JavaScript footprint
- CSS transitions over JavaScript animations where possible

## File Versioning Convention

All artifacts follow this naming pattern:
```
filename-v[major].[minor]-[descriptor]
```

Examples:
- `functions-v1.0-initial` (first version)
- `functions-v1.1-bugfix` (minor update)  
- `functions-v2.0-refactor` (major changes)
- `project-plan-v1.2-session3` (updated in session 3)

**Rule**: Only latest version stays in project knowledge. Archive old versions locally.

## File Dependencies Map

### Core PHP Files
- **functions.php** → Depends on: inc/custom-post-types.php, inc/template-functions.php, inc/customizer.php, inc/carousel-functions.php
- **header.php** → Depends on: functions.php, assets/js/navigation.js, assets/css/portfolio-theme-style.css
- **footer.php** → Depends on: functions.php, assets/js/custom-carousel.js, assets/js/dynamic-background.js
- **index.php** → Depends on: header.php, footer.php, template-parts/content-block.php, functions.php
- **single.php** → Depends on: header.php, footer.php, functions.php
- **page.php** → Depends on: header.php, footer.php, functions.php

### Include Files
- **inc/custom-post-types.php** → Depends on: WordPress core
- **inc/template-functions.php** → Depends on: inc/custom-post-types.php, WordPress core
- **inc/customizer.php** → Depends on: functions.php, WordPress core
- **inc/carousel-functions.php** → Depends on: inc/custom-post-types.php

### JavaScript Files
- **assets/js/custom-carousel.js** → Depends on: jQuery (WordPress core), carousel-styles.css
- **assets/js/dynamic-background.js** → Depends on: jQuery (WordPress core), parallax-scroll.js
- **assets/js/navigation.js** → Depends on: jQuery (WordPress core)
- **assets/js/parallax-scroll.js** → Depends on: jQuery (WordPress core), dynamic-background.js

### CSS Files
- **assets/css/portfolio-theme-style.css** → Depends on: reset.css
- **assets/css/carousel-styles.css** → Depends on: portfolio-theme-style.css
- **assets/css/responsive.css** → Depends on: portfolio-theme-style.css
- **assets/css/reset.css** → No dependencies (base layer)

### Template Parts
- **template-parts/content-block.php** → Depends on: template-parts/carousel.php, inc/template-functions.php
- **template-parts/carousel.php** → Depends on: inc/carousel-functions.php, assets/js/custom-carousel.js

## Development Status

### Updated Development Status

#### ✅ DEPLOYMENT READY (Complete and Tested)
- **functions.php** - Core theme setup, fixed and tested ✅
- **style.css** - WordPress-required main stylesheet ✅
- **custom-post-types.php** - Portfolio post type with admin interface ✅
- **carousel-styles.css** - Advanced carousel with fullscreen mode ✅
- **portfolio-theme-styles.css** - Main theme with floating blocks and parallax ✅
- **parallax-scroll.js** - Core parallax functionality ✅
- **header.php** - Site header with fade navigation and mobile menu ✅ **NEW**
- **footer.php** - Site footer with portfolio data injection and social features ✅ **NEW**
- **index.php** - Main template with complete portfolio block structure and working carousels ✅ **NEW**

#### 🎯 ~~PHASE 1 - IMMEDIATE PRIORITY~~ ✅ **COMPLETE!**
- [x] **header.php** - Site header with fade navigation ✅ COMPLETED
- [x] **footer.php** - Site footer with data injection ✅ COMPLETED  
- [x] **index.php** - Main template with portfolio blocks ✅ COMPLETED

**🚀 MILESTONE ACHIEVED: FULLY DEPLOYABLE WORDPRESS THEME!**

#### 📋 PHASE 2 - JavaScript Enhancement (Current Priority)
- [ ] **custom-carousel.js** - Frontend carousel implementation ⚡ NEXT
- [ ] **dynamic-background.js** - Background crossfade transitions  
- [ ] **navigation.js** - Navigation fade effects

#### 📊 PHASE 3 - Backend & Content Generation
- [ ] **template-functions.php** - Directory scanning, block generation
- [ ] **customizer.php** - Theme options
- [ ] **carousel-functions.php** - Carousel backend
- [ ] **Admin interface** - Directory import management page

#### 🎨 PHASE 4 - Template Parts & Polish  
- [ ] **single.php**, **page.php** - Individual content templates
- [ ] **content-block.php**, **carousel.php** - Template parts
- [ ] **reset.css**, **responsive.css** - Additional styles

### Key Technical Decisions Finalized

#### Background Image Strategy ✅ IMPLEMENTED
- **Hierarchy**: First carousel image → Manual background field → Fallback
- **Data Injection**: Footer provides complete portfolio data as JSON for JavaScript
- **Integration**: Content blocks have data-background-image attributes for crossfade system

#### Navigation Fade Behavior ✅ PARTIALLY IMPLEMENTED  
- **Current**: Fade in at 50px scroll, fade out after 3 seconds
- **Enhancement Needed**: Add scroll direction logic (up = fade in, down = fade out)
- **Future**: WordPress admin customization panel

#### Content Block Organization ✅ IMPLEMENTED
- **Auto-splitting**: Maximum 20 images per carousel, automatic carousel creation
- **Progressive Transparency**: Hazy, undefined edges via CSS masks
- **Data Attributes**: Ready for JavaScript integration (block-index, post-id, background-image)

#### WordPress Admin Integration ✅ READY
- **Customizer Hooks**: All settings use get_theme_mod() for future admin panel
- **Debug Mode**: Shows missing files and system status during development
- **User Experience**: Welcome notices and helpful content creation links

### Current Deployment Capabilities

#### What Works Right Now (No Additional Development Needed):
- ✅ **Theme Activation**: Installs and activates without errors in WordPress
- ✅ **Portfolio Management**: Full admin interface for creating/editing portfolio items
- ✅ **Image Carousels**: Working carousels with fullscreen mode, auto-play, controls
- ✅ **Responsive Design**: Mobile-first layout that works on all devices
- ✅ **Content Structure**: Proper content blocks with floating design
- ✅ **Basic Navigation**: Header navigation with basic fade functionality
- ✅ **Social Integration**: Footer with social media links and contact info
- ✅ **Performance**: Lazy loading, optimized images, minimal JavaScript

#### What Gets Enhanced in Phase 2:
- ⚡ **Background Crossfades**: Smooth transitions between content block backgrounds
- ⚡ **Advanced Parallax**: Enhanced scrolling effects and animations  
- ⚡ **Smart Navigation**: Scroll-direction-based fade logic
- ⚡ **Dynamic Carousels**: Better aspect ratio handling and transitions


### File Integration Status

#### Core Template Structure (Complete):
```
header.php → index.php → footer.php
    ↑            ↓           ↓
functions.php → Portfolio Data → JavaScript Integration
```

#### Data Flow (CRITICAL ISSUES):
```
Portfolio Posts → Content Blocks → Background Data → JavaScript Crossfades
                     ↓                   ↓
               Carousel Images → First Image → Background Fallback
```

#### Admin Integration (NOT IMPLEMENTED YET):
```
WordPress Admin → Portfolio Post Type → Meta Boxes → Carousel Data
                       ↓                    ↓           ↓
                   Directory Path → File Scanning → Auto-Import (Phase 3)
```

# Infrastructure & Deployment

## Current Setup
- Laragon development environment
- Dual web server setup:
  - Apache on port 80 (http://lupoportfoliotest.local)
  - Nginx on port 8081 (http://lupoportfoliotest.local:8081)
- WordPress installation at D:\laragon\www\LupoPortfolioTest
- Automated deployment script in place

## Deployment Automation ✨
- PowerShell deployment script (`deploy-to-laragon.ps1`) implemented ✅
  - Copies theme files to WordPress installation
  - Clears WordPress cache
  - Validates paths and reports errors
  - Co-authored by: Genevieve (VS Code Shard)

### Future Deployment Enhancements
- [ ] Add `-WhatIf` parameter for deployment preview
- [ ] Implement backup feature before deployment
- [ ] Add WordPress CLI integration for database operations
- [ ] Add `-Verbose` switch for detailed logging
- [ ] Add Apache cache clearing functionality
- [ ] Consider adding Nginx cache management

## Infrastructure Notes
- Nginx showing changes immediately after deployment
- Apache may need additional cache configuration
- Consider swapping ports (Nginx → 80, Apache → 8081) for primary access
- Port 8080 reserved for Stable Diffusion web server

// ...existing code...

