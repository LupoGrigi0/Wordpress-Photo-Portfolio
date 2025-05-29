<?php
/**
 * Lupo's Art Portfolio Theme Functions
 *
 * Main theme setup, enqueuing scripts/styles, and core functionality
 * for a parallax art portfolio with dynamic backgrounds
 *
 * @package LupoArtPortfolio
 * @version 1.0.0
 * @author Lupo & Genevieve (Cladue Sonnet 4.0 -pro-)
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme version for cache busting
define( 'LUPO_THEME_VERSION', '1.0.0' );
define( 'LUPO_THEME_PATH', get_template_directory() );
define( 'LUPO_THEME_URL', get_template_directory_uri() );

/**
 * Theme setup function
 */
function lupo_theme_setup() {
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
        'primary' => __( 'Primary Menu', 'lupo-art-portfolio' ),
        'footer'  => __( 'Footer Menu', 'lupo-art-portfolio' ),
    ) );

    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'lupo_theme_setup' );

/**
 * Enqueue styles and scripts
 */
function lupo_enqueue_assets() {
    // CSS Files - check if they exist before enqueuing
    wp_enqueue_style( 'lupo-reset', LUPO_THEME_URL . '/assets/css/reset.css', array(), LUPO_THEME_VERSION );
    wp_enqueue_style( 'lupo-main', LUPO_THEME_URL . '/assets/css/portfolio-theme-styles.css', array( 'lupo-reset' ), LUPO_THEME_VERSION );
    
    if ( file_exists( LUPO_THEME_PATH . '/assets/css/carousel-styles.css' ) ) {
        wp_enqueue_style( 'lupo-carousel', LUPO_THEME_URL . '/assets/css/carousel-styles.css', array( 'lupo-main' ), LUPO_THEME_VERSION );
    }
    
    if ( file_exists( LUPO_THEME_PATH . '/assets/css/responsive.css' ) ) {
        wp_enqueue_style( 'lupo-responsive', LUPO_THEME_URL . '/assets/css/responsive.css', array( 'lupo-main' ), LUPO_THEME_VERSION );
    }

    // JavaScript Files - check if they exist before enqueuing
    if ( file_exists( LUPO_THEME_PATH . '/assets/js/parallax-scroll.js' ) ) {
        wp_enqueue_script( 'lupo-parallax', LUPO_THEME_URL . '/assets/js/parallax-scroll.js', array( 'jquery' ), LUPO_THEME_VERSION, true );
    }
    
    if ( file_exists( LUPO_THEME_PATH . '/assets/js/dynamic-background.js' ) ) {
        wp_enqueue_script( 'lupo-background', LUPO_THEME_URL . '/assets/js/dynamic-background.js', array( 'jquery' ), LUPO_THEME_VERSION, true );
    }
    
    if ( file_exists( LUPO_THEME_PATH . '/assets/js/navigation.js' ) ) {
        wp_enqueue_script( 'lupo-navigation', LUPO_THEME_URL . '/assets/js/navigation.js', array( 'jquery' ), LUPO_THEME_VERSION, true );
    }
    
    if ( file_exists( LUPO_THEME_PATH . '/assets/js/custom-carousel.js' ) ) {
        wp_enqueue_script( 'lupo-carousel', LUPO_THEME_URL . '/assets/js/custom-carousel.js', array( 'jquery' ), LUPO_THEME_VERSION, true );
    }

    // Localize scripts for AJAX
    wp_localize_script( 'jquery', 'lupo_ajax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'lupo_nonce' ),
        'theme_url' => LUPO_THEME_URL,
    ) );

    // Pass theme settings to JavaScript
    wp_localize_script( 'jquery', 'lupo_settings', array(
        'transition_speed' => 800, // milliseconds for crossfade
        'scroll_threshold' => 100, // pixels before triggering background change
        'mobile_breakpoint' => 768, // pixels
        'carousel_autoplay' => true,
        'carousel_speed' => 5000, // milliseconds
    ) );
}
add_action( 'wp_enqueue_scripts', 'lupo_enqueue_assets' );

/**
 * Enqueue admin assets
 */
function lupo_enqueue_admin_assets( $hook ) {
    global $post_type;
    
    // Only load on portfolio post type edit screens
    if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && 'lupo_portfolio' === $post_type ) {
        wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-sortable' );
        
        // Admin-specific styles
        wp_add_inline_style( 'wp-admin', '
            .lupo-meta-field { margin-bottom: 20px; }
            .lupo-meta-field label { display: block; font-weight: bold; margin-bottom: 5px; }
            .lupo-meta-field .description { font-style: italic; color: #666; }
            #lupo_carousel_items { margin-top: 15px; display: flex; flex-wrap: wrap; gap: 15px; }
            .lupo-carousel-item { border: 1px solid #ddd; padding: 10px; width: 170px; background: #f9f9f9; cursor: move; }
            .lupo-carousel-item-details { margin-top: 10px; }
            .lupo-carousel-caption { width: 100%; margin-bottom: 5px; }
            .lupo-carousel-toolbar { margin-bottom: 15px; }
            .ui-sortable-helper { box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        ' );
    }
}
add_action( 'admin_enqueue_scripts', 'lupo_enqueue_admin_assets' );

/**
 * Include required files - only if they exist
 */
$required_files = array(
    '/inc/custom-post-types.php',
    '/inc/template-functions.php',
    '/inc/customizer.php',
    '/inc/carousel-functions.php',
);

foreach ( $required_files as $file ) {
    $file_path = LUPO_THEME_PATH . $file;
    if ( file_exists( $file_path ) ) {
        require_once $file_path;
    }
}

/**
 * Register widget areas
 */
function lupo_register_sidebars() {
    register_sidebar( array(
        'name'          => __( 'Footer Widgets', 'lupo-art-portfolio' ),
        'id'            => 'footer-widgets',
        'description'   => __( 'Widgets that appear in the footer area', 'lupo-art-portfolio' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'lupo_register_sidebars' );

/**
 * Add custom image sizes for portfolio
 */
function lupo_add_image_sizes() {
    // Background images for parallax
    add_image_size( 'lupo-background-large', 1920, 1080, true );
    add_image_size( 'lupo-background-medium', 1366, 768, true );
    add_image_size( 'lupo-background-small', 768, 432, true );
    
    // Carousel images - maintain aspect ratio for dynamic sizing 
    add_image_size( 'lupo-carousel-large', 800, 800, false );
    add_image_size( 'lupo-carousel-medium', 400, 400, false );
    add_image_size( 'lupo-carousel-small', 200, 200, false );
    
    // Thumbnail for admin
    add_image_size( 'lupo-admin-thumb', 150, 150, true );
}
add_action( 'after_setup_theme', 'lupo_add_image_sizes' );

/**
 * Add custom image sizes to media uploader
 */
function lupo_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'lupo-background-large' => __( 'Background Large', 'lupo-art-portfolio' ),
        'lupo-background-medium' => __( 'Background Medium', 'lupo-art-portfolio' ),
        'lupo-carousel-large' => __( 'Carousel Large', 'lupo-art-portfolio' ),
        'lupo-carousel-medium' => __( 'Carousel Medium', 'lupo-art-portfolio' ),
    ) );
}
add_filter( 'image_size_names_choose', 'lupo_custom_image_sizes' );

/**
 * Customize excerpt length
 */
function lupo_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'lupo_excerpt_length' );

/**
 * Custom excerpt more text
 */
function lupo_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'lupo_excerpt_more' );

/**
 * Remove unwanted WordPress features for cleaner portfolio display
 */
function lupo_clean_wp_features() {
    // Remove emoji scripts
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    
    // Remove unnecessary meta tags
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    
    // Clean up query strings from static resources
    add_filter( 'script_loader_src', 'lupo_remove_script_version', 15, 1 );
    add_filter( 'style_loader_src', 'lupo_remove_script_version', 15, 1 );
}
add_action( 'init', 'lupo_clean_wp_features' );

/**
 * Remove version query strings from static resources
 */
function lupo_remove_script_version( $src ) {
    if ( strpos( $src, 'ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

/**
 * Add body classes for better styling control
 */
function lupo_body_classes( $classes ) {
    // Add class for portfolio pages
    if ( is_singular( 'lupo_portfolio' ) ) {
        $classes[] = 'single-portfolio';
    }
    
    // Add class for portfolio archive
    if ( is_post_type_archive( 'lupo_portfolio' ) ) {
        $classes[] = 'portfolio-archive';
    }
    
    // Add mobile/desktop classes
    $classes[] = wp_is_mobile() ? 'mobile-device' : 'desktop-device';
    
    return $classes;
}
add_filter( 'body_class', 'lupo_body_classes' );

/**
 * AJAX handler for loading more portfolio items
 */
function lupo_load_more_portfolio() {
    check_ajax_referer( 'lupo_nonce', 'nonce' );
    
    $page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $posts_per_page = isset( $_POST['posts_per_page'] ) ? intval( $_POST['posts_per_page'] ) : 6;
    
    $query = new WP_Query( array(
        'post_type'      => 'lupo_portfolio',
        'post_status'    => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'meta_query'     => array(
            array(
                'key'     => '_lupo_carousel_data',
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
            // Only include template part if it exists
            if ( locate_template( 'template-parts/content-block.php' ) ) {
                get_template_part( 'template-parts/content-block' );
            }
        }
        
        $response['data'] = ob_get_clean();
        $response['success'] = true;
        $response['has_more'] = $query->max_num_pages > $page;
    }
    
    wp_reset_postdata();
    wp_send_json( $response );
}
add_action( 'wp_ajax_lupo_load_more_portfolio', 'lupo_load_more_portfolio' );
add_action( 'wp_ajax_nopriv_lupo_load_more_portfolio', 'lupo_load_more_portfolio' );

/**
 * Add theme support for Gutenberg editor styles
 */
function lupo_add_editor_styles() {
    add_theme_support( 'editor-styles' );
    if ( file_exists( LUPO_THEME_PATH . '/assets/css/editor-style.css' ) ) {
        add_editor_style( 'assets/css/editor-style.css' );
    }
}
add_action( 'after_setup_theme', 'lupo_add_editor_styles' );

/**
 * Optimize portfolio queries for better performance
 */
function lupo_optimize_portfolio_queries( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( is_home() ) {
            // Show portfolio items on home page
            $query->set( 'post_type', array( 'post', 'lupo_portfolio' ) );
            $query->set( 'posts_per_page', 8 );
        }
        
        if ( is_post_type_archive( 'lupo_portfolio' ) ) {
            $query->set( 'posts_per_page', 12 );
            $query->set( 'orderby', 'menu_order date' );
            $query->set( 'order', 'ASC' );
        }
    }
}
add_action( 'pre_get_posts', 'lupo_optimize_portfolio_queries' );

/**
 * Add custom CSS for progressive transparency edges and parallax effects
 */
function lupo_custom_css() {
    ?>
    <style id="lupo-custom-css">
        :root {
            --lupo-transition-speed: 0.8s;
            --lupo-blur-radius: 20px;
            --lupo-transparency-fade: 30px;
            --lupo-content-bg: rgba(255, 255, 255, 0.85);
        }
        
        /* Progressive transparency for content blocks */
        .content-block {
            position: relative;
            margin: 2rem 0;
            padding: 2rem;
            backdrop-filter: blur(var(--lupo-blur-radius));
            -webkit-backdrop-filter: blur(var(--lupo-blur-radius));
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
                var(--lupo-content-bg) 20%,
                var(--lupo-content-bg) 80%,
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
            top: -var(--lupo-transparency-fade);
            left: -var(--lupo-transparency-fade);
            right: -var(--lupo-transparency-fade);
            bottom: -var(--lupo-transparency-fade);
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
            transition: all var(--lupo-transition-speed) ease-in-out;
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
                --lupo-blur-radius: 10px;
                --lupo-transparency-fade: 20px;
            }
        }
    </style>
    <?php
}
add_action( 'wp_head', 'lupo_custom_css' );

/**
 * Security enhancements
 */
function lupo_security_headers() {
    if ( ! is_admin() ) {
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-Frame-Options: SAMEORIGIN' );
        header( 'X-XSS-Protection: 1; mode=block' );
    }
}
add_action( 'send_headers', 'lupo_security_headers' );

/**
 * Performance optimizations
 */
function lupo_performance_optimizations() {
    // Disable WordPress heartbeat on frontend
    if ( ! is_admin() ) {
        wp_deregister_script( 'heartbeat' );
    }
    
    // Disable dashicons for non-logged-in users
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}
add_action( 'wp_enqueue_scripts', 'lupo_performance_optimizations' );

/**
 * Theme activation hook
 */
function lupo_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default options
    if ( ! get_option( 'lupo_theme_version' ) ) {
        update_option( 'lupo_theme_version', LUPO_THEME_VERSION );
        
        // Set default customizer options
        set_theme_mod( 'lupo_parallax_enabled', true );
        set_theme_mod( 'lupo_crossfade_speed', 800 );
        set_theme_mod( 'lupo_navigation_fade', true );
    }
}
add_action( 'after_switch_theme', 'lupo_theme_activation' );

/**
 * Add admin notice for theme setup
 */
function lupo_admin_notices() {
    if ( ! get_option( 'lupo_theme_setup_complete' ) ) {
        ?>
        <div class="notice notice-info is-dismissible">
            <p>
                <strong><?php _e( 'Lupo Art Portfolio Theme', 'lupo-art-portfolio' ); ?></strong> - 
                <?php _e( 'Welcome! Visit the Portfolio Directory Manager to import your artwork.', 'lupo-art-portfolio' ); ?>
                <a href="<?php echo admin_url( 'edit.php?post_type=lupo_portfolio' ); ?>" class="button button-primary" style="margin-left: 10px;">
                    <?php _e( 'Get Started', 'lupo-art-portfolio' ); ?>
                </a>
            </p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'lupo_admin_notices' );

/**
 * Theme debug information (only for admins in development)
 */
function lupo_debug_info() {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG && current_user_can( 'manage_options' ) ) {
        $missing_files = array();
        $check_files = array(
            'header.php',
            'footer.php', 
            'index.php',
            'assets/css/reset.css',
            'assets/js/parallax-scroll.js',
            'assets/js/dynamic-background.js',
            'assets/js/navigation.js',
            'assets/js/custom-carousel.js',
        );
        
        foreach ( $check_files as $file ) {
            if ( ! file_exists( LUPO_THEME_PATH . '/' . $file ) ) {
                $missing_files[] = $file;
            }
        }
        
        if ( ! empty( $missing_files ) ) {
            echo '<div style="position: fixed; bottom: 20px; right: 20px; background: #f44336; color: white; padding: 10px; border-radius: 5px; z-index: 9999; max-width: 300px;">';
            echo '<strong>Theme Development:</strong><br>';
            echo 'Missing files: ' . implode( ', ', $missing_files );
            echo '</div>';
        }
    }
}
add_action( 'wp_footer', 'lupo_debug_info' );
