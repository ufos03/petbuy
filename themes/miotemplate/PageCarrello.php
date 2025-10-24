<?php
/*
*
* Template Name: PageCarrello
* Description: pagina carrello
*
*/
get_header();
?>

<div class="container tra-menu-e-titolo d-none d-md-block">
	<div class="row">
		<div class="col-md-8 pe-5">
			<h1>Il tuo carrello</h1>
			<hr class="mb-5">

			<?php
			// Avvia il loop di WooCommerce per ogni prodotto nel carrello.
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>

					<div class="row">
						<div class="col-md-3 position-relative bone-product-wrapper">
							<div class="paw-print-sx">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-sx-prodotto1.svg'; ?>" alt="Impronte zampa sinistra">
							</div>
							<div class="paw-print-dx">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-dx-prodotto1.svg'; ?>" alt="Impronte zampa destra">
							</div>
							<?php
							// Immagine del prodotto dinamica
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
							if ( ! $product_permalink ) {
								echo $thumbnail;
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
							}
							?>
						</div>

						<div class="col-md-9">
							<div class="d-flex align-items-center mt-2 ilconteggio" data-price="<?php echo esc_attr( $_product->get_price() ); ?>">
								<div class="item-details">
									<div class="item-name noacapo fortablet2">
										<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										}
										?>
									</div>
									<div class="item-delivery noacapo small fortablet">Consegna il 23 giugno</div>
								</div>

								
								<div class="item-quantity-carrello">
    <button class="quantity-button decrement">-</button>
    <input
        type="text"
        class="quantity-input"
        name="cart[<?php echo $cart_item_key; ?>][qty]"
        value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
        min="0"
        max="<?php echo esc_attr( $_product->get_max_purchase_quantity() ); ?>"
    >
    <button class="quantity-button increment">+</button>
</div>
								
								

								<div class="item-price-carrello">
									<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
								</div>
							</div>

							<div class="d-flex justify-content-between align-items-end mb-2 m-carrello">
								<div class="col-auto text-start fortablet">
									<a href="#">Modifica</a>
								</div>
								<div class="col-auto text-center fortablet">
									<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_html__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() ),
										esc_html__( 'Rimuovi', 'woocommerce' )
									), $cart_item_key );
									?>
								</div>
								<div class="col-auto text-center fortablet">
									<a href="#">Trasferisci alla wishlist</a>
								</div>
								<div class="col-auto text-end fortablet">
									<a href="#">Salva per dopo</a>
								</div>
							</div>
						</div>
					</div>
					<hr class="my-5">
					<?php
				}
			}
			?>

		</div>
		<div class="col-md-4 ps-4">
		
			<label for="promoCode" class="mb-2 margin-promo">Inserisci codice promo</label>				
			
			<form class="checkout_coupon woocommerce-form-coupon" method="post">
				<div class="coupon d-flex align-items-center pb-4">
					<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
					<input type="text" name="coupon_code" class="input-text form-control input-promo fortablet2" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Promo code', 'woocommerce' ); ?>" />
					<button type="submit" class="btn btn-promo input-promo noacapo fs-5 pb-2 button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Invia', 'woocommerce' ); ?></button>
				</div>
				<input type="hidden" name="action" value="woocommerce_apply_coupon" />
				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</form>
			
			<?php
			// Totali del carrello, coupon, e pulsante di checkout dinamici
			woocommerce_cart_totals();
			?>
			
			<div class="position-relative">
				<div style="position: absolute; top: -18px; left: -20px; z-index: 1;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-sx-checkout.svg'; ?>" class="" alt="Immagine2 sx chechout">
				</div>
				<div style="position: absolute; top: 8px; left: -5px; z-index: 1;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-sx-checkout.svg'; ?>" class="" alt="Immagine1 sx checkout">
				</div>
			</div>
			<div class="position-relative">
				<div style="position: absolute; top: -25px; right: -75px; z-index: 1;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-basso-checkout.svg'; ?>" class="" alt="impronte in basso">
				</div>
			</div>
			<div class="background-aggiungi px-2 pb-5">
				<div class="sfondo-cane-nero-aggiungi">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/cane-nero.svg'; ?>" class="mx-auto d-block" alt="..." style="width: 60%;">
				</div>
				<div class="primary fw-bold small mt-5 text-center">Bundle Prodotti</div>
				<h1 class="text-center px-3 lh-base">Aggiungi anche il nostro cibo</h1>
				<p class="small px-4 text-center">Aggiungendo alla tua ciotola per cani, il nostro cibo, risparmi sul prezzo!</p>
				<a href="#" style="width:100%;color:#F87537!important;border-color:#F87537;border-style: solid;" class="btn noacapo bg-trasparente border-3 fw-bold fortablet2" role="button">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/shopping-cart-arancione.svg'; ?>" class="me-2" alt=""/>Aggiungi al carrello
				</a>
			</div>
		</div>
	</div>
</div>



<div class="d-block d-sm-none sfondo-pcm">
	<div class="container">
		<?php
		// Inserisci qui il loop per i prodotti
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<div class="bordo-centrato-checkout"></div>
				<div class="cart-item px-0 check-conteggio-checkout-mobile" data-price="<?php echo esc_attr( $_product->get_price() ); ?>">
					<div class="item-image">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						if ( ! $product_permalink ) {
							echo $thumbnail;
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
						}
						?>
					</div>
					<div class="col">
						<div class="item-details">
							<div class="item-name noacapo"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></div>
							<div class="item-delivery noacapo small">Consegna il 23 giugno</div>
						</div>
						<div class="" style="display: flex; align-items: center; justify-content: space-between; width:100%;">
							
							
							<div class="item-quantity-carrello">
    <button class="quantity-button decrement">-</button>
    <input
        type="text"
        class="quantity-input"
        name="cart[<?php echo $cart_item_key; ?>][qty]"
        value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
        min="0"
        max="<?php echo esc_attr( $_product->get_max_purchase_quantity() ); ?>"
    >
    <button class="quantity-button increment">+</button>
</div>
							
							
							<div class="item-image p-0 m-0">
								<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><img src="%s" class="cestina" alt="cestino"/></a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_html__( 'Remove this item', 'woocommerce' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() ),
									esc_url( get_template_directory_uri() . '/ufficiale/cestino.svg' )
								) );
								?>
							</div>
						</div>
					</div>
					<div class="check-item-price-checkout-mobile d-none">
						<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
					</div>
				</div>
				<?php
			}
		}
		?>
		<div class="bordo-centrato-checkout"></div>
		<?php
		// Totali del carrello dinamici per mobile
		do_action( 'woocommerce_cart_collaterals' );
		?>
		<div class="modal-footer mt-3 px-0">
			<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" style="width:100%;" class="btn btn-warning noacapo" role="button">
				<div class="font-shop">
					<span id="" class="js-total-display"><?php echo WC()->cart->get_cart_total(); ?></span> - Procedi al check out
				</div>
			</a>
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
	<div class="container pt-3" style="background-color:#faf9f8!important;">
		<div class="primary fw-bold small">Bundle Prodotti</div>
		<h1 class="noacapo fs-2 mb-3" style="font-size:130%!important;">Aggiungi anche il nostro cibo</h1>
		<div class="row">
			<div class="col-12">
				<div class="">
					<p class="" style="font-size: 12px;">Aggiungendo alla tua ciotola per cani, il nostro cibo, risparmi sul prezzo!</p>
					<div class="conteggioaggmobile" style="margin-top:70px!important;" data-price="39.99">
						<div class="conteggio-numero w-100">
							<button class="quantity-button decrementmobile">-</button>
							<input type="text" class="quantity-input-mobile" value="1" min="1">
							<button class="quantity-button incrementmobile">+</button>
						</div>
						<a href="#" style="width:100%;color:#F87537!important;border-color:#F87037;border-style: solid;" class="btn noacapo bg-trasparente border-2 fw-bold fs-5 mt-3" role="button">
							<img src="<?php echo get_template_directory_uri() . '/ufficiale/shopping-cart-arancione.svg'; ?>" class="me-2" alt=""/>Aggiungi al carrello
						</a>
						<div class="item-price-mobile d-none">$39.99</div>
					</div>
					<div class="noacapo my-5 text-center">
						<div class="">
							<span class="text-secondary fs-4" id="sub-total-mobile">$39.99</span> +
							<span class="fs-4" id="loshippingmobile">$15.99</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
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









<?php

//get footer.php file
get_footer();


?>








