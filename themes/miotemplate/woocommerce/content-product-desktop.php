<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>

<div <?php wc_product_class( '', $product ); ?>>

	<div class="scheda-prodotto">
		<a href="<?php the_permalink(); ?>">
			<?php woocommerce_template_loop_product_thumbnail(); ?>
		</a>

		<div class="container">
			<div class="row align-items-start pb-3">
				<div class="col-md-6">
					<?php the_title( '<div class="noacapo fortablet2 sideshop">', '</div>' ); ?>
					<div class=""><?php woocommerce_template_loop_price(); ?></div>
				</div>
				<div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
				</div>
			</div>
		</div>

</div>

</div>
