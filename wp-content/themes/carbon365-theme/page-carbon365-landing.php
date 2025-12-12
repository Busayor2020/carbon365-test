<?php
/**
 * Template Name: Carbon365 Landing Page
 * Description: Custom landing page with latest posts and CTA buttons
 */

get_header(); ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?> | <?php the_title(); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="landing-container">
    
    <!-- Hero Section -->
    <section class="hero-section">
        <h1>Welcome to Carbon365</h1>
        <p>Your trusted partner for sustainable solutions and innovative services</p>
        
        <!-- Call to Action Buttons -->
        <div class="cta-buttons">
            <a href="tel:+2348012345678" class="btn btn-call">
                📞 Call Us Now
            </a>
            <a href="https://wa.me/2348012345678?text=Hello%20Carbon365" target="_blank" class="btn btn-whatsapp">
                💬 WhatsApp Us
            </a>
        </div>
    </section>

    <!-- Latest Posts Section -->
    <section class="posts-section">
        <h2>Latest Updates</h2>
        
        <div class="posts-grid">
            <?php
            // Custom Query for Latest 5 Posts
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 5,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'post_status'    => 'publish'
            );
            
            $latest_posts = new WP_Query($args);
            
            if ($latest_posts->have_posts()) :
                while ($latest_posts->have_posts()) : $latest_posts->the_post();
            ?>
                
                <article class="post-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('medium'); ?>" 
                             alt="<?php the_title(); ?>" 
                             class="post-thumbnail">
                    <?php else : ?>
                        <img src="https://via.placeholder.com/400x200/667eea/ffffff?text=<?php echo urlencode(get_the_title()); ?>" 
                             alt="<?php the_title(); ?>" 
                             class="post-thumbnail">
                    <?php endif; ?>
                    
                    <div class="post-content">
                        <h3 class="post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <p class="post-excerpt">
                            <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                        </p>
                        
                        <div class="post-meta">
                            <span>📅 <?php echo get_the_date(); ?></span>
                            <span> | 👤 <?php the_author(); ?></span>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="read-more">
                            Read More →
                        </a>
                    </div>
                </article>
                
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <p>No posts found. Please add some posts to see them here.</p>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <h2>What Our Clients Say</h2>
        <?php echo do_shortcode('[cc_testimonials]'); ?>
    </section>

</div>

<?php wp_footer(); ?>
</body>
</html>