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

    if( get_comment_pages_count()>1 && get_option('page_comments') )
    {
        previous_comments_link(__('&larr; Older Comments', 'rotorwash'));
        next_comments_link(__('Newer Comments &rarr;', 'rotorwash'));
    }

?>

            <ol>
                <?php
                    // See rw_comment() in rotorwash/functions.php for more.
                    wp_list_comments(array('callback' => 'rw_comment'));
                ?>
            </ol>

<?php

    if( get_comment_pages_count()>1 && get_option('page_comments') ):
        previous_comments_link(__('&larr; Older Comments', 'rotorwash'));
        next_comments_link(__('Newer Comments &rarr;', 'rotorwash'));
    endif;

else: // if no comments have been posted

    if( !comments_open() ):

?>
            <p><?php _e('Comments are closed.', 'rotorwash'); ?></p>
<?php

    endif;
endif;

comment_form();
