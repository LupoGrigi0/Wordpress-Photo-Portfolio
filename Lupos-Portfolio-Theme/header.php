<?php
/**
 * Header template for Lupo's Art Portfolio Theme
 *
 * Displays the site header with fade-in/out navigation based on scroll position
 * Sets up the parallax background container and main site structure
 *
 * @package LupoArtPortfolio
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php 
    // WordPress head hook - critical for plugins and theme functionality
    wp_head(); 
    ?>
    
    <!-- Preload key resources for better performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Theme specific meta -->
    <meta name="theme-color" content="#111111">
    <meta name="msapplication-TileColor" content="#111111">
</head>

<body <?php body_class(); ?>>

<?php 
// WordPress 5.2+ wp_body_open hook
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}
?>

<!-- Skip link for accessibility -->
<a class="skip-link screen-reader-text" href="#main"><?php _e( 'Skip to content', 'lupo-art-portfolio' ); ?></a>

<!-- Background Container - Fixed positioned for parallax effect -->
<div class="background-container" id="lupo-background-container">
    <!-- Dynamic background images will be injected here by JavaScript -->
    <?php
    // Get current page's portfolio items for initial background setup
    if ( is_home() || is_front_page() ) {
        $portfolio_query = new WP_Query( array(
            'post_type' => 'lupo_portfolio',
            'posts_per_page' => 1,
            'orderby' => 'menu_order date',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => '_lupo_background_image',
                    'compare' => 'EXISTS',
                ),
            ),
        ) );
        
        if ( $portfolio_query->have_posts() ) {
            while ( $portfolio_query->have_posts() ) {
                $portfolio_query->the_post();
                $bg_image = get_post_meta( get_the_ID(), '_lupo_background_image', true );
                if ( ! empty( $bg_image ) ) {
                    echo '<div class="background-image active" style="background-image: url(' . esc_url( $bg_image ) . ');"></div>';
                    break;
                }
            }
        }
        wp_reset_postdata();
    }
    ?>
</div>

<!-- Site Navigation - Fade in/out based on scroll -->
<nav class="site-navigation" id="lupo-main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'lupo-art-portfolio' ); ?>">
    <div class="nav-container">
        
        <!-- Site Branding -->
        <div class="site-branding">
            <?php if ( has_custom_logo() ) : ?>
                <div class="site-logo">
                    <?php the_custom_logo(); ?>
                </div>
            <?php else : ?>
                <div class="site-title">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <?php bloginfo( 'name' ); ?>
                    </a>
                </div>
                <?php 
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) : ?>
                    <div class="site-description">
                        <?php echo $description; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Primary Navigation Menu -->
        <div class="primary-navigation">
            <?php
            // Check if primary menu exists
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'depth'          => 2,
                    'fallback_cb'    => 'lupo_fallback_menu',
                ) );
            } else {
                // Fallback menu if no menu is set
                lupo_fallback_menu();
            }
            ?>
        </div>

        <!-- Mobile Navigation Toggle -->
        <button class="mobile-nav-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle Navigation', 'lupo-art-portfolio' ); ?>">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="screen-reader-text"><?php _e( 'Menu', 'lupo-art-portfolio' ); ?></span>
        </button>

    </div>
</nav>

<!-- Main Site Container -->
<div class="site-container" id="lupo-site-container">
    
    <!-- Main Content Area -->
    <main id="main" class="site-main" role="main">
        
        <!-- Content Wrapper for Parallax Scrolling -->
        <div class="content-wrapper" id="lupo-content-wrapper">

<?php
/**
 * Fallback menu function
 * Creates a basic menu if no custom menu is set
 */
function lupo_fallback_menu() {
    ?>
    <ul id="primary-menu" class="nav-menu">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'lupo-art-portfolio' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>"><?php _e( 'Portfolio', 'lupo-art-portfolio' ); ?></a></li>
        <?php
        // Add portfolio categories to menu
        $portfolio_terms = get_terms( array(
            'taxonomy' => 'lupo_medium',
            'hide_empty' => true,
            'number' => 5,
        ) );
        
        if ( ! empty( $portfolio_terms ) && ! is_wp_error( $portfolio_terms ) ) {
            foreach ( $portfolio_terms as $term ) {
                echo '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></li>';
            }
        }
        ?>
        <?php if ( get_page_by_path( 'about' ) ) : ?>
            <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php _e( 'About', 'lupo-art-portfolio' ); ?></a></li>
        <?php endif; ?>
        <?php if ( get_page_by_path( 'contact' ) ) : ?>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php _e( 'Contact', 'lupo-art-portfolio' ); ?></a></li>
        <?php endif; ?>
    </ul>
    <?php
}

/**
 * Add navigation-specific JavaScript
 * This will be moved to navigation.js when that file is created
 */
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navigation = document.getElementById('lupo-main-navigation');
    const mobileToggle = document.querySelector('.mobile-nav-toggle');
    const primaryMenu = document.getElementById('primary-menu');
    let navTimeout;
    let lastScrollY = window.scrollY;
    
    // Navigation fade logic
    function handleNavVisibility() {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 50) { // Show nav when scrolled down
            navigation.classList.add('visible');
        } else {
            navigation.classList.remove('visible');
        }
        
        // Clear previous timeout
        clearTimeout(navTimeout);
        
        // Hide nav after 3 seconds of no scrolling (except when at top)
        if (currentScrollY > 50) {
            navTimeout = setTimeout(() => {
                if (window.scrollY > 50) {
                    navigation.classList.remove('visible');
                }
            }, 3000);
        }
        
        lastScrollY = currentScrollY;
    }
    
    // Throttled scroll event
    let ticking = false;
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(function() {
                handleNavVisibility();
                ticking = false;
            });
            ticking = true;
        }
    });
    
    // Show nav on mouse movement near top of screen
    document.addEventListener('mousemove', function(e) {
        if (e.clientY < 100) { // Mouse near top 100px
            navigation.classList.add('visible');
            
            clearTimeout(navTimeout);
            navTimeout = setTimeout(() => {
                if (window.scrollY > 50) {
                    navigation.classList.remove('visible');
                }
            }, 3000);
        }
    });
    
    // Mobile navigation toggle
    if (mobileToggle && primaryMenu) {
        mobileToggle.addEventListener('click', function() {
            const isExpanded = mobileToggle.getAttribute('aria-expanded') === 'true';
            
            mobileToggle.setAttribute('aria-expanded', !isExpanded);
            mobileToggle.classList.toggle('active');
            primaryMenu.classList.toggle('mobile-open');
            
            // Prevent body scroll when mobile menu is open
            if (!isExpanded) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (primaryMenu && primaryMenu.classList.contains('mobile-open')) {
            if (!navigation.contains(e.target)) {
                mobileToggle.setAttribute('aria-expanded', 'false');
                mobileToggle.classList.remove('active');
                primaryMenu.classList.remove('mobile-open');
                document.body.style.overflow = '';
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && primaryMenu.classList.contains('mobile-open')) {
            mobileToggle.setAttribute('aria-expanded', 'false');
            mobileToggle.classList.remove('active');
            primaryMenu.classList.remove('mobile-open');
            document.body.style.overflow = '';
        }
    });
    
    // Initial check
    handleNavVisibility();
});
</script>

<!-- Navigation-specific styles (will be moved to separate CSS file) -->
<style>
.site-navigation {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    padding: 1rem 2rem;
    background: linear-gradient(
        to bottom, 
        rgba(0, 0, 0, 0.7) 0%, 
        rgba(0, 0, 0, 0.3) 70%,
        rgba(0, 0, 0, 0) 100%
    );
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.3s ease-in-out;
    pointer-events: none;
}

.site-navigation.visible {
    opacity: 1;
    transform: translateY(0);
    pointer-events: all;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.site-branding .site-title a {
    color: white;
    text-decoration: none;
    font-size: 1.5rem;
    font-weight: 300;
    letter-spacing: 1px;
}

.site-description {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

.nav-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 2rem;
}

.nav-menu a {
    color: white;
    text-decoration: none;
    font-weight: 400;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.9rem;
    padding: 0.5rem 0;
    position: relative;
    transition: all 0.3s ease;
}

.nav-menu a:hover {
    color: rgba(255, 255, 255, 0.8);
}

.nav-menu a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: white;
    transition: width 0.3s ease;
}

.nav-menu a:hover::after {
    width: 100%;
}

/* Mobile Navigation */
.mobile-nav-toggle {
    display: none;
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    position: relative;
    width: 30px;
    height: 30px;
}

.hamburger-line {
    display: block;
    width: 25px;
    height: 2px;
    background-color: white;
    margin: 5px 0;
    transition: 0.3s;
}

.mobile-nav-toggle.active .hamburger-line:nth-child(1) {
    transform: rotate(-45deg) translate(-5px, 6px);
}

.mobile-nav-toggle.active .hamburger-line:nth-child(2) {
    opacity: 0;
}

.mobile-nav-toggle.active .hamburger-line:nth-child(3) {
    transform: rotate(45deg) translate(-5px, -6px);
}

@media (max-width: 768px) {
    .site-navigation {
        padding: 1rem;
    }
    
    .mobile-nav-toggle {
        display: block;
    }
    
    .nav-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.95);
        flex-direction: column;
        padding: 2rem;
        gap: 1rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        border-radius: 0 0 10px 10px;
    }
    
    .nav-menu.mobile-open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .nav-menu li {
        text-align: center;
    }
    
    .nav-menu a {
        display: block;
        padding: 1rem;
        font-size: 1rem;
    }
}

/* Accessibility */
.skip-link {
    position: absolute;
    left: -9999px;
    z-index: 999999;
    padding: 8px 16px;
    background: #000;
    color: #fff;
    text-decoration: none;
}

.skip-link:focus {
    left: 6px;
    top: 7px;
}

.screen-reader-text {
    border: 0;
    clip: rect(1px, 1px, 1px, 1px);
    clip-path: inset(50%);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute !important;
    width: 1px;
    word-wrap: normal !important;
}
</style>