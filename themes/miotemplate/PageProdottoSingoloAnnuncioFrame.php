<?php

/*
*
* Template Name: Prodotto Singolo Annuncio Frame
* Description: pagina prodotto singolo
*
*/

//get header.php file
get_header();


?>



<!--desktop-->
<div class="container tra-menu-e-titolo d-none d-md-block">
	  <div class="row">

      <div class="col-md-6 ps-0 pe-4">
    <div class="position-relative">
        <div style="position: absolute; top: 130px; left: -33px; z-index: -1;">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-alto-sinistra-gallery.svg'; ?>" class="" alt="Immagine Alto Sinistra 1">
        </div>
        <div style="position: absolute; top: 220px; left: -45px; z-index: 1;">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-alto-sinistra-gallery.svg'; ?>" class="" alt="Immagine Alto Sinistra 2">
        </div>
        <div class="">
            <div class="">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-osso-votato.svg'; ?>" class="" style="width: 100%; height: auto;" alt=""/>
            </div>
        </div>
    </div>

    <div class="mt-4 position-relative">




				<div class=" mt-3">
			    <div class="gallery-tablet">
			      <div class="img-box">
			        <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-galleria.svg;' ?>" alt="Immagine 1" class="img-uniform">
			      </div>
			      <div class="img-box">
			        <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-galleria.svg;' ?>" alt="Immagine 2" class="img-uniform">
			      </div>
			      <div class="img-box">
			        <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-galleria.svg;' ?>" alt="Immagine 3" class="img-uniform">
			      </div>
			    </div>
			  </div>



        <div style="position: absolute; bottom: 53px; right: -38px; z-index: 1; display: flex;">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-basso-destra-gallery.svg'; ?>" class="" alt="Immagine Basso Destra 1">
        </div>
        <div style="position: absolute; bottom: -28px; right: -25px; z-index: -1; display: flex;">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-basso-destra-gallery.svg'; ?>" class="" alt="Immagine Basso Destra 2">
        </div>
    </div>
</div>

		  	<div class="col-md-6 col-md-6-img pe-0 ps-4 position-relative">
			  <div class="immagine-sfondo immagine-sfondo-alto">
				<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-trasparenti-in-alto-descrizione-prodotto-singolo-annuncio-frame.svg'; ?>" alt="Immagine in alto">
			  </div>
			  <div class="">
				<div class="row align-items-start pb-3">
				  <div class="col-md-6">
					<div class="noacapo">
					  <h1>Ciotola per cani</h1>
					</div>
					<div class="acapo" style="font-size:23px;">
					  $39,99
					  <span class="text-secondary text-decoration-line-through" style="font-size:20px;">$59.00</span>
					</div>
				  </div>
				  <div class="col-md-6 d-flex justify-content-end">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-sfondo-grigio.svg'; ?>" class="" alt="Immagine 2">
				  </div>
				</div>
				<hr style="margin-bottom:25px!important;margin-top:10px!important;">
				<a href="#">Descrizione <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
				<div class="acapo small text-secondary" style="margin-top:10px">
				  Fusce sagittis porttitor interdum. Curabitur mattis purus dolor, non pellentesque neque pellentesque et. Pellentesque neque urna, vulputate vitae nisi vitae, feugiat semper dui. Etiam congue consequat ultrices. Nullam blandit neque in nisi facilisis, sit amet ullamcorper sem fermentum. Quisque tempor pharetra lorem, sit amet pellentesque massa. Integer dapibus, eros in consectetur suscipit, sapien nulla dignissim lectus, vitae ullamcorper erat risus consequat risus. Proin mattis libero ac elit ultricies, eu sodales ante pretium. Nam eget mi eu metus gravida porttitor. Vivamus in ipsum sapien.
				</div>
				<hr style="margin-bottom:1.3cm!important;margin-top:0.9cm!important;">

				<a href="#" style="width:100%;" class="btn btn-warning noacapo font-annuncio" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/fumetto-contatta.svg'; ?>" class="me-2" alt=""/>Contatta</a>

				<a href="#" style="width:100%;border-radius:0px!important;font-weight:normal!important;" class="btn noacapo mt-4 bg-light text-dark font-annuncio" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/cerchio-esclamativo.svg'; ?>" class="me-2" alt=""/>Evita di contattare il venditore al di fuori di PetBuy</a>

			  </div>
			  <div class="immagine-sfondo immagine-sfondo-basso-destra">
				<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-trasparenti-in-basso-descrizione-prodotto-singolo-annuncio-frame.svg'; ?>" alt="Immagine in basso a destra">
			  </div>
			</div>


	  </div>
	</div>


	<!--mobile-->
	<div class="d-block d-sm-none my-4">

		<div class="container">
			<div class="row">
				<img src="<?php echo get_template_directory_uri() . '/ufficiale/img-principale-prodotto-annuncio.svg'; ?>" alt="Immagine principale">
			</div>

			<!--div class="row d-flex align-items-center mt-3 g-2" style="width:110%;">
					<div class="col-auto">
							<div class="text-start">
									<img src="?php echo get_template_directory_uri() . '/ufficiale/prodott1-gallery-annuncio.svg'; ?>" class="" alt="Immagine 1">
							</div>
					</div>
					<div class="col-auto">
							<div class="text-center">
									<img src="?php echo get_template_directory_uri() . '/ufficiale/prodott2-gallery-annuncio.svg'; ?>" class="" alt="Immagine 2">
							</div>
					</div>
					<div class="col-auto">
							<div class="text-end">
									<img src="?php echo get_template_directory_uri() . '/ufficiale/prodott3-gallery-annuncio.svg'; ?>" class="" alt="Immagine 3">
							</div>
					</div>
			</div-->

			<div class=" mt-3">
				<div class="gallery-tablet">
					<div class="img-box">
						<img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-galleria.svg;' ?>" alt="Immagine 1" class="img-uniform">
					</div>
					<div class="img-box">
						<img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-galleria.svg;' ?>" alt="Immagine 2" class="img-uniform">
					</div>
					<div class="img-box">
						<img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-galleria.svg;' ?>" alt="Immagine 3" class="img-uniform">
					</div>
				</div>
			</div>

		</div>


		<div class="container position-relative">

			<div class="immagine-sfondo" style="top:90px;left:280px;transform: translateX(-50%);max-width: 80%;height: auto;">
				<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-trasparenti-in-alto-descrizione-prodotto-singolo-annuncio-frame.svg'; ?>" alt="Immagine in alto">
			</div>

			<div class="d-flex align-items-center justify-content-center pt-5">
				<h1 class="me-auto fs-2 my-0">Ciotola per cani</h1> <div class="d-flex align-items-center">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-sfondo-grigio.svg'; ?>" class="" alt="Immagine 2">
				</div>
			</div>

			<div class="acapo" style="font-size:23px;">
				$39,99
				<span class="text-secondary text-decoration-line-through" style="font-size:20px;">$59.00</span>
			</div>

			<hr style="margin-bottom:25px!important;margin-top:20px!important;">
			<a href="#">Descrizione <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
			<div class="acapo small text-secondary" style="margin-top:10px">
				Fusce sagittis porttitor interdum. Curabitur mattis purus dolor, non pellentesque neque pellentesque et. Pellentesque neque urna, vulputate vitae nisi vitae, feugiat semper dui. Etiam congue consequat ultrices. Nullam blandit neque in nisi facilisis, sit amet ullamcorper sem fermentum. Quisque tempor pharetra lorem, sit amet pellentesque massa. Integer dapibus, eros in consectetur suscipit, sapien nulla dignissim lectus, vitae ullamcorper erat risus consequat risus. Proin mattis libero ac elit ultricies, eu sodales ante pretium. Nam eget mi eu metus gravida porttitor. Vivamus in ipsum sapien.
			</div>

			<hr style="margin-bottom:1.3cm!important;margin-top:0.9cm!important;">

			<a href="#" style="width:100%;" class="btn btn-warning noacapo" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/fumetto-contatta.svg'; ?>" class="me-2" alt=""/>Contatta</a>

			<a href="#" style="width:100%; border-radius:0px!important; font-weight:normal!important;  align-items:center; justify-content:flex-start;padding:12px 0 12px 0;" class="btn noacapo mt-4 bg-light text-dark" role="button">
			  <img src="<?php echo get_template_directory_uri() . '/ufficiale/cerchio-esclamativo.svg'; ?>" class="me-2" alt=""/>Non contattare al di fuori di PetBuy
			</a>
		</div>

	</div>


	<!--mobile-->
	<div class="d-block d-sm-none">
		<div class="image-stack-container" style="margin-top:90px!important; background-color:#faf9f8!important;">
			<img src="<?php echo get_template_directory_uri() . '/ufficiale/sfondo-offerte-del-momento.svg'; ?>" alt="Immagine di sfondo" class="background-image">

			<div class="position-relative">
				<div style="position: absolute; width: 100%;z-index: 2;top:-30px;">
					<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-prodotto-bundle.svg'; ?>" class="" alt="">
				</div>
			</div>

			<div class="overlay-image-wrapper2">
				<img src="<?php echo get_template_directory_uri() . '/ufficiale/cane-nero.svg'; ?>" class="clipped-image2" alt="...">
			</div>
		</div>

		<div class="container pt-3" style="background-color:#faf9f8!important;">
			<div class="primary fw-bold small">Bundle Prodotti</div>
			<h1 class="noacapo fs-2 mb-3" style="font-size:130%!important;">Aggiungi anche il nostro cibo</h1>

			<div class="row">
				<div class="col-12">
					<div class="">
						<p class="" style="font-size: 12px;">Aggiungendo alla tua ciotola per cani, il nostro cibo, risparmi sul prezzo!</p>



						<div class="conteggioaggmobile" style="margin-top:70px!important;" data-price="39.99">
								<!--<div class="item-details">
										<div class="item-name noacapo">Ciotola per cani</div>
										<div class="item-delivery noacapo small">Consegna il 23 giugno</div>
								</div>-->
								<div class="conteggio-numero w-100">
										<button class="quantity-button decrementmobile">-</button>
										<input type="text" class="quantity-input-mobile" value="1" min="1">
										<button class="quantity-button incrementmobile">+</button>
								</div>

								<a href="#" style="width:100%;color:#F87537!important;border-color:#F87537;border-style: solid;" class="btn noacapo bg-trasparente border-2 fw-bold fs-5 mt-3" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/shopping-cart-arancione.svg'; ?>" class="me-2" alt=""/>Aggiungi al carrello</a>

								<div class="item-price-mobile d-none">$39.99</div>
						</div>



						<div class="noacapo my-5 text-center">
							<div class="">
								<span class="text-secondary fs-4" id="sub-total-mobile">$39.99</span> +
								<span class="fs-4" id="loshippingmobile">$15.99</span>

								<!--
								<span class="float-end noacapo">
									<span class="text-secondary fs-4 me-2">Per un totale di:</span>
									<span class="fs-4" id="iltotalmobile">$56.00</span>
								</span>
								-->
							</div>
						</div>

					</div>
				</div>
			</div>


		</div>
</div>


	<!--mobile-->
	<div class="container d-block d-sm-none mt-4">
		<div class="row">

			<div class="col-md-12 fw-bold">
				<h1 class="fs-2 float-start">Recensioni</h1>
			</div>

		</div>
	</div>

	<!--mobile-->
	<div class="container d-block d-sm-none margine-standard">
    <div class="row">
        <div class="col-md-12">
            <div class="p-3 bg-light">
                <p class="small fw-bold text-secondary">Titolo recensione</p>
                <p class="float-start small text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
                <div class="pt-5">
                    <div class="row flex-nowrap align-items-center">
                        <div class="col-auto d-flex align-items-center">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
                        </div>
                        <div class="col-auto small noacapo d-flex align-items-center ms-2 text-secondary">Postato 13 giugno 2025</div>
                    </div>
                </div>
                </div>
        </div>
    </div>
</div>


<!--mobile-->
<div class="container d-block d-sm-none margine-standard">
	<div class="row">
			<div class="col-md-12">
					<div class="p-3 bg-light">
							<p class="small fw-bold text-secondary">Titolo recensione</p>
							<p class="float-start small text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
							<div class="pt-5">
									<div class="row flex-nowrap align-items-center">
											<div class="col-auto d-flex align-items-center">
													<img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
											</div>
											<div class="col-auto small noacapo d-flex align-items-center ms-2 text-secondary">Postato 13 giugno 2025</div>
									</div>
							</div>
							</div>
			</div>
	</div>
</div>


<!--mobile-->
<div class="container d-block d-sm-none margine-standard">
	<div class="row">
			<div class="col-md-12">
					<div class="p-3 bg-light">
							<p class="small fw-bold text-secondary">Titolo recensione</p>
							<p class="float-start small text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
							<div class="pt-5">
									<div class="row flex-nowrap align-items-center">
											<div class="col-auto d-flex align-items-center">
													<img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
											</div>
											<div class="col-auto small noacapo d-flex align-items-center ms-2 text-secondary">Postato 13 giugno 2025</div>
									</div>
							</div>
							</div>
			</div>
	</div>
</div>


<!--mobile-->
<div class="container d-block d-sm-none margine-standard">
	<div class="row">
			<div class="col-md-12">
					<div class="p-3 bg-light">
							<p class="small fw-bold text-secondary">Titolo recensione</p>
							<p class="float-start small text-secondary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
							<div class="pt-5">
									<div class="row flex-nowrap align-items-center">
											<div class="col-auto d-flex align-items-center">
													<img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
											</div>
											<div class="col-auto small noacapo d-flex align-items-center ms-2 text-secondary">Postato 13 giugno 2025</div>
									</div>
							</div>
							</div>
			</div>
	</div>
</div>





	<!--desktop-->
	<div class="container background-bundle d-none d-md-block">

    <div class="row">
      <div class="col-md-6 sfondo-crocchette-bundle ps-desk ps-annuncio" style="">

        <img src="<?php echo get_template_directory_uri() . '/ufficiale/cane-nero.svg'; ?>" class="img-fluid" alt="...">

      </div>

      <div class="col-md-6 pd-desk pd-annuncio" style="">
        <div class="primary fw-bold small">Bundle Prodotti</div>
        <h1 class="fs-2 ">
					<div class="h1tablet">
						Aggiungi anche il nostro cibo
					</div>
				</h1>

        <div class="row">
          <div class="col-12">
            <div class="">
              <p class="" style="font-size: 12px;">Aggiungendo alla tua ciotola per cani, il nostro cibo, risparmi sul prezzo!</p>

              <hr>

              <div class="d-flex align-items-center mt-2 conteggioagg" data-price="39.99">
                  <!--<div class="item-details">
                      <div class="item-name noacapo">Ciotola per cani</div>
                      <div class="item-delivery noacapo small">Consegna il 23 giugno</div>
                  </div>-->
                  <div class="conteggio-numero">
                      <button class="quantity-button decrement">-</button>
                      <input type="text" class="quantity-input" value="1" min="1">
                      <button class="quantity-button increment">+</button>
                  </div>

                  <a href="#" style="width:100%;color:#F87537!important;border-color:#F87537;border-style: solid;" class="btn noacapo bg-trasparente border-3 ms-3 fw-bold fs-5" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/shopping-cart-arancione.svg'; ?>" class="me-2" alt=""/>Aggiungi al carrello</a>

                  <div class="item-price d-none">$39.99</div>
              </div>

              <hr>

              <div class="noacapo riga-conta-sped-annuncio">
                <div class="">
									<span class="conta-sped-annuncio">
	                  <span class="text-secondary fs-4" id="sub-total">$39.99</span> +
	                  <span class="fs-4" id="loshipping">$15.99</span>
									</span>

                  <span class="float-end noacapo">
                    <span class="text-secondary fs-4 me-2">Per un totale di:</span>
                    <span class="fs-4" id="iltotal">$56.00</span>
                  </span>
                </div>
              </div>

            </div>
          </div>
        </div>


      </div>
    </div>

  </div>


		<!--desktop-->
    <div class="container d-none d-md-block">
        <div class="row tra-menu-e-titolo">

          <div class="col-md-12 fw-bold m-0 p-0">
            <h1 class="fs-2 float-start">Recensioni</h1>
          </div>

        </div>
      </div>


			<!--desktop-->
      <div class="container d-none d-md-block margine-standard">
        <div class="row">

          <div class="col-md-12 bg-light">
            <div class="pt-3 ps-2">
              <p class="small fw-bold">Titolo recensione</p>
              <p class="float-start small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
              <p class="float-end small">Postato 13 giugno 2025</p>
              <p class="pt-5">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
              </p>
            </div>
          </div>

        </div>
      </div>

			<!--desktop-->
      <div class="container d-none d-md-block margine-standard">
        <div class="row">

          <div class="col-md-12 bg-light">
            <div class="pt-3 ps-2">
              <p class="small fw-bold">Titolo recensione</p>
              <p class="float-start small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
              <p class="float-end small">Postato 13 giugno 2025</p>
              <p class="pt-5">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
              </p>
            </div>
          </div>

        </div>
      </div>

			<!--desktop-->
      <div class="container d-none d-md-block margine-standard">
        <div class="row">

          <div class="col-md-12 bg-light">
            <div class="pt-3 ps-2">
              <p class="small fw-bold">Titolo recensione</p>
              <p class="float-start small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
              <p class="float-end small">Postato 13 giugno 2025</p>
              <p class="pt-5">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
              </p>
            </div>
          </div>

        </div>
      </div>


			<!--desktop-->
      <div class="container d-none d-md-block margine-standard">
        <div class="row">

          <div class="col-md-12 bg-light">
            <div class="pt-3 ps-2">
              <p class="small fw-bold">Titolo recensione</p>
              <p class="float-start small">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent id lorem non magna laoreet lacinia.</p>
              <p class="float-end small">Postato 13 giugno 2025</p>
              <p class="pt-5">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelle-senza-brillio.svg'; ?>" class="" alt=""/>
              </p>
            </div>
          </div>

        </div>
      </div>


      <div class="spazio-standard d-none d-md-block"></div>



				<!--desktop-->
        <div class="container px-0 mb-5 d-none d-md-block">
          <div class="d-flex justify-content-between">

      			<div class="col-md-6 fw-bold p-0">
      				<h1 class="noacapo float-start fs-2">Categorie che potrebbero interessanti</h1>
      			</div>

      			<div class="col-md-6 py-2 m-0 px-0">
      				<div class="small float-end">
      					<a href="#">Vai allo shop <img src="<?php echo get_template_directory_uri() . '/ufficiale/freccia-destra.svg'; ?>" alt=""/></a>
      				</div>
      			</div>

  		    </div>
        </div>

				<!--mobile-->
				<div class="container mt-5 mb-3 d-block d-sm-none">
          <div class="d-flex justify-content-between">

      			<div class="col-md-6 fw-bold">
      				<h1 class="noacapo float-start fs-2">Categoria tra animali</h1>
      			</div>

      			<div class="col-md-6">

      			</div>

  		    </div>
        </div>


				<div class="container px-0"><div class="d-flex justify-content-between flex-wrap"> <div class="col-6 col-md-3 mx-0 px-0"> <div class="text-center">
                <a href="#">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-cani.svg'; ?>" class="mx-auto d-block img-fluid"/>
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3 mx-0 px-0"> <div class="text-center">
                <a href="#">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-gatti.svg'; ?>" class="mx-auto d-block img-fluid"/>
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3 mx-0 px-0"> <div class="text-center">
                <a href="#">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-uccelli.svg'; ?>" class="mx-auto d-block img-fluid"/>
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3 mx-0 px-0"> <div class="text-center">
                <a href="#">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-criceti.svg'; ?>" class="mx-auto d-block img-fluid"/>
                </a>
            </div>
        </div>

    </div>
</div>


		<!--desktop-->
		<div class="container px-0 tra-menu-e-titolo mb-5 d-none d-md-block">
          <div class="d-flex justify-content-between">

      			<div class="col-md-6 fw-bold p-0">
      				<h1 class="fs-2 float-start">Prodotti correlati</h1>
      			</div>

      			<div class="col-md-6 py-2 mx-0 px-0">
      				<div class="small float-end">
      					<a href="#">Vai allo shop <img src="<?php echo get_template_directory_uri() . '/ufficiale/freccia-destra.svg'; ?>" alt=""/></a>
      				</div>
      			</div>

  		    </div>
        </div>


				<!--mobile-->
				<div class="container mt-5 mb-3 d-block d-sm-none">
		          <div class="d-flex justify-content-between">

		      			<div class="col-md-6 fw-bold">
		      				<h1 class="fs-2 float-start">Prodotti correlati</h1>
		      			</div>

		      			<div class="col-md-6">

		      			</div>

		  		    </div>
		        </div>





      <div class="container p-zero"><!--inizio container-->
    		<div class="row g-2 g-md-4">

    			<div class="col-md-3 col-6">

            <div class="scheda-prodotto">
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
							<!--desktop-->
              <div class="container d-none d-md-block">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet2">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>
							<!--inizio per mobile-->
              <div class="container d-block d-sm-none">
                <div class="row align-items-start pb-3">
                  <div class="col px-0">
                    <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
                  </div>
                  <div class="col px-0 mt-2">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="small me-auto">$20,99</div> <div class="d-flex align-items-center">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1"> <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--fine per mobile-->
          </div>

    			</div>

    			<div class="col-md-3 col-6">

            <div class="scheda-prodotto">
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
							<!--desktop-->
              <div class="container d-none d-md-block">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet2">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>
							<!--inizio per mobile-->
              <div class="container d-block d-sm-none">
                <div class="row align-items-start pb-3">
                  <div class="col px-0">
                    <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
                  </div>
                  <div class="col px-0 mt-2">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="small me-auto">$20,99</div> <div class="d-flex align-items-center">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1"> <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--fine per mobile-->
          </div>

    			</div>

    			<div class="col-md-3 col-6">

            <div class="scheda-prodotto">
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
							<!--desktop-->
              <div class="container d-none d-md-block">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet2">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>
							<!--inizio per mobile-->
              <div class="container d-block d-sm-none">
                <div class="row align-items-start pb-3">
                  <div class="col px-0">
                    <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
                  </div>
                  <div class="col px-0 mt-2">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="small me-auto">$20,99</div> <div class="d-flex align-items-center">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1"> <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--fine per mobile-->
          </div>

    			</div>

    			<div class="col-md-3 col-6">

            <div class="scheda-prodotto">
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
							<!--desktop-->
              <div class="container d-none d-md-block">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet2">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>
							<!--inizio per mobile-->
              <div class="container d-block d-sm-none">
                <div class="row align-items-start pb-3">
                  <div class="col px-0">
                    <div class="noacapo" style="font-size:16px;">Ciotola per cani</div>
                  </div>
                  <div class="col px-0 mt-2">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="small me-auto">$20,99</div> <div class="d-flex align-items-center">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1"> <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--fine per mobile-->
          </div>

    			</div>

    		</div>
        </div><!--fine container-->







<?php

//get footer.php file
get_footer();


?>
