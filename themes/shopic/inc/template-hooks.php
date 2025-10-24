<?php
/**
 * =================================================
 * Hook shopic_page
 * =================================================
 */
add_action('shopic_page', 'shopic_page_header', 10);
add_action('shopic_page', 'shopic_page_content', 20);

/**
 * =================================================
 * Hook shopic_single_post_top
 * =================================================
 */
add_action('shopic_single_post_top', 'shopic_post_header', 10);

/**
 * =================================================
 * Hook shopic_single_post
 * =================================================
 */
add_action('shopic_single_post', 'shopic_post_thumbnail', 10);
add_action('shopic_single_post', 'shopic_post_content', 30);

/**
 * =================================================
 * Hook shopic_single_post_bottom
 * =================================================
 */
add_action('shopic_single_post_bottom', 'shopic_post_taxonomy', 5);
add_action('shopic_single_post_bottom', 'shopic_post_nav', 10);
add_action('shopic_single_post_bottom', 'shopic_display_comments', 20);

/**
 * =================================================
 * Hook shopic_loop_post
 * =================================================
 */
add_action('shopic_loop_post', 'shopic_post_thumbnail', 10);
add_action('shopic_loop_post', 'shopic_post_header', 15);
add_action('shopic_loop_post', 'shopic_post_content', 30);

/**
 * =================================================
 * Hook shopic_footer
 * =================================================
 */
add_action('shopic_footer', 'shopic_footer_default', 20);

/**
 * =================================================
 * Hook shopic_after_footer
 * =================================================
 */

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'shopic_template_account_dropdown', 1);
add_action('wp_footer', 'shopic_mobile_nav', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */
add_action('wp_head', 'shopic_pingback_header', 1);

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
add_action('shopic_sidebar', 'shopic_get_sidebar', 10);

/**
 * =================================================
 * Hook shopic_loop_after
 * =================================================
 */
add_action('shopic_loop_after', 'shopic_paging_nav', 10);

/**
 * =================================================
 * Hook shopic_page_after
 * =================================================
 */
add_action('shopic_page_after', 'shopic_display_comments', 10);

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

/**
 * =================================================
 * Hook shopic_woocommerce_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_woocommerce_after_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook shopic_woocommerce_after_shop_loop_item
 * =================================================
 */
