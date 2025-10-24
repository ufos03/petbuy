<?php
/**
 * Single Product Image
 *
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

use Automattic\WooCommerce\Enums\ProductType;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$post_thumbnail_id      = $product->get_image_id();
$gallery_attachment_ids = $product->get_gallery_image_ids();
$columns                = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );

// Controllo per il mobile
$is_mobile = wp_is_mobile();

?>

<?php if ( ! $is_mobile ) : ?>
    <div class="woocommerce-product-gallery images" data-columns="<?php echo esc_attr( $columns ); ?>">
        <figure class="woocommerce-product-gallery__wrapper">
            <?php
            if ( $post_thumbnail_id ) {
                $html = wc_get_gallery_image_html( $post_thumbnail_id, true );
            } else {
                $html = sprintf( '<div class="woocommerce-product-gallery__image--placeholder"><img src="%s" alt="%s" class="wp-post-image" /></div>', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
            }
            echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
            ?>
        </figure>
    </div>
<?php endif; ?>

<?php if ( $gallery_attachment_ids ) : ?>
    <?php if ( $is_mobile ) : ?>
        <div class="product-gallery-mobile">
            <?php
            if ( $post_thumbnail_id ) {
                $html = wc_get_gallery_image_html( $post_thumbnail_id, true );
                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
            }
            ?>
            <div class="mt-3 overflow-hidden">
                <div class="gallery-tablet d-flex flex-nowrap" style="overflow-x: scroll;">
                    <?php
                    $counter = 0;
                    foreach ( $gallery_attachment_ids as $attachment_id ) :
                        // Imposta un limite a 3 per creare l'effetto "due e mezza"
                        if ( $counter >= 3 ) {
                            break;
                        }
                        $image_url = wp_get_attachment_image_url( $attachment_id, 'medium' );
                        ?>
                        <div class="img-box me-2" style="flex-shrink: 0; width: 33.33%;">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="Immagine galleria" class="img-uniform">
                        </div>
                    <?php
                    $counter++;
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="mt-4 position-relative">
            <div class="gallery-tablet">
                <?php foreach ( $gallery_attachment_ids as $attachment_id ) : ?>
                    <?php
                    $attributes = array(
                        'class' => 'img-uniform ',
                        'alt'   => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
                    );
                    $image_html = wp_get_attachment_image( $attachment_id, 'medium', false, $attributes );
                    printf( '<div class="img-box">%s</div>', $image_html );
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>