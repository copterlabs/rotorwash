<?php

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override rw_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the after_setup_theme hook.
 *
 * @return void
 * @since RotorWash 1.0
 * @uses register_sidebar
 */
function rw_widgets_init() {
    // Located at the top of the sidebar.
    register_sidebar( array(
        'name' => __( 'Sidebar', 'rotorwash' ),
        'id' => 'primary-widget-area',
        'description' => __( 'The primary widget area', 'rotorwash' ),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
}
add_action('widgets_init', 'rw_widgets_init', 10);
