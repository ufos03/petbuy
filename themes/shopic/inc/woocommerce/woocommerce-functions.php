<?php
/**
 * Checks if the current page is a product archive
 *
 * @return boolean
 */
function shopic_is_product_archive() {
    if (is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag()) {
        return true;
    } else {
        return false;
    }
}

/**
 * @param $product WC_Product
 */
function shopic_product_get_image($product){
	return $product->get_image();
}

/**
 * @param $product WC_Product
 */
function shopic_product_get_price_html($product){
	return $product->get_price_html();
}

/**
 * Retrieves the previous product.
 *
 * @param bool $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
function shopic_get_previous_product($in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat') {
    $product = new Shopic_WooCommerce_Adjacent_Products($in_same_term, $excluded_terms, $taxonomy, true);
    return $product->get_product();
}

/**
 * Retrieves the next product.
 *
 * @param bool $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
function shopic_get_next_product($in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat') {
    $product = new Shopic_WooCommerce_Adjacent_Products($in_same_term, $excluded_terms, $taxonomy);
    return $product->get_product();
}


function shopic_is_woocommerce_extension_activated($extension = 'WC_Bookings') {
    if ($extension == 'YITH_WCQV') {
        return class_exists($extension) && class_exists('YITH_WCQV_Frontend') ? true : false;
    }

    return class_exists($extension) ? true : false;
}

function shopic_woocommerce_pagination_args($args) {
    $args['prev_text'] = '<i class="shopic-icon shopic-icon-chevron-left"></i>';
    $args['next_text'] ='<i class="shopic-icon shopic-icon-chevron-right"></i>';
    return $args;
}

add_filter('woocommerce_pagination_args', 'shopic_woocommerce_pagination_args', 10, 1);

if ( ! function_exists( 'wvs_get_wc_attribute_taxonomy' ) ){
    function wvs_get_wc_attribute_taxonomy( $attribute_name ) {

        $transient_name = sprintf( 'wvs_attribute_taxonomy_%s', $attribute_name );

        $cache = new Woo_Variation_Swatches_Cache( $transient_name, 'wvs_attribute_taxonomy' );

        if ( isset( $_GET['wvs_clear_transient'] ) ) {
            $cache->delete_transient();
        }

        if ( false === ( $attribute_taxonomy = $cache->get_transient() ) ) {

            global $wpdb;

            $attribute_name = str_replace( 'pa_', '', wc_sanitize_taxonomy_name( $attribute_name ) );

            $attribute_taxonomy = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name='{$attribute_name}'" );

            $cache->set_transient( $attribute_taxonomy );
        }

        return apply_filters( 'wvs_get_wc_attribute_taxonomy', $attribute_taxonomy, $attribute_name );
    }
}

if ( ! function_exists( 'wvs_taxonomy_meta_fields' ) ){
    function wvs_taxonomy_meta_fields( $field_id = false ) {

        $fields = array();

        $fields['color'] = array(
            array(
                'label' => esc_html__( 'Color', 'shopic' ), // <label>
                'desc'  => esc_html__( 'Choose a color', 'shopic' ), // description
                'id'    => 'product_attribute_color', // name of field
                'type'  => 'color'
            )
        );

        $fields['image'] = array(
            array(
                'label' => esc_html__( 'Image', 'shopic' ), // <label>
                'desc'  => esc_html__( 'Choose an Image', 'shopic' ), // description
                'id'    => 'product_attribute_image', // name of field
                'type'  => 'image'
            )
        );

        $fields = apply_filters( 'wvs_product_taxonomy_meta_fields', $fields );

        if ( $field_id ) {
            return isset( $fields[ $field_id ] ) ? $fields[ $field_id ] : array();
        }

        return $fields;

    }
}