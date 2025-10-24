<?php

//add_action('shopic_woocommerce_single_product_dokan', 'shopic_dokan_sold_store', 1);

remove_action( 'elementor/widgets/wordpress/widget_args', 'dokan_depricated_elementor_store_widgets', 10, 2 );


add_filter('loop_shop_columns', function ($columns){
    if(is_product()){
        $columns = 4;
        if (is_active_sidebar('sidebar-woocommerce-detail')) {
            $columns = 3;
        }
        $product_single_style = shopic_get_theme_option('single_product_gallery_layout', 'horizontal');
        if( $product_single_style === 'gallery' || $product_single_style === 'sticky'){
            $columns = 2;
        }
    }
    return $columns;
});
