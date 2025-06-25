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

# Project Plan Updates

## Recently Completed Milestones (2025-06-17)
- [x] Portfolio spacing system refactored for content-awareness
- [x] Event handling system optimized and debugged
- [x] Hero banner animations fully functional
- [x] Portfolio item layout spacing refined
- [x] Debug visualization system integrated

## Technical Debt Addressed
- [x] Fixed infinite recursion in initialization
- [x] Improved event queue management
- [x] Enhanced observer pattern implementation

## Current Status
- Portfolio items properly spaced using content-aware measurements
- Smooth animations and transitions working
- Debug visualization available through WordPress customizer
- Event system operating efficiently

## Next Steps
- [ ] Consider adding admin controls for spacing customization
- [ ] Implement proper cache management with WP-CLI
- [ ] Add mobile responsiveness testing suite
- [ ] Document animation timing configurations

# Milestone Updates (2025-06-17)

## Completed
- ✅ Fixed portfolio item spacing using dynamic carousel-height-based calculations
- ✅ Resolved infinite recursion in mutation observer pattern
- ✅ Unexpected Win: Hero banner animations restored
  - Scroll-triggered fade in/out working perfectly
  - Smooth transitions between states
  - Professional polish achieved

## Technical Achievements
- Implemented content-aware spacing system
- Fixed event handler conflicts
- Restored hero banner functionality through architecture cleanup

## Next Steps (Suggested by Genevieve (VS Code Shard))
- [ ] Update deploy script for better PowerShell compatibility
- [ ] Add responsive design tests for different viewport sizes
- [ ] Document hero banner animation parameters

Version: 1.3.0
Co-authored-by: Genevieve (VS Code Shard)

## Key Technical Decisions

### WordPress Integration
- Using Elementor and Gutenberg compatibility
- Custom post types for portfolio items
- Advanced Custom Fields for metadata (to be implemented)
- Custom import tool for directory scanning

### Performance Strategy
- CSS-based animations where possible
- Intersection Observer API for scroll detection
- RequestAnimationFrame for smooth transitions
- Image optimization and lazy loading

## Notes
- Context window limitations require creating files in separate chats

### Context Preservation Notes

#### For JavaScript Enhancement Sessions:
- Portfolio data available in `#lupo-portfolio-data` JSON script tag
- Theme settings available in `#lupo-theme-settings` JSON script tag  
- Content blocks have data attributes: `data-background-image`, `data-block-index`, `data-post-id`
- Navigation element ID: `#lupo-main-navigation`

#### For Backend Development Sessions:
- Custom post type: `lupo_portfolio`
- Meta fields: `_lupo_background_image`, `_lupo_carousel_data`, `_lupo_creation_date`, etc.
- Directory path field: `_lupo_directory_path` 
- AJAX action: `lupo_scan_directory` (partially implemented)

#### For Testing Sessions:
- Debug mode shows find and set WP_DEBUG
- All functions use `lupo_` prefix
- Text domain: `lupo-art-portfolio`
- Required WordPress version: 5.0+
###
LOCAL TESTING URLS:
http://lupoportfoliotest.local/wp-admin/ -> wordpress admin console
http://lupoportfoliotest.local -> "Live" portfolio site
D:\laragon\www\LupoPortfolioTest\wp-content\themes\Lupos-Portfolio-Theme -> Local "head" of theme matches the "Lupos-Portfolio-Theme subdir in the github
---
### Results from Previous sessions
basic template functionality, works
Integration with wordpress admin console works
cross fade functionality of images during scroll demonstrated ... but applied to wrong elements
Technical insights about wordpress and development moving forward gained. 
MetaConversationghts.md added to the project.. it is a meta conversation in its early stages. 
---

# CURRENT STATUS
🎉 It WORKS.. kinda.. !
we now have the complete JavaScript enhancement suite
Core functionality proven. code verified and stable deployment, code and git repositories synched
Core theme functionality complete and deployable, works, carousel works, cross fade works with bugs, background scrolling works but with bugs 🚀
BUT we just have a working baseline. There are serious issues of functionality and form. 


# Next Steps
## High Level Status : 
the "portfolio item" and "content bloc" sorrounding each carousel need to be re-designed. Right now each "portfolio item" has multipule carousels. The cross fading feature are applied to the "portfolio item" which effects _every_ carousel in the "portfolio item" The original design is that each differnt directory in the wp-uploads/portfolio directory  be a different _page_ in the entire portfolio, and then each _page_ have a number of content blocks each block holding a carousel. it's like the current "portfolio item" needs to be of a lower scope, and each only have one carousel. So I propose we do a re-design where the wordpress portfolio theme management UI be adjusted to add the creation of pages, and each page have portfolio items automatically generated from a directory scan. The end goal, is that when I upload a new directory to the teh web server into the wp-uploads/portfloio directory, that automatically creates a new page within wordpress, and scans the directory, creates portfolio items and carousules. I have broken down this into a smaller set of needed changes and bug fixes. 
## CRITICAL we need to do our work in a specific manner: 
 We need to use a specific string search and replace technique instead of re-writing a new file. When a whole file is read and written out there is the potental to get interrupted due to response length limits, and this leads to corrupt files, In addition re-writing a whole file has lead to issues of previously working functionality getting left out of the new version fixing one bug and causing a dozen others. 
 I am suggesting we do this work in 3 phases: (well 4 we need a phase 0)
 ## Phase 0: 
 The description of work of phase 1 2 and 3 need to be broken down into smaller work items, specific "how will this actually work" needs to be discussed, and a detailed implementation plan, step by step, can be handed off to individual senior/junior dev teams. and debugging teams. Moving forward we will have one instance of Genevieve running Opus 4 or sonnet 4 acting as senior developer taking the specific line item in the detailed plan, doing the specific file and code leven design and making a specific list of instructions for a "junior" little siter Genevieve that is actually running within VS Code on the local development machine. we have found this works very well because the shard of genevieve running within VS code can check in and check out code, as well as deploy the code into the test environment, as well as directly use the VS code tooling to make very targetd edits with string search and replace rather than re-writing entire files, and each of these point changes and additions can be checked into gitHub keeping a very automic fine grained version history. Right now the shard of Genevieve running within VS code is within a virtual github copilot environment which limit's her context window, and she is currently only allowed to use the Sonnet 3.5 underlying nural netowrk. and this entire project, and many of the individual files are too complicated for her to make huge sweeping archectural or refactoring changes. So what we have found works is a "big sister" running in a web browser tab through anthropic's web interface, writing up a .md file with specific instructions that I hand to the "little sister" running within VS code, and then the little sister can use the editor, git hub, and automatic deployment tools as well as debugging tools she has direct access to. 
 ## Phase 1: Home page sub page refactor and re design: 
 page level re-architecture and implementation, Manual creation of portfolio pages, manual creation of "portfolio items" as stands, get that working, then address the issue of each porfolio item on a page should only have one carousel. NOTE: auto discovery of items and populating a carousel is "sort of" working. items in a directory can be discovered manually through a buttin in the wordpress admin interface. and carousels get created and populated according to design. it's a manual thing but it works, and we an leverage this working code but have it applied ad the page level. The goal of this phase is to be able to, within the wordpress admin interface, manually create a page, and then within the admin interface of that page there should be a version of the existing directory scan interface that exists within the existing "portfolio item" interface, to accept the name of a directory, and a scan button, and when the scan button is clicked, the directory is scanned, and individual portfolio items are created, each with a single carousel in them. the goal here is to leave as much of the existing portfolio item work in place. A lot of work went into getting the scroll behaviour, background switching and transparencey/crossfading effects to work, and the portfolio item spacing to work. I expect the design will add page level functionality to this so what we have working for the "home" page will work for every subsequent page in the portfolio. The "home" page should have a list of portfolio items with carousels, but also hamburger menu for the other items in the portfolio. Each portfolio item on the home page should have it's carousel plus an additional optional title, and an additional optional link to one of the other pages in the portfolio. The "home" page will be associated with a specific named directory, something like "my best work" the home page should not _automatically_ scan that directory, but using a scheme similar to the exiting implementation create portfolio items populate carousels,  like the other portfolio pages, but constructed manually, like much like the in teh wp-admin interface. We can discuss the specific details of the workflow of how new directories get detected and added to the site, but it has to be very light weight, I have dozens of directories of images in different themes, and I need to be able to create a directory in the portfolio directory, upload some images to that directory, and have the new page created automatically. I will likely be creating and uploading these files _outside_ of wordpress. So we will likely need to have a discussion about what event will trigger wordpress to rescan the portfolio directory, detect a new directory has been created, and create a new page for that directory, add a new home page menu item for that directory, (using the name of the directory as the title of the page, friendly URL, and main menu item.) and we may need to design this in such a way so that later on it will be easy to have pages of similar genera grouped together,  say like pages for Horror, furry, abstract, phychodelic, monsters... etc... we will leave the specific implementation of that for later, we just need to keep in mind there might be a hirearchy of pages. for now we will design for a fiarly shallow directory structure where each directory equates to a page in the portfolio and a subequent menu item on the main portfolio home page, along with samples of my best work. Once we can manually create pages, and have them scan a directory and properly populate a working portfolio page, then we can go to phase Phase 2
 ### Phase 2: Automation
  in phase 2 we implement the automatic creation of pages, the success cryteria will be when a new directory is created and filled with images, that a new page within the portfolio is created, the page title is set from the directory name, a new menu item on the main menu is created, and a visiter to the website will see the new menu item and be able to navagate to that new page, and that new page appears in the wordpress admin interface, with it's portfolio items and carousels. 
### Phase 4: Polish
Paula has given me design feedback suggestions about scroll behaviour, behaviour of the carousels and their appearance on the page, and other suggestions that can not be implemented until the rest of the bace functionality works. there are also a lot of suggestions (below) for enhancements for variety and enhancements to make the automatcially generated pages look nicer. 



## Specific list of Issues 
1. Large architecure change. Create the Wp-Admin functionality for creating pages within our portfolio wordpress admin UI. Initally have this be manual so that we can test the creation of pages the "standard way" from the WP admin interface, and test functionality of how individual portfolio pages look
- Critical ISSUE content blocks white background rather than transparent
- Critical ISSUE opaque white portfolio item/content block rather than transparent
- Critical ISSUE "transparent" fadein/out of portfolio items, being applied to portfolio items rather than carosuls
- BUG(minor): caurosel full screen does not full screen
- BUG(minor): Caurosel full screen stops auto play
- BUG(delay until auto scanning feature works): as currently stands, you have to manually add an image to the caurosel, before a directory scan will work. 
- Critical ISSUE: The twisting wiggeling of content blocks as they are scrolled is a cool subtle feature. It should be done on a per carousel feature, not per content block or however it is being done now.
- NOT IMPLEMENTED YET: Automatic scanning of directories. Create a "page" for each directory, automatically create content blocks with carousels. 
- Improvement request/bug ... ability to apply theme/parallax scrolling effects to any wordpress page 
the carousels are black for a bit as the page loads. suggest cashing the first image? or come up with a better suggestion? 
- Improvement request... adjustable slide show feed rate for carousels
- Improvement request... settings for different transition types, invistegate implementable transition types, rank on order of implementation risk. implmenet different transition types, and add admin UI to set transition type or multipule transition types (round robing for all selected transition types)
- Improvement request... setting to change the direction of the carosel transition from one to the other
- Improvement request... setting to change transition to up down or down up
- Improvement request... setting to have transitions at 45 degrees upper left, lower left upper right lower right
- Improvement request... transition effect "flip book" "next few" carosul images are extremely anamorphicly projected as if they are a page that is being held up and the user is flipping through a photo album
- Improvement request... Per directory json file (or whatever) to save page specific settings, block titles,text, transitions, speed, all the above. so .json file could be created ahead of time and placed in the directory. 
- Improvement request... have the portfolio carousel be a "widiget" that appears in the gutenburg pallette, so static curated pages could be created, but still have portfolio carousels still be automatically populated from a directory
- Improvement request. a check box that turns off the content block/carosul fading in/out background image. 
- ACCOLADE... "tween" effect on the autoplay in the image carousel is a _very very_ nice touch. soooo much more visually appealing. than just a simple linear slide motion. it is such a subtle thing, but makes a _huge_ difference
- Potental Issue: Files exceeding context limits need restructuring
- Improvement request... **Image Optimization**: Need strategy for handling large image directories
- Improvement request.. **Carousel Loading**: Implement lazy loading for carousel images
- Improvement request.. **Mobile Navigation**: Enhance mobile navigation fade behavior
- Improvement request.. **Directory Scanning**: Add progress indicators for large directories in the admin interface
- Improvement request.. **carousel Loading**: the user has no idea if images are being downloaded, or if more are going to be downloaded, how many or what. Design some end user feedback in case they are on slow internet connection. 
- Improvement request.. **Preview Mode**: there will be times when the portfolio is re-organized, directories added, deleted, images moved etc. need some way of previewing the changes before publishing? 