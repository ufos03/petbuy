<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function( $ )
{
		// Seleziona tutti gli elementi <li> all'interno della lista
		const listItems = document.querySelectorAll('.woocommerce-MyAccount-navigation ul li');
		const itemsArray = Array.from(listItems);

		jQuery(".woocommerce-MyAccount-navigation ul").html("");
		
		jQuery(".woocommerce-MyAccount-navigation ul").append('<li class="woocommerce-MyAccount-navigation-link"><a href="http://petbuy.local/annunci/" aria-current="page">Annunci</a></li>');
	
		for(let i = 1; i < itemsArray.length; i++)
		{
			jQuery(".woocommerce-MyAccount-navigation ul").append(itemsArray[i]);
		}
   
});</script>
<!-- end Simple Custom CSS and JS -->
