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

// Stores the location of assets
$assets_dir = get_stylesheet_directory_uri() . '/assets';

// Loads theme-specific settings
$opts = get_option('rw_theme_settings');

// Grabs the theme logo if one is set
$logo_src = !isset($opts['default_image']) ? 'http://placekitten.com/200/200' : $opts['default_image'];

// Builds a tagline
$site_tag = get_bloginfo('name') . '&mdash;' . get_bloginfo('description');

// Determines the page or post slug
$slug = NULL;
$post_obj = $wp_query->get_queried_object();
if (is_object($post_obj)) {
    // Get the post slug
    if (property_exists($post_obj, 'post_name')) {
        $slug = $post_obj->post_name;
    }

    // Checks for blog posts
    if (
        (property_exists($post_obj, 'post_type')
        && $post_obj->post_type==='post')
        || is_category() 
        || is_author()
    ) {
        $slug = 'blog';
    }
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title><?php wp_title('&raquo;', TRUE, 'right'); ?></title>

<?php wp_head(); ?>

<!-- For < IE9 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="<?php echo $assets_dir; ?>/js/html5shiv.js"></script>
<![endif]-->

</head>

<body>

<header>
    <a href="<?=home_url('/')?>" 
       title="<?=$site_tag?>" 
       rel="home">
        <img src="<?=$logo_src?>" 
             alt="<?bloginfo('name', 'display')?>" />
    </a>
</header>

<nav id="access" role="navigation">
    <?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
</nav>

<section id="main-content" class="<?=$slug?>">
