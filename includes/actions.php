<?php
/**
 * RotorWash actions
 *
 * @package     WordPress
 * @subpackage  RotorWash
 * @since       1.0
 */

/**
 * Adds Facebook Open Graph tags to the website using the wp_head action.
 *
 * Depending on what type of post/page we're displaying, different information will
 * be used. This makes for those pretty-looking shared links in the Facebook timeline.
 * 
 * @see     http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
 * @since   1.0.2
 * @return  void
 */
function rw_add_fb_og_tags(  ) {
    // Bails if Yoast SEO / Facebook is installed to avoid duplicate OG tags
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    if (
        is_plugin_active('wordpress-seo/wp-seo.php') 
        || is_plugin_active('facebook/facebook.php')
    ) {
        return FALSE;
    }

    // Loads settings
    $opts = get_option('rw_theme_settings');

    // Avoids a warning in the Facebook URL linter
    $locale     = strtolower(get_locale());
    $site_name  = get_bloginfo('name');

    // Adds Facebook admins if present
    if (!empty($opts['fb_admins'])) {
        $fb_admins = '<meta property="fb:admins"      '
                   . 'content="'.$opts['fb_admins'].'" />' 
                   . PHP_EOL;
    } else {
        $fb_admins = NULL;
    }

    // Adds the app ID if one is supplied
    if (!empty($opts['fb_appid'])) {
        $fb_appid = '<meta property="fb:app_id"      '
                  . 'content="'.$opts['fb_appid'].'" />' 
                  . PHP_EOL;
    } else {
        $fb_appid = NULL;
    }

    // Checks for a default image set in the custom theme settings
    $def_img   = !empty($opts['default_image']) ? $opts['default_image'] : '';

    if (is_single()) {
        global $post; // Brings the post into the function scope
        if (get_the_post_thumbnail($post->ID, 'thumbnail')) {
            $thumbnail_id = get_post_thumbnail_id($post->ID, 'thumbnail');
            $thumbnail_object = get_post($thumbnail_id);
            $image = $thumbnail_object->guid;
        } else {
            $image = $def_img;
        }

        // Generates an excerpt if one doesn't exist
        if (!empty($post->post_excerpt)) {
            $excerpt = $post->post_excerpt;
        } else {
            $excerpt = apply_filters('get_the_excerpt', $post->post_content);
        }

        // Gets entry-specific info for display
        $title       = $post->post_title;
        $url         = get_permalink($post->ID);
        $type        = "article";
        $description = trim(strip_tags($excerpt));
    } else {
        // Non-blog posts/pages (home page, loops, etc.) display site info only
        $title       = $site_name;
        $url         = site_url();
        $image       = $def_img;
        $type        = "website";
        $description = get_bloginfo('description');
    }

    // Output the OG tags directly
?>

<!-- Facebook Open Graph tags -->
<meta property="og:url"         content="<?php echo $url; ?>" />
<meta property="og:type"        content="<?php echo $type; ?>" />
<meta property="og:title"       content="<?php echo $title; ?>" />
<meta property="og:locale"      content="<?php echo $locale; ?>" />
<meta property="og:image"       content="<?php echo $image; ?>" />
<meta property="og:description" content="<?php echo $description ?>" />
<meta property="og:site_name"   content="<?php echo $site_name; ?>" />
<?php echo $fb_admins,$fb_appid; ?>

<?php
}
add_action('wp_head', 'rw_add_fb_og_tags');

/**
 * Enqueues scripts and stylesheets for the theme
 * 
 * @return  void
 * @since   1.0
 */
function rw_enqueue_assets(  )
{
    // Set the location of the assets folder
    $assets_dir = get_template_directory_uri() . '/assets';

    // Registers styles
    wp_enqueue_style(
        'main',
        $assets_dir . '/css/main.css',
        FALSE,
        filemtime(get_stylesheet_directory() . '/assets/css/main.css')
    );

    // Include a local copy of jQuery
    wp_dequeue_script('jquery');
    wp_deregister_script('jquery');

    wp_register_script(
        'jquery', 
        $assets_dir . '/js/lib/jquery.js', 
        NULL, 
        '2.0.3', 
        FALSE
    );

    wp_enqueue_script('main',
        $assets_dir . '/js/main.min.js',
        array('jquery'),
        '1.0.0',
        TRUE
    );
}
add_action('wp_enqueue_scripts', 'rw_enqueue_assets');

/**
 * Outputs Google Analytics tracking code if an ID is supplied
 * 
 * @return void
 * @since 1.1
 */
function rw_add_google_analytics(  )
{
    $opts = get_option('rw_theme_settings');
    if( isset($opts['ga_id']) && !empty($opts['ga_id']) ):
        // Determines the domain name
        $domain = preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']);
?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $opts["ga_id"]; ?>', '<?php echo $domain; ?>');
  ga('send', 'pageview');

</script>
<?php
    endif;
}
add_action('wp_footer', 'rw_add_google_analytics');

/**
 * Adds the theme settings button to the dashboard
 * 
 * @return  void
 * @since   1.0.2
 */
function rw_create_menu_item(  )
{
    add_options_page(
        'Settings for ' . wp_get_theme(), 
        wp_get_theme() . ' Settings', 
        'manage_rotorwash', 
        'rotorwash_general', 
        'rw_settings_page'
    );

    add_options_page(
        'Social Settings',
        'Social',
        'manage_rotorwash',
        'rotorwash_social',
        'rw_social_page'
    );

    add_options_page(
        'Site Analytics Settings',
        'Analytics',
        'manage_rotorwash',
        'rotorwash_analytics',
        'rw_analytics_page'
    );

    register_setting('rw-theme-settings', 'rw_theme_settings', 'rw_options_validate');

    add_action( 'admin_init', 'register_custom_settings' );
    add_action( 'admin_init', 'register_social_settings' );
    add_action( 'admin_init', 'register_analytics_settings' );
}
add_action('admin_menu', 'rw_create_menu_item');

/**
 * Registers the custom settings with WordPress
 * 
 * @return  void
 * @since   1.0.2
 */
function register_custom_settings(  ) {
    // Theme Settings
    add_settings_section(
        'rw-theme-settings', 
        'Theme Settings', 
        'rw_theme_settings_text', 
        'rotorwash_general'
    );

    add_settings_field(
        'default_image', 
        'Default Image', 
        'rw_default_image', 
        'rotorwash_general', 
        'rw-theme-settings', 
        array('label_for'=>'default_image')
    );

    add_settings_field(
        'has_products', 
        'Add the Products custom post type', 
        'rw_has_products', 
        'rotorwash_general', 
        'rw-theme-settings', 
        array('label_for'=>'has_products')
    );

    add_settings_field(
        'has_services', 
        'Add the Services custom post type', 
        'rw_has_services', 
        'rotorwash_general', 
        'rw-theme-settings', 
        array('label_for'=>'has_services')
    );

    add_settings_field(
        'has_testimonials', 
        'Add the Testimonials custom post type', 
        'rw_has_testimonials', 
        'rotorwash_general', 
        'rw-theme-settings', 
        array('label_for'=>'has_testimonials')
    );

    do_action( 'custom_settings_hook' );
}

function register_social_settings(  ) {
    // Facebook Stuff
    add_settings_section(
        'rw-facebook-settings', 
        'Facebook Settings', 
        'rw_fb_settings_text', 
        'rotorwash_social'
    );

    add_settings_field(
        'fb_page_url', 
        'Facebook Page URL', 
        'rw_fb_page_url', 
        'rotorwash_social', 
        'rw-facebook-settings', 
        array('label_for'=>'fb_page_url')
    );
    add_settings_field(
        'fb_admins', 
        'Facebook Admin IDs (optional)', 
        'rw_fb_admins', 
        'rotorwash_social', 
        'rw-facebook-settings', 
        array('label_for'=>'fb_admins')
    );
    add_settings_field(
        'fb_appid', 
        'Facebook Page ID (optional)', 
        'rw_fb_appid', 
        'rotorwash_social', 
        'rw-facebook-settings', 
        array('label_for'=>'fb_appid')
    );

    do_action( 'custom_settings_hook' );
}

function register_analytics_settings(  ) {
    add_settings_section(
        'rw-analytics-settings', 
        'Google Analytics', 
        'rw_ga_settings_text', 
        'rotorwash_analytics'
    );

    add_settings_field(
        'ga_id',
        'Google Analytics Tracking ID', 
        'rw_ga_id', 
        'rotorwash_analytics', 
        'rw-analytics-settings', 
        array('label_for'=>'ga_id')
    );

    do_action('custom_settings_hook');
}

/**
 * Adds a message for the top of the theme settings
 * 
 * @return  void
 * @since   1.0.2
 */
function rw_theme_settings_text(  )
{
?>

<p>Add a default image for sharing on Facebook and other fun stuff.</p>

<?php
}

/**
 * Sets a default image for Facebook shares, Gravatars, etc.
 * 
 * @return  void
 * @since   1.0.2
 */
function rw_default_image(  )
{
    rw_upload(__FUNCTION__);
}

/**
 * Sets whether or not a products page is required
 * 
 * @return  void
 * @since   1.0.2
 */
function rw_has_products(  )
{
    rw_radio(__FUNCTION__);
}

/**
 * Sets whether or not a services page is required
 * 
 * @return  void
 * @since   1.0.2
 */
function rw_has_services(  )
{
    rw_radio(__FUNCTION__);
}

/**
 * Sets whether or not a testimonials page is required
 * 
 * @return  void
 * @since   1.0.2
 */
function rw_has_testimonials(  )
{
    rw_radio(__FUNCTION__);
}

/**
 * Shows Google Analytics section text
 * 
 * @return  void
 * @since   1.0.4
 */
function rw_ga_settings_text(  )
{
?>

<p>
    Keep track of your site traffic and stats: 
    <a href="http://www.google.com/analytics/">sign up for Google Analytics</a>
</p>
<p>Your Analytics ID will look something like this: UA-12345678-9</p>

<?php
}

/**
 * Sets the Google Analytics ID
 * 
 * @return  void
 * @since   1.0.4
 */
function rw_ga_id(  )
{
    rw_text_field(__FUNCTION__);
}

/**
 * Shows text for the Facebook settings section
 * 
 * @return  void
 * @since   1.0.2
 */
function rw_fb_settings_text(  )
{
?>

<p>
    Facebook settings. These are for likes, likes, and more likes. We 
    Like Likes!
</p>
<p>
    Copy and paste the URL to your Facebook page here, to connect the like 
    button at the bottom of your site to the page on Facebook you want 
    to promote.
</p>
<p>
    If you want access to Facebook Insights for this site, you'll need to add 
    account ID(s) for yourself and/or your admins. Use the 
    <a href="https://developers.facebook.com/tools/explorer">Graph API
    Explorer</a> to figure out your account ID. If you want to assign more than 
    one admin, separate the IDs with commas (i.e. 1234567,8901234).
</p>
<p>
    To give access to a Facebook page instead, use the Page ID field instead.
</p>

<?php
}

/**
 * Adds the Facebook Page ID field
 * 
 * @return  void
 * @since   1.0.3
 */
function rw_fb_page_url(  )
{
    rw_text_field(__FUNCTION__);
}

/**
 * Adds the Facebook Page ID field
 * 
 * @return  void
 * @since   1.0.3
 */
function rw_fb_admins(  )
{
    rw_text_field(__FUNCTION__);
}

/**
 * Adds the Facebook Page ID field
 * 
 * @return  void
 * @since   1.0.3
 */
function rw_fb_appid(  )
{
    rw_text_field(__FUNCTION__);
}

/**
 * Creates a text field for the Theme settings page
 * 
 * @return  void
 * @since   1.0.1
 */
function rw_text_field( $func )
{
    $field = str_replace('rw_', '', $func);
    $opts = get_option('rw_theme_settings');
    $value = isset($opts[$field]) ? $opts[$field] : '';
?>

<input id="<?php echo $field; ?>" 
       name="rw_theme_settings[<?php echo $field; ?>]" 
       size="40" 
       type="text" 
       value="<?php echo $value; ?>" />

<?php
}


/**
 * Creates a text field for the Theme settings page
 * 
 * @return  void
 * @since   1.0.1
 */
function rw_textarea( $func )
{
    $field = str_replace('rw_', '', $func);
    $opts = get_option('rw_theme_settings');
    $value = isset($opts[$field]) ? $opts[$field] : '';
?>

<textarea id="<?php echo $field; ?>" rows="5" cols="60" name="rw_theme_settings[<?php echo $field; ?>]"><?php echo $value; ?></textarea>

<?php
}


/**
 * Creates a checkbox for the Theme settings page
 * 
 * @return  void
 * @since   1.0.1
 */
function rw_radio( $func )
{
    $field = str_replace('rw_', '', $func);
    $opts  = get_option('rw_theme_settings');
    $yes   = (isset($opts[$field]) && $opts[$field]==='yes') ? 'checked' : '';
    $no    = $yes==='checked'  ? '' : 'checked';
?>

<label>
    <input type="radio" 
           id="<?php echo $field; ?>_on" 
           name="rw_theme_settings[<?php echo $field; ?>]" 
           value="yes" <?php echo $yes; ?> />
    Yes
</label>
<label>
    <input type="radio" 
           id="<?php echo $field; ?>_off" 
           name="rw_theme_settings[<?php echo $field; ?>]" 
           value="no" <?php echo $no; ?> />
    No
</label>

<?php
}

function rw_upload( $func ) {  
    $field = str_replace('rw_', '', $func);
    $opts = get_option('rw_theme_settings');
    $value = isset($opts[$field]) ? $opts[$field] : '';
    $preview = (!empty($value)) ? '<img src="' . $value . '" />' : NULL;
?>  

<input type="hidden" 
       id="<?php echo $field; ?>" 
       class="rotorwash-admin-upload-text"
       name="rw_theme_settings[<?php echo $field; ?>]" 
       value="<?php echo $value; ?>" />  
<input id="<?php echo $field; ?>_button" 
       type="button" 
       class="button rotorwash-admin-upload" 
       value="Upload" />
<php if (!empty($value)): ?>
<input id="<?php echo $field;?>_delete" 
       type="submit" 
       class="button" 
       name="rw_theme_settings[<?php echo $field; ?>_delete]" 
       value="Delete" />
<span class="description">Choose an image from your hard drive or the media library.</span>
<div class="rotorwash-admin-upload-preview"><?php echo $preview; ?></div>

<?php  
}  


/**
 * Loads the custom theme settings area
 * 
 * @return  void
 * @since   1.0.1
 */
function rw_settings_page(  )
{
    $page_title = "Settings for " . wp_get_theme();
    $custom_settings = 'rotorwash_general';
    require_once TEMPLATEPATH . '/assets/includes/rotorwash-settings.php';
}

function rw_social_page(  )
{
    $page_title = "Social Settings";
    $custom_settings = 'rotorwash_social';
    require_once TEMPLATEPATH . '/assets/includes/rotorwash-settings.php';
}

function rw_analytics_page(  )
{
    $page_title = "Analytics Settings";
    $custom_settings = 'rotorwash_analytics';
    require_once TEMPLATEPATH . '/assets/includes/rotorwash-settings.php';
}


function rw_admin_enqueue_scripts(  )
{
    wp_enqueue_style('rotorwash_admin_styles');

    if (get_current_screen()->id==='settings_page_rotorwash_general') {
        wp_enqueue_script('jquery');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('rw_admin_upload',
            get_template_directory_uri() . '/assets/js/admin-upload.js',
            array('jquery', 'media-upload', 'thickbox'),
            '1.0.0'
        );
    }
}
add_action('admin_enqueue_scripts', 'rw_admin_enqueue_scripts');

function replace_thickbox_text($translated_text, $text ) {  
    if ('Insert into Post'===$text) {
        $referer = strpos(wp_get_referer(), 'rotorwash_general');
        if ($referer!=='') {
            return "Use This Image";
        }
    }

    return $translated_text;
}

function rw_options_validate( $input ) {
    $opts = get_option('rw_theme_settings');
    $opts = array_merge($opts, $input);
    
    $submit = !empty($input['submit']) ? TRUE : FALSE;
    $delete_image = !empty($input['default_image_delete']) ? TRUE : FALSE;
    
    if ($submit && isset($input['default_image'])) {
        if (
            isset($opts['default_image'])
            && $opts['default_image']!==$input['default_image'] 
            && $opts['default_image']!==''
        ) {
            delete_image($opts['default_image']);
        }
    } else if ($delete_image) {
        delete_image($opts['default_image']);
        $opts['default_image'] = '';
    }
    
    return $opts;
}

function delete_image( $image_url ) {
    global $wpdb;
    
    // We need to get the image's meta ID..
    $query = "SELECT ID FROM wp_posts where guid = '" . esc_url($image_url) . "' AND post_type = 'attachment'";  
    $results = $wpdb -> get_results($query);

    // And delete them (if more than one attachment is in the Library
    foreach ($results as $row) {
        wp_delete_attachment($row->ID);
    }   
}

/**
 * Register custom styles for the admin dashboard widget
 *
 * @return void
 * @since 1.1
 */
function rw_admin_init(  )
{
    wp_register_style('rotorwash_admin_styles', get_template_directory_uri() . '/assets/css/admin.css');

    global $pagenow;
    if ('media-upload.php'===$pagenow || 'async-upload.php'===$pagenow) {
        add_filter('gettext', 'replace_thickbox_text', 1, 3);
    }
}
add_action('admin_init', 'rw_admin_init');

/**
 * Removes unnecessary dashboard home widgets
 * 
 * @return  void
 * @since   1.1
 */
function rw_update_dashboard_widgets(  )
{
    global $wp_meta_boxes;

    $widget_title = 'Helpful Links for Your New Site by Copter Labs';
    wp_add_dashboard_widget('rotorwash_dashboard', $widget_title, 'rw_add_dashboard_widget');

    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'rw_update_dashboard_widgets' );

function rw_add_dashboard_widget(  )
{
?>
<p>
    This site was built by 
    <a href="http://www.copterlabs.com/" target="_blank">Copter Labs</a>. Here 
    are some links and contact info that you may find helpful.
</p>
<h4>Support</h4>
<ul>
    <li>Email: <a href="mailto:info@copterlabs.com">info@copterlabs.com</a></li>
    <li>Phone: <a href="callto:+15033950165">+1 503-395-0165</a></li>
</ul>
<h4>Connect with Copter Labs</h4>
<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2Fcopterlabs&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35&amp;appId=121970801204701" 
        scrolling="no" frameborder="0" 
        style="border:none; overflow:hidden; width:450px; height:35px;" 
        allowTransparency="true"></iframe>
<p>
    You can find Copter Labs on 
    <a href="http://twitter.com/copterlabs" target="_blank">Twitter</a> and 
    <a href="http://facebook.com/copterlabs" target="_blank">Facebook</a>.
</p>
<h4>Referral Bonus</h4>
<p>
    Don't forget that <strong>Copter Labs pays $100 for each client you refer 
    to us.</strong> It's our way of saying thanks for spreading the word about 
    Copter Labs! If you have questions about how it works, just 
    <a href="mailto:ali.porter@copterlabs.com">ask Ali</a>.
</p>
<?php
}

function rw_progress_alert(  ) {
    if (!current_user_can('manage_rotorwash')) {
        return FALSE;
    }

    $opts = get_option('rw_theme_settings');

    if (!isset($opts['ga_id']) || empty($opts['ga_id'])):
        $nag_message = '<strong>Important:</strong> In order to track visitors to your site, <a href="' 
                     . get_bloginfo('wpurl') 
                     . '/wp-admin/options-general.php?page=rotorwash_analytics">please add your Google Analytics ID here.</a>';
?>
<script type="text/javascript">
jQuery(function($){
    $('.wrap > h2')
        .parent().prev()
        .after('<div class="update-nag"><?php echo $nag_message; ?></div>');
});
</script>
<?php
    endif;
}
add_action('admin_head','rw_progress_alert');
