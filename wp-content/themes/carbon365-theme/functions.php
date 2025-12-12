<?php
/**
 * Carbon365 Theme Functions
 */

// Theme Support
function carbon365_theme_support() {
    // Add title tag support
    add_theme_support('title-tag');
    
    // Add post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add HTML5 support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'carbon365_theme_support');

// Enqueue Styles
function carbon365_enqueue_styles() {
    wp_enqueue_style('carbon365-style', get_stylesheet_uri(), array(), '1.0');
}
add_action('wp_enqueue_scripts', 'carbon365_enqueue_styles');

// Custom excerpt length
function carbon365_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'carbon365_excerpt_length');
?>