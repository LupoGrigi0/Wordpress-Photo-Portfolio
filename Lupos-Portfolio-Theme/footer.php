<?php
/**
 * Footer template for Lupo's Art Portfolio Theme
 *
 * Closes the main content wrapper, includes footer content,
 * and loads the JavaScript modules for carousel and background effects
 *
 * @package LupoArtPortfolio
 * @version 1.5.0
 * @author Lupo & Genevieve (Cladue Sonnet 4.0 -pro-)

 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

        </div><!-- .content-wrapper -->
    </main><!-- #main -->

    <!-- Site Footer -->
    <footer class="site-footer" id="lupo-site-footer" role="contentinfo">
        <div class="footer-container">
            
            <!-- Footer Widgets Area -->
            <?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
                <div class="footer-widgets">
                    <?php dynamic_sidebar( 'footer-widgets' ); ?>
                </div>
            <?php endif; ?>
            
            <!-- Footer Navigation -->
            <?php if ( has_nav_menu( 'footer' ) ) : ?>
                <nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Navigation', 'lupo-art-portfolio' ); ?>">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'menu_class'     => 'footer-nav-menu',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ) );
                    ?>
                </nav>
            <?php endif; ?>
            
            <!-- Site Info -->
            <div class="site-info">
                <div class="site-info-content">
                    
                    <!-- Copyright -->
                    <div class="copyright">
                        <p>&copy; <?php echo date( 'Y' ); ?> 
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                        <?php _e( 'All rights reserved.', 'lupo-art-portfolio' ); ?>
                        </p>
                    </div>
                    
                    <!-- Artist Statement / Tagline -->
                    <?php 
                    $artist_statement = get_theme_mod( 'lupo_artist_statement', '' );
                    if ( ! empty( $artist_statement ) ) : ?>
                        <div class="artist-statement">
                            <p><?php echo esc_html( $artist_statement ); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Contact Info -->
                    <div class="contact-info">
                        <?php 
                        $contact_email = get_theme_mod( 'lupo_contact_email', '' );
                        if ( ! empty( $contact_email ) ) : ?>
                            <span class="contact-email">
                                <a href="mailto:<?php echo esc_attr( $contact_email ); ?>">
                                    <?php echo esc_html( $contact_email ); ?>
                                </a>
                            </span>
                        <?php endif; ?>
                        
                        <?php 
                        $contact_location = get_theme_mod( 'lupo_location', '' );
                        if ( ! empty( $contact_location ) ) : ?>
                            <span class="contact-location">
                                <?php echo esc_html( $contact_location ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Social Media Links -->
                    <?php 
                    $social_links = array(
                        'instagram' => get_theme_mod( 'lupo_instagram_url', '' ),
                        'facebook'  => get_theme_mod( 'lupo_facebook_url', '' ),
                        'twitter'   => get_theme_mod( 'lupo_twitter_url', '' ),
                        'linkedin'  => get_theme_mod( 'lupo_linkedin_url', '' ),
                        'website'   => get_theme_mod( 'lupo_website_url', '' ),
                    );
                    
                    $has_social = array_filter( $social_links );
                    if ( ! empty( $has_social ) ) : ?>
                        <div class="social-media">
                            <ul class="social-links">
                                <?php foreach ( $social_links as $platform => $url ) : ?>
                                    <?php if ( ! empty( $url ) ) : ?>
                                        <li class="social-link social-<?php echo esc_attr( $platform ); ?>">
                                            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>">
                                                <?php echo lupo_get_social_icon( $platform ); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Back to Top Button -->
            <button class="back-to-top" id="lupo-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'lupo-art-portfolio' ); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 14L12 9L17 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="screen-reader-text"><?php _e( 'Back to top', 'lupo-art-portfolio' ); ?></span>
            </button>
            
        </div>
    </footer>

</div><!-- .site-container -->

<!-- Debug Info (only shown when WP_DEBUG is enabled) -->
<?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG && current_user_can( 'manage_options' ) ) : ?>
    <div id="lupo-debug-info" style="position: fixed; bottom: 10px; left: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; font-size: 12px; border-radius: 5px; max-width: 300px; z-index: 9999;">
        <strong>Debug Info:</strong><br>
        <?php
        $debug_info = array();
        
        // Check for missing JavaScript files
        $js_files = array(
            'custom-carousel.js',
            'dynamic-background.js', 
            'navigation.js'
        );
        
        foreach ( $js_files as $js_file ) {
            if ( ! file_exists( LUPO_THEME_PATH . '/assets/js/' . $js_file ) ) {
                $debug_info[] = "Missing: assets/js/{$js_file}";
            }
        }
        
        // Check for portfolio posts - FIXED: Using proper post count method
        $portfolio_count = wp_count_posts( 'lupo_portfolio' );
        if ( $portfolio_count && isset( $portfolio_count->publish ) && $portfolio_count->publish == 0 ) {
            $debug_info[] = "No portfolio posts found";
        } elseif ( ! $portfolio_count ) {
            $debug_info[] = "Portfolio post type not registered properly";
        }
        
        // Display debug info
        if ( ! empty( $debug_info ) ) {
            foreach ( $debug_info as $info ) {
                echo esc_html( $info ) . '<br>';
            }
        } else {
            echo "All systems ready! 🎨";
        }
        ?>
    </div>
<?php endif; ?>

<!-- Portfolio Data for JavaScript -->
<script type="application/json" id="lupo-portfolio-data">
<?php
// Prepare portfolio data for JavaScript consumption
$portfolio_data = array();

if ( is_home() || is_front_page() ) {
    // Get all portfolio posts for the main page - FIXED: Using lupo_portfolio consistently
    $portfolio_query = new WP_Query( array(
        'post_type' => 'lupo_portfolio',
        'posts_per_page' => -1,
        'orderby' => 'menu_order date',
        'order' => 'ASC',
        'post_status' => 'publish',
    ) );
    
    if ( $portfolio_query->have_posts() ) {
        while ( $portfolio_query->have_posts() ) {
            $portfolio_query->the_post();
            
            $post_id = get_the_ID();
            $background_image = get_post_meta( $post_id, '_lupo_background_image', true );
            $carousel_data = get_post_meta( $post_id, '_lupo_carousel_data', true );
            
            // Get first image from carousel data as fallback background
            $first_carousel_image = '';
            if ( ! empty( $carousel_data ) ) {
                $carousel_images = json_decode( $carousel_data, true );
                if ( is_array( $carousel_images ) && ! empty( $carousel_images ) ) {
                    $first_carousel_image = $carousel_images[0]['url'] ?? '';
                }
            }
            
            $portfolio_data[] = array(
                'id' => $post_id,
                'title' => get_the_title(),
                'background_image' => ! empty( $background_image ) ? $background_image : $first_carousel_image,
                'carousel_data' => $carousel_data,
            );
        }
    }
    wp_reset_postdata();
}

echo wp_json_encode( $portfolio_data );
?>
</script>

<!-- Theme Settings for JavaScript -->
<script type="application/json" id="lupo-theme-settings">
<?php
echo wp_json_encode( array(
    'transition_speed' => get_theme_mod( 'lupo_transition_speed', 800 ),
    'scroll_threshold' => get_theme_mod( 'lupo_scroll_threshold', 100 ),
    'nav_fade_delay' => get_theme_mod( 'lupo_nav_fade_delay', 3000 ),
    'parallax_intensity' => get_theme_mod( 'lupo_parallax_intensity', 0.2 ),
    'mobile_breakpoint' => get_theme_mod( 'lupo_mobile_breakpoint', 768 ),
    'carousel_autoplay' => get_theme_mod( 'lupo_carousel_autoplay', true ),
    'carousel_speed' => get_theme_mod( 'lupo_carousel_speed', 5000 ),
    'debug_mode' => defined( 'WP_DEBUG' ) && WP_DEBUG,
) );
?>
</script>

<!-- Back to Top Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('lupo-back-to-top');
    
    if (backToTopButton) {
        // Show/hide back to top button
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });
        
        // Handle click
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
</script>

<?php
// WordPress footer hook - critical for plugins and theme functionality
wp_footer();
?>

</body>
</html>

<?php
/**
 * Helper function to get social media icons
 */
function lupo_get_social_icon( $platform ) {
    $icons = array(
        'instagram' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
        'facebook' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'twitter' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
        'linkedin' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
        'website' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>',
    );
    
    return $icons[ $platform ] ?? '';
}
?>

<!-- Footer Styles -->
<style>
.site-footer {
    background: linear-gradient(135deg, rgba(17, 17, 17, 0.95) 0%, rgba(34, 34, 34, 0.95) 100%);
    color: rgba(255, 255, 255, 0.8);
    margin-top: 4rem;
    padding: 3rem 2rem 2rem;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
}

.footer-widgets {
    margin-bottom: 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.footer-navigation {
    margin-bottom: 2rem;
}

.footer-nav-menu {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    list-style: none;
    margin: 0;
    padding: 0;
    justify-content: center;
}

.footer-nav-menu a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: color 0.3s ease;
    font-size: 0.9rem;
}

.footer-nav-menu a:hover {
    color: white;
}

.site-info {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.copyright {
    margin-bottom: 1rem;
}

.copyright a {
    color: white;
    text-decoration: none;
}

.artist-statement {
    margin-bottom: 1.5rem;
    font-style: italic;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.contact-info {
    margin-bottom: 1.5rem;
    display: flex;
    gap: 2rem;
    justify-content: center;
    flex-wrap: wrap;
}

.contact-email a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.contact-email a:hover {
    color: white;
}

.social-links {
    display: flex;
    gap: 1rem;
    justify-content: center;
    list-style: none;
    margin: 0;
    padding: 0;
}

.social-link a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    color: rgba(255, 255, 255, 0.7);
    transition: all 0.3s ease;
}

.social-link a:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    transform: translateY(-2px);
}

.back-to-top {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 100;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.back-to-top.visible {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.back-to-top:hover {
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
}

@media (max-width: 768px) {
    .site-footer {
        padding: 2rem 1rem 1rem;
    }
    
    .footer-nav-menu {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    
    .contact-info {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .back-to-top {
        bottom: 1rem;
        right: 1rem;
        width: 45px;
        height: 45px;
    }
}
</style>