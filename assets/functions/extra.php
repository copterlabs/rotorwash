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
    <div class="g-plusone" data-size="medium" data-href="<?php echo $permalink; ?>"></div>

<?php

endif;
if( $fb ):

?>
    <!-- Facebook Like Button -->
    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode($permalink); ?>&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21"
            scrolling="no" frameborder="0"
            style="border:none; overflow:hidden; width:100px; height:21px;"
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

if( !function_exists('rw_social_links') ):
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

    $social_links = get_bookmarks(array('category_name'=>'Social', 'orderby'=>'rating' ));
    
    foreach($social_links as $slink):
        $slug = strtolower(preg_replace('/[^\w-]/', '', $slink->link_name));
        if( isset($slink->link_image) )
        {
            $link = '<img src="' . $slink->link_image . '" alt="' . $slink->link_name . '" />';
        }
        else
        {
            $link = $slink->link_name;
        }
?>
    <li class="<?php echo $slug; ?>">
        <a href="<?php echo $slink->link_url; ?>" 
           title="<?php echo $slink->link_name; ?>"><?php echo $link; ?></a>
    </li>
<?php endforeach; ?>

</ul><!-- end .<?php echo $class; ?> -->

<?php
}
endif;
