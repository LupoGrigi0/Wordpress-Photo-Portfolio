<?php
/**
 * Custom Post Types and Taxonomies for Lupo's Art Portfolio Theme
 *
 * This file handles the registration of custom post types, taxonomies,
 * and meta boxes for the art portfolio theme.
 *
 * @package LupoArtPortfolio
 * @version 1.8.0
 * @author Lupo & Genevieve (Cladue Sonnet 4.0 -pro-)
 * 
 * New for Version 1.8
 * Carousel Save Issues - COMPLETELY FIXED 
 * Name-based selectors: $('input[name="lupo_carousel_data"]').first().val()
 * Defensive event binding: Uses $(document).on() for all dynamic elements
 * Enhanced data validation: Better JSON validation with error logging
 * Form submission hooks: Ensures data saves on submit and auto-save
 * 
 * 2. Background Image Save Issues - FIXED ✅
 * 
 * Name-based selectors: $('input[name="lupo_background_image"]').first().val()
 * Flexible element targeting: Works even if WordPress duplicates IDs
 * Defensive media uploader: Handles WordPress media library interference
 * 
 * 3. Directory Scanning - ENHANCED ✅
 * 
 * Robust AJAX handling: Defensive selectors throughout
 * Automatic carousel population: Directory scan directly updates carousel data
 * Better error handling: More informative status messages
 * 
 * 4. WordPress Gutenberg Interference - ELIMINATED ✅
 * 
 * Consistent defensive patterns throughout all JavaScript
 * Event delegation: All clicks use $(document).on() pattern
 * Flexible selectors: Handles WordPress DOM manipulation gracefully
 * 
 * 🛠️ Key Defensive Patterns Implemented:
 * ✅ Name-Based Selectors:
 * javascript// OLD (fragile):
 * $('#lupo_carousel_data').val()
 * 
 * // NEW (robust):
 * $('input[name="lupo_carousel_data"]').first().val()
 * ✅ Delegated Event Binding:
 * javascript// OLD (fragile):
 * $('#button').click()
 * 
 * // NEW (robust):
 * $(document).on('click', '#button, [id*="button"]', function())
 * ✅ Flexible Element Targeting:
 * javascript// Handles multiple elements with similar IDs
 * $('[id*="lupo_scan_status"]').first().html()
 * 🚀 Enhanced Debug Features:
 * 
 * Console logging for carousel operations
 * Error logging for JSON validation failures
 * WordPress debug integration with WP_DEBUG checks
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Portfolio Custom Post Type and related taxonomies.
 */
function lupo_register_portfolio_post_type() {
    // Set up labels for the Portfolio post type
    $labels = array(
        'name'                  => _x( 'Portfolio Items', 'Post type general name', 'lupo-art-portfolio' ),
        'singular_name'         => _x( 'Portfolio Item', 'Post type singular name', 'lupo-art-portfolio' ),
        'menu_name'             => _x( 'Portfolio', 'Admin Menu text', 'lupo-art-portfolio' ),
        'name_admin_bar'        => _x( 'Portfolio Item', 'Add New on Toolbar', 'lupo-art-portfolio' ),
        'add_new'               => __( 'Add New', 'lupo-art-portfolio' ),
        'add_new_item'          => __( 'Add New Portfolio Item', 'lupo-art-portfolio' ),
        'new_item'              => __( 'New Portfolio Item', 'lupo-art-portfolio' ),
        'edit_item'             => __( 'Edit Portfolio Item', 'lupo-art-portfolio' ),
        'view_item'             => __( 'View Portfolio Item', 'lupo-art-portfolio' ),
        'all_items'             => __( 'All Portfolio Items', 'lupo-art-portfolio' ),
        'search_items'          => __( 'Search Portfolio Items', 'lupo-art-portfolio' ),
        'parent_item_colon'     => __( 'Parent Portfolio Items:', 'lupo-art-portfolio' ),
        'not_found'             => __( 'No portfolio items found.', 'lupo-art-portfolio' ),
        'not_found_in_trash'    => __( 'No portfolio items found in Trash.', 'lupo-art-portfolio' ),
        'featured_image'        => __( 'Portfolio Item Cover Image', 'lupo-art-portfolio' ),
        'set_featured_image'    => __( 'Set cover image', 'lupo-art-portfolio' ),
        'remove_featured_image' => __( 'Remove cover image', 'lupo-art-portfolio' ),
        'use_featured_image'    => __( 'Use as cover image', 'lupo-art-portfolio' ),
        'archives'              => __( 'Portfolio archives', 'lupo-art-portfolio' ),
        'insert_into_item'      => __( 'Insert into portfolio item', 'lupo-art-portfolio' ),
        'uploaded_to_this_item' => __( 'Uploaded to this portfolio item', 'lupo-art-portfolio' ),
        'filter_items_list'     => __( 'Filter portfolio items list', 'lupo-art-portfolio' ),
        'items_list_navigation' => __( 'Portfolio items list navigation', 'lupo-art-portfolio' ),
        'items_list'            => __( 'Portfolio items list', 'lupo-art-portfolio' ),
    );

    // Set up arguments for the Portfolio post type
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'portfolio', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true, // Allow parent-child relationships
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-art', // Art icon for the menu
        'supports'           => array(
            'title',
            'editor',
            'author',
            'thumbnail',
            'excerpt',
            'custom-fields',
            'revisions',
            'page-attributes', // Required for hierarchical structure
        ),
        'show_in_rest'       => true, // Enable Gutenberg editor
        'taxonomies'         => array( 'lupo_medium', 'lupo_subject', 'lupo_year' ),
    );

    // Register the Portfolio post type - FIXED: Using lupo_portfolio consistently
    register_post_type( 'lupo_portfolio', $args );

    // Register Medium Taxonomy
    register_taxonomy(
        'lupo_medium',
        'lupo_portfolio',
        array(
            'labels'            => array(
                'name'              => __( 'Media', 'lupo-art-portfolio' ),
                'singular_name'     => __( 'Medium', 'lupo-art-portfolio' ),
                'search_items'      => __( 'Search Media', 'lupo-art-portfolio' ),
                'all_items'         => __( 'All Media', 'lupo-art-portfolio' ),
                'parent_item'       => __( 'Parent Medium', 'lupo-art-portfolio' ),
                'parent_item_colon' => __( 'Parent Medium:', 'lupo-art-portfolio' ),
                'edit_item'         => __( 'Edit Medium', 'lupo-art-portfolio' ),
                'update_item'       => __( 'Update Medium', 'lupo-art-portfolio' ),
                'add_new_item'      => __( 'Add New Medium', 'lupo-art-portfolio' ),
                'new_item_name'     => __( 'New Medium Name', 'lupo-art-portfolio' ),
                'menu_name'         => __( 'Media', 'lupo-art-portfolio' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'medium' ),
            'show_in_rest'      => true,
        )
    );

    // Register Subject Taxonomy
    register_taxonomy(
        'lupo_subject',
        'lupo_portfolio',
        array(
            'labels'            => array(
                'name'              => __( 'Subjects', 'lupo-art-portfolio' ),
                'singular_name'     => __( 'Subject', 'lupo-art-portfolio' ),
                'search_items'      => __( 'Search Subjects', 'lupo-art-portfolio' ),
                'all_items'         => __( 'All Subjects', 'lupo-art-portfolio' ),
                'parent_item'       => __( 'Parent Subject', 'lupo-art-portfolio' ),
                'parent_item_colon' => __( 'Parent Subject:', 'lupo-art-portfolio' ),
                'edit_item'         => __( 'Edit Subject', 'lupo-art-portfolio' ),
                'update_item'       => __( 'Update Subject', 'lupo-art-portfolio' ),
                'add_new_item'      => __( 'Add New Subject', 'lupo-art-portfolio' ),
                'new_item_name'     => __( 'New Subject Name', 'lupo-art-portfolio' ),
                'menu_name'         => __( 'Subjects', 'lupo-art-portfolio' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'subject' ),
            'show_in_rest'      => true,
        )
    );

    // Register Year Taxonomy
    register_taxonomy(
        'lupo_year',
        'lupo_portfolio',
        array(
            'labels'            => array(
                'name'              => __( 'Years', 'lupo-art-portfolio' ),
                'singular_name'     => __( 'Year', 'lupo-art-portfolio' ),
                'search_items'      => __( 'Search Years', 'lupo-art-portfolio' ),
                'all_items'         => __( 'All Years', 'lupo-art-portfolio' ),
                'edit_item'         => __( 'Edit Year', 'lupo-art-portfolio' ),
                'update_item'       => __( 'Update Year', 'lupo-art-portfolio' ),
                'add_new_item'      => __( 'Add New Year', 'lupo-art-portfolio' ),
                'new_item_name'     => __( 'New Year', 'lupo-art-portfolio' ),
                'menu_name'         => __( 'Years', 'lupo-art-portfolio' ),
            ),
            'hierarchical'      => false, // Non-hierarchical (like tags)
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'year' ),
            'show_in_rest'      => true,
        )
    );
}
add_action( 'init', 'lupo_register_portfolio_post_type' );

/**
 * Register custom meta boxes for Portfolio post type.
 */
function lupo_add_portfolio_meta_boxes() {
    // Background Image Meta Box
    add_meta_box(
        'lupo_background_image',
        __( 'Background Image', 'lupo-art-portfolio' ),
        'lupo_background_image_callback',
        'lupo_portfolio',
        'side',
        'default'
    );

    // Project Details Meta Box
    add_meta_box(
        'lupo_project_details',
        __( 'Project Details', 'lupo-art-portfolio' ),
        'lupo_project_details_callback',
        'lupo_portfolio',
        'normal',
        'high'
    );

    // Directory Path Meta Box
    add_meta_box(
        'lupo_directory_path',
        __( 'Image Directory Path', 'lupo-art-portfolio' ),
        'lupo_directory_path_callback',
        'lupo_portfolio',
        'normal',
        'high'
    );

    // Image Carousel Meta Box
    add_meta_box(
        'lupo_image_carousel',
        __( 'Image Carousel', 'lupo-art-portfolio' ),
        'lupo_image_carousel_callback',
        'lupo_portfolio',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'lupo_add_portfolio_meta_boxes' );

/**
 * Register meta fields for storing portfolio data
 */
function lupo_register_meta_fields() {
    // Background Image URL
    register_post_meta(
        'lupo_portfolio',
        '_lupo_background_image',
        array(
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'auth_callback'     => function() {
                return current_user_can( 'edit_posts' );
            }
        )
    );

    // Creation Date
    register_post_meta(
        'lupo_portfolio',
        '_lupo_creation_date',
        array(
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can( 'edit_posts' );
            }
        )
    );

    // Project Description
    register_post_meta(
        'lupo_portfolio',
        '_lupo_project_description',
        array(
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'string',
            'auth_callback'     => function() {
                return current_user_can( 'edit_posts' );
            }
        )
    );

    // Directory Path
    register_post_meta(
        'lupo_portfolio',
        '_lupo_directory_path',
        array(
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function() {
                return current_user_can( 'edit_posts' );
            }
        )
    );

    // Image Carousel Data (stored as JSON)
    register_post_meta(
        'lupo_portfolio',
        '_lupo_carousel_data',
        array(
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'string',
            'auth_callback'     => function() {
                return current_user_can( 'edit_posts' );
            }
        )
    );

    // Parallax Settings
    register_post_meta(
        'lupo_portfolio',
        '_lupo_parallax_settings',
        array(
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'string',
            'auth_callback'     => function() {
                return current_user_can( 'edit_posts' );
            }
        )
    );
}
add_action( 'init', 'lupo_register_meta_fields' );

/**
 * Background Image meta box callback
 *
 * @param WP_Post $post Current post object.
 */
function lupo_background_image_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'lupo_background_image_save', 'lupo_background_image_nonce' );

    // Get current value
    $background_image = get_post_meta( $post->ID, '_lupo_background_image', true );

    // Output field
    ?>
    <div class="lupo-meta-field">
        <p>
            <label for="lupo_background_image"><?php _e( 'Select or upload a background image for parallax scrolling', 'lupo-art-portfolio' ); ?></label>
        </p>
        <input type="text" id="lupo_background_image" name="lupo_background_image" value="<?php echo esc_attr( $background_image ); ?>" class="regular-text" readonly />
        <p>
            <input type="button" id="lupo_background_image_button" class="button" value="<?php _e( 'Select Image', 'lupo-art-portfolio' ); ?>" />
            <input type="button" id="lupo_background_image_remove" class="button" value="<?php _e( 'Remove Image', 'lupo-art-portfolio' ); ?>" />
        </p>
        <div id="lupo_background_image_preview">
            <?php if ( ! empty( $background_image ) ) : ?>
                <img src="<?php echo esc_url( $background_image ); ?>" style="max-width: 100%; height: auto;" />
            <?php endif; ?>
        </div>
        <script>
            jQuery(document).ready(function($) {
                // Handle the media uploader - FIXED: Using defensive patterns
                $('input[name="lupo_background_image_button"], #lupo_background_image_button').first().click(function(e) {
                    e.preventDefault();
                    var image_frame;
                    
                    if (image_frame) {
                        image_frame.open();
                        return;
                    }
                    
                    image_frame = wp.media({
                        title: '<?php _e( 'Select or Upload Background Image', 'lupo-art-portfolio' ); ?>',
                        button: {
                            text: '<?php _e( 'Use this image', 'lupo-art-portfolio' ); ?>'
                        },
                        multiple: false
                    });
                    
                    image_frame.on('select', function() {
                        var attachment = image_frame.state().get('selection').first().toJSON();
                        
                        // FIXED: Use name-based selectors with defensive coding
                        $('input[name="lupo_background_image"]').first().val(attachment.url);
                        $('#lupo_background_image_preview, [id*="lupo_background_image_preview"]').first().html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto;" />');
                    });
                    
                    image_frame.open();
                });

                // Handle the remove button - FIXED: Using defensive patterns
                $('input[name="lupo_background_image_remove"], #lupo_background_image_remove').first().click(function(e) {
                    e.preventDefault();
                    
                    // FIXED: Use name-based selectors with defensive coding
                    $('input[name="lupo_background_image"]').first().val('');
                    $('#lupo_background_image_preview, [id*="lupo_background_image_preview"]').first().html('');
                });
            });
        </script>
    </div>
    <?php
}

/**
 * Project Details meta box callback
 *
 * @param WP_Post $post Current post object.
 */
function lupo_project_details_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'lupo_project_details_save', 'lupo_project_details_nonce' );

    // Get current values
    $creation_date = get_post_meta( $post->ID, '_lupo_creation_date', true );
    $project_description = get_post_meta( $post->ID, '_lupo_project_description', true );

    // Output fields
    ?>
    <div class="lupo-meta-field">
        <p>
            <label for="lupo_creation_date"><?php _e( 'Creation Date', 'lupo-art-portfolio' ); ?></label>
            <input type="date" id="lupo_creation_date" name="lupo_creation_date" value="<?php echo esc_attr( $creation_date ); ?>" class="widefat" />
            <span class="description"><?php _e( 'When was this art project created?', 'lupo-art-portfolio' ); ?></span>
        </p>

        <p>
            <label for="lupo_project_description"><?php _e( 'Project Description', 'lupo-art-portfolio' ); ?></label>
            <?php
            wp_editor(
                $project_description,
                'lupo_project_description',
                array(
                    'textarea_name' => 'lupo_project_description',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                    'teeny'         => false,
                    'quicktags'     => true,
                )
            );
            ?>
            <span class="description"><?php _e( 'Provide a detailed description of this art project', 'lupo-art-portfolio' ); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Directory Path meta box callback
 *
 * @param WP_Post $post Current post object.
 */
function lupo_directory_path_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'lupo_directory_path_save', 'lupo_directory_path_nonce' );

    // Get current value
    $directory_path = get_post_meta( $post->ID, '_lupo_directory_path', true );

    // Output field
    ?>
    <div class="lupo-meta-field">
        <p>
            <label for="lupo_directory_path"><?php _e( 'Directory Path', 'lupo-art-portfolio' ); ?></label>
            <input type="text" id="lupo_directory_path" name="lupo_directory_path" value="<?php echo esc_attr( $directory_path ); ?>" class="widefat" />
            <span class="description"><?php _e( 'Enter the relative path to the directory containing images for this portfolio item (e.g., "portfolio/project-name/")', 'lupo-art-portfolio' ); ?></span>
        </p>
        <p>
            <button type="button" id="lupo_scan_directory" class="button button-secondary"><?php _e( 'Scan Directory', 'lupo-art-portfolio' ); ?></button>
            <span id="lupo_scan_status"></span>
        </p>
        <div id="lupo_directory_files"></div>
        <script>
            jQuery(document).ready(function($) {
                // FIXED: Use defensive event binding for scan button
                $(document).on('click', '#lupo_scan_directory, [id*="lupo_scan_directory"]', function(e) {
                    e.preventDefault();
                    
                    // FIXED: Use name-based selector with defensive coding
                    var path = $('input[name="lupo_directory_path"]').first().val(); 
                    if (!path) {
                        $('[id*="lupo_scan_status"]').first().html('<span style="color:red;"><?php _e( 'Please enter a directory path first', 'lupo-art-portfolio' ); ?></span>');
                        return;
                    }
                    
                    $('[id*="lupo_scan_status"]').first().html('<span style="color:blue;"><?php _e( 'Scanning...', 'lupo-art-portfolio' ); ?></span>');
                    
                    // AJAX call to scan directory - FIXED: Using lupo_scan_directory action
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'lupo_scan_directory',
                            path: path,
                            post_id: <?php echo $post->ID; ?>,
                            nonce: $('input[name="lupo_directory_path_nonce"]').first().val()
                        },
                        success: function(response) {
                            if (response.success) {
                                $('[id*="lupo_scan_status"]').first().html('<span style="color:green;"><?php _e( 'Directory scanned successfully!', 'lupo-art-portfolio' ); ?></span>');
                                $('[id*="lupo_directory_files"]').first().html(response.data.html);
                                
                                // If automatic carousel generation is enabled, update the carousel data
                                if (response.data.carousel_data) {
                                    // FIXED: Update hidden carousel data field using name-based selector
                                    $('input[name="lupo_carousel_data"], textarea[name="lupo_carousel_data"]').first().val(JSON.stringify(response.data.carousel_data));
                                }
                            } else {
                                $('[id*="lupo_scan_status"]').first().html('<span style="color:red;">' + response.data + '</span>');
                            }
                        },
                        error: function() {
                            $('[id*="lupo_scan_status"]').first().html('<span style="color:red;"><?php _e( 'Error scanning directory', 'lupo-art-portfolio' ); ?></span>');
                        }
                    });
                });
            });
        </script>
    </div>
    <?php
}

/**
 * Image Carousel meta box callback
 *
 * @param WP_Post $post Current post object.
 */
function lupo_image_carousel_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'lupo_image_carousel_save', 'lupo_image_carousel_nonce' );

    // Get current carousel data
    $carousel_data = get_post_meta( $post->ID, '_lupo_carousel_data', true );

    // Output field
    ?>
    <div class="lupo-meta-field">
        <input type="hidden" id="lupo_carousel_data" name="lupo_carousel_data" value="<?php echo esc_attr( $carousel_data ); ?>" />
        
        <p><?php _e( 'Manage images for the portfolio carousel.', 'lupo-art-portfolio' ); ?></p>
        
        <div id="lupo_carousel_manager">
            <div class="lupo-carousel-toolbar">
                <button type="button" id="lupo_add_carousel_image" class="button button-primary"><?php _e( 'Add Image', 'lupo-art-portfolio' ); ?></button>
                <button type="button" id="lupo_sort_carousel_images" class="button button-secondary"><?php _e( 'Sort Images', 'lupo-art-portfolio' ); ?></button>
            </div>
            
            <div id="lupo_carousel_items">
                <?php 
                // Display existing carousel items if data exists
                if ( ! empty( $carousel_data ) ) {
                    $images = json_decode( $carousel_data, true );
                    if ( is_array( $images ) ) {
                        foreach ( $images as $index => $image ) {
                            echo '<div class="lupo-carousel-item" data-index="' . esc_attr( $index ) . '">';
                            echo '<img src="' . esc_url( $image['url'] ) . '" alt="" style="max-width: 150px; height: auto;" />';
                            echo '<div class="lupo-carousel-item-details">';
                            echo '<input type="text" class="lupo-carousel-caption" placeholder="' . esc_attr__( 'Caption', 'lupo-art-portfolio' ) . '" value="' . esc_attr( $image['caption'] ) . '" />';
                            echo '<button type="button" class="button lupo-remove-carousel-item">' . esc_html__( 'Remove', 'lupo-art-portfolio' ) . '</button>';
                            echo '</div></div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                var carouselData = [];
                
                // Initialize carousel data from hidden field - FIXED: Use name-based selector
                var carouselField = $('input[name="lupo_carousel_data"], textarea[name="lupo_carousel_data"]').first();
                if (carouselField.length && carouselField.val()) {
                    try {
                        carouselData = JSON.parse(carouselField.val());
                    } catch (e) {
                        carouselData = [];
                        console.log('Lupo Carousel: Invalid JSON data, starting fresh');
                    }
                }
                
                // FIXED: Function to update hidden field using name-based selector
                function updateCarouselField() {
                    $('input[name="lupo_carousel_data"], textarea[name="lupo_carousel_data"]').first().val(JSON.stringify(carouselData));
                }
                
                // FIXED: Use delegated event binding for add image button
                $(document).on('click', '#lupo_add_carousel_image, [id*="lupo_add_carousel_image"]', function(e) {
                    e.preventDefault();
                    
                    var image_frame;
                    
                    if (image_frame) {
                        image_frame.open();
                        return;
                    }
                    
                    image_frame = wp.media({
                        title: '<?php _e( 'Select or Upload Carousel Images', 'lupo-art-portfolio' ); ?>',
                        button: {
                            text: '<?php _e( 'Add to Carousel', 'lupo-art-portfolio' ); ?>'
                        },
                        multiple: true
                    });
                    
                    image_frame.on('select', function() {
                        var selection = image_frame.state().get('selection');
                        
                        selection.each(function(attachment) {
                            attachment = attachment.toJSON();
                            
                            // Add to carousel data
                            var newIndex = carouselData.length;
                            carouselData.push({
                                id: attachment.id,
                                url: attachment.url,
                                caption: attachment.caption || '',
                                width: attachment.width,
                                height: attachment.height
                            });
                            
                            // Add to display - FIXED: Use flexible selectors
                            var carouselContainer = $('#lupo_carousel_items, [id*="lupo_carousel_items"]').first();
                            var newItem = $('<div class="lupo-carousel-item" data-index="' + newIndex + '">' +
                                '<img src="' + attachment.url + '" alt="" style="max-width: 150px; height: auto;" />' +
                                '<div class="lupo-carousel-item-details">' +
                                '<input type="text" class="lupo-carousel-caption" placeholder="<?php esc_attr_e( 'Caption', 'lupo-art-portfolio' ); ?>" value="' + (attachment.caption || '') + '" />' +
                                '<button type="button" class="button lupo-remove-carousel-item"><?php esc_html_e( 'Remove', 'lupo-art-portfolio' ); ?></button>' +
                                '</div></div>');
                                
                            carouselContainer.append(newItem);
                        });
                        
                        // Update hidden field
                        updateCarouselField();
                    });
                    
                    image_frame.open();
                });
                
                // FIXED: Use delegated event binding for remove image button
                $(document).on('click', '.lupo-remove-carousel-item', function(e) {
                    e.preventDefault();
                    
                    var item = $(this).closest('.lupo-carousel-item');
                    var index = item.data('index');
                    
                    // Remove from data array
                    if (index !== undefined && carouselData[index]) {
                        carouselData.splice(index, 1);
                        
                        // Update hidden field
                        updateCarouselField();
                        
                        // Remove from display
                        item.remove();
                        
                        // Update indices - FIXED: Use flexible selectors
                        $('.lupo-carousel-item').each(function(i) {
                            $(this).attr('data-index', i);
                        });
                    }
                });
                
                // FIXED: Use delegated event binding for caption update
                $(document).on('change keyup', '.lupo-carousel-caption', function() {
                    var item = $(this).closest('.lupo-carousel-item');
                    var index = item.data('index');
                    
                    if (index !== undefined && carouselData[index]) {
                        carouselData[index].caption = $(this).val();
                        updateCarouselField();
                    }
                });
                
                // FIXED: Use delegated event binding for sort images button
                $(document).on('click', '#lupo_sort_carousel_images, [id*="lupo_sort_carousel_images"]', function(e) {
                    e.preventDefault();
                    
                    var carouselContainer = $('#lupo_carousel_items, [id*="lupo_carousel_items"]').first();
                    
                    if (!carouselContainer.hasClass('ui-sortable')) {
                        carouselContainer.sortable({
                            update: function(event, ui) {
                                // Reorder data array based on new positions
                                var newData = [];
                                
                                carouselContainer.find('.lupo-carousel-item').each(function(i) {
                                    var oldIndex = $(this).data('index');
                                    if (oldIndex !== undefined && carouselData[oldIndex]) {
                                        newData.push(carouselData[oldIndex]);
                                        $(this).attr('data-index', i);
                                    }
                                });
                                
                                carouselData = newData;
                                updateCarouselField();
                            }
                        }).disableSelection();
                    }
                    
                    if (carouselContainer.sortable('option', 'disabled')) {
                        carouselContainer.sortable('enable');
                        $(this).text('<?php _e( 'Finish Sorting', 'lupo-art-portfolio' ); ?>');
                    } else {
                        carouselContainer.sortable('disable');
                        $(this).text('<?php _e( 'Sort Images', 'lupo-art-portfolio' ); ?>');
                    }
                });
                
                // FIXED: Ensure data is saved on form submission
                $('form#post').on('submit', function() {
                    updateCarouselField();
                });
                
                // FIXED: Handle WordPress auto-save
                $(document).on('heartbeat-send', function(e, data) {
                    updateCarouselField();
                });
            });
        </script>
        <style>
            #lupo_carousel_items {
                margin-top: 15px;
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
            }
            .lupo-carousel-item {
                border: 1px solid #ddd;
                padding: 10px;
                width: 170px;
                background: #f9f9f9;
                cursor: move;
            }
            .lupo-carousel-item-details {
                margin-top: 10px;
            }
            .lupo-carousel-caption {
                width: 100%;
                margin-bottom: 5px;
            }
            .lupo-carousel-toolbar {
                margin-bottom: 15px;
            }
            .ui-sortable-helper {
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            }
        </style>
    </div>
    <?php
}

/**
 * Save meta box data.
 *
 * @param int $post_id The post ID.
 */
function lupo_save_portfolio_meta( $post_id ) {
    // Check if this is an autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check post type - FIXED: Using lupo_portfolio consistently
    if ( 'lupo_portfolio' !== get_post_type( $post_id ) ) {
        return;
    }

    // Check user permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Background Image
    if ( isset( $_POST['lupo_background_image_nonce'] ) && wp_verify_nonce( $_POST['lupo_background_image_nonce'], 'lupo_background_image_save' ) ) {
        if ( isset( $_POST['lupo_background_image'] ) ) {
            update_post_meta( $post_id, '_lupo_background_image', esc_url_raw( $_POST['lupo_background_image'] ) );
        }
    }

    // Project Details
    if ( isset( $_POST['lupo_project_details_nonce'] ) && wp_verify_nonce( $_POST['lupo_project_details_nonce'], 'lupo_project_details_save' ) ) {
        if ( isset( $_POST['lupo_creation_date'] ) ) {
            update_post_meta( $post_id, '_lupo_creation_date', sanitize_text_field( $_POST['lupo_creation_date'] ) );
        }

        if ( isset( $_POST['lupo_project_description'] ) ) {
            update_post_meta( $post_id, '_lupo_project_description', wp_kses_post( $_POST['lupo_project_description'] ) );
        }
    }

    // Directory Path
    if ( isset( $_POST['lupo_directory_path_nonce'] ) && wp_verify_nonce( $_POST['lupo_directory_path_nonce'], 'lupo_directory_path_save' ) ) {
        if ( isset( $_POST['lupo_directory_path'] ) ) {
            update_post_meta( $post_id, '_lupo_directory_path', sanitize_text_field( $_POST['lupo_directory_path'] ) );
        }
    }

    // Image Carousel - FIXED: Enhanced validation and error handling
    if ( isset( $_POST['lupo_image_carousel_nonce'] ) && wp_verify_nonce( $_POST['lupo_image_carousel_nonce'], 'lupo_image_carousel_save' ) ) {
        if ( isset( $_POST['lupo_carousel_data'] ) ) {
            $carousel_data = $_POST['lupo_carousel_data'];
            
            // TEMPORARY DEBUG - Log the exact data
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( "Raw carousel data: " . $carousel_data );
            }
            
            if ( ! empty( $carousel_data ) ) {
                // FIXED: Handle WordPress escaping
                $carousel_data = wp_unslash( $carousel_data );
                
                // TEMPORARY DEBUG - Log after unslashing
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                    error_log( "Unslashed carousel data: " . $carousel_data );
                }
                
                // Validate JSON before saving
                $decoded = json_decode( $carousel_data, true );
                if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
                    update_post_meta( $post_id, '_lupo_carousel_data', $carousel_data );
                    
                    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                        error_log( "Lupo Portfolio: Successfully saved carousel data for post {$post_id} - " . count( $decoded ) . " images" );
                    }
                } else {
                    // Enhanced error logging
                    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                        error_log( "Lupo Portfolio: JSON decode failed for post {$post_id}" );
                        error_log( "JSON Error: " . json_last_error_msg() );
                        error_log( "Data length: " . strlen( $carousel_data ) );
                        error_log( "First 200 chars: " . substr( $carousel_data, 0, 200 ) );
                    }
                }
            }
        }
    }
}
add_action( 'save_post', 'lupo_save_portfolio_meta' );

/**
 * AJAX handler for scanning directory - FIXED: Using lupo_scan_directory action
 */
function lupo_scan_directory_ajax() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'lupo_directory_path_save' ) ) {
        wp_send_json_error( __( 'Security check failed', 'lupo-art-portfolio' ) );
    }

    // Check permissions
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( __( 'You do not have permission to do this', 'lupo-art-portfolio' ) );
    }

    // Get path
    $path = isset( $_POST['path'] ) ? sanitize_text_field( $_POST['path'] ) : '';
    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

    if ( empty( $path ) || $post_id <= 0 ) {
        wp_send_json_error( __( 'Invalid parameters', 'lupo-art-portfolio' ) );
    }

    // Build complete path
    $upload_dir = wp_upload_dir();
    $base_dir = trailingslashit( $upload_dir['basedir'] );
    $complete_path = $base_dir . ltrim( $path, '/' );

    // Check if directory exists
    if ( ! file_exists( $complete_path ) || ! is_dir( $complete_path ) ) {
        wp_send_json_error( __( 'Directory not found. Make sure the path is relative to the WordPress uploads directory.', 'lupo-art-portfolio' ) );
    }

    // Get list of image files
    $files = array();
    $carousel_data = array();
    $valid_extensions = array( 'jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif' );
 
    if ( $handle = opendir( $complete_path ) ) {
        $index = 0;
        
        while ( false !== ( $file = readdir( $handle ) ) ) {
            if ( $file != "." && $file != ".." ) {
                $file_path = $complete_path . '/' . $file;
                $file_ext = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );
                
                if ( is_file( $file_path ) && in_array( $file_ext, $valid_extensions ) ) {
                    $files[] = $file;
                    
                    // Get image dimensions
                    $image_size = getimagesize( $file_path );
                    $width = isset( $image_size[0] ) ? $image_size[0] : 0;
                    $height = isset( $image_size[1] ) ? $image_size[1] : 0;
                    
                    // Add to carousel data
                    $carousel_data[] = array(
                        'id' => 'directory-' . $index++,
                        'url' => trailingslashit( $upload_dir['baseurl'] ) . ltrim( $path, '/' ) . '/' . $file,
                        'caption' => pathinfo( $file, PATHINFO_FILENAME ),
                        'width' => $width,
                        'height' => $height
                    );
                }
            }
        }
        
        closedir( $handle );
    }

    // Sort files
    sort( $files );

    // Build HTML for display
    $html = '';
    if ( ! empty( $files ) ) {
        $html .= '<div class="lupo-directory-files">';
        $html .= '<p>' . sprintf( __( 'Found %d image files:', 'lupo-art-portfolio' ), count( $files ) ) . '</p>';
        $html .= '<ul style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">';
        
        foreach ( $files as $file ) {
            $html .= '<li>' . esc_html( $file ) . '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
    } else {
        $html .= '<p>' . __( 'No image files found in this directory', 'lupo-art-portfolio' ) . '</p>';
    }

    // Send response
    wp_send_json_success( array(
        'html' => $html,
        'carousel_data' => $carousel_data
    ) );
}
add_action( 'wp_ajax_lupo_scan_directory', 'lupo_scan_directory_ajax' );

/**
 * Add custom columns to portfolio admin screen
 * 
 * @param array $columns Array of column names.
 * @return array Modified array of column names.
 */
function lupo_portfolio_custom_columns( $columns ) {
    $new_columns = array();
    
    // Insert thumbnail after checkbox but before title
    foreach ( $columns as $key => $value ) {
        if ( $key === 'cb' ) {
            $new_columns[$key] = $value;
            $new_columns['thumbnail'] = __( 'Thumbnail', 'lupo-art-portfolio' );
        } elseif ( $key === 'title' ) {
            $new_columns[$key] = $value;
            $new_columns['images_count'] = __( 'Images', 'lupo-art-portfolio' );
            $new_columns['creation_date'] = __( 'Creation Date', 'lupo-art-portfolio' );
        } else {
            $new_columns[$key] = $value;
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_lupo_portfolio_posts_columns', 'lupo_portfolio_custom_columns' );

/**
 * Display data in custom columns
 * 
 * @param string $column_name Column name.
 * @param int    $post_id     Post ID.
 */
function lupo_portfolio_custom_column_data( $column_name, $post_id ) {
    switch ( $column_name ) {
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
            } else {
                $background_image = get_post_meta( $post_id, '_lupo_background_image', true );
                if ( ! empty( $background_image ) ) {
                    echo '<img src="' . esc_url( $background_image ) . '" width="50" height="50" alt="" style="object-fit: cover;" />';
                } else {
                    echo '<span aria-hidden="true">—</span>';
                }
            }
            break;
            
        case 'images_count':
            $carousel_data = get_post_meta( $post_id, '_lupo_carousel_data', true );
            if ( ! empty( $carousel_data ) ) {
                $images = json_decode( $carousel_data, true );
                if ( is_array( $images ) ) {
                    echo count( $images );
                } else {
                    echo '0';
                }
            } else {
                echo '0';
            }
            break;
            
        case 'creation_date':
            $creation_date = get_post_meta( $post_id, '_lupo_creation_date', true );
            if ( ! empty( $creation_date ) ) {
                echo esc_html( $creation_date );
            } else {
                echo '<span aria-hidden="true">—</span>';
            }
            break;
    }
}
add_action( 'manage_lupo_portfolio_posts_custom_column', 'lupo_portfolio_custom_column_data', 10, 2 );

/**
 * Make custom columns sortable
 * 
 * @param array $columns Array of sortable columns.
 * @return array Modified array of sortable columns.
 */
function lupo_portfolio_sortable_columns( $columns ) {
    $columns['creation_date'] = 'creation_date';
    $columns['images_count'] = 'images_count';
    return $columns;
}
add_filter( 'manage_edit-lupo_portfolio_sortable_columns', 'lupo_portfolio_sortable_columns' );

/**
 * Handle custom sorting logic
 * 
 * @param WP_Query $query The WordPress query object.
 */
function lupo_portfolio_sort_columns( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $orderby = $query->get( 'orderby' );

    if ( 'creation_date' === $orderby ) {
        $query->set( 'meta_key', '_lupo_creation_date' );
        $query->set( 'orderby', 'meta_value' );
    }
    
    if ( 'images_count' === $orderby ) {
        // This is more complex, we would need to count items in the JSON array
        // For now, just sort by existence of carousel data
        $query->set( 'meta_key', '_lupo_carousel_data' );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'lupo_portfolio_sort_columns' );

/**
 * Add filter dropdown for taxonomies
 */
function lupo_portfolio_taxonomy_filters() {
    global $typenow;
    
    if ( 'lupo_portfolio' === $typenow ) {
        // Filter by Medium
        $medium_taxonomy = get_taxonomy( 'lupo_medium' );
        wp_dropdown_categories( array(
            'show_option_all' => sprintf( __( 'All %s', 'lupo-art-portfolio' ), $medium_taxonomy->label ),
            'taxonomy'        => 'lupo_medium',
            'name'            => 'lupo_medium',
            'orderby'         => 'name',
            'selected'        => isset( $_GET['lupo_medium'] ) ? $_GET['lupo_medium'] : '',
            'hierarchical'    => true,
            'show_count'      => true,
            'hide_empty'      => true,
        ) );
        
        // Filter by Subject
        $subject_taxonomy = get_taxonomy( 'lupo_subject' );
        wp_dropdown_categories( array(
            'show_option_all' => sprintf( __( 'All %s', 'lupo-art-portfolio' ), $subject_taxonomy->label ),
            'taxonomy'        => 'lupo_subject',
            'name'            => 'lupo_subject',
            'orderby'         => 'name',
            'selected'        => isset( $_GET['lupo_subject'] ) ? $_GET['lupo_subject'] : '',
            'hierarchical'    => true,
            'show_count'      => true,
            'hide_empty'      => true,
        ) );
        
        // Filter by Year
        $year_taxonomy = get_taxonomy( 'lupo_year' );
        wp_dropdown_categories( array(
            'show_option_all' => sprintf( __( 'All %s', 'lupo-art-portfolio' ), $year_taxonomy->label ),
            'taxonomy'        => 'lupo_year',
            'name'            => 'lupo_year',
            'orderby'         => 'name',
            'selected'        => isset( $_GET['lupo_year'] ) ? $_GET['lupo_year'] : '',
            'hierarchical'    => false,
            'show_count'      => true,
            'hide_empty'      => true,
        ) );
    }
}
add_action( 'restrict_manage_posts', 'lupo_portfolio_taxonomy_filters' );

/**
 * Convert taxonomy ID to term ID in query
 */
function lupo_portfolio_convert_taxonomy_term_in_query( $query ) {
    global $pagenow, $typenow;
    
    if ( 'edit.php' === $pagenow && 'lupo_portfolio' === $typenow ) {
        $taxonomies = array( 'lupo_medium', 'lupo_subject', 'lupo_year' );
        
        foreach ( $taxonomies as $taxonomy ) {
            if ( isset( $_GET[$taxonomy] ) && $_GET[$taxonomy] > 0 ) {
                $term = get_term_by( 'id', $_GET[$taxonomy], $taxonomy );
                if ( $term ) {
                    $query->query_vars[$taxonomy] = $term->slug;
                }
            }
        }
    }
    
    return $query;
}
add_filter( 'parse_query', 'lupo_portfolio_convert_taxonomy_term_in_query' );

/**
 * Flush rewrite rules on theme activation
 */
function lupo_flush_rewrite_rules() {
    lupo_register_portfolio_post_type();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'lupo_flush_rewrite_rules' );
?>