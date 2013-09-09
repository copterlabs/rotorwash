<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2><?php echo $page_title; ?></h2>

<form method="post" action="options.php" class="rotorwash-admin-form">
<?php
    settings_fields('rw-theme-settings');
    do_settings_sections($custom_settings);
?>

    <p class="submit">
        <input type="submit" 
               id="rotorwash-settings-submit" 
               class="button-primary" 
               name="rw_theme_settings[submit]"
               value="<?php _e('Save Changes', 'rotorwash') ?>" />
    </p>

</form>

</div><!-- end .wrap -->
