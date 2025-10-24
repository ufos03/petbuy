<!DOCTYPE html>
<html>

<style>
	
	.qty {
		border: none;
	}
	
	/* Nasconde le frecce in Chrome, Safari, Edge e Opera */
	input[type="number"]::-webkit-inner-spin-button,
	input[type="number"]::-webkit-outer-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	/* Nasconde le frecce in Firefox */
	input[type="number"] {
		-moz-appearance: textfield;
	}
	
	
</style>
<body>



<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<!--desktop-->
<div class="container tra-menu-e-titolo d-none d-md-block">
    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <div class="row">
            <div class="col-md-8 pe-5">
                <h1>Il tuo carrello</h1>
                <hr class="mb-5">

                <?php do_action( 'woocommerce_before_cart_table' ); ?>

                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product    = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                    $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        
                        <div class="row">
                            <div class="col-md-3 position-relative bone-product-wrapper">
                                <div class="paw-print-sx"> 
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-sx-prodotto1.svg'; ?>" alt="Immagine2 sx prodotto1">
                                </div>
                                
                                <div class="paw-print-dx"> 
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-dx-prodotto1.svg'; ?>" alt="Immagine1 sx prodotto1">
                                </div>
                                
                                <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                    if ( ! $product_permalink ) {
                                        echo $thumbnail;
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                                    }
                                ?>
                            </div>

                            <div class="col-md-9">
                                <div class="d-flex align-items-center mt-2">
                                    <div class="item-name me-3 item-details noacapo fortablet2">
                                        <?php
                                        if ( ! $product_permalink ) {
                                            echo wp_kses_post( $product_name );
                                        } else {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                        }
                                        echo wc_get_formatted_cart_item_data( $cart_item );
                                        ?>
                                        <div class="item-delivery noacapo small fortablet">
                                            Consegna prevista: <span class="fw-bold">
                                                <?php 
                                                    do_action( 'your_plugin_delivery_date_hook', $cart_item );
                                                ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="item-quantity-carrello">
										<button type="button" class="quantity-button minus">-</button>
										<?php
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'  => "cart[{$cart_item_key}][qty]",
												'input_value' => $cart_item['quantity'],
												'max_value'   => $max_quantity,
												'min_value'   => $min_quantity,
												'product_name' => $product_name,
											),
											$_product,
											false
										);
										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
										?>
										<button type="button" class="quantity-button plus">+</button>
									</div>

                                    <div class="item-price-carrello">
                                        <small class="text-muted d-md-none">Prezzo:</small>
                                        <strong>
                                            <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                                        </strong>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-end mb-2 m-carrello">
									
                                    <div class="col-auto text-start fortablet">
										<a href="#" class="text-decoration-none"><small>Modifica</small></a>
									</div>
									
									<div class="col-auto text-start fortablet">
										<?php
										echo apply_filters(
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a role="button" href="%s" class="text-decoration-none" aria-label="%s" data-product_id="%s" data-product_sku="%s"><small>Rimuovi</small></a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											),
											$cart_item_key
										);
										?>
									</div>
									
									<div class="col-auto text-start fortablet">
                                    	<a href="#" class="text-decoration-none"><small>Trasferisci alla wishlist</small></a>
									</div>
									
									<div class="col-auto text-start fortablet">
                                    	<a href="#" class="text-decoration-none"><small>Salva per dopo</small></a>
									</div>
									
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-5">
                        
                        <?php
                    }
                }
                ?>
                
                <div class="actions">
                    <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </div>

            </div>
            <div class="col-md-4 ps-4">
                <div class="">
                    <?php if ( wc_coupons_enabled() ) { ?>
                        <div class="coupon">
                            <label for="coupon_code" class="form-label mb-2 margin-promo"><?php esc_html_e( 'Inserisci codice promo', 'woocommerce' ); ?></label>
                            
                            <div class="d-flex align-items-center">
                                <input type="text" name="coupon_code" class="form-control input-promo fortablet2" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Promo code', 'woocommerce' ); ?>" />
                                <button type="submit" class="btn btn-promo input-promo noacapo fs-5 pb-2" style="margin-left: -65px; display: flex; align-items: center; justify-content: center;" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Invia', 'woocommerce' ); ?></button>
                            </div>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="pt-4">
                    <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
                    <div class="cart-collaterals">
                        <?php do_action( 'woocommerce_cart_collaterals' ); ?>
                    </div>
                </div>
            </div>
            </div>
    </form>
</div>



		<!--desktop-->
		<div class="container d-none d-md-block">
        	<div class="d-flex justify-content-between mb-3 tra-menu-e-titolo">

    			<div class="col-md-6 fw-bold p-2">
    				<h1 class="noacapo float-start">Prodotti consigliati</h1>
    			</div>

    			<div class="col-md-6 p-2">
    				<div class="small float-end">
    					<a href="#">Vai allo shop <img src="<?php echo get_template_directory_uri() . '/ufficiale/freccia-destra.svg'; ?>" alt=""/></a>
    				</div>
    			</div>

		    </div>
        </div>
        
        
        
        <!--desktop-->
        <div class="container d-none d-md-block"><!--inizio container-->
    		<div class="row">

    			<div class="col-md-3">

            <div class="scheda-prodotto">
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

              <div class="container">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>

          </div>

    			</div>

    			<div class="col-md-3">

            <div class="scheda-prodotto">
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

              <div class="container">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>

          </div>

    			</div>

    			<div class="col-md-3">

            <div class="scheda-prodotto">
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

              <div class="container">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>

          </div>

    			</div>

    			<div class="col-md-3">

            <div class="scheda-prodotto">
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

              <div class="container">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>

          </div>

    			</div>

    		</div>
        </div><!--fine container-->
        
        
        <!--mobile-->
        <div class="d-block d-sm-none sfondo-pcm">

		<div class="container">
    <div class="">
        <div class="">
            <div class="bordo-centrato-checkout"></div>

            <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                <?php do_action( 'woocommerce_before_cart_table' ); ?>
                
                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        <div class="row my-4">
                            <div class="col-4 position-relative bone-product-wrapper">
                                <?php $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                if ( ! $product_permalink ) { echo $thumbnail; } else { printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); } ?>
                            </div>
                            <div class="col-8">
                                <div class="item-details">
                                    <div class="item-name noacapo"><?php if ( ! $product_permalink ) { echo wp_kses_post( $_product->get_name() ); } else { echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) ); } ?></div>
                                    <div class="item-delivery noacapo small">Consegna prevista: <span class="fw-bold"><?php do_action( 'your_plugin_delivery_date_hook', $cart_item ); ?></span></div>
                                </div>
                                <div class="" style="display: flex; align-items: center; justify-content: space-between; width:100%;">
                                    <div class="item-quantity-carrello w-100 bg-white me-2">
										<button type="button" class="quantity-button minus">-</button>
										<?php
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'  => "cart[{$cart_item_key}][qty]",
												'input_value' => $cart_item['quantity'],
												'max_value'   => $max_quantity,
												'min_value'   => $min_quantity,
												'product_name' => $product_name,
											),
											$_product,
											false
										);
										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
										?>
										<button type="button" class="quantity-button plus">+</button>
									</div>
                                    
                                    <div class="item-price-carrello d-none">
                                        <strong><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></strong>
                                    </div>
                                    <div class="item-image p-0 m-0">
										
										
										
										<?php
										echo apply_filters(
											'woocommerce_cart_item_remove_link',
											sprintf(
												'<a role="button" href="%s" class="text-decoration-none" aria-label="%s" data-product_id="%s" data-product_sku="%s"><img src="' . esc_url( get_template_directory_uri() . '/ufficiale/cestino.svg' ) . '" class="cestina" alt="cestino"/></a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											),
											$cart_item_key
										);
										?>
                                    
									</div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="bordo-centrato-checkout"></div>
                <?php }
                } ?>

                <div class="actions my-4">
                    <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </div>
                
                <!--div class="bordo-centrato-checkout"></div-->

                <div class="">
                    <?php if ( wc_coupons_enabled() ) { ?>
                        <div class="coupon">
                            <label for="coupon_code" class="form-label mb-2 margin-promo"><?php esc_html_e( 'Inserisci codice promo', 'woocommerce' ); ?></label>
                            
                            <div class="d-flex align-items-center">
                                <input type="text" name="coupon_code" class="form-control input-promo fortablet2" style="border-top-left-radius: 5px!important;border-bottom-left-radius: 5px!important;" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Promo code', 'woocommerce' ); ?>" />
                                <button type="submit" class="btn btn-promo input-promo noacapo pb-2"  style="margin-left: -65px; border-top-left-radius: 5px !important; border-bottom-left-radius: 5px !important; border-top-right-radius: 5px !important; border-bottom-right-radius: 5px !important; display: flex; align-items: center; justify-content: center;" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Invia', 'woocommerce' ); ?></button>
                            </div>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="pt-4">                                        
                    <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
                    <div class="cart-collaterals">
                        <?php do_action( 'woocommerce_cart_collaterals' ); ?>
                    </div>
                </div>
                
                	
                	<!--utile per usare minus e plus quantità carrello-->
					<script>

						jQuery(document).ready(function($) {
							// Funzione che gestisce l'aggiornamento forzato del carrello
							function forceUpdateCart() {
								var cartContainer = $('.woocommerce-cart-form');

								cartContainer.block({
									message: null,
									overlayCSS: {
										background: '#fff',
										opacity: 0.6
									}
								});

								// Aggiunge un parametro univoco per disabilitare la cache del browser
								var cacheBuster = $.now();

								$.ajax({
									type: 'POST',
									url: woocommerce_params.ajax_url,
									data: {
										action: 'woocommerce_get_refreshed_fragments',
										_t: cacheBuster
									},
									success: function(response) {
										if (response && response.fragments) {
											// Aggiorna tutti i frammenti del carrello con i nuovi dati
											$.each(response.fragments, function(key, value) {
												$(key).replaceWith(value);
											});
										}
									},
									complete: function() {
										cartContainer.unblock();
									}
								});
							}

							// Gestione del click sui pulsanti di quantità (+ e -)
							$('.woocommerce-cart-form').on('click', '.quantity-button', function() {
								var button = $(this);
								var input_qty = button.siblings('.quantity').find('.qty');
								var current_value = parseFloat(input_qty.val());

								if (button.hasClass('plus')) {
									input_qty.val(current_value + 1);
								} else if (button.hasClass('minus')) {
									if (current_value > 1) {
										input_qty.val(current_value - 1);
									}
								}

								// Attiva l'aggiornamento forzato dopo un breve ritardo
								clearTimeout(window.wcUpdateCartTimer);
								window.wcUpdateCartTimer = setTimeout(function() {
									forceUpdateCart();
								}, 500);
							});

							// Gestione dell'input manuale nel campo quantità
							$('.woocommerce-cart-form').on('change', '.qty', function() {
								clearTimeout(window.wcUpdateCartTimer);
								window.wcUpdateCartTimer = setTimeout(function() {
									forceUpdateCart();
								}, 500);
							});
						});

					</script>
            </form>

        </div>
    </div>
</div>


		<div class="image-stack-container" style="margin-top:50px!important; background-color:#faf9f8!important;">
			<img src="<?php echo get_template_directory_uri() . '/ufficiale/sfondo-offerte-del-momento.svg'; ?>" alt="Immagine di sfondo" class="background-image">

			<div class="position-relative">
				<div style="position: absolute; width: 100%;z-index: 2;top:-30px;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-prodotto-bundle.svg'; ?>" class="" alt="">
				</div>
			</div>

			<div class="overlay-image-wrapper2">
				<img src="<?php echo get_template_directory_uri() . '/ufficiale/cane-nero.svg'; ?>" class="clipped-image2" alt="...">
			</div>
		</div>


		<div class="container pt-3"  style="background-color:#faf9f8!important;">
			<div class="primary fw-bold small">Bundle Prodotti</div>
			<h1 class="noacapo fs-2 mb-3" style="font-size:130%!important;">Aggiungi anche il nostro cibo</h1>

			<div class="row">
				<div class="col-12">
					<div class="">
						<p class="" style="font-size: 12px;">Aggiungendo alla tua ciotola per cani, il nostro cibo, risparmi sul prezzo!</p>



						<div class="conteggioaggmobile" style="margin-top:70px!important;" data-price="39.99">
								<!--<div class="item-details">
										<div class="item-name noacapo">Ciotola per cani</div>
										<div class="item-delivery noacapo small">Consegna il 23 giugno</div>
								</div>-->
								<div class="conteggio-numero w-100">
										<button class="quantity-button decrementmobile">-</button>
										<input type="text" class="quantity-input-mobile" value="1" min="1">
										<button class="quantity-button incrementmobile">+</button>
								</div>

								<a href="#" style="width:100%;color:#F87537!important;border-color:#F87537;border-style: solid;" class="btn noacapo bg-trasparente border-2 fw-bold fs-5 mt-3" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/shopping-cart-arancione.svg'; ?>" class="me-2" alt=""/>Aggiungi al carrello</a>

								<div class="item-price-mobile d-none">$39.99</div>
						</div>



						<div class="noacapo my-5 text-center">
							<div class="">
								<span class="text-secondary fs-4" id="sub-total-mobile">$39.99</span> +
								<span class="fs-4" id="loshippingmobile">$15.99</span>
								<!--
								<span class="float-end noacapo">
									<span class="text-secondary fs-4 me-2">Per un totale di:</span>
									<span class="fs-4" id="iltotal">$56.00</span>
								</span>
								-->
							</div>
						</div>

					</div>
				</div>
			</div>


		</div>

	</div>
       
       
       
    			<!--mobile-->
				<div class="container mt-5 mb-3 d-block d-sm-none">
							<div class="d-flex justify-content-between">

								<div class="col-md-6 fw-bold">
									<h1 class="fs-2 float-start">Prodotti correlati</h1>
								</div>

								<div class="col-md-6">

								</div>

							</div>
						</div>
       
       
       <!--mobile-->
       <div class="container d-block d-sm-none">
		      <div class="row g-2">

		        <div class="col-6">

		          <div class="scheda-prodotto">
		            <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

		            <div class="container">
		              <div class="row align-items-start pb-3">
		                <div class="col px-0">
		                  <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
		                </div>
		                <div class="col px-0 mt-2">
		                  <div class="d-flex align-items-center justify-content-center">
		                    <div class="small me-auto">$20,99</div> <div class="d-flex align-items-center">
		                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1"> <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
		                    </div>
		                  </div>
		                </div>
		              </div>
		            </div>
		        </div>

		        </div>

		        <div class="col-6">

		          <div class="scheda-prodotto">
		            <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
		            <div class="container ">
		              <div class="row align-items-start pb-3">
		                <div class="col px-0">
		                  <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
		                </div>
		                <div class="col px-0 mt-2">
		                  <div class="d-flex align-items-center justify-content-center">
		                    <div class="small me-auto">$20,99</div> <div class="d-flex align-items-center">
		                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1"> <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
		                    </div>
		                  </div>
		                </div>
		              </div>
		            </div>
		        </div>

		        </div>

		        <div class="col-6">

		          <div class="scheda-prodotto">
		            <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
		            <div class="container">
		              <div class="row align-items-start pb-3">
		                <div class="col px-0">
		                  <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
		                </div>
		                <div class="col px-0 mt-2">
		                  <div class="d-flex align-items-center justify-content-center">
		                    <div class="small me-auto">$20,99</div> <div class="d-flex align-items-center">
		                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1"> <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
		                    </div>
		                  </div>
		                </div>
		              </div>
		            </div>
		        </div>

		        </div>

		        <div class="col-6">

		          <div class="scheda-prodotto">
		            <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
		            <div class="container">
		              <div class="row align-items-start pb-3">
		                <div class="col px-0">
		                  <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
		                </div>
		                <div class="col px-0 mt-2">
		                  <div class="d-flex align-items-center justify-content-center">
		                    <div class="small me-auto">$20,99</div> <div class="d-flex align-items-center">
		                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1"> <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
		                    </div>
		                  </div>
		                </div>
		              </div>
		            </div>
		        </div>

		        </div>

		      </div>
		      </div><!--fine container-->
		      
		      
		      
		      <!--utile per simulare il click sul pulsante "aggiorna carrello"-->
		      <script>
				jQuery(document).ready(function($) {
					// Funzione per aggiornare il carrello
					function updateCart(form) {
						var updateCartBtn = form.find('button[name="update_cart"]');
						if (updateCartBtn.length) {
							updateCartBtn.trigger('click');
						}
					}

					// Ascolta i cambiamenti nel campo della quantità nella versione desktop
					$('.woocommerce-cart-form .qty').on('change', function() {
						var currentForm = $(this).closest('.woocommerce-cart-form');
						clearTimeout(window.wcUpdateCartTimer);
						window.wcUpdateCartTimer = setTimeout(function() {
							updateCart(currentForm);
						}, 500);
					});

					// Ascolta i click sui pulsanti di incremento/decremento nella versione mobile
					$('.woocommerce-cart-form').on('click', '.quantity-button', function() {
						var currentForm = $(this).closest('.woocommerce-cart-form');
						clearTimeout(window.wcUpdateCartTimer);
						window.wcUpdateCartTimer = setTimeout(function() {
							updateCart(currentForm);
						}, 500);
					});
				});
			</script>	     
       
       
       
        
        
        

<?php do_action( 'woocommerce_after_cart' ); ?>





	</body>
</html>






