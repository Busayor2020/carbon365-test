<?php
/**
 * Plugin Name: CC Simple Testimonials
 * Plugin URI: https://github.com/yourusername/wordpress-developer-test
 * Description: A simple testimonials plugin with custom post type, rating meta box, and shortcode display
 * Version: 1.0
 * Author: Victor Busayo
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
<div class="cc-testimonials-wrapper">
    <style>
    /* Carousel layout: 3 per view desktop, 1 per view on small screens */
    .cc-testimonials-wrapper {
        margin: 40px 0;
    }

    .cc-testimonials-viewport {
        overflow: hidden;
    }

    .cc-testimonials-track {
        display: flex;
        gap: 20px;
        transition: transform 420ms cubic-bezier(.22, .9, .3, 1);
        will-change: transform;
    }

    .cc-testimonial-card {
        flex: 0 0 calc(100% / 3);
        box-sizing: border-box;
        background: #f9fafb;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #0b63d3;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .cc-testimonial-photo {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid rgba(11, 99, 211, 0.08);
        background: linear-gradient(135deg, #0b63d3, #00bfa6);
    }

    .cc-testimonial-body {
        display: flex;
        flex-direction: column;
    }

    .cc-testimonial-title {
        margin: 0;
        font-size: 1rem;
        color: var(--cc-brand, #0b63d3);
    }

    .cc-testimonial-role {
        margin: 4px 0 8px;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .cc-testimonial-content {
        margin: 0;
        color: #374151;
        line-height: 1.5;
        font-style: normal;
    }

    .cc-testimonials-controls {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 14px;
    }

    .cc-testimonial-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(15, 23, 42, 0.12);
        cursor: pointer;
        border: 0;
    }

    .cc-testimonial-dot[aria-selected="true"] {
        background: var(--cc-brand, #0b63d3);
        transform: scale(1.2);
    }

    /* Responsive: single card per view on small screens */
    @media (max-width: 880px) {
        .cc-testimonial-card {
            flex: 0 0 100%;
        }
    }
    </style>

    <div class="cc-testimonials-viewport">
        <div class="cc-testimonials-track">
            <?php while ($testimonials->have_posts()) : $testimonials->the_post(); 
                $rating = get_post_meta(get_the_ID(), '_cc_testimonial_rating', true);
                $rating = $rating ? $rating : 5;
                $stars = str_repeat('★', $rating);
                $thumb = '';
                if (has_post_thumbnail()){
                    $thumb = get_the_post_thumbnail(get_the_ID(), 'thumbnail', array('class'=>'cc-testimonial-photo', 'alt'=>get_the_title()));
                } else {
                    // fallback: empty avatar wrapper
                    $thumb = '<span class="cc-testimonial-photo" aria-hidden="true"></span>';
                }
            ?>
            <div class="cc-testimonial-card">
                <?php echo $thumb; ?>
                <div class="cc-testimonial-body">
                    <h3 class="cc-testimonial-title"><?php the_title(); ?></h3>
                    <div class="cc-testimonial-rating" style="color: #e0a717ff; font-size: 24px; margin-bottom: 8px;">
                        <?php echo esc_html($stars); ?>
                    </div>
                    <div class="cc-testimonial-role">
                        <?php echo esc_html(get_post_meta(get_the_ID(), '_cc_testimonial_role', true)); ?></div>
                    <div class="cc-testimonial-content"><?php the_content(); ?></div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="cc-testimonials-controls" role="tablist" aria-label="Testimonials navigation"></div>

    <script>
    (function() {
        const root = document.currentScript ? document.currentScript.parentElement : document.querySelector(
            '.cc-testimonials-wrapper');
        if (!root) return;
        const track = root.querySelector('.cc-testimonials-track');
        const items = Array.from(track.children);
        const controls = root.querySelector('.cc-testimonials-controls');

        let isCarousel = window.innerWidth >= 880;
        let itemsPerView = isCarousel ? 3 : 1;
        let index = 0;
        let autoplay = null;

        function applyStackStyles() {
            track.style.transform = 'none';
            track.style.transition = 'none';
            track.style.display = 'block';
            track.style.gap = '0';
            items.forEach(it => {
                it.style.flex = '1 1 100%';
                it.style.marginBottom = '20px';
            });
            if (controls) controls.style.display = 'none';
        }

        function applyCarouselStyles() {
            track.style.display = 'flex';
            track.style.transition = 'transform 420ms cubic-bezier(.22, .9, .3, 1)';
            track.style.gap = '';
            items.forEach(it => {
                it.style.flex = `0 0 calc(100% / ${itemsPerView})`;
                it.style.marginBottom = '';
            });
            if (controls) controls.style.display = '';
        }

        function buildDots() {
            if (!isCarousel) {
                if (controls) controls.innerHTML = '';
                return;
            }
            controls.innerHTML = '';
            const pages = Math.max(1, Math.ceil(items.length / itemsPerView));
            for (let i = 0; i < pages; i++) {
                const btn = document.createElement('button');
                btn.className = 'cc-testimonial-dot';
                btn.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
                btn.addEventListener('click', () => {
                    goTo(i * itemsPerView);
                });
                controls.appendChild(btn);
            }
        }

        function update() {
            if (!isCarousel) return; // no sliding on small screens
            const shiftPercent = (index * (100 / itemsPerView));
            track.style.transform = `translateX(-${shiftPercent}%)`;
            updateDots();
        }

        function updateDots() {
            if (!controls) return;
            const dots = Array.from(controls.children);
            const page = Math.floor(index / itemsPerView);
            dots.forEach((d, i) => d.setAttribute('aria-selected', i === page ? 'true' : 'false'));
        }

        function goTo(i) {
            const maxIndex = Math.max(0, items.length - itemsPerView);
            index = Math.max(0, Math.min(maxIndex, i));
            update();
        }

        function next() {
            const maxIndex = Math.max(0, items.length - itemsPerView);
            index = index + itemsPerView > maxIndex ? 0 : index + itemsPerView;
            update();
        }

        function prev() {
            const maxIndex = Math.max(0, items.length - itemsPerView);
            index = index - itemsPerView < 0 ? maxIndex : index - itemsPerView;
            update();
        }

        // touch/drag for mobile - only enabled when carousel is active
        let startX = 0,
            currentX = 0,
            dragging = false;
        track.addEventListener('touchstart', (e) => {
            if (!isCarousel) return;
            startX = e.touches[0].clientX;
            dragging = true;
            track.style.transition = 'none';
        });
        track.addEventListener('touchmove', (e) => {
            if (!isCarousel || !dragging) return;
            currentX = e.touches[0].clientX;
            const dx = currentX - startX;
            track.style.transform = `translateX(calc(-${index * (100 / itemsPerView)}% + ${dx}px))`;
        });
        track.addEventListener('touchend', () => {
            if (!isCarousel) return;
            dragging = false;
            track.style.transition = 'transform 420ms cubic-bezier(.22,.9,.3,1)';
            const dx = currentX - startX;
            if (dx < -60) next();
            else if (dx > 60) prev();
            else update();
            startX = currentX = 0;
        });

        // keyboard navigation only for carousel
        root.addEventListener('keydown', (e) => {
            if (!isCarousel) return;
            if (e.key === 'ArrowLeft') prev();
            if (e.key === 'ArrowRight') next();
        });

        // resize handler
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const wasCarousel = isCarousel;
                isCarousel = window.innerWidth >= 880;
                itemsPerView = isCarousel ? 3 : 1;
                index = 0;
                if (!isCarousel) {
                    // switch to stacked layout
                    applyStackStyles();
                    if (autoplay) {
                        clearInterval(autoplay);
                        autoplay = null;
                    }
                    buildDots();
                } else {
                    // restore carousel layout
                    applyCarouselStyles();
                    buildDots();
                    update();
                    if (!autoplay) autoplay = setInterval(next, 6000);
                }
            }, 120);
        });

        // initial setup
        if (!isCarousel) {
            applyStackStyles();
        } else {
            applyCarouselStyles();
            buildDots();
            update();
            // optional autoplay
            autoplay = setInterval(next, 6000);
            root.addEventListener('mouseenter', () => clearInterval(autoplay));
            root.addEventListener('mouseleave', () => autoplay = setInterval(next, 6000));
        }
    })();
    </script>

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