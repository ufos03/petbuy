<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title></title>


	<style>

	.widgettitle {
	    display: none;
	}

  .woocommerce .ui-slider-range {
    background-color: #F87537!important; /* Sostituisci con il tuo colore di sfondo */
}

.woocommerce .ui-slider-handle {
  background-color: #F87537!important; /* Sostituisci con il tuo colore di sfondo */
}

div .price_slider {
  background-color: #e0e0e0!important;
}

	</style>

</head>
<body>

  <?php
/**
 * The template for displaying product price filter widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-price-filter.php
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

?>
<?php do_action( 'woocommerce_widget_price_filter_start', $args ); ?>

<form method="get" id="price_filter_form" action="<?php echo esc_url( $form_action ); ?>">
	<div class="price_slider_wrapper">
		<div class="price_slider_amount" data-step="<?php echo esc_attr( $step ); ?>">
			<?php // SPOSIZIONATO SOPRA IL RANGE ?>
			<div class="price_label mb-3 float-start" style="display:none;">
				<?php echo esc_html__( 'Prezzo da:', 'woocommerce' ); ?> <span class="from"></span> &mdash; <span class="to"></span>
			</div>
			<label class="screen-reader-text" for="min_price"><?php esc_html_e( 'Min price', 'woocommerce' ); ?></label>
			<input type="text" id="min_price" name="min_price" value="<?php echo esc_attr( $current_min_price ); ?>" data-min="<?php echo esc_attr( $min_price ); ?>" placeholder="<?php echo esc_attr__( 'Min price', 'woocommerce' ); ?>" />
			<label class="screen-reader-text" for="max_price"><?php esc_html_e( 'Max price', 'woocommerce' ); ?></label>
			<input type="text" id="max_price" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>" data-max="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php echo esc_attr__( 'Max price', 'woocommerce' ); ?>" />
			<?php // RIMOSSO IL PULSANTE DI FILTRO ?>
			<?php echo wc_query_string_form_fields( null, array( 'min_price', 'max_price', 'paged' ), '', true ); ?>
			<div class="clear"></div>
		</div>
		<div class="price_slider" style="display:none;"></div>
	</div>
</form>

<?php do_action( 'woocommerce_widget_price_filter_end', $args ); ?>


<!--avvia il filtro immediatamente dopo aver deciso il range del prezzo-->
<script>

document.addEventListener('DOMContentLoaded', function() {
    const priceFilterForm = document.getElementById('price_filter_form');

    if (priceFilterForm) {
        // Seleziona il contenitore dello slider
        const priceSlider = priceFilterForm.querySelector('.price_slider');

        if (priceSlider) {
            // Aggiungi un listener per l'evento 'slidestop'
            // Questo evento si attiva quando l'utente rilascia il cursore
            // dopo averlo trascinato
            jQuery(priceSlider).on('slidestop', function() {
                priceFilterForm.submit();
            });
        }
    }
});

</script>


</body>
</html>
