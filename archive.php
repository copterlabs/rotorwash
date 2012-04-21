<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

get_header();

/* Grab the first post to determine what type of 
 * post is being displayed.
 */
if ( have_posts() )
{
    the_post();
}

?>

        <h2 class="page-title"><?php   if( is_post_type_archive() )
                    {
                        post_type_archive_title();
                    }
                    else
                    {
                        if ( is_day() )
                        {
                            printf( __( 'Daily Archives: %s', 'rotorwash' ), get_the_date() );
                        }
                        elseif( is_month() )
                        {
                            printf( __( 'Monthly Archives: %s', 'rotorwash' ), get_the_date('F Y') );
                        }
                        elseif ( is_year() )
                        {
                            printf( __( 'Yearly Archives: %s', 'rotorwash' ), get_the_date('Y') );
                        }
                        else
                        {
                            _e( 'Blog Archives', 'rotorwash' );
                        }
                    } ?></h2>

<?php

/* Since we called the_post() above, we need to
 * rewind the loop.
 */
rewind_posts();

/* Overwrite this in a child theme using a file called 
 * loop-archives.php to add your own styles to the posts.
 */
get_template_part( 'loop', 'archive' );
get_sidebar();
get_footer();
