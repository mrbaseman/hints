/**
 *
 * @category        page
 * @package         Hints
 * @version         0.6.1
 * @authors         Martin Hecht (mrbaseman), Ruud Eisinga (Dev4me)
 * @copyright       (c) 2018 - 2020, Martin Hecht
 * @link            https://github.com/WebsiteBaker-modules/hints
 * @license         GNU General Public License v3 - The javascript features are third party software, spectrum color picker and autosize, both licensed under MIT license
 * @platform        2.8.x
 * @requirements    PHP 7.x
 *
 **/

if (typeof backendLoaded == 'undefined') {

if (window.jQuery) {

        $(document).ready(function () {

                var atCurrentPosition = false;

                $('.hints_content_float, .hints_content_div').each(function(e){
                        atCurrentPosition = $(this).hasClass('hints_content_div');
                        var hint = $(this).clone();
                        var parent = $(this).parent();
                        var backend = 0;                                  // WebsiteBaker >= 2.10
                        if($('.fg12').length) backend = 1;                // WBCE flat theme
                        if($('#main-header').length) backend = 2;         // Fraggy WBCE
                        if($('#admin-header').length) backend = 3;        // Argos reloaded WBCE
                        if($('table.container').length) backend= -1;      // WB < 2.10 Default theme
                        if($('#content_container').length) backend= -2;   // original Argos
                        if($('#headerarea').length) backend = -3;         // WBCE 1.1 flat theme
                        if (backend == -2)
                                if($('table.header-info').length) backend = 4;     // Argos on WB >= 2.10
                        hint.css( "display", "none" );
                        hint.css( "width", "auto" );
                        hint.css( "margin", "10px 1px 10px 1px" );
                        hint.css( "padding", "20px 30px" );
                        hint.css( "borderRadius", parent.css("borderTopLeftRadius"));
                        hint.css( "color", isDark(hint.css("background-color")) ? 'white' : 'black');
                        if((backend>0)&&(backend!=4)) parent.prev().remove();
                        if(atCurrentPosition) {
                                if(backend >= 0) hint.insertBefore(parent);
                                else hint.insertBefore(this);
                        } else {
                                if(backend==0) hint.insertBefore('.default-content');
                                if(backend==1) hint.insertBefore('.page_titel');
                                if(backend==-3) hint.insertBefore('.page_titel');
                                if(backend==2) hint.insertBefore('.content-body');
                                if(backend==3) hint.insertBefore('#content-container');
                                if(backend==4) hint.insertBefore('#content_container');
                                if(backend==-1) hint.insertBefore('.section-info');
                                if(backend==-2) hint.insertBefore('.section-info');
                        }
                        if(backend >= 0) // starting with WB 2.10 we remove the section header
                                parent.remove();
                        else
                                this.remove();
                        if (hint.html().length > 0) hint.show();
                });

                $('.hints_inner').each(function(e){
                        var hint = $(this);
                        hint.css("color", isDark(hint.css("background-color")) ? 'white' : 'black');
                });


        });

        function isDark( color ) {
                var match = /rgb\((\d+).*?(\d+).*?(\d+)\)/.exec(color);
                return parseFloat(match[1])
                         + parseFloat(match[2])
                         + parseFloat(match[3])
                           < 3 * 256 / 2; // r+g+b should be less than half of max (3 * 256)
        }
}

}

var backendLoaded = true;
