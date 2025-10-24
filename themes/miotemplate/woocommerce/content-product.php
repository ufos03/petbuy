<?php
// Controlla se il dispositivo è mobile
if ( wp_is_mobile() ) {
    // Includi il layout per mobile
    wc_get_template( 'content-product-mobile.php' );
} else {
    // Includi il layout per desktop
    wc_get_template( 'content-product-desktop.php' );
}
?>
