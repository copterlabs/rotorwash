jQuery(function($) {
    $('.rotorwash-admin-upload').click(function() {
        tb_show(
            'Upload a File',
            'media-upload.php?referer=rotorwash_general&type=image&TB_iframe=true&post_id=0',
            false
        );
        return false;
    });

    window.send_to_editor = function(html) {
        var image_url = $('img',html).attr('src');
        $('.rotorwash-admin-upload-text').val(image_url);
        tb_remove();

        $("#rotorwash-settings-submit").trigger('click');
    };
});
