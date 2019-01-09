if(typeof dropdown_initialized == 'undefined' ) {
  jQuery(function ($) {
        var checkgrps = $('.dropdown-check-list');
        checkgrps.on('click', 'span.anchor', function(event){
            var element = $(this).parent();

            if ( element.hasClass('visible') )
            {
                element.removeClass('visible');
            }
            else
            {
                element.addClass('visible');
            }
        });
    });
}

var dropdown_initialized = 1;
