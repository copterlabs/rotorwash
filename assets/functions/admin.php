<?php

/**
 * Custom class for creating the "Developer" role
 */
class RW_Role
{

    /**
     * All WordPress caps go here, plus a new one for managing RotorWash
     * @var array
     */
    private static $_default_caps = array(
        'activate_plugins' => TRUE,
        'delete_others_pages' => TRUE,
        'delete_others_posts' => TRUE,
        'delete_pages' => TRUE,
        'delete_plugins' => TRUE,
        'delete_posts' => TRUE,
        'delete_private_pages' => TRUE,
        'delete_private_posts' => TRUE,
        'delete_published_pages' => TRUE,
        'delete_published_posts' => TRUE,
        'edit_dashboard' => TRUE,
        'edit_files' => TRUE,
        'edit_others_pages' => TRUE,
        'edit_others_posts' => TRUE,
        'edit_pages' => TRUE,
        'edit_posts' => TRUE,
        'edit_private_pages' => TRUE,
        'edit_private_posts' => TRUE,
        'edit_published_pages' => TRUE,
        'edit_published_posts' => TRUE,
        'edit_theme_options' => TRUE,
        'export' => TRUE,
        'import' => TRUE,
        'list_users' => TRUE,
        'manage_categories' => TRUE,
        'manage_links' => TRUE,
        'manage_options' => TRUE,
        'moderate_comments' => TRUE,
        'promote_users' => TRUE,
        'publish_pages' => TRUE,
        'publish_posts' => TRUE,
        'read_private_pages' => TRUE,
        'read_private_posts' => TRUE,
        'read' => TRUE,
        'remove_users' => TRUE,
        'switch_themes' => TRUE,
        'upload_files' => TRUE,
        'create_product' => TRUE,
        'update_core' => TRUE,
        'update_plugins' => TRUE,
        'update_themes' => TRUE,
        'install_plugins' => TRUE,
        'install_themes' => TRUE,
        'delete_themes' => TRUE,
        'edit_plugins' => TRUE,
        'edit_themes' => TRUE,
        'edit_users' => TRUE,
        'create_users' => TRUE,
        'delete_users' => TRUE,
        'unfiltered_html' => TRUE,
        'manage_rotorwash' => TRUE,
    );

    public function __construct(  ) {
        if (!get_role('developer')) {
            self::add_developer_role();
        }
    }

    public static function add_developer_role(  ) {
        add_role('developer', 'Developer', self::$_default_caps);
    }

}
