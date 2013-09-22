<?php
/**
 * RotorWash functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, rw_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'rw_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

// Creates a CONSTANT easy to grab Child Theme URL
define('CHILD_TEMPLATE_URL', get_stylesheet_directory_uri());

if (!function_exists('rw_setup')):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override rw_setup() in a child theme, add your own rw_setup to your 
 * child theme's functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 *
 * @since RotorWash 1.0
 */
function rw_setup(  )
{
    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    // This theme uses post thumbnails
    add_theme_support( 'post-thumbnails' );

    // Removes the WLW manifest and RSD links
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
        'primary' => 'Main Navigation',
        'footer'  => 'Footer Navigation',
    ));

    // Retrieves options to determine which CPT to grab
    $opts = get_option('rw_theme_settings');

    // Custom post type party!
    $custom_post_types = array();

    if (isset($opts['has_products']) && $opts['has_products']==='yes') {
        $custom_post_types[] = array(
            'singular'      => 'Product',
            'plural'        => 'Products',
            'menu_position' => 6, // Lower number means higher placement
            'supports'      => array('title'),
        );
    }

    if (isset($opts['has_services']) && $opts['has_services']==='yes') {
        $custom_post_types[] = array(
            'singular'      => 'Service',
            'plural'        => 'Services',
            'menu_position' => 7, // Lower number means higher placement
            'supports'      => array('title'),
        );
    }

    if (isset($opts['has_testimonials']) && $opts['has_testimonials']==='yes') {
        $custom_post_types[] = array(
            'singular'      => 'Testimonial',
            'plural'        => 'Testimonials',
            'menu_position' => 8, // Lower number means higher placement
            'supports'      => array('title'),
        );
    }
    
    rw_add_custom_post_types($custom_post_types);
}
endif;
add_action('after_setup_theme', 'rw_setup');

/* In an attempt to make this code easier to read, the major chunks have been broken into
 * smaller files with code pertaining only that functionality
 */

// Widgets
require_once TEMPLATEPATH . '/includes/widgets.php';

// Custom post types
require_once TEMPLATEPATH . '/includes/custom-post-types.php';

// Filters
require_once TEMPLATEPATH . '/includes/filters.php';

// Actions
require_once TEMPLATEPATH . '/includes/actions.php';

// Extra functions and miscellaneous theme code
require_once TEMPLATEPATH . '/includes/extra.php';

// Admin stuff
require_once TEMPLATEPATH . '/includes/admin.php';

$role = new RW_Role;
