<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

    $opts = get_option('rw_theme_settings');
    if( !empty($opts['fb_page_url']) ) {
    	$data_href = $opts['fb_page_url'];
    } else {
    	$data_href = get_site_url();
    }
?>

    </section><!--/#main-content-->

    <footer id="site-credits" class="row-fluid">
        <small>
            All content copyright &copy; 
            <a href="<?php echo home_url( '/' ) ?>" 
               title="<?php echo esc_attr(get_bloginfo('name', 'display')), ' &mdash; ', esc_attr(get_bloginfo( 'description' )); ?>" 
               rel="home"><?php bloginfo( 'name', 'display' ); ?></a>
        </small>
        <small class="go-right">
            <a href="http://www.copterlabs.com/" title="Web design for the terminally rad">Web design by Copter Labs</a>
        </smalL>
    </footer>

<?php wp_footer(); ?>
</body>
</html>
