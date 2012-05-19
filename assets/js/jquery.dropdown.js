jQuery(function($) {

    $(document).ready(function() {

        var config = {    
             sensitivity: 3, // number = sensitivity threshold (must be 1 or higher)    
             interval: 200,  // number = milliseconds for onMouseOver polling interval    
             over: doOpen,   // function = onMouseOver callback (REQUIRED)    
             timeout: 200,   // number = milliseconds delay before onMouseOut    
             out: doClose    // function = onMouseOut callback (REQUIRED)    
        };
        
        function doOpen() {
            $(this).addClass("hover");
            $('ul:first',this).css('visibility', 'visible');
        }
     
        function doClose() {
            $(this).removeClass("hover");
            $('ul:first',this).css('visibility', 'hidden');
        }

        $("ul#menu-main-menu li").hoverIntent(config);
        
        $("ul#menu-main-menu li ul li:has(ul)").find("a:first").append(" &raquo; ");

    });
});