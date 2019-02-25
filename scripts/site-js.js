jQuery(function($) {
    /*
     |----------------------------------------------------------------
     |  Smooth Scrolling
     |  (targeting all [data-smooth-scroll])
     |----------------------------------------------------------------
     */
    $('.smooth-scroll, [data-smooth-scroll]').click(function(e) {
        var $target = $($(this).attr('data-smooth-scroll'));

        if (! $target.size()) {
            var href = $(this).attr('href').split("#");
            $target = $('#' + href[href.length - 1]);
        }

        if ($target.size()) {
            e.preventDefault();

            $('html, body').animate({
                scrollTop: $target.offset().top - 50
            }, 250);
        }
    });

    $('.menu-trigger').click(function(e){
        e.preventDefault();
        $('.site-header').toggleClass('menu-opened');

        $('.main-menu').slideToggle();
    });
})