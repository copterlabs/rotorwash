<?php
/**
 * RotorWash extra functions.
 *
 * @package     WordPress
 * @subpackage  RotorWash
 * @since       1.0
 */

/**
 * Outputs sharing links.
 *
 * @param   string  $permalink  The post/page permalink
 * @param   bool    $su         Whether or not to display StumbleUpon "stumble" button
 * @param   bool    $tw         Whether or not to display Twitter "tweet" button
 * @param   bool    $gp         Whether or not to display Google "+1" button
 * @param   bool    $fb         Whether or not to display Facebook "like" button
 * @return  void
 * @since   1.0.2
 */
function rw_social_sharing( $permalink=NULL, $su=TRUE, $tw=TRUE, $gp=TRUE, $fb=TRUE )
{
    if( $permalink===NULL )
    {
        trigger_error('No value supplied for <code>$permalink</code>.');
        return;
    }
?>
<span class="rw-social-sharing">

<?php if( $su ): ?>
    <!-- StumbleUpon -->
    <span class="stumble">
        <script src="http://www.stumbleupon.com/hostedbadge.php?s=2&amp;r=<?php echo $permalink; ?>"></script>
    </span>

<?php

endif;
if( $gp ):

?>
    <!-- Google +1 -->
    <div class="g-plusone" data-size="medium" data-href="<?php the_permalink(); ?>"></div>

<?php

endif;
if( $fb ):

?>
    <!-- Facebook Like Button -->
    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink()); ?>&amp;layout=button_count&amp;show_faces=true&amp;width=80&amp;action=like&amp;colorscheme=light&amp;height=21"
            scrolling="no" frameborder="0"
            style="border:none; overflow:hidden; width:80px; height:21px;"
            allowTransparency="true" class="facebook">
    </iframe>

<?php

endif;
if( $tw ):

?>
    <!-- Tweet Button -->
    <a href="http://twitter.com/share" class="twitter-share-button" 
       data-count="none" data-href="<?php echo $permalink; ?>">Tweet</a>

<?php endif; ?>
</span><!-- end .rw-social-sharing -->

<?php
}

/**
 * Displays social sharing links
 * 
 * @return      void
 * @since       1.0
 * @deprecated  1.0.2
 */
function rotor_social_sharing( $permalink=NULL, $su=TRUE, $tw=TRUE, $gp=TRUE, $fb=TRUE )
{
    trigger_error('rotor_social_sharing() is deprecated. Use rw_social_sharing() instead.');
    rw_social_sharing($permalink, $su, $tw, $gp, $fb);
}

/**
 * Outputs a PayPal donation button
 * 
 * @return  void
 * @since   1.0.2
 */
function rw_donate_button( $post_id=NULL, $options=array() )
{
    // Load options that were entered at the theme settings page
    $opts = get_option('rw_theme_settings');

    // If no PayPal email address is supplied, stop
    if( empty($opts['paypal_addr']) )
    {
        trigger_error(
                'No PayPal email address supplied. Add this on the "' 
                . get_current_theme() 
                . '" Settings page in the Dashboard.'
            );
        return;
    }

    $defaults = array(
            'title_before'  => '<h3>',
            'title_after'   => '</h3>',
            'class'         => 'paypal-donation',
            'button_image'  => 'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif',
        );

    // Check if user-supplied options were created
    $o = is_array($options) ? array_merge($defaults, $options) : $defaults;
    
    // Check for settings, supply defaults otherwise
    $paypal_title = !empty($opts['paypal_title']) ? $opts['paypal_title'] : 'Donate to ' . get_bloginfo('name');
    $paypal_currency = !empty($opts['paypal_currency']) ? $opts['paypal_currency'] : 'USD';

    // Since the item description is shown to the user on checkout, complain if it's blank
    if( empty($opts['paypal_item']) )
    {
        trigger_error(
                'No PayPal item name supplied. Add this on the "' 
                . get_current_theme() 
                . '" Settings page in the Dashboard.'
            );
        $paypal_item = 'Donation to ' . get_bloginfo('name');
    }
    else
    {
        $paypal_item = $opts['paypal_item'];
    }

?>

<!-- PayPal Donation, because pimpin' ain't free -->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" 
      class="<?php echo esc_attr($o['class']); ?>">
    <?php echo $o['title_before'], $opts['paypal_title'], $o['title_after']; ?>

    <input type="image" name="submit" border="0" 
           src="<?php echo $o['button_image']; ?>" 
           alt="PayPal - The safer, easier way to pay online" />

    <input type="hidden" name="business" value="<?php echo $opts['paypal_addr']; ?>" />
    <input type="hidden" name="cmd" value="_donations" />
    <input type="hidden" name="item_name" value="<?php echo $paypal_item; ?>" />
    <input type="hidden" name="item_number" value="<?php echo $post_id; ?>" />
    <input type="hidden" name="currency_code" value="<?php echo $paypal_currency; ?>" />
    <img alt="" border="0" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" />
</form>
<?php

}

if( !function_exists( 'rw_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @return  void
 * @since   1.0
 */
function rw_posted_on( )
{
    printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'rotorwash' ),
        'meta-prep meta-prep-author',
        sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
            get_permalink(),
            esc_attr( get_the_time() ),
            get_the_date()
        ),
        sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
            get_author_posts_url( get_the_author_meta( 'ID' ) ),
            sprintf( esc_attr__( 'View all posts by %s', 'rotorwash' ), get_the_author() ),
            get_the_author()
        )
    );
}
endif;

if( ! function_exists( 'rw_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @param   bool $show_tags Whether or not tags should be displayed (defaults to TRUE)
 * @return  void
 * @since   1.0
 */
function rw_posted_in( $show_tags=TRUE )
{
    $tag_list = get_the_tag_list( '', ', ' );

    if( $tag_list && $show_tags )
    {
        $posted_in = __( 'Category: %1$s Tags: %2$s.', 'rotorwash' );
    }
    elseif( is_object_in_taxonomy( get_post_type(), 'category' ) )
    {
        $posted_in = __( 'Category %1$s.', 'rotorwash' );
    }
    else
    {
        $posted_in = __( '', 'rotorwash' );
    }

    printf($posted_in, get_the_category_list( ', ' ), $tag_list);
}
endif;

if( !function_exists('rw_get_social_links') ):
/**
 * Outputs a list of social media links.
 * 
 * To edit these links, use the Links tab in the dashboard. Links must be 
 * categorized with "Social Links" to be retrieved. The links are ordered by 
 * rating, ascending. It's all the way at the bottom of the link editor.
 *
 * @param   string $id    An ID attribute for the element
 * @param   string $class A class attribute for the element
 * @return  void
 * @since   1.0
 */
function rw_social_links( $id="social-links", $class=NULL )
{
?>

<ul id="<?php echo $id; ?>"
    class="<?php echo $class; ?>">
<?php

$links = get_bookmarks(array('category_name' => 'Social Links', 'orderby'=>'rating'));
foreach( $links as $link ):
    $slug = strtolower(preg_replace('/[^\w-]/', '', $link->link_name));

?>
    <li class="<?php echo $slug; ?>">
        <a href="<?php echo $link->link_url; ?>" 
           data-window="new"><?php echo $link->link_name; ?></a>
    </li>
<?php endforeach; ?>

</ul>

<?php
}
endif;
