if (!Omeka) {
    var Omeka = {};
}

(function ($) {
    Omeka.showAdvancedForm = function () {
        var advancedForm = $('#advanced-form');
        var searchTextbox = $('#search-form input[type=text]');
        var searchSubmit = $('#search-form button');
        if (advancedForm.length > 0) {
            advancedForm.css("display", "none");
            $('#search-form').addClass("with-advanced").after('<a href="#" id="advanced-search" class="button">Advanced Search</a>');
            advancedForm.click(function (event) {
                event.stopPropagation();
            });
            $("#advanced-search").click(function (event) {
                event.preventDefault();
                event.stopPropagation();
                advancedForm.fadeToggle();
                $(document).click(function (event) {
                    if (event.target.id == 'query') {
                        return;
                    }
                    advancedForm.fadeOut();
                    $(this).unbind(event);
                });
            });
        } else {
            $('#search-form input[type=submit]').addClass("blue button");
        }
        
        
    };
    
    Omeka.dropDown = function(){
        var dropdownMenu = $('#mobile-nav');
        dropdownMenu.prepend('<a class="menu"><i class="fa fa-bars"></i></a>');
        //Hide the rest of the menu
        $('#mobile-nav .navigation').hide();

        //function the will toggle the menu
        $('.menu').click(function() {
            var x = $(this).attr('id');

            if (x==1) {
                $("#mobile-nav .navigation").slideUp();
                $(this).attr('id', '0');
            } else {
                $("#mobile-nav .navigation").slideDown();
                $(this).attr('id','1');
            }
        });
    };
})(jQuery);
