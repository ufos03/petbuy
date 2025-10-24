<html>

<style>
	
	h3 {
		display: none!important;
	}
	
	input {
		border: none!important;
	}
	
	body.woocommerce-checkout .form-row {
		margin-bottom: 20px; /* Regola il valore per aumentare o diminuire la spaziatura */
	}
	
	.select2-selection {
		border: none!important;
		color: aquamarine!important;
	}
	
	.select2-selection__placeholder {
		color: #757575!important;
	}
	
	.woocommerce-checkout-payment .wc_payment_methods {
		display: none !important;
	}
	
	#payment {
		display: none!important;
	}
	
	#aggiornamenti_novita {
		width: 18px !important;
		height: 18px !important;
	}
	
	.optional {
		display: none!important;
	}
	
	
	
	@media (max-width: 768px) {
		/* Assicura che i campi di nome e cognome occupino il 100% della larghezza */
		#billing_first_name_field,
		#billing_last_name_field, 
		#billing_dokan_bank_name_field, 
		#billing_dokan_bank_iban_field {
			width: 100% !important;
			float: none !important;
			clear: both !important;
		}
	}
	
</style>

<body>



<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<div class="tra-menu-e-titolo">
	<div class="mb-0">
		<div class="container">
			<div class="row position-relative">
				<div class="col-md-8 sfondo-checkout" style="z-index:1!important;">

					<div class="d-flex justify-content-between align-items-center mb-3 tra-menu-e-titolo">
						<div class="col-md-6 fw-bold py-2">
							<h1 class="m-0 float-start fs-2">Contatti</h1>
						</div>
						<div class="col-md-6 py-2">
							<div class="small float-end text-decoration-underline">
								<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">Fai il login al tuo account</a>
							</div>
						</div>
					</div>

					<form name="checkout" method="post" class="checkout woocommerce-checkout mb-5" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

						<?php if ( $checkout->get_checkout_fields() ) : ?>
							
							<?php
							woocommerce_form_field( 'billing_email', array(
								'type'        => 'email',
								'class'       => array( 'form-row-wide', 'form-control', 'border-0' ),
								'label'       => '',
								'placeholder' => 'Email *',
								'required'    => true,
							), $checkout->get_value( 'billing_email' ) );
							?>

							<?php
								$aggiornamenti_novita_field = array(
									'type'        => 'checkbox',
									'class'       => array( 'form-check', 'category-item' ),
									'label'       => 'Resta aggiornato per ogni novità',
									'input_class' => array( 'form-check-input', 'mt-1' ), 
									'label_class' => array( 'form-check-label', 'mt-1', 'small' ),
									'default'     => 1,
									'required'    => false,
								);
								woocommerce_form_field( 'aggiornamenti_novita', $aggiornamenti_novita_field, $checkout->get_value( 'aggiornamenti_novita' ) );
							?>
							
							<hr class="bg-white my-4" style="height: 3px; border: none;">

							<h1 class="fw-bold mb-4 fs-2">Spedizione</h1>

							<div id="customer_details">
								<div class="col-12">
									<?php do_action( 'woocommerce_checkout_billing' ); ?>
								</div>
								<div class="col-12">
									<?php do_action( 'woocommerce_checkout_shipping' ); ?>
								</div>
							</div>
							
							<div class="form-text text-secondary mb-4">Inserisci il tuo indirizzo manualmente</div>

							<hr class="bg-white mt-1" style="height: 3px; border: none;">
							
							<h1 class="fw-bold my-4 fs-2">Metodo di pagamento</h1>
                           	
                           	<!--nascosto-->
                            <div class="woocommerce-checkout-payment">
                                <?php do_action( 'woocommerce_checkout_payment' ); ?>
                            </div>
                            
                            <!--mostrato-->
                            <?php do_action( 'woocommerce_checkout_payment' ); ?>
                            
						<?php endif; ?>
					</form>
				</div>

				<div class="col-md-4 sfondino" style="z-index:2!important;">
					<div class="bordo-centrato-checkout"></div>
					
					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_checkout_order_review' ); ?>
					</div>

					<div class="position-relative d-none d-md-block">
						<div style="position: absolute; bottom: 7px; left: -20px; z-index: 1;">
							<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-sx-checkout.svg'; ?>" class="" alt="Immagine2 sx chechout">
						</div>
						<div style="position: absolute; bottom: -20px; left: -5px; z-index: 1;">
							<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-sx-checkout.svg'; ?>" class="" alt="Immagine1 sx checkout">
						</div>
					</div>
					
				</div>
			</div>
			<div class="position-relative">
				<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-gatto-1.svg'; ?>" alt="Gattino" class="" style="position:absolute; z-index:1.5!important; bottom: -115px; right: 120px; ">
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>






</body>
</html>






