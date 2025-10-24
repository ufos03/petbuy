(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/shopic-image-carousel.default', ($scope) => {
            let $carousel = $('.shopic-carousel', $scope);
            if ($carousel.length > 0) {
                let data = $carousel.data('settings'),
                    rtl = $('body').hasClass('rtl');
                $carousel.slick(
                    {
                        dots: false,
                        arrows: false,
                        infinite: true,
                        speed: 20000,
                        slidesToShow: parseInt(data.items),
                        autoplay: true,
                        autoplaySpeed: 0,
                        slidesToScroll: 1,
                        lazyLoad: 'ondemand',
                        pauseOnHover: false,
                        cssEase: 'linear',
                        draggable: true,
                        rtl: rtl,
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: parseInt(data.items_tablet),
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: parseInt(data.items_mobile),
                                }
                            }
                        ]
                    }
                );
            }
        });
    });

})(jQuery);
