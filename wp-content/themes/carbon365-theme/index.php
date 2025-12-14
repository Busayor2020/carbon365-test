<?php
/**
 * Main template file for Carbon365 Theme
 * Minimal index.php so WordPress recognizes the theme as valid.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url( get_stylesheet_uri() ); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <main id="main" class="site-main">
        <?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        the_title( '<h1 class="entry-title">', '</h1>' );
        the_content();
    endwhile;
else :
    echo '<p>No content found.</p>';
endif;
?>
    </main>

    <?php get_footer(); ?>