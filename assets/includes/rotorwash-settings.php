<div class="wrap">

<h2>Settings for <?php echo wp_get_theme(); ?></h2>

<?php if( isset($_GET['settings-updated']) && $_GET['settings-updated']==='true' ): ?>
<div id="message" class="updated below-h2"><p>Settings updated.</p></div>
<?php endif; ?>

<form method="post" action="options.php">
<?php
    settings_fields('rw-theme-settings');
    do_settings_sections('rw-theme-settings');
?>

    <p class="submit">
        <input type="submit" class="button-primary" 
               value="<?php _e('Save Changes', 'rotorwash') ?>" />
    </p>

</form>

</div><!-- end .wrap -->
