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

    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php
    	/*
    	 * Print the <title> tag based on what is being viewed.
    	 * We filter the output of wp_title() a bit -- see
    	 * rw_filter_wp_title() in functions.php.
    	 */
    	wp_title( '&rsaquo;', true, 'right' );
    
    	?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* If not using Disqus, uncomment this code snippet
	 * to support sites with threaded comments (when in use).
	 */
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
            <a href="#content" class="skip-to-content" 
               title="<?php esc_attr_e( 'Skip to content', 'rotorwash' ); ?>"><?php _e( 'Skip to content', 'rotorwash' ); ?></a>
    		<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
    	</nav><!-- #access -->

    </header>

    <section id="content">
