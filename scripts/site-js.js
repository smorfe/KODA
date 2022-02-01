jQuery(function($) {
    var stickyOffset = $('.site-header').offset().top + 20;
    $(window).scroll(function () {
        var sticky = $('.site-header'),
            width = $(document).width(),
            scroll = $(window).scrollTop();

        if (scroll >= stickyOffset) {
            sticky.addClass('is-sticky');
        } else {
            sticky.removeClass('is-sticky');
        }
    });

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

    $('.cta-gallery-wrapper').slick({
        arrows: false,
        autoplay: true,
        rows: 0,
        dots: true,
        customPaging : function(slider, i) {
            return '<span class="slider-dot"></span>';
        },
    });
})