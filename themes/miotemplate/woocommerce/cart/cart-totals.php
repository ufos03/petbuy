<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="UTF-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>cart totals</title>
  </head>
  
  <style>

	  table {
	  border-collapse: collapse!important; /* Questo unisce e rimuove i bordi tra le celle */
	  border: none!important;  
	}

	th, td, tr {
	  border: none!important;            /* Assicura che le celle non abbiano bordi */
		padding-left: 0!important;
		padding-right: 0!important;
		background-color: transparent !important;
	}
	  
  </style>

  <body>


<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals w-100 <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<!--h2><php esc_html_e( 'Cart totals', 'woocommerce' ); ?></h2-->

	<table cellspacing="0" class="shop_table shop_table_responsive">

		<tr class="cart-subtotal">
			<th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			<td style="text-align: right!important;" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td style="text-align: right!important;" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr class="shipping">
				<th><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
				<td style="text-align: right!important;" data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td style="text-align: right!important;" data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
						<td style="text-align: right!important;" data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
					<td style="text-align: right!important;" data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			<td style="text-align: right!important;" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>
	
	<!--desktop-->
	<div class="position-relative d-none d-md-block">
		<div style="position: absolute; top: -18px; left: -20px; z-index: 1;">
			<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-sx-checkout.svg'; ?>" class="" alt="Immagine2 sx chechout">
		</div>
		
		<div style="position: absolute; top: 8px; left: -5px; z-index: 1;">
			<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-sx-checkout.svg'; ?>" class="" alt="Immagine1 sx checkout">
		</div>
	</div>

	<!--desktop-->
	<div class="position-relative d-none d-md-block">
		<div style="position: absolute; top: -25px; right: -75px; z-index: 1;">
			<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-basso-checkout.svg'; ?>" class="" alt="impronte in basso">
		</div>
	</div>
	
	<!--desktop-->
	<div class="background-aggiungi px-2 pb-5 d-none d-md-block">
		<div class="sfondo-cane-nero-aggiungi">

			<img src="<?php echo get_template_directory_uri() . '/ufficiale/cane-nero.svg'; ?>" class="mx-auto d-block" alt="..." style="width: 60%;">

		</div>

		<div class="primary fw-bold small mt-5 text-center">Bundle Prodotti</div>
		<h1 class="text-center px-3 lh-base">Aggiungi anche il nostro cibo</h1>

		<p class="small px-4 text-center">Aggiungendo alla tua ciotola per cani, il nostro cibo, risparmi sul prezzo!</p>

		<a href="#" style="width:100%;color:#F87537!important;border-color:#F87537;border-style: solid;" class="btn noacapo bg-trasparente border-3 fw-bold fortablet2" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/shopping-cart-arancione.svg'; ?>" class="me-2" alt=""/>Aggiungi al carrello</a>

	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>




</body>
</html>




