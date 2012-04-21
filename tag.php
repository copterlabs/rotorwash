<?php
/**
 * The template for displaying Tag Archive pages.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

get_header();

?>

        <h2 class="page-title"><?php printf(__('Posts Tagged with "%s"', 'rotorwash'), single_tag_title('', FALSE)); ?></h2>
<?php

$category_description = category_description();
if ( ! empty( $category_description ) )
{
    echo '' . $category_description . '';
}

/* Overwrite this in a child theme using a file called 
 * loop-tag.php to style tag output.
 */
get_template_part( 'loop', 'tag' );
get_sidebar();
get_footer();
