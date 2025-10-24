<?php
/**
 * Shopic WooCommerce hooks
 *
 * @package shopic
 */

/**
 * Layout
 *
 * @see  shopic_before_content()
 * @see  shopic_after_content()
 * @see  woocommerce_breadcrumb()
 * @see  shopic_shop_messages()
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_action('woocommerce_before_main_content', 'shopic_before_content', 10);
add_action('woocommerce_after_main_content', 'shopic_after_content', 10);


add_action('woocommerce_before_shop_loop', 'shopic_sorting_wrapper', 19);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
add_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 36);
add_action('woocommerce_before_shop_loop', 'shopic_button_shop_canvas', 19);
add_action('woocommerce_before_shop_loop', 'shopic_button_shop_dropdown', 19);
add_action('woocommerce_before_shop_loop', 'shopic_button_grid_list_layout', 25);
add_action('woocommerce_before_shop_loop', 'woocommerce_pagination', 35);
add_action('woocommerce_before_shop_loop', 'shopic_sorting_wrapper_close', 40);
if (shopic_get_theme_option('woocommerce_archive_layout') == 'dropdown') {
    add_action('woocommerce_before_shop_loop', 'shopic_render_woocommerce_shop_dropdown', 35);
}

//Position label onsale
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
add_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 30);

//Wrapper content single
add_action('woocommerce_before_single_product_summary', 'shopic_woocommerce_single_content_wrapper_start', 0);
add_action('woocommerce_single_product_summary', 'shopic_woocommerce_single_content_wrapper_end', 99);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 4);


// Legacy WooCommerce columns filter.
if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.3', '<')) {
    add_filter('loop_shop_columns', 'shopic_loop_columns');
    add_action('woocommerce_before_shop_loop', 'shopic_product_columns_wrapper', 40);
    add_action('woocommerce_after_shop_loop', 'shopic_product_columns_wrapper_close', 40);
}

/**
 * Products
 *
 * @see shopic_upsell_display()
 * @see shopic_single_product_pagination()
 */


remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20);
add_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 21);
add_action('yith_quick_view_custom_style_scripts', function () {
    wp_enqueue_script('flexslider');
});

add_action('woocommerce_single_product_summary', 'shopic_woocommerce_single_brand', 1);
add_action('woocommerce_single_product_summary', 'shopic_stock_label', 2);
add_action('woocommerce_single_product_summary', 'shopic_woocommerce_time_sale', 11);
add_action('woocommerce_single_product_summary', 'shopic_single_product_extra', 70);

// Wishlist
add_action('woocommerce_after_add_to_cart_button', 'shopic_woocommerce_product_loop_wishlist_button', 10);
if (class_exists('YITH_Woocompare_Frontend')) {
    global $yith_woocompare;
    add_action('woocommerce_after_add_to_cart_button', array($yith_woocompare->obj, 'add_compare_link'), 20);
}

add_action('woocommerce_share', 'shopic_social_share', 10);

$product_single_style = shopic_get_theme_option('single_product_gallery_layout', 'horizontal');

add_theme_support('wc-product-gallery-lightbox');
if ($product_single_style === 'horizontal' || $product_single_style === 'vertical') {
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-slider');
}
if ($product_single_style === 'gallery' || $product_single_style === 'sticky') {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    add_action('woocommerce_single_product_summary', 'shopic_output_product_data_accordion', 70);
    add_filter('woocommerce_single_product_image_thumbnail_html', 'shopic_woocommerce_single_product_image_thumbnail_html', 10, 2);
}

/**
 * Cart fragment
 *
 * @see shopic_cart_link_fragment()
 */
if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3', '>=')) {
    add_filter('woocommerce_add_to_cart_fragments', 'shopic_cart_link_fragment');
} else {
    add_filter('add_to_cart_fragments', 'shopic_cart_link_fragment');
}

remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');

add_action('woocommerce_checkout_order_review', 'woocommerce_checkout_order_review_start', 5);
add_action('woocommerce_checkout_order_review', 'woocommerce_checkout_order_review_end', 15);

add_filter('woocommerce_get_script_data', function ($params, $handle) {
    if ($handle == "wc-add-to-cart") {
        $params['i18n_view_cart'] = '';
    }
    return $params;
}, 10, 2);

/*
 *
 * Layout Product
 *
 * */
function shopic_include_hooks_product_blocks() {

    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

    add_action('woocommerce_before_shop_loop_item', 'shopic_woocommerce_product_loop_start', -1);
    /**
     * Integrations
     *
     * @see shopic_template_loop_product_thumbnail()
     *
     */

    add_action('woocommerce_before_shop_loop_item_title', 'shopic_woocommerce_product_loop_image', 10);
    add_action('shopic_woocommerce_product_loop_image', 'shopic_woocommerce_get_product_label_stock', 9);
    add_action('shopic_woocommerce_product_loop_image', 'shopic_template_loop_product_thumbnail', 10);
    add_action('shopic_woocommerce_product_loop_image', 'woocommerce_template_loop_product_link_open', 99);
    add_action('shopic_woocommerce_product_loop_image', 'woocommerce_template_loop_product_link_close', 99);

    add_action('woocommerce_shop_loop_item_title', 'shopic_woocommerce_product_caption_start', -1);


    add_action('woocommerce_after_shop_loop_item', 'shopic_woocommerce_product_caption_end', 998);
    add_action('woocommerce_after_shop_loop_item', 'shopic_woocommerce_product_loop_end', 999);

    if (shopic_get_theme_option('wocommerce_show_rating', 'yes') == 'yes') {
        add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    }

    if (shopic_get_theme_option('wocommerce_show_cat', 'yes') == 'yes') {
        add_action('woocommerce_shop_loop_item_title', 'shopic_woocommerce_get_product_category', 5);
    }


    add_action('shopic_woocommerce_product_loop_image', 'shopic_woocommerce_product_loop_action', 20);

    add_action('shopic_woocommerce_product_loop_action', 'woocommerce_template_loop_add_to_cart', 5);

    // Wishlist
    add_action('shopic_woocommerce_product_loop_action', 'shopic_woocommerce_product_loop_wishlist_button', 10);

    // Compare
    add_action('shopic_woocommerce_product_loop_action', 'shopic_woocommerce_product_loop_compare_button', 15);

    // QuickView
    if (shopic_is_woocommerce_extension_activated('YITH_WCQV')) {
        remove_action('woocommerce_after_shop_loop_item', array(
            YITH_WCQV_Frontend::get_instance(),
            'yith_add_quick_view_button'
        ), 15);
        add_action('shopic_woocommerce_product_loop_action', array(
            YITH_WCQV_Frontend::get_instance(),
            'yith_add_quick_view_button'
        ), 15);

        add_action('shopic_woocommerce_after_shop_loop_item_title', array(
            YITH_WCQV_Frontend::get_instance(),
            'yith_add_quick_view_button'
        ), 40);
    }

}

shopic_include_hooks_product_blocks();

function shopic_update_setting_yith_plugin() {
    if (get_option('yith_woocompare_compare_button_in_product_page') == 'yes') update_option('yith_woocompare_compare_button_in_product_page', 'no');
    if (get_option('yith_woocompare_compare_button_in_products_list') == 'yes') update_option('yith_woocompare_compare_button_in_products_list', 'no');
}

shopic_update_setting_yith_plugin();

add_filter('woocommerce_loop_add_to_cart_link', function ($quantity, $product) {
    return '<div class="opal-add-to-cart-button">' . $quantity . '</div>';
}, 10, 2);

add_action('shopic_single_product_video_360', 'shopic_single_product_video_360', 10);
