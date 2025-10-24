<?php

/*
*
* Template Name: Checkout
* Description: pagina checkout
*
*/

//get header.php file
get_header();


?>




<div class="tra-menu-e-titolo">
<div class="mb-0">
<div class="container">

	<div class="row position-relative">
		<div class="col-md-8 sfondo-checkout" style="z-index:1!important;">

			<div class="d-flex justify-content-between align-items-center mb-3 tra-menu-e-titolo">

			    <div class="col-md-6 fw-bold py-2">
			        <h1 class="m-0 float-start fs-2">Contatti</h1>
			    </div>

			    <div class="col-md-6 py-2">
			        <div class="small float-end text-decoration-underline">
			            <a href="#">Fai il login al tuo account</a>
			        </div>
			    </div>

			</div>


			<form class="mb-5">

					<div class="mb-3">
					<input type="email" class="form-control edit-form-header" id="" placeholder="email">
				</div>
				<div class="mb-3 form-check category-item">
					<input type="checkbox" style="" class="form-check-input me-2" id="" checked>
					<label class="form-check-label mt-1 small" for="check">Resta aggiornato per ogni novità</label>
				</div>

				<hr class="bg-white my-4" style="height: 3px; border: none;">


				<h1 class="fw-bold mb-4 fs-2">Spedizione</h1>


				<div class="mb-4">
					<input type="text" class="form-control edit-form-header" id="" placeholder="Paese/regione">
				</div>
				<div class="row mb-4">
				    <div class="col-12 col-md-6 m-input">
				        <input type="text" class="form-control edit-form-header" id="" placeholder="Nome">
				    </div>
				    <div class="col-12 col-md-6">
				        <input type="text" class="form-control edit-form-header" id="" placeholder="Cognome">
				    </div>
				</div>
				<div class="mb-4 d-flex">
					<input type="text" class="form-control edit-form-header" id="" placeholder="Indirizzo"><i id="lente" class="fa fa-search"></i>
				</div>
				<div class="form-text text-secondary mb-4">Inserisci il tuo indirizzo manualmente</div>

				<hr class="bg-white mt-1" style="height: 3px; border: none;">

				<h1 class="my-4 fs-2">Metodo di pagamento</h1>

				<div class="payment-method mt-4 mb-0">
					<div class="form-check py-2 carte-spunta d-flex align-items-center justify-content-between" style="border: 1px solid #ccc;border-radius: 5px;">
					    <div class="d-flex align-items-center">
					        <input class="form-check-input me-2 mb-1" type="radio" name="paymentMethod" id="creditCard" value="creditCard" checked>
					        <label class="form-check-label mb-0 fw-normal small" for="creditCard">
					            Carta di credito
					        </label>
					    </div>
					    <span class="float-end">
					        <img src="<?php echo get_template_directory_uri() . '/ufficiale/carte-di-credito-metodo-di-pagamento.svg'; ?>" alt="carte di credito"/>
					    </span>
					</div>
					<div id="creditCardFields" class="carte" style="margin-top:30px!important;">
						<div class="mb-3">
							<div class="input-group">
								<input type="text" class="form-control edit-form-header" style="background-color: #faf9f8;" id="cardNumber" placeholder="Numero carta">
								<span class="input-group-text" style="background-color:#faf9f8;border:0;"><img src="<?php echo get_template_directory_uri() . '/ufficiale/lucchetto.svg'; ?>" alt="lucchetto"/></span>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<input type="text" class="form-control edit-form-header m-input" style="background-color: #faf9f8;" id="expiryDate" placeholder="Data di scadenza (MM / YY)">
							</div>
							<div class="col-md-6">
								<div class="input-group">
									<input type="text" class="form-control edit-form-header" style="background-color: #faf9f8;" id="cvv" placeholder="Codice di sicurezza">
									<span class="input-group-text" style="background-color:#faf9f8;border:0;"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cvv.svg'; ?>" alt="cvv"/></span>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<input type="text" class="form-control edit-form-header" style="background-color: #faf9f8;" id="cardHolderName" placeholder="Nome del proprietario">
						</div>
						<div class="pb-4 form-check">
							<input type="checkbox" class="form-check-input mt-0" id="sameBilling" checked>
							<label class="form-check-label mb-0 fw-normal small" for="sameBilling">Utilizza l'indirizzo di spedizione come fattura</label>
						</div>
					</div>
				</div>

				<div class="payment-method mb-5" style="margin-top:-2px!important;">
					<div class="form-check py-2 carte-spunta spazio-link-img-top" style="border: 1px solid #ccc;border-radius: 5px;">
						<input class="form-check-input mt-0" type="radio" name="paymentMethod" id="otherPayment" value="other">
						<label class="form-check-label mb-0 fw-normal small" for="otherPayment">
							Altri metodi di pagamento
						</label>
					</div>
					<div id="otherPaymentFields" class="mt-3" style="display: none;margin-top:30px!important;padding:0px 35px 0px 35px!important;">
						<div class="mb-3 pb-4">
							<textarea class="form-control" id="otherDetails" rows="3" placeholder="Inserisci i dettagli per altri metodi di pagamento"></textarea>
						</div>
					</div>
				</div>

				<a href="#" style="width:100%;" class="btn btn-warning noacapo" role="button">Paga ora</a>
			</form>

		</div>

		<div class="col-md-4 sfondino" style="z-index:2!important;">

			<div class="bordo-centrato-checkout"></div>

			<!--desktop-->
			<div class="d-none d-md-block">
				<div class="cart-item spazi-checkout px-0 pb-2 conteggio" data-price="20.00">
						<div class="item-image item-image-conteggio">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-osso-checkout.svg'; ?>">
						</div>
						<div class="item-details">
								<div class="item-name noacapo fortablet2">Ciotola per cani</div>
								<div class="item-delivery noacapo small fortablet2">Per cani / gatti</div>
						</div>
						<!--<div class="item-quantity">
								<button class="quantity-button decrement">-</button>
								<input type="text" class="quantity-input" value="1" min="1">
								<button class="quantity-button increment">+</button>
						</div>-->
						<div class="item-price-carrello fw-normal fortablet2">$20.00</div>
						<!--<div class="item-image p-0 m-0">
							<a href="#"><img src="ufficiale/cestino.svg" class="cestina" alt="cestino"/></a>
						</div>-->
				</div>

				<div class="cart-item px-0 py-2 conteggio" data-price="20.00">
						<div class="item-image item-image-conteggio">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-osso-checkout.svg'; ?>">
						</div>
						<div class="item-details">
								<div class="item-name noacapo fortablet2">Ciotola per cani</div>
								<div class="item-delivery noacapo small fortablet2">Per cani / gatti</div>
						</div>
						<!--<div class="item-quantity">
								<button class="quantity-button decrement">-</button>
								<input type="text" class="quantity-input" value="1" min="1">
								<button class="quantity-button increment">+</button>
						</div>-->
						<div class="item-price-carrello fw-normal fortablet2">$20.00</div>
						<!--<div class="item-image p-0 m-0">
							<a href="#"><img src="ufficiale/cestino.svg" class="cestina" alt="cestino"/></a>
						</div>-->
				</div>

				<div class="cart-item px-0 pt-2 conteggio" data-price="20.00">
						<div class="item-image item-image-conteggio">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-osso-checkout.svg'; ?>">
						</div>
						<div class="item-details">
								<div class="item-name noacapo fortablet2">Ciotola per cani</div>
								<div class="item-delivery noacapo small fortablet2">Per cani / gatti</div>
						</div>
						<!--<div class="item-quantity">
								<button class="quantity-button decrement">-</button>
								<input type="text" class="quantity-input" value="1" min="1">
								<button class="quantity-button increment">+</button>
						</div>-->
						<div class="item-price-carrello fw-normal fortablet2">$20.00</div>
						<!--<div class="item-image p-0 m-0">
							<a href="#"><img src="ufficiale/cestino.svg" class="cestina" alt="cestino"/></a>
						</div>-->
				</div>
			</div>

			<!--mobile-->
			<div class="d-block d-sm-none">
				<div class="cart-item px-0 check-conteggio-checkout-mobile" data-price="20.00">
					<div class="item-image">
							<img src="<?php echo get_template_directory_uri() . '/ufficiale/rettangolo-osso-popup-mobile.svg'; ?>" class="">
					</div>

					<div class="col">

						<div class="position-relative ">
							<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-gatto-1-original.png'; ?>" alt="Gattino" class="" style="position:absolute; z-index:1.5!important; top: -35px; left: -140px; ">
						</div>

						<div class="item-details">
								<div class="item-name noacapo">Ciotola per cani!</div>
								<div class="item-delivery noacapo small">Consegna il 23 giugno</div>
						</div>

						<div class="" style="display: flex; align-items: center; justify-content: space-between; width:100%;">
							<div class="item-quantity">
									<button class="quantity-button check-decrement-checkout-mobile">-</button>
									<input type="text" class="check-quantity-input-checkout-mobile" value="1" min="1">
									<button class="quantity-button check-increment-checkout-mobile">+</button>
							</div>

							<div class="item-image p-0 m-0">
								<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cestino.svg'; ?>" class="cestina" alt="cestino"/></a>
							</div>
						</div>

					</div>

					<div class="check-item-price-checkout-mobile d-none">$20.00</div>

				</div>

				<div class="bordo-centrato-checkout"></div>

				<div class="cart-item px-0 check-conteggio-checkout-mobile" data-price="20.00">
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
										<button class="quantity-button check-decrement-checkout-mobile">-</button>
										<input type="text" class="check-quantity-input-checkout-mobile" value="1" min="1">
										<button class="quantity-button check-increment-checkout-mobile">+</button>
								</div>

								<div class="item-image p-0 m-0">
									<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cestino.svg'; ?>" class="cestina" alt="cestino"/></a>
								</div>
							</div>

						</div>

						<div class="check-item-price-checkout-mobile d-none">$20.00</div>

				</div>

				<div class="bordo-centrato-checkout"></div>

				<div class="cart-item px-0 check-conteggio-checkout-mobile" data-price="20.00">
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
										<button class="quantity-button check-decrement-checkout-mobile">-</button>
										<input type="text" class="check-quantity-input-checkout-mobile" value="1" min="1">
										<button class="quantity-button check-increment-checkout-mobile">+</button>
								</div>

								<div class="item-image p-0 m-0">
									<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cestino.svg'; ?>" class="cestina" alt="cestino"/></a>
								</div>
							</div>

						</div>

						<div class="check-item-price-checkout-mobile d-none">$20.00</div>

				</div>

				<div class="bordo-centrato-checkout"></div>
			</div>

			<label for="promoCode" class="mb-2 margin-promo">Inserisci codice promo</label>

			<div class="d-flex align-items-center">
				<input type="text" class="form-control input-promo fortablet2" style="border-top-left-radius: 5px!important;border-bottom-left-radius: 5px!important;" id="promoCode" placeholder="Promo code">
				<a href="#" class="btn btn-promo input-promo noacapo pb-2" style="margin-left: -65px; border-top-left-radius: 5px !important; border-bottom-left-radius: 5px !important; border-top-right-radius: 5px !important; border-bottom-right-radius: 5px !important; display: flex; align-items: center; justify-content: center;" role="button">Invia</a>
			</div>

			<div class="text-end pt-4 fixed-bottom-conteggio">
					<div class="summary-row">
							<div>Subtotal</div>
							<div id="subtotal-checkout-mobile">$60.00</div>
					</div>
					<div class="summary-row">
							<div>Spedizione</div>
							<div id="shipping-checkout-mobile">$10.00</div>
					</div>
					<div class="summary-row">
							<div>Totale</div>
							<div id="" class="js-total-display">$70.00</div>
					</div>

			</div>

			<!--desktop-->
			<div class="position-relative d-none d-md-block">
				<div style="position: absolute; bottom: 7px; left: -20px; z-index: 1;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-sx-checkout.svg'; ?>" class="" alt="Immagine2 sx chechout">
				</div>
				<div style="position: absolute; bottom: -20px; left: -5px; z-index: 1;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-sx-checkout.svg'; ?>" class="" alt="Immagine1 sx checkout">
				</div>
			</div>

			<!--desktop-->
			<div class="position-relative d-none d-md-block">
				<div style="position: absolute; bottom: 250px; right: -75px; z-index: 1;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-basso-checkout.svg'; ?>" class="" alt="impronte in basso">
				</div>
			</div>

		</div>
	</div>

	<div class="position-relative">
	<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-gatto-1.svg'; ?>" alt="Gattino" class="" style="position:absolute; z-index:1.5!important; bottom: -115px; right: 110px; ">
</div>

</div>
</div>
</div>







<?php

//get footer.php file
get_footer();


?>
