<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and the header
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

    <meta charset="<?php bloginfo('charset'); ?>" />
    <title><?php wp_title(); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" 
          href="<?php bloginfo('stylesheet_url'); echo '?' . filemtime(get_stylesheet_directory() . '/style.css'); ?>" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
    // not using disqus? uncomment the following
    /*
    	if ( is_singular() && get_option( 'thread_comments' ) )
    		wp_enqueue_script( 'comment-reply' );
    */

    wp_head();
?>
</head>

<body <?php body_class(); ?>>

    <header>

        <h1>
            <a href="<?php echo home_url( '/' ); ?>" 
               title="<?php echo esc_attr(get_bloginfo('name', 'display')), ' &mdash; ', esc_attr(get_bloginfo( 'description' )); ?>" 
               rel="home"><?php bloginfo( 'name' ); ?></a>
        </h1>

        <nav id="access" role="navigation">
            <?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
        </nav>

    </header>

    <section id="rw-content-wrapper">

