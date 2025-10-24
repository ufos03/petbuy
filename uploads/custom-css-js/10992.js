<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
function goToShopping()
{
	jQuery("#new-products-home > div > div > ul").append(`
		<li class="product">
    <a href="http://petbuy.local/negozio">
        <div class="product-block">
            <span class="product-line"></span>
            <div class="product-transition">
                <div class="product-image">
                    <dotlottie-player src="https://lottie.host/bff63ec6-3d1f-4207-98a3-69121a48758e/3mz0FKymrp.json" background="transparent" speed="1" style="height: 300px" direction="1" playMode="normal" loop autoplay></dotlottie-player>
                </div>
            </div>
            <div class="product-caption">
                <h2 class="woocommerce-loop-product__title go-to-shop-card">Vai allo shop</h2>
            </div>
        </div>
    </a>
   
</li>
        `)
}

jQuery(document).ready(function() {
    goToShopping()
})

</script>
<!-- end Simple Custom CSS and JS -->
