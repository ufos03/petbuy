(function ($) {
    'use strict';

    function login_dropdown() {
        $('.site-header-account').mouseenter(function () {
            $('.account-dropdown', this).append($('.account-wrap'));
        });
    }

    function handleWindow() {
        var body = document.querySelector('body');

        if (window.innerWidth > body.clientWidth + 5) {
            body.classList.add('has-scrollbar');
            body.setAttribute('style', '--scroll-bar: ' + (window.innerWidth - body.clientWidth) + 'px');
        } else {
            body.classList.remove('has-scrollbar');
        }
    }

    function minHeight() {
        var $body = $('body'),
            bodyHeight = $(window).outerHeight(),
            headerHeight = $('header.header-1').outerHeight(),
            footerHeight = $('footer.site-footer').outerHeight();
            if($body.find('header.header-1').length){

                $('.site-content').css({
                    'min-height': bodyHeight - headerHeight - footerHeight - 114
                });
            }
    }
    minHeight();
    handleWindow();
    login_dropdown();
})(jQuery);
