<?php
/**
 * Debug visualization settings for the WordPress customizer
 * 
 * @package Lupos-Portfolio-Theme
 * Version: 1.0.0
 * Co-authored-by: Genevieve (VS Code Shard)
 */

function portfolio_debug_customizer_settings($wp_customize) {
    // Add new section for debug settings
    $wp_customize->add_section('portfolio_debug_section', array(
        'title'    => __('Debug Visualization', 'lupos-portfolio'),
        'priority' => 200,
    ));

    // Add setting for debug visualization
    $wp_customize->add_setting('portfolio_debug_visualization', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    // Add control for debug visualization
    $wp_customize->add_control('portfolio_debug_visualization', array(
        'label'       => __('Enable Debug Visualization', 'lupos-portfolio'),
        'description' => __('Shows red tinted backgrounds to help visualize content block spacing', 'lupos-portfolio'),
        'section'     => 'portfolio_debug_section',
        'type'        => 'checkbox',
    ));
}
add_action('customize_register', 'portfolio_debug_customizer_settings');

// Output custom CSS based on debug setting
function portfolio_debug_custom_css() {
    if (get_theme_mod('portfolio_debug_visualization', false)) {
        echo '<style type="text/css">
            :root {
                --debug-block-opacity: 0.1;
            }
        </style>';
    }
}
add_action('wp_head', 'portfolio_debug_custom_css');

// Enable debug visualization if WP_DEBUG is true
function portfolio_debug_init() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        set_theme_mod('portfolio_debug_visualization', true);
    }
}
add_action('init', 'portfolio_debug_init');
