<?php

if( !function_exists('rw_add_custom_post_types') ):
/**
 * Register custom post type function.
 *
 *
 * @return void
 * @since RotorWash 1.0
 * @uses register_sidebar
 */

function rw_add_custom_post_types($custom_post_types) {
      foreach ($custom_post_types as $cpt) {
            $labels = array(
                  'name'                  => _x($cpt['plural'], 'General post type descriptor'),
                  'singular_name'         => _x($cpt['singular'], 'Singular post type descriptor'),
                  'add_new'               => _x('Add New', $cpt['singular']),
                  'add_new_item'          => __('Add New '.$cpt['singular']),
                  'edit_item'             => __('Edit '.$cpt['singular']),
                  'new_item'              => __('New '.$cpt['singular']),
                  'all_items'             => __('All '.$cpt['plural']),
                  'view_item'             => __('View '.$cpt['singular']),
                  'search_items'          => __('Search '.$cpt['plural']),
                  'not_found'             => __('No '.$cpt['plural'].' found'),
                  'not_found_in_trash'    => __('No '.$cpt['plural'].' in the trash'),
                  'parent_item_colon'     => '',
                  'menu_name'             => $cpt['plural'],
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
                  'supports'              => $cpt['supports'],
            );
            // Add a register_post_type() call for each needed custom post type
            register_post_type(strtolower($cpt['plural']), $args);
      }
}
endif;
