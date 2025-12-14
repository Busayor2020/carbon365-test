<?php
/**
 * Theme header - Carbon365
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="site-header" role="banner">
        <div class="header-inner">
            <a class="site-logo" href="<?php echo esc_url( home_url('/') ); ?>" aria-label="<?php bloginfo('name'); ?>">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Carbon-BG.png' ); ?>"
                    alt="<?php bloginfo('name'); ?>">
            </a>

            <button class="nav-toggle" aria-controls="site-navigation" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <nav id="site-navigation" class="main-nav" role="navigation" aria-label="Primary menu">
                <?php
        if ( has_nav_menu( 'primary' ) ) {
          wp_nav_menu( array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'menu',
            'depth'          => 2,
            'fallback_cb'    => false,
          ) );
        } else {
      ?>
                <ul class="menu">
                    <li class="menu-item"><a href="<?php echo esc_url( home_url('/') ); ?>">Home</a></li>
                    <li class="menu-item"><a href="#latest-posts">Latest Posts</a></li>
                    <li class="menu-item"><a href="#testimonials">Testimonial</a></li>
                </ul>
                <?php } ?>
            </nav>

            <div class="cta-wrap">
                <a class="btn-cta" href="<?php echo esc_url( home_url('/contact') ); ?>">Get a Quote</a>
            </div>
        </div>
    </header>
    <?php
/* header.php end */
?>