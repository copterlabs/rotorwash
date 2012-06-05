<?php
/**
 * RotorWash actions
 *
 * @package     WordPress
 * @subpackage  RotorWash
 * @since       1.0
 */

/**
 * Adds Facebook root to the footer of the site.
 * 
 * @return  void
 * @since   1.0.2
 */
function rw_add_fb_root(  )
{
    $opts = get_option('rw_theme_settings');
    $appid = isset($opts['fb_appid']) ? '&appId=' . $opts['fb_appid'] : NULL;
?>

<!-- Initializes Facebook ("Like" buttons and such) -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?php echo $appid; ?>";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php
}
add_action('wp_footer', 'rw_add_fb_root');

/**
 * Adds Google +1 button to the footer of the site
 * 
 * @return  void
 * @since   1.0.5
 */
function rw_add_gplus_root(  )
{
?>

<!-- Initializes Google +1 Buttons -->
<script>(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();</script>

<?php
}
add_action('wp_footer', 'rw_add_gplus_root');

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
function rw_add_fb_og_tags()
{
    $opts = get_option('rw_theme_settings');

    $locale     = strtolower(get_locale()); // This avoids a warning in the Facebook URL linter
    $site_name  = get_bloginfo('name'); // Loads the name of the website

    if( !empty($opts['fb_admins']) )
    {
        $fb_admins = '<meta property="fb:admins"      content="'.$opts['fb_admins'].'" />' . PHP_EOL;
    }
    else
    {
        $fb_admins = NULL;
    }

    if( !empty($opts['fb_appid']) )
    {
        $fb_appid = '<meta property="fb:app_id"      content="'.$opts['fb_appid'].'" />' . PHP_EOL;
    }
    else
    {
        $fb_appid = NULL;
    }

    // Checks for a default image set in the custom theme settings
    $def_img   = !empty($opts['default_image']) ? $opts['default_image'] : '';

    if( is_single() )
    {
        global $post; // Brings the post into the function scope
        if( get_the_post_thumbnail($post->ID, 'thumbnail') )
        {
            $thumbnail_id = get_post_thumbnail_id($post->ID, 'thumbnail');
            $thumbnail_object = get_post($thumbnail_id);
            $image = $thumbnail_object->guid;
        }
        else
        {
            $image = $def_img;
        }

        $excerpt = !empty($post->post_excerpt) ? $post->post_excerpt : apply_filters('get_the_excerpt', $post->post_content);

        // Gets entry-specific info for display
        $title       = $post->post_title;
        $url         = get_permalink($post->ID);
        $type        = "article";
        $description = trim(strip_tags($excerpt));
    }
    else
    {
        // For non-blog posts (pages, home page, etc.), we display website info only
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
 * Enqueues scripts for the theme - dropped the shiv right in there, for older Mozilla / Safari as well.
 * 
 * @return  void
 * @since   1.0
 */
function rw_enqueue_scripts(  )
{
    wp_enqueue_script('jquery');

    wp_enqueue_script(
        'html5shiv',
        'http://html5shiv.googlecode.com/svn/trunk/html5.js'
    );

    wp_enqueue_script(
        'hoverIntent',
        get_bloginfo('template_url') . "/assets/js/hoverIntent.js",
        array('jquery')
    );

    wp_enqueue_script(
        'dropdown',
        get_bloginfo('template_url') . "/assets/js/jquery.dropdown.js",
        array('hoverIntent')
    );

    wp_register_script('twitter_widgets', 'http://platform.twitter.com/widgets.js', NULL, FALSE, TRUE);
    wp_enqueue_script('twitter_widgets');
}
add_action('wp_enqueue_scripts', 'rw_enqueue_scripts');

/**
 * Outputs Google Analytics tracking code if an ID is supplied
 * 
 * @return void
 * @since 1.1
 */
function rw_add_google_analytics(  )
{
    $opts = get_option('rw_theme_settings');
    if( isset($opts['ga_id']) ):
?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $opts['ga_id']; ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

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
    $page_title = 'Settings for ' . wp_get_theme();
    $btn_text = wp_get_theme() . ' Settings';
    $btn_icon = get_bloginfo('template_directory') . '/assets/images/copter-wp-admin-icon.png';
    add_menu_page($page_title, $btn_text, 'administrator', __FILE__, 'rw_settings_page', $btn_icon);

    add_action( 'admin_init', 'register_custom_settings' );
}
add_action('admin_menu', 'rw_create_menu_item');

/**
 * Registers the custom settings with WordPress
 * 
 * @return  void
 * @since   1.0.2
 */
function register_custom_settings() 
{

    register_setting('rw-theme-settings', 'rw_theme_settings');

    // Theme Settings
    add_settings_section('rw-theme-settings', 'Theme Settings', 'rw_theme_settings_text', 'rw-theme-settings');
    add_settings_field( 'default_image', 'Default Image', 'rw_default_image', 'rw-theme-settings', 'rw-theme-settings', array('label_for'=>'default_image'));

    // Google Analytics
    add_settings_section('rw-analytics-settings', 'Site Stats Settings', 'rw_ga_settings_text', 'rw-theme-settings');
    add_settings_field( 'ga_id', 'Google Analytics ID', 'rw_ga_id', 'rw-theme-settings', 'rw-analytics-settings', array('label_for'=>'ga_id'));

    // Facebook Stuff
    add_settings_section('rw-facebook-settings', 'Facebook Settings', 'rw_fb_settings_text', 'rw-theme-settings');
    add_settings_field( 'fb_page_url', 'Facebook Page URL', 'rw_fb_page_url', 'rw-theme-settings', 'rw-facebook-settings', array('label_for'=>'fb_page_url'));
    add_settings_field( 'fb_admins', 'Facebook Admin IDs (optional)', 'rw_fb_admins', 'rw-theme-settings', 'rw-facebook-settings', array('label_for'=>'fb_admins'));
    add_settings_field( 'fb_appid', 'Facebook Page ID (optional)', 'rw_fb_appid', 'rw-theme-settings', 'rw-facebook-settings', array('label_for'=>'fb_appid'));

    do_action( 'custom_settings_hook' );
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
    rw_text_field(__FUNCTION__);
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
 * Loads the custom theme settings area
 * 
 * @return  void
 * @since   1.0.1
 */
function rw_settings_page(  )
{
    require_once TEMPLATEPATH . '/assets/includes/rotorwash-settings.php';
}


function rw_admin_enqueue_scripts(  )
{
    wp_enqueue_style('rotorwash_admin_styles');
}
add_action('admin_enqueue_scripts', 'rw_admin_enqueue_scripts');

/**
 * Register custom styles for the admin dashboard widget
 *
 * @return void
 * @since 1.1
 */
function rw_admin_init(  )
{
    wp_register_style('rotorwash_admin_styles', get_template_directory_uri() . '/assets/styles/admin.css');
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
