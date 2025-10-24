<html>

<style>
	
	#order_review #payment {
		display: none !important;
	}
	
	/*.woocommerce-checkout-review-order {
		margin-top: 100px!important;
	}*/
	
	
	/* Riduce la grandezza delle immagini nel riepilogo dell'ordine */
	body.woocommerce-checkout .woocommerce-checkout-review-order .item-image img {
		width: 80px; /* Imposta la larghezza desiderata */
		height: auto; /* Imposta l'altezza desiderata */
		object-fit: cover; /* Assicura che l'immagine non si deformi */
	}
	
	.cestina {
		width: 30px!important;
		height: auto!important;
	}
	
</style>

<body>




<?php
/**
 * Review Order Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="shop_table woocommerce-checkout-review-order-table">
    <div id="order_review" class="shop_table woocommerce-checkout-review-order-table">
    
    <div class="position-relative d-block d-sm-none">
		<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-gatto-1-original.png'; ?>" alt="Gattino" class="" style="position:absolute; z-index:1.5!important; top: -35px; left: -20px; ">
	</div>
						
    <div class="product-list-container">
        <?php
        do_action( 'woocommerce_review_order_before_cart_contents' );
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                $product_image_id = $_product->get_image_id();
                if ( $product_image_id ) {
                    $image_url = wp_get_attachment_image_url( $product_image_id, 'thumbnail' );
                } else {
                    $image_url = wc_placeholder_img_src( 'thumbnail' );
                }
                ?>
                <div id="item-<?php echo esc_attr( $cart_item_key ); ?>" class="cart-item-custom d-flex justify-content-between align-items-center py-2">
                    <div class="item-image item-image-conteggio">
                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $_product->get_name() ); ?>" style="width:200px!important;height:auto;" />
                    </div>
                    <div class="item-details-custom d-flex flex-column w-100">
                        <div class="item-name noacapo fortablet2"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></div>
                        
                        <div class="item-delivery noacapo small fortablet2 d-none d-md-block">
                            x <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <span class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); ?>
                        </div>

                        <div class="item-delivery noacapo small fortablet2 d-md-none">
                            <div class="d-flex align-items-center">
                                <div class="item-quantity-carrello w-100 bg-white me-2">
                                    <button type="button" class="quantity-button minus">-</button>
                                    <?php
                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name'  => "cart[{$cart_item_key}][qty]",
                                            'input_value' => $cart_item['quantity'],
                                            'max_value'   => $_product->get_max_purchase_quantity(),
                                            'min_value'   => 1,
                                            'product_name' => $_product->get_name(),
                                        ),
                                        $_product,
                                        false
                                    );
                                    echo apply_filters( 'woocommerce_checkout_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                    ?>
                                    <button type="button" class="quantity-button plus">+</button>
                                </div>
                                <div class="item-image p-0 m-0">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a role="button" href="%s" class="text-decoration-none" aria-label="%s" data-product_id="%s" data-product_sku="%s"><img src="' . esc_url( get_template_directory_uri() . '/ufficiale/cestino.svg' ) . '" class="cestina" alt="cestino"/></a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $_product->get_name() ) ) ),
                                            esc_attr( $_product->get_id() ),
                                            esc_attr( $_product->get_sku() )
                                        ),
                                        $cart_item_key
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item-price-carrello fw-normal fortablet2 d-none d-md-block"><?php echo wc_price( $_product->get_price() * $cart_item['quantity'] ); ?></div>
                </div>
                
                <div class="bordo-centrato-checkout my-3"></div>
                
                <?php
            }
        }
        do_action( 'woocommerce_review_order_after_cart_contents' );
        ?>
    </div>
    
    				<div class="position-relative d-none d-md-block">
						<div style="position: absolute; top: -50px; right: -65px; z-index: 1;">
							<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-basso-checkout.svg'; ?>" class="" alt="impronte in basso">
						</div>
					</div>
					
					
		<form class="checkout_coupon woocommerce-form-coupon border-0 m-0 p-0" method="post">
   
   			<label for="coupon_code" class="mb-2 margin-promo fortablet2">Inserisci codice promo</label>

			<div class="d-flex align-items-center">
			
				<input type="text" name="coupon_code" class="form-control input-promo fortablet border" style="border-top-left-radius: 5px!important;border-bottom-left-radius: 5px!important;" placeholder="Promo code" id="coupon_code" value="" />

				<button type="submit" class="btn btn-promo input-promo noacapo pb-2" style="margin-left: -65px; border-top-left-radius: 5px !important; border-bottom-left-radius: 5px !important; border-top-right-radius: 5px !important; border-bottom-right-radius: 5px !important; display: flex; align-items: center; justify-content: center;" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Invia', 'woocommerce' ); ?></button>
			
			</div>
		</form>					
					
					
					
    <div class="text-end pt-4 fixed-bottom-conteggio">
        <div class="summary-row d-flex justify-content-between">
            <div><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></div>
            <div><?php wc_cart_totals_subtotal_html(); ?></div>
        </div>
        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="summary-row d-flex justify-content-between">
                <div><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
            </div>
        <?php endforeach; ?>
        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            <div class="summary-row d-flex justify-content-between">
                <div><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></div>
                <div>
                    <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
                    <?php wc_cart_totals_shipping_html(); ?>
                    <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="summary-row d-flex justify-content-between">
                <div><?php echo esc_html( $fee->name ); ?></div>
                <div><?php wc_cart_totals_fee_html( $fee ); ?></div>
            </div>
        <?php endforeach; ?>
        <div class="summary-row d-flex justify-content-between">
            <div class="fw-bold"><?php esc_html_e( 'Total', 'woocommerce' ); ?></div>
            <div class="fw-bold"><?php wc_cart_totals_order_total_html(); ?></div>
        </div>
    </div>
</div>




</body>
</html>