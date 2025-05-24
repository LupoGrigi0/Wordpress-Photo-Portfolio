<?php
/**
 * Art Portfolio Theme Functions
 *
 * Main theme setup, enqueuing scripts/styles, and core functionality
 *
 * @package ArtPortfolioTheme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme version for cache busting
define( 'APT_VERSION', '1.0.0' );

/**
 * Theme setup function
 */
function apt_theme_setup() {
    // Add theme support for various features
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'art-portfolio-theme' ),
        'footer'  => __( 'Footer Menu', 'art-portfolio-theme' ),
    ) );

    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'apt_theme_setup' );

/**
 * Enqueue styles and scripts
 */
function apt_enqueue_assets() {
    // CSS Files
    wp_enqueue_style( 'apt-reset', get_template_directory_uri() . '/assets/css/reset.css', array(), APT_VERSION );
    wp_enqueue_style( 'apt-main', get_template_directory_uri() . '/assets/css/portfolio-theme-style.css', array( 'apt-reset' ), APT_VERSION );
    wp_enqueue_style( 'apt-carousel', get_template_directory_uri() . '/assets/css/carousel.css', array( 'apt-main' ), APT_VERSION );
    wp_enqueue_style( 'apt-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array( 'apt-main' ), APT_VERSION );

    // JavaScript Files
    wp_enqueue_script( 'apt-parallax', get_template_directory_uri() . '/assets/js/parallax-scroll.js', array( 'jquery' ), APT_VERSION, true );
    wp_enqueue_script( 'apt-background', get_template_directory_uri() . '/assets/js/dynamic-background.js', array( 'jquery' ), APT_VERSION, true );
    wp_enqueue_script( 'apt-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array( 'jquery' ), APT_VERSION, true );
    wp_enqueue_script( 'apt-carousel', get_template_directory_uri() . '/assets/js/custom-carousel.js', array( 'jquery' ), APT_VERSION, true );

    // Localize scripts for AJAX
    wp_localize_script( 'apt-parallax', 'apt_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'apt_nonce' ),
    ) );

    // Pass theme settings to JavaScript
    wp_localize_script( 'apt-background', 'apt_settings', array(
        'transition_speed' => 800, // milliseconds for crossfade
        'scroll_threshold' => 100, // pixels before triggering background change
        'mobile_breakpoint' => 768, // pixels
    ) );
}
add_action( 'wp_enqueue_scripts', 'apt_enqueue_assets' );

/**
 * Enqueue admin assets
 */
function apt_enqueue_admin_assets( $hook ) {
    global $post_type;
    
    // Only load on portfolio post type edit screens
    if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && 'apt_portfolio' === $post_type ) {
        wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-sortable' );
        
        // Admin-specific styles
        wp_add_inline_style( 'wp-admin', '
            .apt-meta-field { margin-bottom: 20px; }
            .apt-meta-field label { display: block; font-weight: bold; margin-bottom: 5px; }
            .apt-meta-field .description { font-style: italic; color: #666; }
            #apt_carousel_items { margin-top: 15px; display: flex; flex-wrap: wrap; gap: 15px; }
            .apt-carousel-item { border: 1px solid #ddd; padding: 10px; width: 170px; background: #f9f9f9; cursor: move; }
            .apt-carousel-item-details { margin-top: 10px; }
            .apt-carousel-caption { width: 100%; margin-bottom: 5px; }
            .apt-carousel-toolbar { margin-bottom: 15px; }
            .ui-sortable-helper { box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        ' );
    }
}
add_action( 'admin_enqueue_scripts', 'apt_enqueue_admin_assets' );

/**
 * Include required files
 */
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/carousel-functions.php';

/**
 * Register widget areas
 */
function apt_register_sidebars() {
    register_sidebar( array(
        'name'          => __( 'Footer Widgets', 'art-portfolio-theme' ),
        'id'            => 'footer-widgets',
        'description'   => __( 'Widgets that appear in the footer area', 'art-portfolio-theme' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'apt_register_sidebars' );

/**
 * Add custom image sizes for portfolio
 */
function apt_add_image_sizes() {
    // Background images for parallax
    add_image_size( 'apt-background-large', 1920, 1080, true );
    add_image_size( 'apt-background-medium', 1366, 768, true );
    add_image_size( 'apt-background-small', 768, 432, true );
    
    // Carousel images - maintain aspect ratio
    add_image_size( 'apt-carousel-large', 800, 800, false );
    add_image_size( 'apt-carousel-medium', 400, 400, false );
    add_image_size( 'apt-carousel-small', 200, 200, false );
    
    // Thumbnail for admin
    add_image_size( 'apt-admin-thumb', 150, 150, true );
}
add_action( 'after_setup_theme', 'apt_add_image_sizes' );

/**
 * Add custom image sizes to media uploader
 */
function apt_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'apt-background-large' => __( 'Background Large', 'art-portfolio-theme' ),
        'apt-background-medium' => __( 'Background Medium', 'art-portfolio-theme' ),
        'apt-carousel-large' => __( 'Carousel Large', 'art-portfolio-theme' ),
        'apt-carousel-medium' => __( 'Carousel Medium', 'art-portfolio-theme' ),
    ) );
}
add_filter( 'image_size_names_choose', 'apt_custom_image_sizes' );

/**
 * Customize excerpt length
 */
function apt_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'apt_excerpt_length' );

/**
 * Custom excerpt more text
 */
function apt_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'apt_excerpt_more' );

/**
 * Remove unwanted WordPress features for cleaner portfolio display
 */
function apt_clean_wp_features() {
    // Remove emoji scripts
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    
    // Remove unnecessary meta tags
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    
    // Clean up query strings from static resources
    add_filter( 'script_loader_src', 'apt_remove_script_version', 15, 1 );
    add_filter( 'style_loader_src', 'apt_remove_script_version', 15, 1 );
}
add_action( 'init', 'apt_clean_wp_features' );

/**
 * Remove version query strings from static resources
 */
function apt_remove_script_version( $src ) {
    if ( strpos( $src, 'ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

/**
 * Add body classes for better styling control
 */
function apt_body_classes( $classes ) {
    // Add class for portfolio pages
    if ( is_singular( 'apt_portfolio' ) ) {
        $classes[] = 'single-portfolio';
    }
    
    // Add class for portfolio archive
    if ( is_post_type_archive( 'apt_portfolio' ) ) {
        $classes[] = 'portfolio-archive';
    }
    
    // Add mobile/desktop classes
    $classes[] = wp_is_mobile() ? 'mobile-device' : 'desktop-device';
    
    return $classes;
}
add_filter( 'body_class', 'apt_body_classes' );

/**
 * AJAX handler for loading more portfolio items
 */
function apt_load_more_portfolio() {
    check_ajax_referer( 'apt_nonce', 'nonce' );
    
    $page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $posts_per_page = isset( $_POST['posts_per_page'] ) ? intval( $_POST['posts_per_page'] ) : 6;
    
    $query = new WP_Query( array(
        'post_type'      => 'apt_portfolio',
        'post_status'    => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'meta_query'     => array(
            array(
                'key'     => '_apt_carousel_data',
                'compare' => 'EXISTS',
            ),
        ),
    ) );
    
    $response = array(
        'success' => false,
        'data'    => '',
        'has_more' => false,
    );
    
    if ( $query->have_posts() ) {
        ob_start();
        
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/content-block' );
        }
        
        $response['data'] = ob_get_clean();
        $response['success'] = true;
        $response['has_more'] = $query->max_num_pages > $page;
    }
    
    wp_reset_postdata();
    wp_send_json( $response );
}
add_action( 'wp_ajax_apt_load_more_portfolio', 'apt_load_more_portfolio' );
add_action( 'wp_ajax_nopriv_apt_load_more_portfolio', 'apt_load_more_portfolio' );

/**
 * Add theme support for Gutenberg editor styles
 */
function apt_add_editor_styles() {
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor-style.css' );
}
add_action( 'after_setup_theme', 'apt_add_editor_styles' );

/**
 * Optimize portfolio queries for better performance
 */
function apt_optimize_portfolio_queries( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( is_home() ) {
            // Show portfolio items on home page
            $query->set( 'post_type', array( 'post', 'apt_portfolio' ) );
            $query->set( 'posts_per_page', 8 );
        }
        
        if ( is_post_type_archive( 'apt_portfolio' ) ) {
            $query->set( 'posts_per_page', 12 );
            $query->set( 'orderby', 'menu_order date' );
            $query->set( 'order', 'ASC' );
        }
    }
}
add_action( 'pre_get_posts', 'apt_optimize_portfolio_queries' );

/**
 * Add custom CSS for progressive transparency edges
 */
function apt_custom_css() {
    ?>
    <style id="apt-custom-css">
        :root {
            --apt-transition-speed: 0.8s;
            --apt-blur-radius: 20px;
            --apt-transparency-fade: 30px;
        }
        
        /* Progressive transparency for content blocks */
        .content-block {
            position: relative;
            margin: 2rem 0;
            padding: 2rem;
            backdrop-filter: blur(var(--apt-blur-radius));
            -webkit-backdrop-filter: blur(var(--apt-blur-radius));
        }
        
        .content-block::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                to bottom,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 20%,
                rgba(255, 255, 255, 0.1) 80%,
                rgba(255, 255, 255, 0) 100%
            );
            border-radius: 20px;
            pointer-events: none;
            z-index: -1;
        }
        
        /* Edge fade effect */
        .content-block::after {
            content: '';
            position: absolute;
            top: -var(--apt-transparency-fade);
            left: -var(--apt-transparency-fade);
            right: -var(--apt-transparency-fade);
            bottom: -var(--apt-transparency-fade);
            background: radial-gradient(
                ellipse at center,
                transparent 60%,
                rgba(0, 0, 0, 0.1) 100%
            );
            pointer-events: none;
            z-index: -2;
        }
        
        /* Smooth background transitions */
        .parallax-background {
            transition: all var(--apt-transition-speed) ease-in-out;
            background-blend-mode: multiply;
        }
        
        /* Navigation fade effect */
        .site-navigation {
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        
        .site-navigation.fade-out {
            opacity: 0;
            transform: translateY(-20px);
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .content-block {
                margin: 1rem 0;
                padding: 1.5rem 1rem;
            }
            
            :root {
                --apt-blur-radius: 10px;
                --apt-transparency-fade: 20px;
            }
        }
    </style>
    <?php
}
add_action( 'wp_head', 'apt_custom_css' );

/**
 * Security enhancements
 */
function apt_security_headers() {
    if ( ! is_admin() ) {
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-Frame-Options: SAMEORIGIN' );
        header( 'X-XSS-Protection: 1; mode=block' );
    }
}
add_action( 'send_headers', 'apt_security_headers' );

/**
 * Performance optimizations
 */
function apt_performance_optimizations() {
    // Disable WordPress heartbeat on frontend
    if ( ! is_admin() ) {
        wp_deregister_script( 'heartbeat' );
    }
    
    // Disable dashicons for non-logged-in users
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}
add_action( 'wp_enqueue_scripts', 'apt_performance_optimizations' );

/**
 * Theme activation hook
 */
function apt_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default options
    if ( ! get_option( 'apt_theme_version' ) ) {
        update_option( 'apt_theme_version', APT_VERSION );
        
        // Set default customizer options
        set_theme_mod( 'apt_parallax_enabled', true );
        set_theme_mod( 'apt_crossfade_speed', 800 );
        set_theme_mod( 'apt_navigation_fade', true );
    }
}
add_action( 'after_switch_theme', 'apt_theme_activation' );
