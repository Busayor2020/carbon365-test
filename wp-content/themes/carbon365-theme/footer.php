<?php
/**
 * Theme Footer
 */
?>
<footer class="site-footer" aria-labelledby="footer-heading">
    <div class="container footer-grid">
        <div class="footer-col footer-left">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="footer-logo" aria-label="Home">
                <?php /* Use theme directory for image assets; get_template_directory_uri() ensures correct path */ ?>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Carbon-BG.png' ); ?>"
                    alt="Carbon365 logo" width="160" height="48" loading="lazy">
            </a>
            <p class="footer-desc">We are the authorized distributors of Dunlop and Michelin tires, specializing in
                high-performance options ideal for African roads. Our expert services include wheel alignment,
                balancing, car servicing, repairs, and premium detailing, ensuring complete vehicle care.</p>
        </div>

        <nav class="footer-col footer-middle" aria-label="Services">
            <h3 class="footer-heading">Services</h3>
            <ul class="footer-links">
                <li><a href="<?php echo esc_url( home_url('/services/cloud') ); ?>">Tyre Installation</a></li>
                <li><a href="<?php echo esc_url( home_url('/services/migration') ); ?>">Brake Service</a></li>
                <li><a href="<?php echo esc_url( home_url('/services/devops') ); ?>">Wheel Balancing and Alignment</a>
                </li>
                <li><a href="<?php echo esc_url( home_url('/services/support') ); ?>">Suspension and shock repair</a>
                <li><a href="<?php echo esc_url( home_url('/services/support') ); ?>">Car detailing</a>
                <li><a href="<?php echo esc_url( home_url('/services/support') ); ?>">Ceramic Coating</a>
                <li><a href="<?php echo esc_url( home_url('/services/support') ); ?>">AC Repair</a>
                </li>
            </ul>
        </nav>

        <div class="footer-col footer-right" aria-label="Contact">
            <h3 class="footer-heading">Contact</h3>
            <address class="contact-info">
                <div>Located in Victoria</div>
                <div>Island, Lekki, Jakande -Lagos</div>
                <div>Phone: <a href="tel:+234 913 851 8812">+234 913 851 8812</a></div>
            </address>

            <form class="subscribe-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post"
                novalidate>
                <input type="hidden" name="action" value="footer_subscribe">
                <label for="footer-email" class="visually-hidden">Email address</label>
                <div class="subscribe-row">
                    <input id="footer-email" name="email" type="email" placeholder="Your email" required>
                    <button type="submit" class="btn-accent">Subscribe</button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer-bottom">
        <small>© <span id="footer-year"></span> Carbon365 — All rights reserved.</small>
    </div>

    <?php wp_footer(); ?>
</footer>

</body>

</html>