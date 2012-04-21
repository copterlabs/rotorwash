<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

get_header();

if( have_posts() ):
    while( have_posts() ):
        the_post();

        previous_post_link('%link', _x('&larr;', 'Previous post link', 'rotorwash') . ' %title');
        next_post_link('%link', '%title ' . _x('&rarr;', 'Next post link', 'rotorwash'));

?>

            <article class="post">
                <h2><?php the_title(); ?></h2>

<?php 

the_content();
wp_link_pages(array('before' => '<p class="post-pagination">' . __('Pages:', 'rotorwash'), 'after' => '</p>'));

?>

                <ul class="post-meta">
                    <li><?php rw_posted_on(); ?></li>
                    <li><?php rw_posted_in(); ?></li>
                    <li><?php comments_popup_link(__('Leave a comment', 'rotorwash'), __('1 Comment', 'rotorwash'), __('% Comments', 'rotorwash')); ?></li>
                </ul>
                <?php edit_post_link(__('Edit', 'rotorwash'), '<p class="edit-link">', '</p>'); ?>

<?php

        if( get_the_author_meta('description') ):
            echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'rw_author_bio_avatar_size', 60 ) );

?>
                <h2><?php printf( esc_attr__( 'About %s', 'rotorwash' ), get_the_author() ); ?></h2>
                <?php the_author_meta( 'description' ); ?>
                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
                    <?php printf(__('View all posts by %s &rarr;', 'rotorwash'), get_the_author()); ?>
                </a>
<?php

        endif;

        previous_post_link('%link', _x('&larr;', 'Previous post link', 'rotorwash') . ' %title');
        next_post_link('%link', '%title ' . _x('&rarr;', 'Next post link', 'rotorwash'));

?>

                <?php comments_template('', TRUE); ?>

            </article><!-- end .post -->
<?php 

    endwhile;
endif;

get_sidebar();
get_footer();
