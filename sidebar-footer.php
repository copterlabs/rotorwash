<?php
/**
 * The Footer widget areas.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */

// Make sure there are active widgets first.
if( !is_active_sidebar('footer-widget-area') )
{
	return;
}

if( is_active_sidebar( 'footer-widget-area' ) ):

?>
            <ul class="rotor-widgets footer">
                <?php dynamic_sidebar('footer-widget-area'); ?>
            </ul>
<?php

endif;
