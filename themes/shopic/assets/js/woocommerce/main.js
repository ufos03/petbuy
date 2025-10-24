(function ($) {
    'use strict';

    function tooltip() {
        $('body').on('mouseenter', '.woosc-btn:not(.tooltipstered), .woosq-btn:not(.tooltipstered), .woosw-btn:not(.tooltipstered), .group-action .compare-button .compare:not(.tooltipstered),.product-list .compare-button .compare:not(.tooltipstered), .group-action .yith-wcqv-button:not(.tooltipstered),.product-list .yith-wcqv-button:not(.tooltipstered), .group-action .yith-wcwl-add-to-wishlist > div > a:not(.tooltipstered),.product-list .yith-wcwl-add-to-wishlist > div > a:not(.tooltipstered),.group-action .opal-add-to-cart-button:not(.tooltipstered) ,.shopic-color-type:not(.tooltipstered)', function () {

            var $element = $(this);
            if (typeof $.fn.tooltipster !== 'undefined') {
                $element.tooltipster({
                    position: 'top',
                    functionBefore: function (instance, helper) {
                        instance.content(instance._$origin.text());
                    },
                    theme: 'opal-product-tooltipster',
                    delay: 0,
                    animation: 'grow'
                }).tooltipster('show');
            }
        });
    }

    function ajax_wishlist_count() {

        $(document).on('added_to_wishlist removed_from_wishlist', function () {
            var counter = $('.header-wishlist .count, .footer-wishlist .count, .header-wishlist .wishlist-count-item');
            $.ajax({
                url: yith_wcwl_l10n.ajax_url,
                data: {
                    action: 'yith_wcwl_update_wishlist_count'
                },
                dataType: 'json',
                success: function (data) {
                    counter.html(data.count);
                    $('.wishlist-count-text').html(data.text);
                },
            });
        });

        $('body').on('woosw_change_count', function(event,count){
            var counter = $('.header-wishlist .count, .footer-wishlist .count, .header-wishlist .wishlist-count-item');

            $.ajax({
                url: woosw_vars.ajax_url,
                data: {
                    action: 'woosw_ajax_update_count'
                },
                dataType: 'json',
                success: function (data) {
                    $('.wishlist-count-text').html(data.text);
                },
            });
            counter.html(count);
        });
    }

    function ajax_live_search() {
        $(document).ready(function () {
            var $parent = $('.woocommerce-product-search'),
                $inputsearch = $('.woocommerce-product-search .search-field'),
                $result = $('.ajax-search-result'),
                template = wp.template('ajax-live-search-template');

            $('body').on('click', function () {
				$result.hide();
			})

            if ($inputsearch.length) {
                // $inputsearch.focusout(function () {
                //     $result.hide();
                // });

                $inputsearch.keyup(function () {
                    if (this.value.length > 2) {
                        $.ajax({
                            url: shopicAjax.ajaxurl,
                            type: 'post',
                            data: {
                                action: 'shopic_ajax_search_products',
                                query: this.value
                            },
                            beforeSend: function () {
                                $parent.addClass('loading');
                            },
                            success: function (data) {
                                $parent.removeClass('loading');
                                var $data = $.parseJSON(data);
                                $result.empty();
                                $result.show();
                                $.each($data, function (i, item) {
                                    $result.append(template({
                                        url: item.url,
                                        title: item.value,
                                        img: item.img,
                                        price: item.price
                                    }));
                                });
                            }
                        });
                    } else {
                        $result.hide();
                    }
                })
					.on('click', function(e){
						e.stopPropagation();
					})
					.on('focus', function (event) {
						if (this.value.length > 2) {
							$result.show();
						}
					});
            }
        });

    }

    function woo_widget_categories() {
        var widget = $('.widget_product_categories'),
            main_ul = widget.find('ul');
        if ( main_ul.length ) {
            var dropdown_widget_nav = function () {

                main_ul.find('li').each(function () {

                    var main = $(this),
                        link = main.find('> a'),
                        ul = main.find('> ul.children');
                    if (ul.length) {

                        //init widget
                        // main.removeClass('opened').addClass('closed');

                        if (main.hasClass('closed')) {
                            ul.hide();

                            link.before('<i class="icon-plus"></i>');
                        }
                        else if (main.hasClass('opened')) {
                            link.before('<i class="icon-minus"></i>');
                        }
                        else {
                            main.addClass('opened');
                            link.before('<i class="icon-minus"></i>');
                        }

                        // on click
                        main.find('i').on('click', function(e) {

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('>i').removeClass('icon-plus').addClass('icon-minus');
                            }
                            else {
                                main.removeClass('opened').addClass('closed');
                                main.find('>i').removeClass('icon-minus').addClass('icon-plus');
                            }

                            e.stopImmediatePropagation();
                        });

                        main.on('click', function(e){

                            if( $(e.target).filter('a').length)
                                return ;

                            ul.slideToggle('slow');

                            if (main.hasClass('closed')) {
                                main.removeClass('closed').addClass('opened');
                                main.find('i').removeClass('icon-plus').addClass('icon-minus');
                            }
                            else {
                                main.removeClass('opened').addClass('closed');
                                main.find('i').removeClass('icon-minus').addClass('icon-plus');
                            }

                            e.stopImmediatePropagation();
                        });
                    }
                });
            };
            dropdown_widget_nav();
        }
    }

    function cross_sells_carousel() {
        var csell_wrap = $('body.woocommerce-cart .cross-sells ul.products');
        var item = csell_wrap.find('li.product');

        if(item.length > 3) {
            csell_wrap.slick(
                {
                    dots: true,
                    arrows: false,
                    infinite: false,
                    speed: 300,
                    slidesToShow: parseInt(3),
                    autoplay: false,
                    slidesToScroll: 1,
                    lazyLoad: 'ondemand',
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: parseInt(3),
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: parseInt(2),
                            }
                        }
                    ]
                }
            );
        }
    }

    $(document).ready(function () {
        cross_sells_carousel();
    });
    woo_widget_categories();
    tooltip();
    ajax_wishlist_count();
    ajax_live_search();

})(jQuery);
