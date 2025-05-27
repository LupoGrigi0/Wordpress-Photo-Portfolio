<?php
/**
 * Main template file for Lupo's Art Portfolio Theme
 *
 * Displays the main portfolio page with content blocks floating over
 * dynamic parallax backgrounds. Each content block contains image carousels
 * generated from directory structures.
 *
 * @package LupoArtPortfolio
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); ?>

<!-- Hero Section / Fullscreen Text -->
<?php 
$hero_title = get_theme_mod( 'lupo_hero_title', get_bloginfo( 'name' ) );
$hero_subtitle = get_theme_mod( 'lupo_hero_subtitle', get_bloginfo( 'description' ) );
$hero_description = get_theme_mod( 'lupo_hero_description', '' );

if ( ! empty( $hero_title ) || ! empty( $hero_subtitle ) || ! empty( $hero_description ) ) : ?>
    <section class="fullscreen-text hero-section" id="lupo-hero-section">
        <div class="hero-content">
            <?php if ( ! empty( $hero_title ) ) : ?>
                <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
            <?php endif; ?>
            
            <?php if ( ! empty( $hero_subtitle ) ) : ?>
                <p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
            <?php endif; ?>
            
            <?php if ( ! empty( $hero_description ) ) : ?>
                <div class="hero-description">
                    <?php echo wp_kses_post( wpautop( $hero_description ) ); ?>
                </div>
            <?php endif; ?>
            
            <!-- Scroll indicator -->
            <div class="scroll-indicator">
                <div class="scroll-arrow">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="scroll-text"><?php _e( 'Scroll to explore', 'lupo-art-portfolio' ); ?></span>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Portfolio Content Blocks -->
<div class="portfolio-content" id="lupo-portfolio-content">
    
    <?php
    // Query portfolio items
    $portfolio_query = new WP_Query( array(
        'post_type' => 'lupo_portfolio',
        'posts_per_page' => -1,
        'orderby' => 'menu_order date',
        'order' => 'ASC',
        'post_status' => 'publish',
    ) );

    if ( $portfolio_query->have_posts() ) :
        $block_index = 0;
        
        while ( $portfolio_query->have_posts() ) :
            $portfolio_query->the_post();
            
            $post_id = get_the_ID();
            $background_image = get_post_meta( $post_id, '_lupo_background_image', true );
            $carousel_data = get_post_meta( $post_id, '_lupo_carousel_data', true );
            $creation_date = get_post_meta( $post_id, '_lupo_creation_date', true );
            $project_description = get_post_meta( $post_id, '_lupo_project_description', true );
            
            // Get first image from carousel for background fallback
            $first_carousel_image = '';
            $carousel_images = array();
            if ( ! empty( $carousel_data ) ) {
                $carousel_images = json_decode( $carousel_data, true );
                if ( is_array( $carousel_images ) && ! empty( $carousel_images ) ) {
                    $first_carousel_image = $carousel_images[0]['url'] ?? '';
                }
            }
            
            // Determine background image (carousel first, then manual, then fallback)
            $block_background = '';
            if ( ! empty( $first_carousel_image ) ) {
                $block_background = $first_carousel_image;
            } elseif ( ! empty( $background_image ) ) {
                $block_background = $background_image;
            }
            ?>
            
            <!-- Content Block -->
            <article class="content-block portfolio-block" 
                     id="content-block-<?php echo esc_attr( $block_index ); ?>"
                     data-post-id="<?php echo esc_attr( $post_id ); ?>"
                     data-background-image="<?php echo esc_attr( $block_background ); ?>"
                     data-block-index="<?php echo esc_attr( $block_index ); ?>">
                
                <div class="block-content">
                    
                    <!-- Block Header -->
                    <header class="block-header">
                        <h2 class="block-title"><?php the_title(); ?></h2>
                        
                        <?php if ( ! empty( $creation_date ) ) : ?>
                            <div class="block-meta">
                                <time class="creation-date" datetime="<?php echo esc_attr( $creation_date ); ?>">
                                    <?php echo esc_html( date( 'F Y', strtotime( $creation_date ) ) ); ?>
                                </time>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( has_excerpt() ) : ?>
                            <div class="block-subtitle">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </header>
                    
                    <!-- Project Description -->
                    <?php if ( ! empty( $project_description ) ) : ?>
                        <div class="block-description">
                            <?php echo wp_kses_post( wpautop( $project_description ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Image Carousel(s) -->
                    <?php if ( ! empty( $carousel_images ) && is_array( $carousel_images ) ) : ?>
                        <div class="block-carousels">
                            <?php
                            // Split images into carousels (max 20 images per carousel)
                            $images_per_carousel = 20;
                            $carousel_chunks = array_chunk( $carousel_images, $images_per_carousel );
                            $carousel_count = 0;
                            
                            foreach ( $carousel_chunks as $carousel_images_chunk ) :
                                $carousel_id = "carousel-{$post_id}-{$carousel_count}";
                                ?>
                                
                                <div class="advanced-carousel" 
                                     id="<?php echo esc_attr( $carousel_id ); ?>"
                                     data-carousel-id="<?php echo esc_attr( $carousel_id ); ?>"
                                     data-autoplay="<?php echo esc_attr( get_theme_mod( 'lupo_carousel_autoplay', 'true' ) ); ?>"
                                     data-speed="<?php echo esc_attr( get_theme_mod( 'lupo_carousel_speed', '5000' ) ); ?>">
                                    
                                    <!-- Carousel Inner Container -->
                                    <div class="carousel-inner">
                                        <?php foreach ( $carousel_images_chunk as $image_index => $image ) : ?>
                                            <div class="carousel-slide <?php echo $image_index === 0 ? 'active' : ''; ?>" 
                                                 data-slide-index="<?php echo esc_attr( $image_index ); ?>">
                                                <img src="<?php echo esc_url( $image['url'] ); ?>" 
                                                     alt="<?php echo esc_attr( $image['caption'] ?? '' ); ?>"
                                                     loading="<?php echo $image_index < 3 ? 'eager' : 'lazy'; ?>"
                                                     width="<?php echo esc_attr( $image['width'] ?? '' ); ?>"
                                                     height="<?php echo esc_attr( $image['height'] ?? '' ); ?>">
                                                
                                                <?php if ( ! empty( $image['caption'] ) ) : ?>
                                                    <div class="carousel-caption">
                                                        <p><?php echo esc_html( $image['caption'] ); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Carousel Controls -->
                                    <?php if ( count( $carousel_images_chunk ) > 1 ) : ?>
                                        
                                        <!-- Previous/Next Buttons -->
                                        <button class="carousel-control prev" aria-label="<?php esc_attr_e( 'Previous image', 'lupo-art-portfolio' ); ?>">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        
                                        <button class="carousel-control next" aria-label="<?php esc_attr_e( 'Next image', 'lupo-art-portfolio' ); ?>">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Indicators -->
                                        <div class="carousel-indicators">
                                            <?php for ( $i = 0; $i < count( $carousel_images_chunk ); $i++ ) : ?>
                                                <button class="carousel-indicator <?php echo $i === 0 ? 'active' : ''; ?>" 
                                                        data-slide-to="<?php echo esc_attr( $i ); ?>"
                                                        aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'lupo-art-portfolio' ), $i + 1 ) ); ?>"></button>
                                            <?php endfor; ?>
                                        </div>
                                        
                                        <!-- Progress Bar -->
                                        <div class="carousel-progress">
                                            <div class="carousel-progress-inner"></div>
                                        </div>
                                        
                                    <?php endif; ?>
                                    
                                    <!-- Fullscreen Button -->
                                    <button class="carousel-fullscreen" aria-label="<?php esc_attr_e( 'View fullscreen', 'lupo-art-portfolio' ); ?>">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    
                                </div>
                                
                                <?php 
                                $carousel_count++; 
                                
                                // Add spacing between carousels
                                if ( $carousel_count < count( $carousel_chunks ) ) : ?>
                                    <div class="carousel-spacer"></div>
                                <?php endif; ?>
                                
                            <?php endforeach; ?>
                        </div>
                        
                    <?php else : ?>
                        
                        <!-- No carousel data - show placeholder or featured image -->
                        <div class="block-placeholder">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="featured-image">
                                    <?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
                                </div>
                            <?php else : ?>
                                <div class="no-content-message">
                                    <p><?php _e( 'No images available for this portfolio item.', 'lupo-art-portfolio' ); ?></p>
                                    <?php if ( current_user_can( 'edit_posts' ) ) : ?>
                                        <p><a href="<?php echo esc_url( get_edit_post_link() ); ?>"><?php _e( 'Add images', 'lupo-art-portfolio' ); ?></a></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    <?php endif; ?>
                    
                    <!-- Taxonomies -->
                    <?php 
                    $medium_terms = get_the_terms( $post_id, 'lupo_medium' );
                    $subject_terms = get_the_terms( $post_id, 'lupo_subject' );
                    $year_terms = get_the_terms( $post_id, 'lupo_year' );
                    
                    if ( $medium_terms || $subject_terms || $year_terms ) : ?>
                        <footer class="block-footer">
                            <div class="block-taxonomies">
                                
                                <?php if ( $medium_terms && ! is_wp_error( $medium_terms ) ) : ?>
                                    <div class="taxonomy-group medium-terms">
                                        <span class="taxonomy-label"><?php _e( 'Medium:', 'lupo-art-portfolio' ); ?></span>
                                        <?php foreach ( $medium_terms as $term ) : ?>
                                            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="taxonomy-term">
                                                <?php echo esc_html( $term->name ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $subject_terms && ! is_wp_error( $subject_terms ) ) : ?>
                                    <div class="taxonomy-group subject-terms">
                                        <span class="taxonomy-label"><?php _e( 'Subject:', 'lupo-art-portfolio' ); ?></span>
                                        <?php foreach ( $subject_terms as $term ) : ?>
                                            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="taxonomy-term">
                                                <?php echo esc_html( $term->name ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $year_terms && ! is_wp_error( $year_terms ) ) : ?>
                                    <div class="taxonomy-group year-terms">
                                        <span class="taxonomy-label"><?php _e( 'Year:', 'lupo-art-portfolio' ); ?></span>
                                        <?php foreach ( $year_terms as $term ) : ?>
                                            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="taxonomy-term">
                                                <?php echo esc_html( $term->name ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </footer>
                    <?php endif; ?>
                    
                </div><!-- .block-content -->
            </article><!-- .content-block -->
            
            <?php 
            $block_index++;
            
        endwhile;
        
        wp_reset_postdata();
        
    else : ?>
        
        <!-- No portfolio items found -->
        <div class="no-portfolio-message">
            <div class="content-block">
                <div class="block-content">
                    <h2><?php _e( 'No Portfolio Items Found', 'lupo-art-portfolio' ); ?></h2>
                    <p><?php _e( 'It looks like there are no portfolio items to display yet.', 'lupo-art-portfolio' ); ?></p>
                    
                    <?php if ( current_user_can( 'edit_posts' ) ) : ?>
                        <p>
                            <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=lupo_portfolio' ) ); ?>" class="button">
                                <?php _e( 'Add Your First Portfolio Item', 'lupo-art-portfolio' ); ?>
                            </a>
                        </p>
                        <p>
                            <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=lupo_portfolio' ) ); ?>">
                                <?php _e( 'Manage Portfolio Items', 'lupo-art-portfolio' ); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    <?php endif; ?>
    
</div><!-- .portfolio-content -->

<!-- Load More Button (for future pagination) -->
<?php if ( $portfolio_query->max_num_pages > 1 ) : ?>
    <div class="load-more-container">
        <button class="load-more-button" id="lupo-load-more" 
                data-page="2" 
                data-max-pages="<?php echo esc_attr( $portfolio_query->max_num_pages ); ?>">
            <?php _e( 'Load More Portfolio Items', 'lupo-art-portfolio' ); ?>
        </button>
    </div>
<?php endif; ?>

<!-- Carousel initialization (temporary - will move to custom-carousel.js) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize basic carousel functionality
    const carousels = document.querySelectorAll('.advanced-carousel');
    
    carousels.forEach(function(carousel) {
        const slides = carousel.querySelectorAll('.carousel-slide');
        const indicators = carousel.querySelectorAll('.carousel-indicator');
        const prevButton = carousel.querySelector('.carousel-control.prev');
        const nextButton = carousel.querySelector('.carousel-control.next');
        const fullscreenButton = carousel.querySelector('.carousel-fullscreen');
        
        let currentSlide = 0;
        let autoplayInterval;
        
        // Show specific slide
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
            });
            
            currentSlide = index;
        }
        
        // Next slide
        function nextSlide() {
            const nextIndex = (currentSlide + 1) % slides.length;
            showSlide(nextIndex);
        }
        
        // Previous slide
        function prevSlide() {
            const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prevIndex);
        }
        
        // Event listeners
        if (nextButton) {
            nextButton.addEventListener('click', nextSlide);
        }
        
        if (prevButton) {
            prevButton.addEventListener('click', prevSlide);
        }
        
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => showSlide(index));
        });
        
        // Fullscreen functionality
        if (fullscreenButton) {
            fullscreenButton.addEventListener('click', function() {
                carousel.classList.toggle('fullscreen');
                
                if (carousel.classList.contains('fullscreen')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
        
        // Close fullscreen with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && carousel.classList.contains('fullscreen')) {
                carousel.classList.remove('fullscreen');
                document.body.style.overflow = '';
            }
        });
        
        // Auto-play
        const autoplay = carousel.getAttribute('data-autoplay') === 'true';
        const speed = parseInt(carousel.getAttribute('data-speed')) || 5000;
        
        if (autoplay && slides.length > 1) {
            autoplayInterval = setInterval(nextSlide, speed);
            
            // Pause on hover
            carousel.addEventListener('mouseenter', () => {
                clearInterval(autoplayInterval);
            });
            
            carousel.addEventListener('mouseleave', () => {
                autoplayInterval = setInterval(nextSlide, speed);
            });
        }
    });
});
</script>

<?php get_footer(); ?>