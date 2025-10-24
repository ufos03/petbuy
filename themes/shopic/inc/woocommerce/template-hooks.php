<?php
/**
 * =================================================
 * Hook shopic_page
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_single_post_top
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_single_post
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_single_post_bottom
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_loop_post
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_footer
 * =================================================
 */
add_action('shopic_footer', 'shopic_handheld_footer_bar', 25);

/**
 * =================================================
 * Hook shopic_after_footer
 * =================================================
 */
add_action('shopic_after_footer', 'shopic_sticky_single_add_to_cart', 999);

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'shopic_render_woocommerce_shop_canvas', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_before_header
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_before_content
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_content_top
 * =================================================
 */
add_action('shopic_content_top', 'shopic_shop_messages', 10);

/**
 * =================================================
 * Hook shopic_post_header_before
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_post_content_before
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_post_content_after
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_sidebar
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_loop_after
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_page_after
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_woocommerce_before_shop_loop_item
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_woocommerce_before_shop_loop_item_title
 * =================================================
 */
add_action('shopic_woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
add_action('shopic_woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

/**
 * =================================================
 * Hook shopic_woocommerce_shop_loop_item_title
 * =================================================
 */
add_action('shopic_woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
add_action('shopic_woocommerce_shop_loop_item_title', 'shopic_woocommerce_get_product_category', 10);
add_action('shopic_woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

/**
 * =================================================
 * Hook shopic_woocommerce_after_shop_loop_item_title
 * =================================================
 */
add_action('shopic_woocommerce_after_shop_loop_item_title', 'shopic_woocommerce_get_product_description', 15);
add_action('shopic_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 20);
add_action('shopic_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 25);
add_action('shopic_woocommerce_after_shop_loop_item_title', 'shopic_woocommerce_product_loop_wishlist_button', 30);
add_action('shopic_woocommerce_after_shop_loop_item_title', 'shopic_woocommerce_product_loop_compare_button', 35);

/**
 * =================================================
 * Hook shopic_woocommerce_after_shop_loop_item
 * =================================================
 */
