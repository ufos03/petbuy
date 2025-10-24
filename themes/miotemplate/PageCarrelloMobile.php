<?php

/*
*
* Template Name: Carrello Mobile 1
* Description: pagina carrello mobile
*
*/

//get header.php file
get_header();


?>



<!--mobile-->
<div class="modal-dialog d-block d-sm-none sfondo-pcm" style="width: 100vw; max-width: 100vw; margin: 0; top: 0; left: 0; transform: none;">
		<div class="modal-content container" style="height: 100vh; border-radius: 0;">
				<!--div class="modal-header">
						<h5 class="modal-title" id="fullWidthModalLabel">Il mio Popup Personalizzato</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div-->
				<div class="modal-body" style="display: flex; flex-direction: column; justify-content: space-between;">

					<div class="w-100" style="border-bottom: 2px solid #e8e8e8; padding-bottom: 5px;"></div>

					<div class="cart-item px-0 ilconteggioPCM" data-price="20.00">
						<div class="item-image">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/rettangolo-osso-popup-mobile.svg'; ?>" class="">
						</div>

						<div class="col">

							<div class="item-details">
									<div class="item-name noacapo">Ciotola per cani</div>
									<div class="item-delivery noacapo small">Consegna il 23 giugno</div>
							</div>

							<div class="" style="display: flex; align-items: center; justify-content: space-between; width:100%;">
								<div class="item-quantity">
										<button class="quantity-button decrement">-</button>
										<input type="text" class="quantity-input" value="1" min="1">
										<button class="quantity-button increment">+</button>
								</div>

								<div class="item-image p-0 m-0">
									<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cestino.svg'; ?>" class="cestina" alt="cestino"/></a>
								</div>
							</div>

						</div>

						<div class="item-pricePCM d-none">$20.00</div>

				</div>

				<div class="w-100" style="border-bottom: 2px solid #e8e8e8; padding-bottom: 5px;"></div>

				<div class="cart-item px-0 ilconteggioPCM" data-price="20.00">
						<div class="item-image">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/rettangolo-osso-popup-mobile.svg'; ?>" class="">
						</div>

						<div class="col">

							<div class="item-details">
									<div class="item-name noacapo">Ciotola per cani</div>
									<div class="item-delivery noacapo small">Consegna il 23 giugno</div>
							</div>

							<div class="" style="display: flex; align-items: center; justify-content: space-between; width:100%;">
								<div class="item-quantity">
										<button class="quantity-button decrement">-</button>
										<input type="text" class="quantity-input" value="1" min="1">
										<button class="quantity-button increment">+</button>
								</div>

								<div class="item-image p-0 m-0">
									<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cestino.svg'; ?>" class="cestina" alt="cestino"/></a>
								</div>
							</div>

						</div>

						<div class="item-pricePCM d-none">$20.00</div>

				</div>

				<div class="w-100" style="border-bottom: 2px solid #e8e8e8; padding-bottom: 5px;"></div>

				<div class="cart-item px-0 ilconteggioPCM" data-price="20.00">
						<div class="item-image">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/rettangolo-osso-popup-mobile.svg'; ?>" class="">
						</div>

						<div class="col">

							<div class="item-details">
									<div class="item-name noacapo">Ciotola per cani</div>
									<div class="item-delivery noacapo small">Consegna il 23 giugno</div>
							</div>

							<div class="" style="display: flex; align-items: center; justify-content: space-between; width:100%;">
								<div class="item-quantity">
										<button class="quantity-button decrement">-</button>
										<input type="text" class="quantity-input" value="1" min="1">
										<button class="quantity-button increment">+</button>
								</div>

								<div class="item-image p-0 m-0">
									<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cestino.svg'; ?>" class="cestina" alt="cestino"/></a>
								</div>
							</div>

						</div>

						<div class="item-pricePCM d-none">$20.00</div>

				</div>

				<div class="w-100" style="border-bottom: 2px solid #e8e8e8; padding-bottom: 5px;"></div>

				<div class="modal-footer mt-2 px-0">
						<a href="#" style="width:100%;" class="btn btn-warning noacapo" role="button">
							<div class="font-shop">
								<span id="totalPCM">$70.00</span> -
								Procedi al check out
							</div>
						</a>
				</div>
				</div>
		</div>

		<div class="text-end">
		    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cane-carrello-mobile-1.png'; ?>" alt="cane" class="mt-4" style="">
		</div>

</div>







<?php

//get footer.php file
get_footer();


?>
