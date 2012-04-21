<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

get_header(); ?>

        <article class="post">
            <h2><?php _e( 'That Page Doesn\'t Exist! (404)', 'rotorwash' ); ?></h2>
            <p><?php _e( 'Bummer. Looks like the URL you requested doesn\'t exist. Maybe try a search?', 'rotorwash' ); ?></p>

            <?php get_search_form(); ?>


            <script type="text/javascript">
                // focus on search field after it has loaded
                document.getElementById('s') && document.getElementById('s').focus();
            </script>
        </article>

<?php get_footer();
