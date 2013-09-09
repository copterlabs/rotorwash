<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to rw_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

if( post_password_required() ):

?>
            <p><?php _e('This post is password protected. Enter the password to view any comments.', 'rotorwash'); ?></p>
<?php

    return;
endif;
    
if( have_comments() ):

?>
            <h3 id="comments-title"><?php
            printf(
                    _n('One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'rotorwash'),
                    number_format_i18n(get_comments_number()), 
                    get_the_title()
                );
            ?></h3>

<?php

    if (get_comment_pages_count()>1 && get_option('page_comments')) {
        previous_comments_link('&larr; Older Comments');
        next_comments_link('Newer Comments &rarr;');
    }

?>

            <ol id="post-comments">
                <?php
                    wp_list_comments();
                ?>
            </ol>

<?php

    if( get_comment_pages_count()>1 && get_option('page_comments') ):
        previous_comments_link(__('&larr; Older Comments', 'rotorwash'));
        next_comments_link(__('Newer Comments &rarr;', 'rotorwash'));
    endif;

endif;

comment_form();
