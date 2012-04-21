<?php

if( !function_exists('rw_add_custom_post_types') ):
/**
 * Register custom post types.
 *
 * To override rw_add_custom_post_types() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @return void
 * @since RotorWash 1.0
 * @uses register_sidebar
 */
function rw_add_custom_post_types()
{
    // Add a register_post_type() call for each needed custom post type
    $labels = array(
            'name'                  => _x('Slides', 'General post type descriptor'),
            'singular_name'         => _x('Slide', 'Singular post type descriptor'),
            'add_new'               => _x('Add New', 'slide'),
            'add_new_item'          => __('Add New Slide'),
            'edit_item'             => __('Edit Slide'),
            'new_item'              => __('New Slide'),
            'all_items'             => __('All Slides'),
            'view_item'             => __('View Slide'),
            'search_items'          => __('Search Slides'),
            'not_found'             => __('No slides found'),
            'not_found_in_trash'    => __('No slides in the trash'),
            'parent_item_colon'     => '',
            'menu_name'             => 'Slides',
        );
    $args = array(
            'labels'                => $labels,
            'public'                => TRUE,
            'publicly_queryable'    => TRUE,
            'show_ui'               => TRUE,
            'show_in_menu'          => TRUE,
            'query_var'             => TRUE,
            'rewrite'               => TRUE,
            'capability_type'       => 'post',
            'has_archive'           => TRUE,
            'hierarchical'          => FALSE,
            'menu_position'         => NULL,
            'supports'              => array('title', 'thumbnail'),
            
        );

    register_post_type('slides', $args);
}
endif;
add_action('init', 'rw_add_custom_post_types');
