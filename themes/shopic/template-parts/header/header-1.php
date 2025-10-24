<header id="masthead" class="site-header header-1" role="banner">
    <div class="header-container">
        <div class="container header-main">
            <div class="header-left">
                <?php
                shopic_site_branding();
                if (shopic_is_woocommerce_activated()) {
                    ?>
                    <div class="site-header-cart header-cart-mobile">
                        <?php shopic_cart_link(); ?>
                    </div>
                    <?php
                }
                ?>
                <?php shopic_mobile_nav_button(); ?>
            </div>
            <div class="header-center">
                <?php shopic_primary_navigation(); ?>
            </div>
            <div class="header-right desktop-hide-down">
                <div class="header-group-action">
                    <?php
                    shopic_header_account();
                    if (shopic_is_woocommerce_activated()) {
                        shopic_header_wishlist();
                        shopic_header_cart();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</header><!-- #masthead -->
