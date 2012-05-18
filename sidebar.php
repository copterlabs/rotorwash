<?php
/**
 * The Sidebar containing the primary widget area.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */
?>

<aside id="sidebar">
    <ul>
<?php if( !dynamic_sidebar('primary-widget-area') ): ?>
        <li>
            <h3><?php _e( 'Archives', 'rotorwash' ); ?></h3>
            <ul>
                <?php wp_get_archives( 'type=monthly' ); ?>
            </ul>
        </li>
<?php endif; // end primary widget area ?>
    </ul>
</aside>
