<?php
/**
 * The template for displaying product content within loops
 */
defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>




<div class="scheda-prodotto">
    <a href="<?php the_permalink(); ?>">
        <?php woocommerce_template_loop_product_thumbnail(); ?>
    </a>

    <div class="">
        <div class="row">
            <div class="col-12">
                <?php the_title('<div class="noacapo" style="font-size:16px;">', '</div>'); ?>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div class="small"><?php woocommerce_template_loop_price(); ?></div>

                <div class="d-flex align-items-center">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                </div>
            </div>
        </div>
    </div>
</div>
