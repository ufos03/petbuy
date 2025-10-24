</div><!-- .col-full -->
</div><!-- #content -->

<?php do_action('shopic_before_footer');
if (function_exists('hfe_init') && (hfe_footer_enabled() || hfe_is_before_footer_enabled())) {
    do_action('hfe_footer_before');
    do_action('hfe_footer');
} else {
    ?>

    <footer id="colophon" class="site-footer" role="contentinfo">
        <?php
        /**
         * Functions hooked in to shopic_footer action
         *
         * @see shopic_footer_default - 20
         * @see shopic_handheld_footer_bar - 25 - woo
         *
         */
        do_action('shopic_footer');

        ?>

    </footer><!-- #colophon -->

    <?php
}
/**
 * Functions hooked in to shopic_after_footer action
 * @see shopic_sticky_single_add_to_cart    - 999 - woo
 */
do_action('shopic_after_footer');
?>

</div><!-- #page -->

<?php

/**
 * Functions hooked in to wp_footer action
 * @see shopic_template_account_dropdown    - 1
 * @see shopic_mobile_nav - 1
 * @see shopic_render_woocommerce_shop_canvas - 1 - woo
 */

wp_footer();
?>
</body>
</html>
