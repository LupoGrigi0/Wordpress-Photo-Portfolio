<?php
/**
 * Custom Post Types and Taxonomies for Art Portfolio Theme
 *
 * This file handles the registration of custom post types, taxonomies,
 * and meta boxes for the art portfolio theme.
 *
 * @package ArtPortfolioTheme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
add_action( 'save_post', 'apt_save_portfolio_meta' );

/**
 * AJAX handler for scanning directory
 */
function apt_scan_directory_ajax() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'apt_directory_path_save' ) ) {
        wp_send_json_error( __( 'Security check failed', 'art-portfolio-theme' ) );
    }

    // Check permissions
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( __( 'You do not have permission to do this', 'art-portfolio-theme' ) );
    }

    // Get path
    $path = isset( $_POST['path'] ) ? sanitize_text_field( $_POST['path'] ) : '';
    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

    if ( empty( $path ) || $post_id <= 0 ) {
        wp_send_json_error( __( 'Invalid parameters', 'art-portfolio-theme' ) );
    }

    // Build complete path
    $upload_dir = wp_upload_dir();
    $base_dir = trailingslashit( $upload_dir['basedir'] );
    $complete_path = $base_dir . ltrim( $path, '/' );

    // Check if directory exists
    if ( ! file_exists( $complete_path ) || ! is_dir( $complete_path ) ) {
        wp_send_json_error( __( 'Directory not found. Make sure the path is relative to the WordPress uploads directory.', 'art-portfolio-theme' ) );
    }

    // Get list of image files
    $files = array();
    $carousel_data = array();
    $valid_extensions = array( 'jpg', 'jpeg', 'png', 'gif', 'webp' );
    
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
        $html .= '<div class="apt-directory-files">';
        $html .= '<p>' . sprintf( __( 'Found %d image files:', 'art-portfolio-theme' ), count( $files ) ) . '</p>';
        $html .= '<ul style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">';
        
        foreach ( $files as $file ) {
            $html .= '<li>' . esc_html( $file ) . '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</div>';
    } else {
        $html .= '<p>' . __( 'No image files found in this directory', 'art-portfolio-theme' ) . '</p>';
    }

    // Send response
    wp_send_json_success( array(
        'html' => $html,
        'carousel_data' => $carousel_data
    ) );
}
add_action( 'wp_ajax_apt_scan_directory', 'apt_scan_directory_ajax' );

/**
 * Add custom columns to portfolio admin screen
 * 
 * @param array $columns Array of column names.
 * @return array Modified array of column names.
 */
function apt_portfolio_custom_columns( $columns ) {
    $new_columns = array();
    
    // Insert thumbnail after checkbox but before title
    foreach ( $columns as $key => $value ) {
        if ( $key === 'cb' ) {
            $new_columns[$key] = $value;
            $new_columns['thumbnail'] = __( 'Thumbnail', 'art-portfolio-theme' );
        } elseif ( $key === 'title' ) {
            $new_columns[$key] = $value;
            $new_columns['images_count'] = __( 'Images', 'art-portfolio-theme' );
            $new_columns['creation_date'] = __( 'Creation Date', 'art-portfolio-theme' );
        } else {
            $new_columns[$key] = $value;
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_apt_portfolio_posts_columns', 'apt_portfolio_custom_columns' );

/**
 * Display data in custom columns
 * 
 * @param string $column_name Column name.
 * @param int    $post_id     Post ID.
 */
function apt_portfolio_custom_column_data( $column_name, $post_id ) {
    switch ( $column_name ) {
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
            } else {
                $background_image = get_post_meta( $post_id, '_apt_background_image', true );
                if ( ! empty( $background_image ) ) {
                    echo '<img src="' . esc_url( $background_image ) . '" width="50" height="50" alt="" style="object-fit: cover;" />';
                } else {
                    echo '<span aria-hidden="true">—</span>';
                }
            }
            break;
            
        case 'images_count':
            $carousel_data = get_post_meta( $post_id, '_apt_carousel_data', true );
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
            $creation_date = get_post_meta( $post_id, '_apt_creation_date', true );
            if ( ! empty( $creation_date ) ) {
                echo esc_html( $creation_date );
            } else {
                echo '<span aria-hidden="true">—</span>';
            }
            break;
    }
}
add_action( 'manage_apt_portfolio_posts_custom_column', 'apt_portfolio_custom_column_data', 10, 2 );

/**
 * Make custom columns sortable
 * 
 * @param array $columns Array of sortable columns.
 * @return array Modified array of sortable columns.
 */
function apt_portfolio_sortable_columns( $columns ) {
    $columns['creation_date'] = 'creation_date';
    $columns['images_count'] = 'images_count';
    return $columns;
}
add_filter( 'manage_edit-apt_portfolio_sortable_columns', 'apt_portfolio_sortable_columns' );

/**
 * Handle custom sorting logic
 * 
 * @param WP_Query $query The WordPress query object.
 */
function apt_portfolio_sort_columns( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $orderby = $query->get( 'orderby' );

    if ( 'creation_date' === $orderby ) {
        $query->set( 'meta_key', '_apt_creation_date' );
        $query->set( 'orderby', 'meta_value' );
    }
    
    if ( 'images_count' === $orderby ) {
        // This is more complex, we would need to count items in the JSON array
        // For now, just sort by existence of carousel data
        $query->set( 'meta_key', '_apt_carousel_data' );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'apt_portfolio_sort_columns' );

/**
 * Add filter dropdown for taxonomies
 */
function apt_portfolio_taxonomy_filters() {
    global $typenow;
    
    if ( 'apt_portfolio' === $typenow ) {
        // Filter by Medium
        $medium_taxonomy = get_taxonomy( 'apt_medium' );
        wp_dropdown_categories( array(
            'show_option_all' => sprintf( __( 'All %s', 'art-portfolio-theme' ), $medium_taxonomy->label ),
            'taxonomy'        => 'apt_medium',
            'name'            => 'apt_medium',
            'orderby'         => 'name',
            'selected'        => isset( $_GET['apt_medium'] ) ? $_GET['apt_medium'] : '',
            'hierarchical'    => true,
            'show_count'      => true,
            'hide_empty'      => true,
        ) );
        
        // Filter by Subject
        $subject_taxonomy = get_taxonomy( 'apt_subject' );
        wp_dropdown_categories( array(
            'show_option_all' => sprintf( __( 'All %s', 'art-portfolio-theme' ), $subject_taxonomy->label ),
            'taxonomy'        => 'apt_subject',
            'name'            => 'apt_subject',
            'orderby'         => 'name',
            'selected'        => isset( $_GET['apt_subject'] ) ? $_GET['apt_subject'] : '',
            'hierarchical'    => true,
            'show_count'      => true,
            'hide_empty'      => true,
        ) );
        
        // Filter by Year
        $year_taxonomy = get_taxonomy( 'apt_year' );
        wp_dropdown_categories( array(
            'show_option_all' => sprintf( __( 'All %s', 'art-portfolio-theme' ), $year_taxonomy->label ),
            'taxonomy'        => 'apt_year',
            'name'            => 'apt_year',
            'orderby'         => 'name',
            'selected'        => isset( $_GET['apt_year'] ) ? $_GET['apt_year'] : '',
            'hierarchical'    => false,
            'show_count'      => true,
            'hide_empty'      => true,
        ) );
    }
}
add_action( 'restrict_manage_posts', 'apt_portfolio_taxonomy_filters' );

/**
 * Convert taxonomy ID to term ID in query
 */
function apt_portfolio_convert_taxonomy_term_in_query( $query ) {
    global $pagenow, $typenow;
    
    if ( 'edit.php' === $pagenow && 'apt_portfolio' === $typenow ) {
        $taxonomies = array( 'apt_medium', 'apt_subject', 'apt_year' );
        
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
add_filter( 'parse_query', 'apt_portfolio_convert_taxonomy_term_in_query' );

/**
 * Flush rewrite rules on theme activation
 */
function apt_flush_rewrite_rules() {
    apt_register_portfolio_post_type();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'apt_flush_rewrite_rules' );

/**
 * Register Portfolio Custom Post Type and related taxonomies.
 */
function apt_register_portfolio_post_type() {
    // Set up labels for the Portfolio post type
    $labels = array(
        'name'                  => _x( 'Portfolio Items', 'Post type general name', 'art-portfolio-theme' ),
        'singular_name'         => _x( 'Portfolio Item', 'Post type singular name', 'art-portfolio-theme' ),
        'menu_name'             => _x( 'Portfolio', 'Admin Menu text', 'art-portfolio-theme' ),
        'name_admin_bar'        => _x( 'Portfolio Item', 'Add New on Toolbar', 'art-portfolio-theme' ),
        'add_new'               => __( 'Add New', 'art-portfolio-theme' ),
        'add_new_item'          => __( 'Add New Portfolio Item', 'art-portfolio-theme' ),
        'new_item'              => __( 'New Portfolio Item', 'art-portfolio-theme' ),
        'edit_item'             => __( 'Edit Portfolio Item', 'art-portfolio-theme' ),
        'view_item'             => __( 'View Portfolio Item', 'art-portfolio-theme' ),
        'all_items'             => __( 'All Portfolio Items', 'art-portfolio-theme' ),
        'search_items'          => __( 'Search Portfolio Items', 'art-portfolio-theme' ),
        'parent_item_colon'     => __( 'Parent Portfolio Items:', 'art-portfolio-theme' ),
        'not_found'             => __( 'No portfolio items found.', 'art-portfolio-theme' ),
        'not_found_in_trash'    => __( 'No portfolio items found in Trash.', 'art-portfolio-theme' ),
        'featured_image'        => __( 'Portfolio Item Cover Image', 'art-portfolio-theme' ),
        'set_featured_image'    => __( 'Set cover image', 'art-portfolio-theme' ),
        'remove_featured_image' => __( 'Remove cover image', 'art-portfolio-theme' ),
        'use_featured_image'    => __( 'Use as cover image', 'art-portfolio-theme' ),
        'archives'              => __( 'Portfolio archives', 'art-portfolio-theme' ),
        'insert_into_item'      => __( 'Insert into portfolio item', 'art-portfolio-theme' ),
        'uploaded_to_this_item' => __( 'Uploaded to this portfolio item', 'art-portfolio-theme' ),
        'filter_items_list'     => __( 'Filter portfolio items list', 'art-portfolio-theme' ),
        'items_list_navigation' => __( 'Portfolio items list navigation', 'art-portfolio-theme' ),
        'items_list'            => __( 'Portfolio items list', 'art-portfolio-theme' ),
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
        'taxonomies'         => array( 'apt_medium', 'apt_subject', 'apt_year' ),
    );

    // Register the Portfolio post type
    register_post_type( 'apt_portfolio', $args );

    // Register Medium Taxonomy
    register_taxonomy(
        'apt_medium',
        'apt_portfolio',
        array(
            'labels'            => array(
                'name'              => __( 'Media', 'art-portfolio-theme' ),
                'singular_name'     => __( 'Medium', 'art-portfolio-theme' ),
                'search_items'      => __( 'Search Media', 'art-portfolio-theme' ),
                'all_items'         => __( 'All Media', 'art-portfolio-theme' ),
                'parent_item'       => __( 'Parent Medium', 'art-portfolio-theme' ),
                'parent_item_colon' => __( 'Parent Medium:', 'art-portfolio-theme' ),
                'edit_item'         => __( 'Edit Medium', 'art-portfolio-theme' ),
                'update_item'       => __( 'Update Medium', 'art-portfolio-theme' ),
                'add_new_item'      => __( 'Add New Medium', 'art-portfolio-theme' ),
                'new_item_name'     => __( 'New Medium Name', 'art-portfolio-theme' ),
                'menu_name'         => __( 'Media', 'art-portfolio-theme' ),
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
        'apt_subject',
        'apt_portfolio',
        array(
            'labels'            => array(
                'name'              => __( 'Subjects', 'art-portfolio-theme' ),
                'singular_name'     => __( 'Subject', 'art-portfolio-theme' ),
                'search_items'      => __( 'Search Subjects', 'art-portfolio-theme' ),
                'all_items'         => __( 'All Subjects', 'art-portfolio-theme' ),
                'parent_item'       => __( 'Parent Subject', 'art-portfolio-theme' ),
                'parent_item_colon' => __( 'Parent Subject:', 'art-portfolio-theme' ),
                'edit_item'         => __( 'Edit Subject', 'art-portfolio-theme' ),
                'update_item'       => __( 'Update Subject', 'art-portfolio-theme' ),
                'add_new_item'      => __( 'Add New Subject', 'art-portfolio-theme' ),
                'new_item_name'     => __( 'New Subject Name', 'art-portfolio-theme' ),
                'menu_name'         => __( 'Subjects', 'art-portfolio-theme' ),
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
        'apt_year',
        'apt_portfolio',
        array(
            'labels'            => array(
                'name'              => __( 'Years', 'art-portfolio-theme' ),
                'singular_name'     => __( 'Year', 'art-portfolio-theme' ),
                'search_items'      => __( 'Search Years', 'art-portfolio-theme' ),
                'all_items'         => __( 'All Years', 'art-portfolio-theme' ),
                'edit_item'         => __( 'Edit Year', 'art-portfolio-theme' ),
                'update_item'       => __( 'Update Year', 'art-portfolio-theme' ),
                'add_new_item'      => __( 'Add New Year', 'art-portfolio-theme' ),
                'new_item_name'     => __( 'New Year', 'art-portfolio-theme' ),
                'menu_name'         => __( 'Years', 'art-portfolio-theme' ),
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
add_action( 'init', 'apt_register_portfolio_post_type' );

/**
 * Register custom meta boxes for Portfolio post type.
 */
function apt_add_portfolio_meta_boxes() {
    // Background Image Meta Box
    add_meta_box(
        'apt_background_image',
        __( 'Background Image', 'art-portfolio-theme' ),
        'apt_background_image_callback',
        'apt_portfolio',
        'side',
        'default'
    );

    // Project Details Meta Box
    add_meta_box(
        'apt_project_details',
        __( 'Project Details', 'art-portfolio-theme' ),
        'apt_project_details_callback',
        'apt_portfolio',
        'normal',
        'high'
    );

    // Directory Path Meta Box
    add_meta_box(
        'apt_directory_path',
        __( 'Image Directory Path', 'art-portfolio-theme' ),
        'apt_directory_path_callback',
        'apt_portfolio',
        'normal',
        'high'
    );

    // Image Carousel Meta Box
    add_meta_box(
        'apt_image_carousel',
        __( 'Image Carousel', 'art-portfolio-theme' ),
        'apt_image_carousel_callback',
        'apt_portfolio',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'apt_add_portfolio_meta_boxes' );

/**
 * Register meta fields for storing portfolio data
 */
function apt_register_meta_fields() {
    // Background Image URL
    register_post_meta(
        'apt_portfolio',
        '_apt_background_image',
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
        'apt_portfolio',
        '_apt_creation_date',
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
        'apt_portfolio',
        '_apt_project_description',
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
        'apt_portfolio',
        '_apt_directory_path',
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
        'apt_portfolio',
        '_apt_carousel_data',
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
        'apt_portfolio',
        '_apt_parallax_settings',
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
add_action( 'init', 'apt_register_meta_fields' );

/**
 * Background Image meta box callback
 *
 * @param WP_Post $post Current post object.
 */
function apt_background_image_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'apt_background_image_save', 'apt_background_image_nonce' );

    // Get current value
    $background_image = get_post_meta( $post->ID, '_apt_background_image', true );

    // Output field
    ?>
    <div class="apt-meta-field">
        <p>
            <label for="apt_background_image"><?php _e( 'Select or upload a background image for parallax scrolling', 'art-portfolio-theme' ); ?></label>
        </p>
        <input type="text" id="apt_background_image" name="apt_background_image" value="<?php echo esc_attr( $background_image ); ?>" class="regular-text" readonly />
        <p>
            <input type="button" id="apt_background_image_button" class="button" value="<?php _e( 'Select Image', 'art-portfolio-theme' ); ?>" />
            <input type="button" id="apt_background_image_remove" class="button" value="<?php _e( 'Remove Image', 'art-portfolio-theme' ); ?>" />
        </p>
        <div id="apt_background_image_preview">
            <?php if ( ! empty( $background_image ) ) : ?>
                <img src="<?php echo esc_url( $background_image ); ?>" style="max-width: 100%; height: auto;" />
            <?php endif; ?>
        </div>
        <script>
            jQuery(document).ready(function($) {
                // Handle the media uploader
                $('#apt_background_image_button').click(function(e) {
                    e.preventDefault();
                    var image_frame;
                    
                    if (image_frame) {
                        image_frame.open();
                        return;
                    }
                    
                    image_frame = wp.media({
                        title: '<?php _e( 'Select or Upload Background Image', 'art-portfolio-theme' ); ?>',
                        button: {
                            text: '<?php _e( 'Use this image', 'art-portfolio-theme' ); ?>'
                        },
                        multiple: false
                    });
                    
                    image_frame.on('select', function() {
                        var attachment = image_frame.state().get('selection').first().toJSON();
                        $('#apt_background_image').val(attachment.url);
                        $('#apt_background_image_preview').html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto;" />');
                    });
                    
                    image_frame.open();
                });

                // Handle the remove button
                $('#apt_background_image_remove').click(function(e) {
                    e.preventDefault();
                    $('#apt_background_image').val('');
                    $('#apt_background_image_preview').html('');
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
function apt_project_details_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'apt_project_details_save', 'apt_project_details_nonce' );

    // Get current values
    $creation_date = get_post_meta( $post->ID, '_apt_creation_date', true );
    $project_description = get_post_meta( $post->ID, '_apt_project_description', true );

    // Output fields
    ?>
    <div class="apt-meta-field">
        <p>
            <label for="apt_creation_date"><?php _e( 'Creation Date', 'art-portfolio-theme' ); ?></label>
            <input type="date" id="apt_creation_date" name="apt_creation_date" value="<?php echo esc_attr( $creation_date ); ?>" class="widefat" />
            <span class="description"><?php _e( 'When was this art project created?', 'art-portfolio-theme' ); ?></span>
        </p>

        <p>
            <label for="apt_project_description"><?php _e( 'Project Description', 'art-portfolio-theme' ); ?></label>
            <?php
            wp_editor(
                $project_description,
                'apt_project_description',
                array(
                    'textarea_name' => 'apt_project_description',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                    'teeny'         => false,
                    'quicktags'     => true,
                )
            );
            ?>
            <span class="description"><?php _e( 'Provide a detailed description of this art project', 'art-portfolio-theme' ); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Directory Path meta box callback
 *
 * @param WP_Post $post Current post object.
 */
function apt_directory_path_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'apt_directory_path_save', 'apt_directory_path_nonce' );

    // Get current value
    $directory_path = get_post_meta( $post->ID, '_apt_directory_path', true );

    // Output field
    ?>
    <div class="apt-meta-field">
        <p>
            <label for="apt_directory_path"><?php _e( 'Directory Path', 'art-portfolio-theme' ); ?></label>
            <input type="text" id="apt_directory_path" name="apt_directory_path" value="<?php echo esc_attr( $directory_path ); ?>" class="widefat" />
            <span class="description"><?php _e( 'Enter the relative path to the directory containing images for this portfolio item (e.g., "portfolio/project-name/")', 'art-portfolio-theme' ); ?></span>
        </p>
        <p>
            <button type="button" id="apt_scan_directory" class="button button-secondary"><?php _e( 'Scan Directory', 'art-portfolio-theme' ); ?></button>
            <span id="apt_scan_status"></span>
        </p>
        <div id="apt_directory_files"></div>
        <script>
            jQuery(document).ready(function($) {
                $('#apt_scan_directory').click(function() {
                    var path = $('#apt_directory_path').val();
                    if (!path) {
                        $('#apt_scan_status').html('<span style="color:red;"><?php _e( 'Please enter a directory path first', 'art-portfolio-theme' ); ?></span>');
                        return;
                    }
                    
                    $('#apt_scan_status').html('<span style="color:blue;"><?php _e( 'Scanning...', 'art-portfolio-theme' ); ?></span>');
                    
                    // AJAX call to scan directory
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'apt_scan_directory',
                            path: path,
                            post_id: <?php echo $post->ID; ?>,
                            nonce: $('#apt_directory_path_nonce').val()
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#apt_scan_status').html('<span style="color:green;"><?php _e( 'Directory scanned successfully!', 'art-portfolio-theme' ); ?></span>');
                                $('#apt_directory_files').html(response.data.html);
                                
                                // If automatic carousel generation is enabled, update the carousel data
                                if (response.data.carousel_data) {
                                    // Update hidden carousel data field
                                    $('#apt_carousel_data').val(JSON.stringify(response.data.carousel_data));
                                }
                            } else {
                                $('#apt_scan_status').html('<span style="color:red;">' + response.data + '</span>');
                            }
                        },
                        error: function() {
                            $('#apt_scan_status').html('<span style="color:red;"><?php _e( 'Error scanning directory', 'art-portfolio-theme' ); ?></span>');
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
function apt_image_carousel_callback( $post ) {
    // Add nonce for security
    wp_nonce_field( 'apt_image_carousel_save', 'apt_image_carousel_nonce' );

    // Get current carousel data
    $carousel_data = get_post_meta( $post->ID, '_apt_carousel_data', true );

    // Output field
    ?>
    <div class="apt-meta-field">
        <input type="hidden" id="apt_carousel_data" name="apt_carousel_data" value="<?php echo esc_attr( $carousel_data ); ?>" />
        
        <p><?php _e( 'Manage images for the portfolio carousel.', 'art-portfolio-theme' ); ?></p>
        
        <div id="apt_carousel_manager">
            <div class="apt-carousel-toolbar">
                <button type="button" id="apt_add_carousel_image" class="button button-primary"><?php _e( 'Add Image', 'art-portfolio-theme' ); ?></button>
                <button type="button" id="apt_sort_carousel_images" class="button button-secondary"><?php _e( 'Sort Images', 'art-portfolio-theme' ); ?></button>
            </div>
            
            <div id="apt_carousel_items">
                <?php 
                // Display existing carousel items if data exists
                if ( ! empty( $carousel_data ) ) {
                    $images = json_decode( $carousel_data, true );
                    if ( is_array( $images ) ) {
                        foreach ( $images as $index => $image ) {
                            echo '<div class="apt-carousel-item" data-index="' . esc_attr( $index ) . '">';
                            echo '<img src="' . esc_url( $image['url'] ) . '" alt="" style="max-width: 150px; height: auto;" />';
                            echo '<div class="apt-carousel-item-details">';
                            echo '<input type="text" class="apt-carousel-caption" placeholder="' . esc_attr__( 'Caption', 'art-portfolio-theme' ) . '" value="' . esc_attr( $image['caption'] ) . '" />';
                            echo '<button type="button" class="button apt-remove-carousel-item">' . esc_html__( 'Remove', 'art-portfolio-theme' ) . '</button>';
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
                
                // Initialize carousel data from hidden field
                if ($('#apt_carousel_data').val()) {
                    try {
                        carouselData = JSON.parse($('#apt_carousel_data').val());
                    } catch (e) {
                        carouselData = [];
                    }
                }
                
                // Add image button handler
                $('#apt_add_carousel_image').click(function() {
                    var image_frame;
                    
                    if (image_frame) {
                        image_frame.open();
                        return;
                    }
                    
                    image_frame = wp.media({
                        title: '<?php _e( 'Select or Upload Carousel Images', 'art-portfolio-theme' ); ?>',
                        button: {
                            text: '<?php _e( 'Add to Carousel', 'art-portfolio-theme' ); ?>'
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
                            
                            // Add to display
                            var newItem = $('<div class="apt-carousel-item" data-index="' + newIndex + '">' +
                                '<img src="' + attachment.url + '" alt="" style="max-width: 150px; height: auto;" />' +
                                '<div class="apt-carousel-item-details">' +
                                '<input type="text" class="apt-carousel-caption" placeholder="<?php esc_attr_e( 'Caption', 'art-portfolio-theme' ); ?>" value="' + (attachment.caption || '') + '" />' +
                                '<button type="button" class="button apt-remove-carousel-item"><?php esc_html_e( 'Remove', 'art-portfolio-theme' ); ?></button>' +
                                '</div></div>');
                                
                            $('#apt_carousel_items').append(newItem);
                            
                            // Update hidden field
                            $('#apt_carousel_data').val(JSON.stringify(carouselData));
                        });
                    });
                    
                    image_frame.open();
                });
                
                // Remove image handler (delegated event)
                $('#apt_carousel_items').on('click', '.apt-remove-carousel-item', function() {
                    var item = $(this).closest('.apt-carousel-item');
                    var index = item.data('index');
                    
                    // Remove from data array
                    if (index !== undefined && carouselData[index]) {
                        carouselData.splice(index, 1);
                        
                        // Update hidden field
                        $('#apt_carousel_data').val(JSON.stringify(carouselData));
                        
                        // Remove from display
                        item.remove();
                        
                        // Update indices
                        $('#apt_carousel_items .apt-carousel-item').each(function(i) {
                            $(this).attr('data-index', i);
                        });
                    }
                });
                
                // Caption update handler
                $('#apt_carousel_items').on('change', '.apt-carousel-caption', function() {
                    var item = $(this).closest('.apt-carousel-item');
                    var index = item.data('index');
                    
                    if (index !== undefined && carouselData[index]) {
                        carouselData[index].caption = $(this).val();
                        $('#apt_carousel_data').val(JSON.stringify(carouselData));
                    }
                });
                
                // Make carousel items sortable
                $('#apt_sort_carousel_images').click(function() {
                    $('#apt_carousel_items').sortable({
                        update: function(event, ui) {
                            // Reorder data array based on new positions
                            var newData = [];
                            
                            $('#apt_carousel_items .apt-carousel-item').each(function(i) {
                                var oldIndex = $(this).data('index');
                                if (oldIndex !== undefined && carouselData[oldIndex]) {
                                    newData.push(carouselData[oldIndex]);
                                    $(this).attr('data-index', i);
                                }
                            });
                            
                            carouselData = newData;
                            $('#apt_carousel_data').val(JSON.stringify(carouselData));
                        }
                    }).disableSelection();
                    
                    $('#apt_carousel_items').sortable('enable');
                    $(this).text('<?php _e( 'Finish Sorting', 'art-portfolio-theme' ); ?>');
                    $(this).click(function() {
                        $('#apt_carousel_items').sortable('disable');
                        $(this).text('<?php _e( 'Sort Images', 'art-portfolio-theme' ); ?>');
                        // Rebind this click event
                        $(this).unbind('click').click(arguments.callee);
                    });
                });
            });
        </script>
        <style>
            #apt_carousel_items {
                margin-top: 15px;
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
            }
            .apt-carousel-item {
                border: 1px solid #ddd;
                padding: 10px;
                width: 170px;
                background: #f9f9f9;
            }
            .apt-carousel-item-details {
                margin-top: 10px;
            }
            .apt-carousel-caption {
                width: 100%;
                margin-bottom: 5px;
            }
            .apt-carousel-toolbar {
                margin-bottom: 15px;
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
function apt_save_portfolio_meta( $post_id ) {
    // Check if this is an autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check post type
    if ( 'apt_portfolio' !== get_post_type( $post_id ) ) {
        return;
    }

    // Check user permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Background Image
    if ( isset( $_POST['apt_background_image_nonce'] ) && wp_verify_nonce( $_POST['apt_background_image_nonce'], 'apt_background_image_save' ) ) {
        if ( isset( $_POST['apt_background_image'] ) ) {
            update_post_meta( $post_id, '_apt_background_image', esc_url_raw( $_POST['apt_background_image'] ) );
        }
    }

    // Project Details
    if ( isset( $_POST['apt_project_details_nonce'] ) && wp_verify_nonce( $_POST['apt_project_details_nonce'], 'apt_project_details_save' ) ) {
        if ( isset( $_POST['apt_creation_date'] ) ) {
            update_post_meta( $post_id, '_apt_creation_date', sanitize_text_field( $_POST['apt_creation_date'] ) );
        }

        if ( isset( $_POST['apt_project_description'] ) ) {
            update_post_meta( $post_id, '_apt_project_description', wp_kses_post( $_POST['apt_project_description'] ) );
        }
    }

    // Directory Path
    if ( isset( $_POST['apt_directory_path_nonce'] ) && wp_verify_nonce( $_POST['apt_directory_path_nonce'], 'apt_directory_path_save' ) ) {
        if ( isset( $_POST['apt_directory_path'] ) ) {
            update_post_meta( $post_id, '_apt_directory_path', sanitize_text_field( $_POST['apt_directory_path'] ) );
        }
    }

    // Image Carousel
    if ( isset( $_POST['apt_image_carousel_nonce'] ) && wp_verify_nonce( $_POST['apt_image_carousel_nonce'], 'apt_image_carousel_save' ) ) {
        if ( isset( $_POST['apt_carousel_data'] ) ) {
            // Validate JSON before saving
            $carousel_data = $_POST['apt_carousel_data'];
            if ( ! empty( $carousel_data ) ) {
                json_decode( $carousel_data );
                if ( json_last_error() === JSON_ERROR_NONE ) {
                    update_post_meta( $post_id, '_apt_carousel_data', $carousel_data );
                }
            } else {
                delete_post_meta( $post_id, '_apt_carousel_data' );
            }
        }
    }
}