<?php
/**
 * Plugin Name: CC Simple Testimonials
 * Plugin URI: https://github.com/yourusername/wordpress-developer-test
 * Description: A simple testimonials plugin with custom post type, rating meta box, and shortcode display
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL2
 * Text Domain: cc-testimonials
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Post Type: Testimonial
 */
function cc_register_testimonial_post_type() {
    $labels = array(
        'name'                  => 'Testimonials',
        'singular_name'         => 'Testimonial',
        'menu_name'             => 'Testimonials',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Testimonial',
        'edit_item'             => 'Edit Testimonial',
        'new_item'              => 'New Testimonial',
        'view_item'             => 'View Testimonial',
        'search_items'          => 'Search Testimonials',
        'not_found'             => 'No testimonials found',
        'not_found_in_trash'    => 'No testimonials found in trash',
        'all_items'             => 'All Testimonials',
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_rest'          => true,
        'menu_icon'             => 'dashicons-testimonial',
        'supports'              => array('title', 'editor', 'thumbnail'),
        'rewrite'               => array('slug' => 'testimonials'),
    );

    register_post_type('testimonial', $args);
}
add_action('init', 'cc_register_testimonial_post_type');

/**
 * Add Meta Box for Rating
 */
function cc_add_rating_meta_box() {
    add_meta_box(
        'cc_testimonial_rating',
        'Testimonial Rating',
        'cc_rating_meta_box_callback',
        'testimonial',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'cc_add_rating_meta_box');

/**
 * Meta Box Callback Function
 */
function cc_rating_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('cc_save_rating_meta', 'cc_rating_nonce');
    
    // Get current rating value
    $rating = get_post_meta($post->ID, '_cc_testimonial_rating', true);
    $rating = $rating ? $rating : 5; // Default to 5 stars
    
    echo '<label for="cc_testimonial_rating">Select Rating (1-5 stars):</label><br>';
    echo '<select name="cc_testimonial_rating" id="cc_testimonial_rating" style="width: 100%; padding: 5px; margin-top: 10px;">';
    
    for ($i = 1; $i <= 5; $i++) {
        $selected = ($rating == $i) ? 'selected' : '';
        $stars = str_repeat('★', $i);
        echo "<option value='$i' $selected>$stars ($i)</option>";
    }
    
    echo '</select>';
    
    echo '<p style="margin-top: 10px; font-size: 12px; color: #666;">Current rating: ' . str_repeat('★', $rating) . '</p>';
}

/**
 * Save Meta Box Data
 */
function cc_save_rating_meta($post_id) {
    // Verify nonce
    if (!isset($_POST['cc_rating_nonce']) || !wp_verify_nonce($_POST['cc_rating_nonce'], 'cc_save_rating_meta')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save rating
    if (isset($_POST['cc_testimonial_rating'])) {
        $rating = intval($_POST['cc_testimonial_rating']);
        
        // Validate rating (1-5)
        if ($rating >= 1 && $rating <= 5) {
            update_post_meta($post_id, '_cc_testimonial_rating', $rating);
        }
    }
}
add_action('save_post_testimonial', 'cc_save_rating_meta');

/**
 * Shortcode to Display Testimonials
 * Usage: [cc_testimonials]
 */
function cc_testimonials_shortcode($atts) {
    // Set default attributes
    $atts = shortcode_atts(array(
        'posts_per_page' => -1, // Show all testimonials
        'orderby'        => 'date',
        'order'          => 'DESC',
    ), $atts);
    
    // Query testimonials
    $args = array(
        'post_type'      => 'testimonial',
        'posts_per_page' => $atts['posts_per_page'],
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
        'post_status'    => 'publish',
    );
    
    $testimonials = new WP_Query($args);
    
    // Start output buffering
    ob_start();
    
    if ($testimonials->have_posts()) :
    ?>
<div class="cc-testimonials-container">
    <style>
    .cc-testimonials-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin: 40px 0;
    }

    .cc-testimonial-card {
        background: #f9fafb;
        border-left: 4px solid #667eea;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .cc-testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .cc-testimonial-title {
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .cc-testimonial-rating {
        color: #fbbf24;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .cc-testimonial-content {
        color: #666;
        line-height: 1.6;
        font-style: italic;
    }

    .cc-testimonial-content::before {
        content: '"';
        font-size: 2rem;
        color: #667eea;
        opacity: 0.3;
    }

    @media (max-width: 768px) {
        .cc-testimonials-container {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <?php while ($testimonials->have_posts()) : $testimonials->the_post(); 
                $rating = get_post_meta(get_the_ID(), '_cc_testimonial_rating', true);
                $rating = $rating ? $rating : 5;
                $stars = str_repeat('★', $rating);
            ?>
    <div class="cc-testimonial-card">
        <h3 class="cc-testimonial-title"><?php the_title(); ?></h3>
        <div class="cc-testimonial-rating"><?php echo $stars; ?></div>
        <div class="cc-testimonial-content">
            <?php the_content(); ?>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php
        wp_reset_postdata();
    else :
        echo '<p>No testimonials found. Please add some testimonials from the admin panel.</p>';
    endif;
    
    return ob_get_clean();
}
add_shortcode('cc_testimonials', 'cc_testimonials_shortcode');

/**
 * Add custom CSS to admin
 */
function cc_testimonials_admin_styles() {
    echo '<style>
        .dashicons-testimonial:before {
            content: "\\f155"; /* star icon */
        }
    </style>';
}
add_action('admin_head', 'cc_testimonials_admin_styles');