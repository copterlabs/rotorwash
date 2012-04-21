<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */
?>

    </section>

    <footer id="site-credits">
        All content copyright &copy; 
        <a href="<?php echo home_url( '/' ) ?>" 
           title="<?php echo esc_attr(get_bloginfo('name', 'display')), ' &mdash; ', esc_attr(get_bloginfo( 'description' )); ?>" 
           rel="home"><?php bloginfo( 'name', 'display' ); ?></a>
        <a href="http://www.copterlabs.com/" title="Web design for the terminally rad">Web design by Copter Labs</a>
        <div class="fb-like" data-href="<?php site_url(); ?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>
    </footer>

<?php wp_footer(); ?>
</body>
</html>