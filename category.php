<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

get_header();

?>

        <h2 class="page-title"><?php printf(__('Posts in the Category "%s"', 'rotorwash'), single_cat_title('', FALSE)); ?></h2>
<?php

$category_description = category_description();
if ( ! empty( $category_description ) )
{
    echo '' . $category_description . '';
}

/* Overwrite this in a child theme using a file called 
 * loop-category.php to style category output.
 */
get_template_part( 'loop', 'category' );
get_sidebar();
get_footer();
