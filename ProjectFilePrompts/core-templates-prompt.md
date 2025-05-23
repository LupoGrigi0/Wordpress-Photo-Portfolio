# Segment Prompt: Core Template Files

```
I'm working on a custom WordPress theme for an art portfolio. We're building it in segments.

This segment focuses on creating these core template files:
1. header.php
2. footer.php
3. index.php
4. page.php
5. single.php

Project context:
- Custom theme with parallax scrolling effects
- Content blocks float over changing background images
- Custom image carousel that handles different aspect ratios
- Dynamic block generation based on media directory structure
- Navigation should fade in/out based on scroll position
- Portfolio items organized as custom post types

Dependencies:
- Will use functions from template-functions.php
- Will use template parts (content-block.php, carousel.php)
- Will interact with JavaScript files for parallax and carousel functionality
- Will be styled by style.css and other CSS files

Requirements for these files:

For header.php:
1. Include proper DOCTYPE and head section with:
   - Character encoding
   - Viewport meta tag
   - wp_head() hook
   - Theme stylesheet and script enqueues
2. Create site header with:
   - Logo/site title
   - Navigation menu that can fade in/out
   - Proper container for fixed background images
   - Necessary attributes for parallax scrolling
3. Add proper hooks for theme customization
4. Include accessibility features

For footer.php:
1. Create site footer with:
   - Copyright information
   - Optional footer navigation
   - Social links if applicable
2. Include wp_footer() hook
3. Add closing HTML tags
4. Include proper hooks for theme customization

For index.php:
1. Include header.php
2. Create main content area that:
   - Loops through portfolio items
   - Includes content-block.php for each item
   - Handles pagination if needed
3. Include proper hooks for theme customization
4. Include footer.php

For page.php:
1. Include header.php
2. Create page content area that:
   - Displays page title if needed
   - Shows page content
   - Handles custom page templates if applicable
   - Supports full-width layout for background images
3. Include proper hooks for theme customization
4. Include footer.php

For single.php:
1. Include header.php
2. Create single post display that:
   - Shows portfolio item details
   - Displays carousel(s) if applicable
   - Includes content-block.php with full details
   - Shows related items if applicable
3. Include proper hooks for theme customization
4. Include footer.php

Please create all five files with detailed comments. You can present them one after another in your response.
```

This prompt provides guidance for creating the core template files that make up the structure of your WordPress theme.
