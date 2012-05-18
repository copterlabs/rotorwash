<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

/* Display navigation to next/previous pages when applicable */
if( $wp_query->max_num_pages > 1 ):

?>
        <div class="pagination">
            <div class="next"><?php next_posts_link(__('&larr; Older posts', 'rotorwash')); ?></div>
            <div class="prev"><?php previous_posts_link(__('Newer posts &rarr;', 'rotorwash')); ?></div>
        </div>

<?php

endif;

/* If there are no posts to display, such as an empty archive page */
if( !have_posts() ):

?>

    <article class="post">
        <h2><?php _e( 'Not Found', 'rotorwash' ); ?></h2>
        <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'rotorwash' ); ?></p>
        <?php get_search_form(); ?>
    </article>

<?php

endif;


/* Start the Loop */
while( have_posts() ):
    the_post();

?>

        <article class="post">

            <h2>
                <a href="<?php the_permalink(); ?>" 
                   title="<?php printf( esc_attr__( 'Permalink to %s', 'rotorwash' ), the_title_attribute( 'echo=0' ) ); ?>" 
                   rel="bookmark"><?php the_title(); ?></a>
            </h2>

            <?php the_excerpt(); ?>

            <? if (get_post_type() == 'post'): ?>

                <ul class="post-meta">
                    <li><?php rw_posted_on(); ?></li>
                    <li><?php rw_posted_in(); ?></li>
                    <li><?php comments_popup_link(__('Leave a comment', 'rotorwash'), __('1 Comment', 'rotorwash'), __('% Comments', 'rotorwash')); ?></li>
                </ul>

            <? endif; ?>

            <?php comments_template('', TRUE); ?>

        </article>

<?php

endwhile;

/* Display navigation to next/previous pages when applicable */
if( $wp_query->max_num_pages > 1 ):

?>
        <div class="pagination">
            <div class="next"><?php next_posts_link(__('&larr; Older posts', 'rotorwash')); ?></div>
            <div class="prev"><?php previous_posts_link(__('Newer posts &rarr;', 'rotorwash')); ?></div>
        </div>

<?php

endif;

