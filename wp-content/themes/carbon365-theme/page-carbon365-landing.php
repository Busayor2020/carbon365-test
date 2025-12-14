<?php
/**
 * Template Name: Carbon365 Landing Page
 * Description: Custom landing page with latest posts and CTA buttons
 */

get_header(); ?>

<!-- Hero Carousel -->
<section class="hero-carousel" aria-label="Primary content carousel">
    <div class="hc-viewport" tabindex="0">
        <div class="hc-track" role="list">
            <article class="hc-slide" role="listitem" aria-roledescription="slide" aria-label="Slide 1 of 2"
                data-index="0"
                style="background-image:url('http://carbon365busayo.com/wp-content/uploads/2025/12/hero_img_blue.jpg')">
                <div class="hc-overlay"></div>
                <div class="hc-content">
                    <h1>Reliable Auto Repair & Maintenance</h1>
                    <p>Expert technicians, transparent pricing — get back on the road confidently.</p>
                    <div class="hc-ctas">
                        <a class="btn-cta btn-call" href="tel:+2349138518812">📞 Call Us Now</a>
                        <a class="btn-cta btn-whatsapp" href="https://wa.me/2349138518812?text=Hello%20Carbon365"
                            target="_blank" rel="noopener">💬 WhatsApp Us</a>
                    </div>
                </div>
            </article>
            <article class="hc-slide" role="listitem" aria-roledescription="slide" aria-label="Slide 2 of 2"
                data-index="1"
                style="background-image:url('https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1600&q=80')">
                <div class="hc-overlay"></div>
                <div class="hc-content">
                    <h1>Fast, Professional Car Servicing</h1>
                    <p>From diagnostics to full service — trusted care for every make and model.</p>
                    <div class="hc-ctas">
                        <a class="btn-cta btn-call" href="tel:+2349138518812">📞 Call Us Now</a>
                        <a class="btn-cta btn-whatsapp" href="https://wa.me/2349138518812?text=Hello%20Carbon365"
                            target="_blank" rel="noopener">💬 WhatsApp Us</a>
                    </div>
                </div>
            </article>
        </div>
    </div>
    <button class="hc-prev" aria-label="Previous slide">&larr;</button>
    <button class="hc-next" aria-label="Next slide">&rarr;</button>
    <div class="hc-dots" role="tablist" aria-label="Slide navigation">
        <button class="hc-dot" role="tab" aria-selected="true" aria-controls="slide-0" data-index="0"></button>
        <button class="hc-dot" role="tab" aria-selected="false" aria-controls="slide-1" data-index="1"></button>
    </div>
</section>

<div class="landing-container">

    <!-- Latest Posts Section (Carousel on large screens) -->
    <section id="latest-posts" class="posts-section">
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
                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>"
                    class="post-thumbnail">
                <?php else : ?>
                <img src="https://via.placeholder.com/400x200/667eea/ffffff?text=<?php echo urlencode(get_the_title()); ?>"
                    alt="<?php the_title(); ?>" class="post-thumbnail">
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
    <section id="testimonials" class="testimonials-section">
        <h2>What Our Clients Say</h2>
        <?php echo do_shortcode('[cc_testimonials]'); ?>
    </section>

</div>

<?php get_footer(); ?>