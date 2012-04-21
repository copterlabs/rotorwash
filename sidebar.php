<?php
/**
 * The Sidebar containing the primary widget area.
 *
 * @package WordPress
 * @subpackage RotorWash
 * @since RotorWash 1.0
 */
?>

        <aside>
            <ul class="rotor-widgets sidebar">

<?php

// Provide some defaults in case there are no widgets active.
if( !dynamic_sidebar('primary-widget-area') ):

?>

                <li>
                    <?php get_search_form(); ?>

                </li>

                <li>
                    <h3><?php _e( 'Archives', 'rotorwash' ); ?></h3>
                    <ul>
                        <?php wp_get_archives( 'type=monthly' ); ?>

                    </ul>
                </li>

<?php endif; // end primary widget area ?>
            </ul>
        </aside>
