jQuery(function(){

var social_init = function(  ) {
    // Initializes Twitter
    var twitter_buttons = $(".twitter-share-button,.twitter-timeline").filter(":not(.active)");
    if (twitter_buttons.length>0) {
        if (typeof (window.twttr)!=='undefined') {
            window.twttr.widgets.load();
        } else {
            $.getScript('http://platform.twitter.com/widgets.js');
        }
        twitter_buttons.addClass('active');
    }

    // Initializes Facebook
    var fb_likes = $('.fb-like:not(.active)');
    if (fb_likes.length>0) {
        if (typeof (window.FB)!=='undefined') {
            window.FB.init({ status: true, cookie: true, xfbml: true });
        } else {
            $.getScript("http://connect.facebook.net/en_US/all.js#xfbml=1", function () {
                window.FB.init({ status: true, cookie: true, xfbml: true });
            });
        }
        fb_likes.addClass('active');
    }

    // Initializes Google+ Page Badge
    var gplus_badge = $('#gplus-page-badge').filter(':not(.active)');
    if (typeof (window.gapi)!=='undefined') {
        window.gapi.plusone.go();
        if (gplus_badge.length>0) {
            window.gapi.plus.render('gplus-page-badge', {
                href: 'https://plus.google.com/107010302774291305989',
                size: 'badge'
            });
            gplus_badge.addClass('active');
        }
    } else {
        $.getScript('https://apis.google.com/js/plusone.js',function(){
            window.gapi.plusone.go();
            if (gplus_badge.length>0) {
                window.gapi.plus.render('gplus-page-badge', {
                    href: 'https://plus.google.com/107010302774291305989',
                    size: 'badge'
                });
                gplus_badge.addClass('active');
            }
        });
    }
};

social_init();

});
