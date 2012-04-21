<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

get_header();

if( have_posts() ):

?>
        <h2 class="page-title"><?php printf(__('Search Results for "%s"', 'rotorwash'), get_search_query()); ?></h2>

<?php

    /* Overwrite this in a child theme using a file called 
     * loop-search.php to style search results.
     */
    get_template_part( 'loop', 'search' );

else:

?>
        <h2><?php printf(_e( 'No Results for "%s"', 'rotorwash' ), get_search_query()); ?></h2>
        <p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'rotorwash' ); ?></p>

<?php

    get_search_form();
endif;

get_sidebar();
get_footer();
