<!DOCTYPE html>
<html>
<style>
    /* Nasconde le frecce di default di input number su Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    /* Nasconde le frecce di default su Firefox */
    input[type="number"] {
        -moz-appearance: textfield;
    }
    /* Nasconde i pulsanti di default di WooCommerce */
    .quantity .quantity-button {
        display: none;
    }
    .qty {
        border: none !important;
    }
    
    /* Regole Flexbox per allineamento */
    .form-buttons-container {
        display: flex;
        align-items: center;
        width: 100%;
    }
    
    .form-buttons-container .conteggio-numero {
        flex-grow: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .form-buttons-container .quantity-button {
        margin: 0 5px;
    }
    
    .form-buttons-container .single_add_to_cart_button {
        flex-grow: 1;
        margin-left: 10px; /* Spazio tra quantità e Aggiungi */
    }
    
    .form-buttons-container .buy-now-form {
        margin-left: 10px; /* Spazio tra Aggiungi e Acquista */
    }
	
	
	@media (min-width:1024px) {
		.marginaggiungi {
			margin-right: 1rem !important;
			font-size: 1.25rem !important;
		}
		
		.contenitore{
		display: flex!important;
		}
	}
	
	
	/*inizio utile alla versione tablet*/
	
	/* Stile per il layout tablet (usando una media query) */
	@media screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {

	  .elemento-che-va-a-capo {
		max-width: 100%!important; /* Forzalo a prendere tutta la riga */
	  }
		
		.marginaggiungi {
		margin-right: 0px !important;
			height: 60px!important;
		}
		
		.mt-tablet {
			margin-top: 1rem !important;
		}
	}
	/*fine utile alla versione tablet*/
    
    
    
</style>
<body>
<?php
/**
 * The Template for displaying all single products
 *
 * @package WooCommerce\Templates
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header( 'shop' );
?>

<?php while ( have_posts() ) : the_post(); ?>
<?php wc_print_notices(); ?>


<!--desktop-->
<div class="container tra-menu-e-titolo d-none d-md-block">
    <div class="row">
        <div class="col-md-6 ps-0 pe-4">
            <div class="position-relative">
               	<div style="position: absolute; top: 40px; left: -33px; z-index: -1;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-alto-sinistra-gallery.svg'; ?>" class="" alt="Immagine Alto Sinistra 1">
				</div>
				<div style="position: absolute; top: 110px; left: -45px; z-index: 1;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-alto-sinistra-gallery.svg'; ?>" class="" alt="Immagine Alto Sinistra 2">
				</div>
                <div>
                    <div>
                        <?php do_action( 'woocommerce_before_single_product_summary' ); ?>
                    </div>
                </div>
            </div>
            <div class="mt-4 position-relative">
                <div class="pt-tablet">
					<div style="position: absolute; bottom: 237px; right: -38px; z-index: 1; display: flex;">
						<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-basso-destra-gallery.svg'; ?>" class="" alt="Immagine Basso Destra 1">
					</div>
					<div style="position: absolute; bottom: 163px; right: -25px; z-index: -1; display: flex;">
						<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-basso-destra-gallery.svg'; ?>" class="" alt="Immagine Basso Destra 2">
					</div>
				</div>
            </div>
        </div>

        <div class="col-md-6 pe-0 ps-4 d-flex flex-column position-relative">
            <div>
                <div class="row align-items-start">
                    <div class="col-md-6">
                        <div class="noacapo">
                            <h1><?php the_title(); ?></h1>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                       	<div class="immagine-sfondo immagine-sfondo-alto">
							<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-trasparenti-in-alto-descrizione-prodotto-singolo-annuncio-frame.svg'; ?>" alt="Immagine in alto">
					  	</div>
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-sfondo-grigio.svg'; ?>" class="" alt="Cuore">
                    </div>
                </div>

                <div class="acapo">
                    <?php
                    global $product;
                    $regular_price = $product->get_regular_price();
                    $sale_price    = $product->get_sale_price();

                    if ( $product->is_on_sale() && $sale_price ) {
                        echo '<div>
                            <span class="price" data-price="' . esc_attr( $sale_price ) . '">' . wc_price( $sale_price ) . '</span>
                            <span class="text-secondary text-decoration-line-through" style="">' . wc_price( $regular_price ) . '</span>
                        </div>';
                    } else {
                        echo '<div>
                            <span class="price" data-price="' . esc_attr( $regular_price ) . '">' . wc_price( $regular_price ) . '</span>
                        </div>';
                    }
                    ?>
                </div>

                <hr class="my-4">
                <a href="#">Descrizione <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt="Freccia"></a>
                <div class="acapo small text-secondary" style="margin-top:10px">
                    <?php woocommerce_template_single_excerpt(); ?>
                </div>
                <hr class="">

                <?php
                if ( $product->is_type( 'simple' ) ) {
                ?>
                <div class="contenitore" style="min-height: 60px;">
    <form class="cart d-flex align-items-stretch flex-grow-1" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" method="post" enctype="multipart/form-data">
        <div class="conteggio-numero d-flex align-items-center justify-content-center me-3" style="border:1px solid #ddd;height: 60px!important;">
            <button type="button" class="quantity-button check-decrement btn btn-sm">-</button>
            <?php
            woocommerce_quantity_input(
                array(
                    'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
                    'min_value'   => $product->get_min_purchase_quantity(),
                    'max_value'   => $product->get_max_purchase_quantity(),
                )
            );
            ?>
            <button type="button" class="quantity-button check-increment btn btn-sm">+</button>
        </div>
        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
        <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button btn  bg-trasparente border-3 fw-bold d-flex align-items-center justify-content-center flex-grow-1 marginaggiungi text-nowrap fortablet" style="color:#F87537!important;border-color:#F87537;border-style: solid;">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/shopping-cart-arancione.svg'; ?>" class="me-3" alt=""/>Aggiungi al carrello
        </button>
    </form>
    <form class="buy-now-form d-flex " action="<?php echo esc_url( wc_get_checkout_url() ); ?>" method="post">
        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
        <input type="hidden" name="quantity" class="buy-now-quantity-input" value="1">
        <button type="submit" class="buy-now-button btn btn-warning elemento-che-va-a-capo d-flex align-items-center justify-content-center fs-5 mt-tablet w-100" style="">
            Acquista
        </button>
    </form>
</div>
                <?php
                } else {
                    woocommerce_template_single_add_to_cart();
                }
                ?>

                <hr class="">
                <div class="spaz-guarantee">
                    <div class="d-flex align-items-center mb-2">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/corriere.svg'; ?>" alt="Corriere" class="me-2">
                        <div class="small"><span class="fw-bold">Data di arrivo:</span> <span class="text-secondary">Giugno 30 - Agosto 03</span></div>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/pacco.svg'; ?>" alt="Pacco" class="me-2">
                        <div class="small"><span class="fw-bold">Tempo di rimborso:</span> <span class="text-secondary">Giugno 30 - Agosto 03</span></div>
                    </div>
                </div>
            </div>
            <div class="immagine-sfondo immagine-sfondo-basso-destra">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-trasparenti-in-basso-descrizione-prodotto-singolo-annuncio-frame.svg'; ?>" alt="Impronte in basso a destra">
            </div>
            <div class="mt-auto py-3" style="background-color: #faf9f8!important;">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/carte-di-credito-acquisti.svg'; ?>" class="mx-auto d-block img-fluid" alt="carte credito">
                <div class="text-center small mt-2">Guarantee safe & secure checkout</div>
            </div>
        </div>
    </div>
</div>


<!--mobile-->
<div class="d-block d-sm-none" id="mobile-product-container">
    <div class="container mt-4">
        <div class="row">
            <?php woocommerce_show_product_images(); ?>
        </div>
    </div>
    
    <div class="container position-relative ">
        <div class="immagine-sfondo" style="top:90px;left:280px;transform: translateX(-50%);max-width: 80%;height: auto;">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-trasparenti-in-alto-descrizione-prodotto-singolo-annuncio-frame.svg'; ?>" alt="Immagine in alto">
        </div>
        <div class="d-flex align-items-center justify-content-center pt-5">
            <h1 class="me-auto fs-2 my-0"><?php the_title(); ?></h1>
            <div class="d-flex align-items-center">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-sfondo-grigio.svg'; ?>" class="" alt="Immagine 2">
            </div>
        </div>

        <div class="acapo">
            <?php
            global $product;
            $regular_price = $product->get_regular_price();
            $sale_price    = $product->get_sale_price();

            if ( $product->is_on_sale() && $sale_price ) {
                echo '<div>
                    <span class="price" data-price="' . esc_attr( $sale_price ) . '">' . wc_price( $sale_price ) . '</span>
                    <span class="text-secondary text-decoration-line-through" style="">' . wc_price( $regular_price ) . '</span>
                </div>';
            } else {
                echo '<div>
                    <span class="price" data-price="' . esc_attr( $regular_price ) . '">' . wc_price( $regular_price ) . '</span>
                </div>';
            }
            ?>
        </div>

        <hr style="margin-bottom:25px!important;margin-top:20px!important;">
        <a href="#">Descrizione <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
        <div class="acapo small text-secondary" style="margin-top:10px">
            <?php woocommerce_template_single_excerpt(); ?>
        </div>

        <hr style="margin-bottom:1.3cm!important;margin-top:0.9cm!important;">

        <?php
        if ( $product->is_type( 'simple' ) ) {
        ?>
        <div class="d-flex flex-column align-items-stretch mobile-form-container" style="height: auto;">
            <form class="cart d-flex w-100 flex-column align-items-stretch mb-3" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" method="post" enctype="multipart/form-data">
                <div class="conteggio-numero d-flex align-items-center justify-content-center w-100 mb-3" style="min-height: 60px; border:1px solid #ddd;">
                    <button type="button" class="quantity-button check-decrement btn btn-sm">-</button>
                    <?php
                    woocommerce_quantity_input(
                        array(
                            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
                            'min_value'   => $product->get_min_purchase_quantity(),
                            'max_value'   => $product->get_max_purchase_quantity(),
                        )
                    );
                    ?>
                    <button type="button" class="quantity-button check-increment btn btn-sm">+</button>
                </div>
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
                <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button btn noacapo bg-trasparente border-2 w-100 fw-bold fs-5 d-flex align-items-center justify-content-center" style="color:#F87537!important;border-color:#F87537;border-style: solid; min-height: 60px;">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/shopping-cart-arancione.svg'; ?>" class="me-2" alt=""/>Aggiungi al carrello
                </button>
            </form>
            <form class="buy-now-form w-100" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" method="post">
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
                <input type="hidden" name="quantity" class="buy-now-quantity-input" value="1">
                <button type="submit" class="buy-now-button btn btn-warning noacapo d-flex align-items-center justify-content-center w-100 fs-5" style="min-height: 60px;">
                    Acquista
                </button>
            </form>
        </div>
        <?php
        } else {
            woocommerce_template_single_add_to_cart();
        }
        ?>
    </div>
    <div class="container mb-4 mt-5" style="">
        <div class="row">
            <div class="d-flex align-items-center mb-2">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/corriere.svg'; ?>" alt="Immagine" class="me-2">
                <div class="small"><span class="fw-bold">Data di arrivo:</span> <span class="text-secondary">Giugno 30 - Agosto 03</span></div>
            </div>
            <div class="d-flex align-items-center">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/pacco.svg'; ?>" alt="Immagine" class="me-2">
                <div class="small"><span class="fw-bold">Tempo di rimborso:</span> <span class="text-secondary">Giugno 30 - Agosto 03</span></div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="" style="background-color: #faf9f8!important;">
            <div class="row">
                <div class="mt-auto py-3">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carte-di-credito-acquisti.svg'; ?>" class="mx-auto d-block img-fluid" alt="carte credito">
                    <div class="text-center small mt-2">Guarantee safe & secure checkout</div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--mobile-->
<div class="d-block d-sm-none">
    <div class="image-stack-container" style="margin-top:70px!important; background-color:#faf9f8!important;">
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
<div class="container d-block d-sm-none mt-4">
    <div class="row">
        <div class="col-md-12 fw-bold">
            <h1 class="fs-2 float-start">Recensioni</h1>
        </div>
    </div>
</div>

<!--mobile-->
<div class="container d-block d-sm-none margine-standard">
    <div class="row">
        <div class="col-md-12">
            <div class="p-3 bg-light">
                <p class="small fw-bold text-secondary">Titolo recensione</p>
                <p class="float-start small text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
                <div class="pt-5">
                    <div class="row flex-nowrap align-items-center">
                        <div class="col-auto d-flex align-items-center">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
                        </div>
                        <div class="col-auto small noacapo d-flex align-items-center ms-2 text-secondary">Postato 13 giugno 2025</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--mobile-->
<div class="container d-block d-sm-none margine-standard">
    <div class="row">
        <div class="col-md-12">
            <div class="p-3 bg-light">
                <p class="small fw-bold text-secondary">Titolo recensione</p>
                <p class="float-start small text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
                <div class="pt-5">
                    <div class="row flex-nowrap align-items-center">
                        <div class="col-auto d-flex align-items-center">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
                        </div>
                        <div class="col-auto small noacapo d-flex align-items-center ms-2 text-secondary">Postato 13 giugno 2025</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--mobile-->
<div class="container d-block d-sm-none margine-standard">
    <div class="row">
        <div class="col-md-12">
            <div class="p-3 bg-light">
                <p class="small fw-bold text-secondary">Titolo recensione</p>
                <p class="float-start small text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
                <div class="pt-5">
                    <div class="row flex-nowrap align-items-center">
                        <div class="col-auto d-flex align-items-center">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
                        </div>
                        <div class="col-auto small noacapo d-flex align-items-center ms-2 text-secondary">Postato 13 giugno 2025</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--mobile-->
<div class="container d-block d-sm-none margine-standard">
    <div class="row">
        <div class="col-md-12">
            <div class="p-3 bg-light">
                <p class="small fw-bold text-secondary">Titolo recensione</p>
                <p class="float-start small text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
                <div class="pt-5">
                    <div class="row flex-nowrap align-items-center">
                        <div class="col-auto d-flex align-items-center">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
                        </div>
                        <div class="col-auto small noacapo d-flex align-items-center ms-2 text-secondary">Postato 13 giugno 2025</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--desktop-->
<div class="container background-bundle d-none d-md-block">
    <div class="row">
        <div class="col-md-6 sfondo-crocchette-bundle ps-desk ps-annuncio" style="">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/cane-nero.svg'; ?>" class="img-fluid" alt="...">
        </div>
        <div class="col-md-6 pd-desk pd-annuncio" style="">
            <div class="primary fw-bold small">Bundle Prodotti</div>
            <h1 class="fs-2 ">
                <div class="h1tablet">
                    Aggiungi anche il nostro cibo
                </div>
            </h1>
            <div class="row">
                <div class="col-12">
                    <div class="">
                        <p class="" style="font-size: 12px;">Aggiungendo alla tua ciotola per cani, il nostro cibo, risparmi sul prezzo!</p>
                        <hr>
                        <div class="d-flex align-items-center mt-2 conteggioagg" data-price="39.99">
						  <!--<div class="item-details">
							  <div class="item-name noacapo">Ciotola per cani</div>
							  <div class="item-delivery noacapo small">Consegna il 23 giugno</div>
						  </div>-->
						  <div class="conteggio-numero">
							  <button class="quantity-button decrement">-</button>
							  <input type="text" class="quantity-input" value="1" min="1">
							  <button class="quantity-button increment">+</button>
						  </div>

						  <a href="#" style="width:100%;color:#F87537!important;border-color:#F87537;border-style: solid;" class="btn noacapo bg-trasparente border-3 ms-3 fw-bold fs-5" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/shopping-cart-arancione.svg'; ?>" class="me-2" alt=""/>Aggiungi al carrello</a>

						  <div class="item-price d-none">$39.99</div>
					  	</div>
                       
                        <hr>
                        
                        <div class="noacapo riga-conta-sped-annuncio">
							<div class="">
								<span class="conta-sped-annuncio">
									  <span class="text-secondary fs-4" id="sub-total">$39.99</span> +
									  <span class="fs-4" id="loshipping">$15.99</span>
								</span>

							    <span class="float-end noacapo">
									<span class="text-secondary fs-4 me-2">Per un totale di:</span>
									<span class="fs-4" id="iltotal">$56.00</span>
							    </span>
							</div>
						</div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--desktop-->
<div class="container d-none d-md-block">
    <div class="row tra-menu-e-titolo">
        <div class="col-md-12 fw-bold m-0 p-0">
            <h1 class="fs-2 float-start">Recensioni</h1>
        </div>
    </div>
</div>

<!--desktop-->
<div class="container d-none d-md-block margine-standard">
    <div class="row">
        <div class="col-md-12 bg-light">
            <div class="pt-3 ps-2">
                <p class="small fw-bold">Titolo recensione</p>
                <p class="float-start small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
                <p class="float-end small">Postato 13 giugno 2025</p>
                <p class="pt-5">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
                </p>
            </div>
        </div>
    </div>
</div>

<!--desktop-->
<div class="container d-none d-md-block margine-standard">
    <div class="row">
        <div class="col-md-12 bg-light">
            <div class="pt-3 ps-2">
                <p class="small fw-bold">Titolo recensione</p>
                <p class="float-start small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
                <p class="float-end small">Postato 13 giugno 2025</p>
                <p class="pt-5">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
                </p>
            </div>
        </div>
    </div>
</div>

<!--desktop-->
<div class="container d-none d-md-block margine-standard">
    <div class="row">
        <div class="col-md-12 bg-light">
            <div class="pt-3 ps-2">
                <p class="small fw-bold">Titolo recensione</p>
                <p class="float-start small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
                <p class="float-end small">Postato 13 giugno 2025</p>
                <p class="pt-5">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
                </p>
            </div>
        </div>
    </div>
</div>

<!--desktop-->
<div class="container d-none d-md-block margine-standard">
    <div class="row">
        <div class="col-md-12 bg-light">
            <div class="pt-3 ps-2">
                <p class="small fw-bold">Titolo recensione</p>
                <p class="float-start small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
                <p class="float-end small">Postato 13 giugno 2025</p>
                <p class="pt-5">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
                </p>
            </div>
        </div>
    </div>
</div>

<!--desktop-->
<div class="spazio-standard d-none d-md-block"></div>


<!--mobile-->
<div class="container mt-5 mb-3 d-block d-sm-none">
    <div class="d-flex justify-content-between">
        <div class="col-md-6 fw-bold">
            <h1 class="noacapo float-start fs-2">Categoria tra animali</h1>
        </div>
        <div class="col-md-6"></div>
    </div>
</div>

<!--desktop-->
<div class="container px-0 mb-5 d-none d-md-block">
    <div class="d-flex justify-content-between">
        <div class="col-md-6 fw-bold p-0">
            <h1 class="noacapo float-start fs-2">Categorie che potrebbero interessanti</h1>
        </div>
        <div class="col-md-6 py-2 m-0 px-0">
            <div class="small float-end">
                <a href="#">Vai allo shop <img src="<?php echo get_template_directory_uri() . '/ufficiale/freccia-destra.svg'; ?>" alt=""/></a>
            </div>
        </div>
    </div>
</div>

<div class="container px-0">
    <div class="d-flex justify-content-between flex-wrap">
        <div class="col-6 col-md-3 mx-0 px-0">
            <div class="text-center">
                <a href="#">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-cani.svg'; ?>" class="mx-auto d-block img-fluid"/>
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3 mx-0 px-0">
            <div class="text-center">
                <a href="#">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-gatti.svg'; ?>" class="mx-auto d-block img-fluid"/>
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3 mx-0 px-0">
            <div class="text-center">
                <a href="#">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-uccelli.svg'; ?>" class="mx-auto d-block img-fluid"/>
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3 mx-0 px-0">
            <div class="text-center">
                <a href="#">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-criceti.svg'; ?>" class="mx-auto d-block img-fluid"/>
                </a>
            </div>
        </div>
    </div>
</div>

<!--desktop-->
<div class="container px-0 tra-menu-e-titolo mb-5 d-none d-md-block">
    <div class="d-flex justify-content-between">
        <div class="col-md-6 fw-bold p-0">
            <h1 class="fs-2 float-start">Prodotti correlati</h1>
        </div>
        <div class="col-md-6 py-2 mx-0 px-0">
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
        <div class="col-md-6"></div>
    </div>
</div>

<div class="container p-zero"><div class="row g-2 g-md-4">
        <div class="col-md-3 col-6">
            <div class="scheda-prodotto">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
                
                <!--desktop-->
                <div class="container d-none d-md-block">
                    <div class="row align-items-start pb-3">
                        <div class="col-md-6">
                            <div class="noacapo fortablet2">Ciotola per cani</div>
                            <div class="acapo small">$20,99</div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                        </div>
                    </div>
                </div>
                
                <!--mobile-->
                <div class="container d-block d-sm-none">
                    <div class="row align-items-start pb-3">
                        <div class="col px-0">
                            <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
                        </div>
                        <div class="col px-0 mt-2">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="small me-auto">$20,99</div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="scheda-prodotto">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
                
                <!--desktop-->
                <div class="container d-none d-md-block">
                    <div class="row align-items-start pb-3">
                        <div class="col-md-6">
                            <div class="noacapo fortablet2">Ciotola per cani</div>
                            <div class="acapo small">$20,99</div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                        </div>
                    </div>
                </div>
                
                <!--mobile-->
                <div class="container d-block d-sm-none">
                    <div class="row align-items-start pb-3">
                        <div class="col px-0">
                            <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
                        </div>
                        <div class="col px-0 mt-2">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="small me-auto">$20,99</div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="scheda-prodotto">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
                
                <!--desktop-->
                <div class="container d-none d-md-block">
                    <div class="row align-items-start pb-3">
                        <div class="col-md-6">
                            <div class="noacapo fortablet2">Ciotola per cani</div>
                            <div class="acapo small">$20,99</div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                        </div>
                    </div>
                </div>
                
                <!--mobile-->
                <div class="container d-block d-sm-none">
                    <div class="row align-items-start pb-3">
                        <div class="col px-0">
                            <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
                        </div>
                        <div class="col px-0 mt-2">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="small me-auto">$20,99</div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="scheda-prodotto">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
                
                <!--desktop-->
                <div class="container d-none d-md-block">
                    <div class="row align-items-start pb-3">
                        <div class="col-md-6">
                            <div class="noacapo fortablet2">Ciotola per cani</div>
                            <div class="acapo small">$20,99</div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                        </div>
                    </div>
                </div>
                
                <!--mobile-->
                <div class="container d-block d-sm-none">
                    <div class="row align-items-start pb-3">
                        <div class="col px-0">
                            <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
                        </div>
                        <div class="col px-0 mt-2">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="small me-auto">$20,99</div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
</div>

<?php endwhile; ?>
<?php get_footer( 'shop' ); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funzione per gestire l'interazione con entrambi i form (desktop e mobile)
        const setupProductForm = (container) => {
            const form = container.querySelector('form.cart');
            const priceElement = container.querySelector('.price');

            if (!form || !priceElement) {
                return;
            }

            const quantityInput = form.querySelector('input.qty');
            const initialPrice = parseFloat(priceElement.dataset.price);
            const buyNowQuantityInput = container.querySelector('.buy-now-quantity-input');

            const updatePriceAndQuantity = () => {
                const currentQuantity = parseInt(quantityInput.value);
                if (!isNaN(currentQuantity) && buyNowQuantityInput) {
                    const newPrice = initialPrice * currentQuantity;
                    const formattedPrice = newPrice.toLocaleString('it-IT', { style: 'currency', currency: 'EUR' });
                    priceElement.textContent = formattedPrice;
                    buyNowQuantityInput.value = currentQuantity;
                }
            };

            const decrementButton = form.querySelector('.check-decrement');
            const incrementButton = form.querySelector('.check-increment');

            if (decrementButton && incrementButton) {
                decrementButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    let value = parseInt(quantityInput.value);
                    let min = parseInt(quantityInput.getAttribute('min'));
                    if (value > min) {
                        quantityInput.value = value - 1;
                        updatePriceAndQuantity();
                    }
                });

                incrementButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    let value = parseInt(quantityInput.value);
                    let max = quantityInput.getAttribute('max');
                    if (max === null || value < parseInt(max)) {
                        quantityInput.value = value + 1;
                        updatePriceAndQuantity();
                    }
                });
            }

            quantityInput.addEventListener('input', updatePriceAndQuantity);
        };

        // Applica la logica ai contenitori desktop e mobile
        const desktopContainer = document.querySelector('.container.tra-menu-e-titolo.d-none.d-md-block');
        const mobileContainer = document.getElementById('mobile-product-container');

        if (desktopContainer) {
            setupProductForm(desktopContainer);
        }
        if (mobileContainer) {
            setupProductForm(mobileContainer);
        }
    });
</script>

</body>
</html>