<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">

function emptyCart(j, waitTime)  
{
	if (j(".woofc-items").hasClass("center") == true)
		return
	j(".woofc-inner").addClass("woofc-inner-loading")
	
	setTimeout(() => {
		j(".woofc-items").addClass("center")
        j("<img src = 'http://petbuy.local/wp-content/uploads/2024/02/pollo-incazzato.webp' class='img-pt'>").insertBefore(".woofc-no-item")
		j(".woofc-items").append("<a class='button to-shop' href='http://petbuy.local/negozio/'>Continua ad acquistare</a>")
    }, waitTime);
	
	j(".woofc-inner").removeClass("woofc-inner-loading")
}

jQuery(document).ready(function( $ ){
    $(document).on("removed_from_cart",function(){
		if ($(".woofc-area-count").text() == '0')
				emptyCart($, 500)
	});
	
	$(document).on("click", ".woofc-empty-cart", function(){
		emptyCart($, 1600)
	});
	
	$(document).on("click", ".cart-contents", function(){
		if ($(".woofc-area-count").text() == '0')
				emptyCart($, 450)
	});
	
	$(document).on("click", ".to-shop", function(){
		 woofc_hide_cart();
	});
});</script>
<!-- end Simple Custom CSS and JS -->
