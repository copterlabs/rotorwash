<div class="wrap">
<h2>Settings for <?php echo get_current_theme(); ?></h2>

<form method="post" action="options.php">
<?php settings_fields('rw-theme-settings'); ?>
<?php do_settings_sections('rw-theme-settings'); ?>


    
    <p class="submit">
        <input type="submit" class="button-primary" 
               value="<?php _e('Save Changes', 'rotorwash') ?>" />
    </p>

</form>
</div>