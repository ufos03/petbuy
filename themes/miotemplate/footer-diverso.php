

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!--filtra per prezzo-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

    <!--nav fissato in alto e cambio immagine logo-->
    <script>
        const logo = document.getElementById('logo');
        const nav = document.querySelector('nav'); // Ottieni l'elemento nav
        const logoInizialeSrc = '<?php echo get_template_directory_uri() . '/ufficiale/petbuy-logo-1.svg'; ?>';
        const logoScrollSrc = '<?php echo get_template_directory_uri() . '/ufficiale/petbuy-logo-no-dog-1.svg'; ?>';
        let scrolled = false;
        let navFixed = false; // Variabile per tenere traccia se la nav è fissa

        window.addEventListener('scroll', () => {
            const scrollThreshold = 100; // Soglia di scroll per il cambio logo e fissaggio nav

            // Gestione cambio logo
            if (window.scrollY > scrollThreshold && !scrolled) {
                logo.src = logoScrollSrc;
                logo.alt = 'Logo allo Scroll';
                scrolled = true;
            } else if (window.scrollY <= scrollThreshold && scrolled) {
                logo.src = logoInizialeSrc;
                logo.alt = 'Logo Iniziale';
                scrolled = false;
            }

            // Gestione fissaggio nav
            if (window.scrollY > 0 && !navFixed) {
                nav.style.position = 'fixed';
                nav.style.top = '0';
                nav.style.left = '0';
                nav.style.width = '100%';
                nav.style.zIndex = '100'; // Assicura che stia sopra altri elementi
				nav.style.backgroundColor = '#ffffff';
                navFixed = true;
            } else if (window.scrollY <= 0 && navFixed) {
                nav.style.position = 'static'; // Rimuovi il posizionamento fisso
                nav.style.top = '';
                nav.style.left = '';
                nav.style.width = '';
                nav.style.zIndex = '';
                navFixed = false;
            }
        });
    </script>



    <!--switch-->
    <script>
        const radioButtons = document.querySelectorAll('input[name="options"]');
        const contenutoOpzioniA = document.querySelectorAll('.opzione-contenuto-a');
        const contenutoOpzioniB = document.querySelectorAll('.opzione-contenuto-b');

        radioButtons.forEach(radioButton => {
            radioButton.addEventListener('change', () => {
                const targetIdA = radioButton.getAttribute('data-target-a');
                const targetIdB = radioButton.getAttribute('data-target-b');

                // Gestisci la Sezione A
                contenutoOpzioniA.forEach(content => {
                    content.classList.remove('active');
                });
                const targetContentA = document.getElementById(targetIdA);
                if (targetContentA) {
                    targetContentA.classList.add('active');
                }

                // Gestisci la Sezione B
                contenutoOpzioniB.forEach(content => {
                    content.classList.remove('active');
                });
                const targetContentB = document.getElementById(targetIdB);
                if (targetContentB) {
                    targetContentB.classList.add('active');
                }
            });
        });

        // Mostra i contenuti iniziali all'avvio
        document.addEventListener('DOMContentLoaded', () => {
            const initialChecked = document.querySelector('input[name="options"]:checked');
            if (initialChecked) {
                const initialTargetIdA = initialChecked.getAttribute('data-target-a');
                const initialTargetContentA = document.getElementById(initialTargetIdA);
                if (initialTargetContentA) {
                    initialTargetContentA.classList.add('active');
                }

                const initialTargetIdB = initialChecked.getAttribute('data-target-b');
                const initialTargetContentB = document.getElementById(initialTargetIdB);
                if (initialTargetContentB) {
                    initialTargetContentB.classList.add('active');
                }
            }
        });
    </script>


    <!--filtra per prezzo-->
    <script>
        const priceRange = document.getElementById('price-range');
        const minPriceDisplay = document.getElementById('min-price');
        const maxPriceDisplay = document.getElementById('max-price');

        noUiSlider.create(priceRange, {
            start: [0, 200], // Valori iniziali del range
            connect: true,   // Barra di connessione tra i due handle
            range: {
                'min': 0,
                'max': 200
            },
            format: {
                to: function (value) {
                    return '$' + Math.round(value);
                },
                from: function (value) {
                    return Number(value.replace('$', ''));
                }
            }
        });

        priceRange.noUiSlider.on('update', function (values, handle) {
            minPriceDisplay.textContent = values[0];
            maxPriceDisplay.textContent = values[1];

            // Qui puoi aggiungere la logica per filtrare i risultati in base ai nuovi valori
            console.log('Min Price:', values[0]);
            console.log('Max Price:', values[1]);
        });
    </script>


    <!--pagina carrello-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartItems = document.querySelectorAll('.ilconteggio');
            const subtotalElement = document.getElementById('totalesub');
            const totalElement = document.getElementById('totalee');
            const shippingCost = parseFloat(document.getElementById('laspedizione').textContent.replace('$', ''));

            function updateSummary() {
                let currentSubtotal = 0;
                cartItems.forEach(item => {
                    const price = parseFloat(item.dataset.price);
                    const quantityInput = item.querySelector('.quantity-input');
                    const quantity = parseInt(quantityInput.value);
                    currentSubtotal += price * quantity;
                });

                subtotalElement.textContent = `$${currentSubtotal.toFixed(2)}`;
                const total = currentSubtotal + shippingCost;
                totalElement.textContent = `$${total.toFixed(2)}`;
            }

            cartItems.forEach(item => {
                const incrementButton = item.querySelector('.increment');
                const decrementButton = item.querySelector('.decrement');
                const quantityInput = item.querySelector('.quantity-input');
                const itemPriceElement = item.querySelector('.item-price-carrello');
                const basePrice = parseFloat(item.dataset.price);

                incrementButton.addEventListener('click', function() {
                    let quantity = parseInt(quantityInput.value);
                    quantity++;
                    quantityInput.value = quantity;
                    itemPriceElement.textContent = `$${(basePrice * quantity).toFixed(2)}`;
                    updateSummary();
                });

                decrementButton.addEventListener('click', function() {
                    let quantity = parseInt(quantityInput.value);
                    if (quantity > parseInt(quantityInput.getAttribute('min'))) {
                        quantity--;
                        quantityInput.value = quantity;
                        itemPriceElement.textContent = `$${(basePrice * quantity).toFixed(2)}`;
                        updateSummary();
                    }
                });

                quantityInput.addEventListener('change', function() {
                    let quantity = parseInt(this.value);
                    const min = parseInt(this.getAttribute('min')) || 1;
                    if (isNaN(quantity) || quantity < min) {
                        this.value = min;
                        quantity = min;
                    }
                    itemPriceElement.textContent = `$${(basePrice * quantity).toFixed(2)}`;
                    updateSummary();
                });
            });

            updateSummary(); // Inizializza il riepilogo al caricamento della pagina
        });
    </script>


    <!--modale carrello pieno-->
    <div class="modal fade" id="carrelloPopup" tabindex="-1" aria-labelledby="mioPopupLabel" aria-hidden="true">
          <div class="modal-dialog">

            <div class="position-relative">
              <div style="position: absolute; top: 4px; left: 153px; z-index: 1;">
                  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-cuore-piccolo-carrello-popup.svg'; ?>" class="" alt="Immagine Alto Sinistra 1">
              </div>
              <div style="position: absolute; top: 31px; left: 165px; z-index: 1;">
                  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-cuore-grande-carrello-popup.svg'; ?>" class="" alt="Immagine Alto Sinistra 2">
              </div>
            </div>

             	<div class="position-relative">
				  <div style="position: absolute; top: -45px; left: -335px; z-index: -1;">
					  <img src="<?php echo get_template_directory_uri() . '/ufficiale/cane-rotazione.svg'; ?>" class="" alt="cane affacciato">
				  </div>
            	</div>

              <div class="modal-content px-1" style="padding-top: 15px;">
                  <div class="modal-header">
                      <div class="" id="mioPopupLabel">Il tuo carrello</div>
                      <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>-->
                      <div class="float-end">
                        <a href="#">Modifica</a>
                      </div>
                  </div>
                  <div class="bordo-centrato"></div>
                  <div class="modal-body">
                    <div class="cart-item pe-0" data-price="20.00">
                        <div class="item-image">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-osso-mini.svg'; ?>">
                        </div>
                        <div class="item-details">
                            <div class="item-name noacapo">Ciotola per cani</div>
                            <div class="item-delivery noacapo small">Consegna il 23 giugno</div>
                        </div>
                        <div class="item-quantity">
                            <button class="quantity-button decrement">-</button>
                            <input type="text" class="quantity-input" value="1" min="1">
                            <button class="quantity-button increment">+</button>
                        </div>
                        <div class="item-price">$20.00</div>
                        <div class="item-image p-0 m-0">
                        	<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cestino.svg'; ?>" class="cestina" alt="cestino"/></a>
                        </div>
                    </div>
                    <div class="bordo-centrato"></div>
                    <div class="cart-item pe-0" data-price="20.00">
                        <div class="item-image">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-osso-mini.svg'; ?>">
                        </div>
                        <div class="item-details">
                            <div class="item-name noacapo">Ciotola per cani</div>
                            <div class="item-delivery noacapo small">Consegna il 23 giugno</div>
                        </div>
                        <div class="item-quantity">
                            <button class="quantity-button decrement">-</button>
                            <input type="text" class="quantity-input" value="1" min="1">
                            <button class="quantity-button increment">+</button>
                        </div>
                        <div class="item-price">$20.00</div>
                        <div class="item-image p-0 m-0 ">
                        	<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cestino.svg'; ?>" class="cestina" alt="cestino"/></a>
                        </div>
                    </div>
                    <div class="bordo-centrato"></div>
                    <div class="cart-item pe-0" data-price="20.00">
                        <div class="item-image">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-osso-mini.svg'; ?>">
                        </div>
                        <div class="item-details">
                            <div class="item-name noacapo">Ciotola per cani</div>
                            <div class="item-delivery noacapo small">Consegna il 23 giugno</div>
                        </div>
                        <div class="item-quantity">
                            <button class="quantity-button decrement">-</button>
                            <input type="text" class="quantity-input" value="1" min="1">
                            <button class="quantity-button increment">+</button>
                        </div>
                        <div class="item-price">$20.00</div>
                        <div class="item-image p-0 m-0 ">
                        	<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cestino.svg'; ?>" class="cestina" alt="cestino"/></a>
                        </div>
                    </div>
                    <div class="bordo-centrato"></div>
                    <div class="summary">
                        <div class="summary-row">
                            <div>Subtotal</div>
                            <div id="subtotal">$60.00</div>
                        </div>
                        <div class="summary-row">
                            <div>Spedizione</div>
                            <div id="shipping">$10.00</div>
                        </div>
                        <div class="summary-row">
                            <div>Totale</div>
                            <div id="total">$70.00</div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                      <a href="#" style="width:100%;" class="btn btn-warning noacapo" role="button">Procedi al check out</a>
                  </div>
              </div>

              	<div class="position-relative">
				  <div style="position: absolute; bottom: 192px; right: 100px; z-index: 1;">
					  <img src="<?php echo get_template_directory_uri() . '/ufficiale/tre-impronte-cuore-carrello-popup.svg'; ?>" class="" alt="Immagine impronte in basso">
				  </div>
            	</div>

          </div>
      </div>


      <!--modale carrello vuoto-->
      <div class="modal fade" id="carrelloPopupVuoto" tabindex="-1" aria-labelledby="mioPopupLabel" aria-hidden="true">
          <div class="modal-dialog">

            <div class="position-relative">
              <div style="position: absolute; top: 13px; left: 153px; z-index: 1;">
                  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-cuore-piccolo-carrello-popup.svg'; ?>" class="" alt="Immagine Alto Sinistra 1">
              </div>
              <div style="position: absolute; top: 40px; left: 165px; z-index: 1;">
                  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-cuore-grande-carrello-popup.svg'; ?>" class="" alt="Immagine Alto Sinistra 2">
              </div>
            </div>



              <div class="modal-content px-1" style="padding-top: 15px;">
                  <div class="modal-header">
                      <div class="" id="mioPopupLabel">Il tuo carrello</div>
                      <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>-->
                      <div class="float-end">
                        <a href="#">Modifica</a>
                      </div>
                  </div>
                  <div class=""></div>
                  <div class="modal-body">


                    <div class="my-5">
                    	<img src="<?php echo get_template_directory_uri() . '/ufficiale/pollo-arrabbiato.svg'; ?>" class="mx-auto d-block img-fluid" alt="img pollo arrabbiato"/>
                    	<div class="mt-2 text-center fs-4">Opss... il tuo carrello è vuoto</div>
                    </div>


                  </div>
                  <div class="modal-footer">
                      <a href="#" style="width:100%;" class="btn btn-warning noacapo" role="button">Vai allo shop</a>
                  </div>
              </div>

              	<div class="position-relative">
				  <div style="position: absolute; bottom: 70px; right: 20px; z-index: 1;">
					  <img src="<?php echo get_template_directory_uri() . '/ufficiale/due-impronte-dx-pulsante-carrello-vuoto.svg'; ?>" class="" alt="Immagine impronte a dx">
				  </div>

           		  <div style="position: absolute; bottom: 38px; right: 65px; z-index: 1;">
					  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-bianca-dx-pulsante-carrello-vuoto.svg'; ?>" class="" alt="Immagine impronta2 a dx">
				  </div>

          		  <div style="position: absolute; bottom: 24px; right: 40px; z-index: 1;">
					  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-bianca-dx-pulsante-carrello-vuoto.svg'; ?>" class="" alt="Immagine impronta1 a dx">
				  </div>

          		  <div style="position: absolute; bottom: 30px; left: 40px; z-index: 1;">
					  <img src="<?php echo get_template_directory_uri() . '/ufficiale/tre-impronte-sx-pulsante-carrello-vuoto.svg'; ?>" class="" alt="Immagine tre impronte a sx">
				  </div>

          		  <div style="position: absolute; bottom: 40px; left: 10px; z-index: 1;">
					  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-sx-pulsante-carrello-vuoto.svg'; ?>" class="" alt="Immagine impronta a sx">
				  </div>

            	</div>

          </div>
      </div>


      <!--carrello popup-->
      <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartItems = document.querySelectorAll('.cart-item');
            const subtotalElement = document.getElementById('subtotal');
            const totalElement = document.getElementById('total');
            const shippingCost = parseFloat(document.getElementById('shipping').textContent.replace('$', ''));

            function updateSummary() {
                let currentSubtotal = 0;
                cartItems.forEach(item => {
                    const price = parseFloat(item.dataset.price);
                    const quantityInput = item.querySelector('.quantity-input');
                    const quantity = parseInt(quantityInput.value);
                    currentSubtotal += price * quantity;
                });

                subtotalElement.textContent = `$${currentSubtotal.toFixed(2)}`;
                const total = currentSubtotal + shippingCost;
                totalElement.textContent = `$${total.toFixed(2)}`;
            }

            cartItems.forEach(item => {
                const incrementButton = item.querySelector('.increment');
                const decrementButton = item.querySelector('.decrement');
                const quantityInput = item.querySelector('.quantity-input');
                const itemPriceElement = item.querySelector('.item-price');
                const basePrice = parseFloat(item.dataset.price);

                incrementButton.addEventListener('click', function() {
                    let quantity = parseInt(quantityInput.value);
                    quantity++;
                    quantityInput.value = quantity;
                    itemPriceElement.textContent = `$${(basePrice * quantity).toFixed(2)}`;
                    updateSummary();
                });

                decrementButton.addEventListener('click', function() {
                    let quantity = parseInt(quantityInput.value);
                    if (quantity > parseInt(quantityInput.getAttribute('min'))) {
                        quantity--;
                        quantityInput.value = quantity;
                        itemPriceElement.textContent = `$${(basePrice * quantity).toFixed(2)}`;
                        updateSummary();
                    }
                });

                quantityInput.addEventListener('change', function() {
                    let quantity = parseInt(this.value);
                    const min = parseInt(this.getAttribute('min')) || 1;
                    if (isNaN(quantity) || quantity < min) {
                        this.value = min;
                        quantity = min;
                    }
                    itemPriceElement.textContent = `$${(basePrice * quantity).toFixed(2)}`;
                    updateSummary();
                });
            });

            updateSummary(); // Inizializza il riepilogo al caricamento della pagina
        });
    </script>


    <!--prodotto singolo annuncio frame-->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
          const cartItems = document.querySelectorAll('.conteggioagg');
          const subtotalElement = document.getElementById('sub-total');
          const totalElement = document.getElementById('iltotal');
          const shippingCost = parseFloat(document.getElementById('loshipping').textContent.replace('$', ''));

          function updateSummary() {
              let currentSubtotal = 0;
              cartItems.forEach(item => {
                  const price = parseFloat(item.dataset.price);
                  const quantityInput = item.querySelector('.quantity-input');
                  const quantity = parseInt(quantityInput.value);
                  currentSubtotal += price * quantity;
              });

              subtotalElement.textContent = `$${currentSubtotal.toFixed(2)}`;
              const total = currentSubtotal + shippingCost;
              totalElement.textContent = `$${total.toFixed(2)}`;
          }

          cartItems.forEach(item => {
              const incrementButton = item.querySelector('.increment');
              const decrementButton = item.querySelector('.decrement');
              const quantityInput = item.querySelector('.quantity-input');
              const itemPriceElement = item.querySelector('.item-price');
              const basePrice = parseFloat(item.dataset.price);

              incrementButton.addEventListener('click', function() {
                  let quantity = parseInt(quantityInput.value);
                  quantity++;
                  quantityInput.value = quantity;
                  itemPriceElement.textContent = `$${(basePrice * quantity).toFixed(2)}`;
                  updateSummary();
              });

              decrementButton.addEventListener('click', function() {
                  let quantity = parseInt(quantityInput.value);
                  if (quantity > parseInt(quantityInput.getAttribute('min'))) {
                      quantity--;
                      quantityInput.value = quantity;
                      itemPriceElement.textContent = `$${(basePrice * quantity).toFixed(2)}`;
                      updateSummary();
                  }
              });

              quantityInput.addEventListener('change', function() {
                  let quantity = parseInt(this.value);
                  const min = parseInt(this.getAttribute('min')) || 1;
                  if (isNaN(quantity) || quantity < min) {
                      this.value = min;
                      quantity = min;
                  }
                  itemPriceElement.textContent = `$${(basePrice * quantity).toFixed(2)}`;
                  updateSummary();
              });
          });

          updateSummary(); // Inizializza il riepilogo al caricamento della pagina
      });
  </script>



  <!--prodotto singolo prodotto frame: prodotto principale-->
    <script>
		document.addEventListener('DOMContentLoaded', function() {
			const cartItems = document.querySelectorAll('.check-conteggio');

			function updateItemPrice(item) {
				const price = parseFloat(item.dataset.price);
				const quantityInput = item.querySelector('.check-quantity-input');
				const quantity = parseInt(quantityInput.value);
				const itemPriceElement = item.querySelector('.check-item-price');
				itemPriceElement.textContent = `$${(price * quantity).toFixed(2)}`;
			}

			cartItems.forEach(item => {
				const incrementButton = item.querySelector('.check-increment');
				const decrementButton = item.querySelector('.check-decrement');
				const quantityInput = item.querySelector('.check-quantity-input');

				incrementButton.addEventListener('click', function() {
					let quantity = parseInt(quantityInput.value);
					quantity++;
					quantityInput.value = quantity;
					updateItemPrice(item);
				});

				decrementButton.addEventListener('click', function() {
					let quantity = parseInt(quantityInput.value);
					if (quantity > parseInt(quantityInput.getAttribute('min'))) {
						quantity--;
						quantityInput.value = quantity;
						updateItemPrice(item);
					}
				});

				quantityInput.addEventListener('change', function() {
					let quantity = parseInt(this.value);
					const min = parseInt(this.getAttribute('min')) || 1;
					if (isNaN(quantity) || quantity < min) {
						this.value = min;
						quantity = min;
					}
					updateItemPrice(item);
				});

				// Inizializza il prezzo dell'elemento al caricamento della pagina
				updateItemPrice(item);
			});
		});
	</script>

  <!--scelta metodo pagamento pagina checkout-->
  <script>
      const creditCardRadio = document.getElementById('creditCard');
      const otherPaymentRadio = document.getElementById('otherPayment');
      const creditCardFields = document.getElementById('creditCardFields');
      const otherPaymentFields = document.getElementById('otherPaymentFields');

      creditCardRadio.addEventListener('change', function() {
          if (this.checked) {
              creditCardFields.style.display = 'block';
              otherPaymentFields.style.display = 'none';
          }
      });

      otherPaymentRadio.addEventListener('change', function() {
          if (this.checked) {
              creditCardFields.style.display = 'none';
              otherPaymentFields.style.display = 'block';
          }
      });
  </script>


  <!--mostra/nascondi password desktop-->
    <script>
    // Funzionalità Desktop
const passwordInputDesktop = document.getElementById('passwordInputDesktop');
const togglePasswordDesktop = document.getElementById('togglePasswordDesktop');
const eyeIconDesktop = document.getElementById('eyeIconDesktop');

if (togglePasswordDesktop) { // Controlla se l'elemento esiste prima di aggiungere il listener
    togglePasswordDesktop.addEventListener('click', function() {
        const type = passwordInputDesktop.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInputDesktop.setAttribute('type', type);
        if (type === 'password') {
            eyeIconDesktop.src = '<?php echo get_template_directory_uri() . '/ufficiale/occhio.svg'; ?>';
            eyeIconDesktop.alt = 'Mostra password';
        } else {
            eyeIconDesktop.src = '<?php echo get_template_directory_uri() . '/ufficiale/occhio.svg'; ?>';
            eyeIconDesktop.alt = 'Nascondi password';
        }
    });
}

// Funzionalità Mobile
const passwordInputMobile = document.getElementById('passwordInputMobile');
const togglePasswordMobile = document.getElementById('togglePasswordMobile');
const eyeIconMobile = document.getElementById('eyeIconMobile');

if (togglePasswordMobile) { // Controlla se l'elemento esiste prima di aggiungere il listener
    togglePasswordMobile.addEventListener('click', function() {
        const type = passwordInputMobile.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInputMobile.setAttribute('type', type);
        if (type === 'password') {
            eyeIconMobile.src = '<?php echo get_template_directory_uri() . '/ufficiale/occhio.svg'; ?>';
            eyeIconMobile.alt = 'Mostra password';
        } else {
            eyeIconMobile.src = '<?php echo get_template_directory_uri() . '/ufficiale/occhio.svg'; ?>';
            eyeIconMobile.alt = 'Nascondi password';
        }
    });
}
    </script>
    
    
    
    
    
    <?php wp_footer(); ?>


  </body>
</html>
