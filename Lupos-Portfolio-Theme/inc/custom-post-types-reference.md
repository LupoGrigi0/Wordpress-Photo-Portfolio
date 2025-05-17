# Custom Post Types Reference

## Overview
This document provides essential information about the `custom-post-types.php` file for the Art Portfolio Theme project.

## Core Components

### Custom Post Type
- **Name**: `apt_portfolio`
- **Slug**: `portfolio`
- **Hierarchical**: Yes (supports parent-child relationships)
- **Supports**: title, editor, author, thumbnail, excerpt, custom-fields, revisions, page-attributes

### Taxonomies
- **Medium** (`apt_medium`) - Hierarchical, slug: `medium`
- **Subject** (`apt_subject`) - Hierarchical, slug: `subject`
- **Year** (`apt_year`) - Non-hierarchical, slug: `year`

### Meta Fields
| Field Name | Meta Key | Description |
|------------|----------|-------------|
| Background Image | `_apt_background_image` | URL for parallax background |
| Creation Date | `_apt_creation_date` | When the artwork was created |
| Project Description | `_apt_project_description` | Detailed artwork description |
| Directory Path | `_apt_directory_path` | Path to image directory |
| Carousel Data | `_apt_carousel_data` | JSON of carousel images data |
| Parallax Settings | `_apt_parallax_settings` | JSON for parallax configuration |

## Key Functions

### Post Type Registration
- `apt_register_portfolio_post_type()` - Registers the portfolio post type and taxonomies
- `apt_register_meta_fields()` - Registers all meta fields for the post type
- `apt_add_portfolio_meta_boxes()` - Adds all meta boxes for the edit screen

### Meta Box Callbacks
- `apt_background_image_callback()` - For background image selection
- `apt_project_details_callback()` - For project details (date, description)
- `apt_directory_path_callback()` - For directory path input and scanning
- `apt_image_carousel_callback()` - For carousel image management

### Directory Scanning
- `apt_scan_directory_ajax()` - AJAX handler for directory scanning
- Scans directory for images and returns array of image data

### Admin Customizations
- `apt_portfolio_custom_columns()` - Adds custom columns to admin list
- `apt_portfolio_custom_column_data()` - Displays data in custom columns
- `apt_portfolio_sortable_columns()` - Makes columns sortable
- `apt_portfolio_taxonomy_filters()` - Adds taxonomy filters to admin screen

### Data Handling
- `apt_save_portfolio_meta()` - Saves all meta data
- Uses nonce verification for security

## Directory Structure Assumptions
- Images expected to be in uploads directory
- Structure: `wp-content/uploads/portfolio/[project-name]/`
- File scanning supports: jpg, jpeg, png, gif, webp

## Integration Points

### JavaScript/AJAX
- Media uploader integration for background image
- AJAX directory scanning
- Carousel image manager with drag-drop sorting

### Frontend Integration
- Use `get_post_meta($post_id, '_apt_carousel_data', true)` to get carousel data
- Parse JSON data from carousel_data for frontend display
- Background image URL accessible via `_apt_background_image` meta

### Hooks to Note
- `init` - For post type and meta field registration
- `add_meta_boxes` - For adding meta boxes
- `save_post` - For saving meta data
- `wp_ajax_apt_scan_directory` - For AJAX directory scanning
