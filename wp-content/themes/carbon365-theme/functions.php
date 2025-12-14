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
    // Header assets
    wp_enqueue_style('carbon365-header', get_template_directory_uri() . '/assets/css/header.css', array(), '1.0');
    wp_enqueue_script('carbon365-header', get_template_directory_uri() . '/assets/js/header.js', array(), '1.0', true);
    // Hero carousel assets
    wp_enqueue_style('carbon365-hero', get_template_directory_uri() . '/assets/css/hero.css', array(), '1.0');
    wp_enqueue_script('carbon365-hero', get_template_directory_uri() . '/assets/js/hero.js', array(), '1.0', true);
    // Footer script (progressive enhancement: sets year, validates email)
    wp_enqueue_script('carbon365-footer', get_template_directory_uri() . '/assets/js/footer.js', array(), '1.0', true);
    // Footer stylesheet
    wp_enqueue_style('carbon365-footer', get_template_directory_uri() . '/assets/css/footer.css', array(), '1.0');
    // Posts carousel script (desktop only behavior controlled in script)
    wp_enqueue_script('carbon365-posts-carousel', get_template_directory_uri() . '/assets/js/posts-carousel.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'carbon365_enqueue_styles');

// Register primary menu
function carbon365_register_menus() {
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'carbon365-theme' ),
    ) );
}
add_action( 'after_setup_theme', 'carbon365_register_menus' );

// Custom excerpt length
function carbon365_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'carbon365_excerpt_length');
?>