<?php

/*
*
* Template Name: HomepagePetBuy
* Description: pagina principale
*
*/

//get header.php file
get_header();


?>

    <div class="sezione-cane">

	  <section class="">
        <div class="container">
          <div class="row">
            <div class="col-md-9">
			        <div class="primary fw-bold small">Negozio Di Animali</div>
              <h1 class="h1principale">PetBuy, trova tutto quello che cerchi per il tuo animale</h1>

              <div class="row">
                <div class="col-md-10">
                  <!--mobile-->
                  <div class="desc-mobile d-block d-sm-none">Dai gadget più divertenti fino al tuo compagno di vita, sei tu a scegliere quello che vuoi!</div>
                  <!--desktop-->
                  <div class="d-none d-md-block pd-desk" style="">Dai gadget più divertenti fino al tuo compagno di vita, sei tu a scegliere quello che vuoi!</div>
                </div>
                <div class="col-md-2">
                </div>
              </div>

              <div class="d-flex gap-4 margine-bottoni mt-tablet">
                <div class="d-none d-md-block">
                  <a href="#" class="btn btn-warning noacapo" role="button">Vai allo shop</a>
                </div>
                <div class="d-none d-md-block">
                  <a href="#" class="btn btn-light noacapo" role="button">Categorie &#8594;</a>
                </div>
           	  </div>

            </div>
            <div class="col-md-3">

            </div>
          </div>
        </div>
      </section>


    </div>


        <div class="container d-block d-sm-none pt-4" style="background-color: #faf9f8!important;padding-bottom:45px!important;">

        	<div class="row mb-4">

        		<div class="col-md-12">
        			<a href="#" class="btn btn-warning w-100" role="button">Vai allo shop</a>
        		</div>

        	</div>

        	<div class="row">

        		<div class="col-md-12">
        			<a href="#" class="btn btn-light noacapo w-100" role="button">Categorie &#8594;</a>
        		</div>

        	</div>

        </div>



        <div class="container-fluid"><!--container-fluid-1-->


    	<div class="container servizi-spazio spazio-link-img-top">
  		  <div class="row justify-content-center">

  			<div class="col-lg-4 col-md-4 spazio-link-img">
  				<img src="<?php echo get_template_directory_uri() . '/ufficiale/ciotola-con-sfondo.svg'; ?>" class="servizi mx-auto d-block"  alt="Descrizione dell'immagine">
          <div class="text-center noacapo">
            <a href="#" class="font-shop">Vendi accessori e animali &#8594;</a>
          </div>
  			</div>

  			<div class="col-lg-4 col-md-4 spazio-link-img">
  				<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-con-sfondo.svg'; ?>" class="servizi mx-auto d-block"  alt="Descrizione dell'immagine">
          <div class="text-center noacapo">
            <a href="#" class="font-shop">Compra il tuo animale preferito &#8594;</a>
          </div>
  			</div>

  			<div class="col-lg-4 col-md-4 spazio-link-img">
  				<img src="<?php echo get_template_directory_uri() . '/ufficiale/calendario-con-sfondo.svg'; ?>" class="servizi mx-auto d-block"  alt="Descrizione dell'immagine">
          <div class="text-center noacapo">
            <a href="#" class="font-shop">Partecipa ad eventi e fiere &#8594;</a>
          </div>
  			</div>

  		  </div>
  		</div>



        <div class="container senza-spazi">
          <div class="d-flex justify-content-between mb-4">

      			<div class="col-md-6 fw-bold  novita-mobile ">
      				<h1 class="fs-2 float-start">Le novità dello shop</h1>
      			</div>

      			<div class="col-md-6 mt-3">
              <div class="small float-end d-none d-md-block">
      					<a href="#">Vai allo shop <img src="<?php echo get_template_directory_uri() . '/ufficiale/freccia-destra.svg'; ?>" class=""  alt="Descrizione dell'immagine"></a>
      				</div>
      			</div>

  		    </div>
        </div>

        <div class="container senza-spazi"><!--inizio container-->
    		<div class="row g-2 g-md-4">

    			<div class="col-6 col-md-3">

            <div class="scheda-prodotto">
              <!--img desktop-->
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail d-none d-md-block"/>
              <!--img mobile-->
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail d-block d-sm-none"/>
              <!--inizio per desktop-->
              <div class="container d-none d-md-block">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>
              <!--fine per desktop-->

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

          <div class="col-6 col-md-3">

            <div class="scheda-prodotto">
              <!--img desktop-->
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail d-none d-md-block"/>
              <!--img mobile-->
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail d-block d-sm-none"/>
              <!--inizio per desktop-->
              <div class="container d-none d-md-block">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>
              <!--fine per desktop-->

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

          <div class="col-6 col-md-3">

            <div class="scheda-prodotto">
              <!--img desktop-->
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail d-none d-md-block"/>
              <!--img mobile-->
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail d-block d-sm-none"/>
              <!--inizio per desktop-->
              <div class="container d-none d-md-block">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>
              <!--fine per desktop-->

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

          <div class="col-6 col-md-3">

            <div class="scheda-prodotto">
              <!--img desktop-->
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail d-none d-md-block"/>
              <!--img mobile-->
              <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail d-block d-sm-none"/>
              <!--inizio per desktop-->
              <div class="container d-none d-md-block">
                <div class="row align-items-start pb-3">
                  <div class="col-md-6">
                    <div class="noacapo fortablet">Ciotola per cani</div>
                    <div class="acapo small">$20,99</div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                  </div>
                </div>
              </div>
              <!--fine per desktop-->

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








        <div class="container senza-spazi"><!--inizio container-->
          <div class="row g-2 g-md-4">

      			<div class="col-6 col-md-3">

              <div class="scheda-prodotto">
                <!--img desktop-->
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail d-none d-md-block"/>
                <!--img mobile-->
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail d-block d-sm-none"/>
                <!--inizio per desktop-->
                <div class="container d-none d-md-block">
                  <div class="row align-items-start pb-3">
                    <div class="col-md-6">
                      <div class="noacapo fortablet">Ciotola per cani</div>
                      <div class="acapo small">$20,99</div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                    </div>
                  </div>
                </div>
                <!--fine per desktop-->

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

            <div class="col-6 col-md-3">

              <div class="scheda-prodotto">
                <!--img desktop-->
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail d-none d-md-block"/>
                <!--img mobile-->
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail d-block d-sm-none"/>
                <!--inizio per desktop-->
                <div class="container d-none d-md-block">
                  <div class="row align-items-start pb-3">
                    <div class="col-md-6">
                      <div class="noacapo fortablet">Ciotola per cani</div>
                      <div class="acapo small">$20,99</div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                    </div>
                  </div>
                </div>
                <!--fine per desktop-->

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

            <div class="col-6 col-md-3 d-none d-md-block">

              <div class="scheda-prodotto">
                <!--img desktop-->
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail d-none d-md-block"/>
                <!--img mobile-->
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail d-block d-sm-none"/>
                <!--inizio per desktop-->
                <div class="container d-none d-md-block">
                  <div class="row align-items-start pb-3">
                    <div class="col-md-6">
                      <div class="noacapo fortablet">Ciotola per cani</div>
                      <div class="acapo small">$20,99</div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                    </div>
                  </div>
                </div>
                <!--fine per desktop-->

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

            <div class="col-6 col-md-3 d-none d-md-block">

              <div class="scheda-prodotto">
                <!--img desktop-->
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail d-none d-md-block"/>
                <!--img mobile-->
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail d-block d-sm-none"/>
                <!--inizio per desktop-->
                <div class="container d-none d-md-block">
                  <div class="row align-items-start pb-3">
                    <div class="col-md-6">
                      <div class="noacapo fortablet">Ciotola per cani</div>
                      <div class="acapo small">$20,99</div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                    </div>
                  </div>
                </div>
                <!--fine per desktop-->

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


        </div><!--container-fluid-1-->







    <div class="spazio-standard d-none d-md-block"></div>


    <div class="sfondo-falco d-none d-md-block">

    <section class="falco ">
        <div class="container">
          <div class="row">
            <div class="col-md-9">
              <div class="primary fw-bold small">Lavora Con Noi</div>
              <h1 class="fs-2">Sei un negozio o un privato?</h1>

              <div class="row mt-3">
                <div class="col-md-8">
                  <div class="small">
                    <p>Sfrutta il nostro sito per raggiungere i tuoi obiettivi. Grazie a PetBuy hai la possibilità di vendere i tuoi prodotti sul nostro sito web.</p>
                    <p>Inzia subito registrando i tuoi prodotti e mettendoli online, e noi faremo il lavoro per te!</p>
                  </div>
                </div>
                <div class="col-md-4">
                </div>
              </div>

              <div class="margine-bottoni d-flex gap-4">
                <a href="#" class="btn btn-light noacapo" role="button">Registra i tuoi prodotti &#8594;</a>
                <!--<img src="ufficiale/registra-i-tuoi-prodotti.svg" class="img-fluid" alt="" />-->
              </div>
            </div>
            <div class="col-md-3">

            </div>
          </div>
        </div>
      </section>


    </div>


    <section class="d-block d-sm-none" style="margin-top: 33px;">

      <div>
        <img src="<?php echo get_template_directory_uri() . '/ufficiale/falco-sfondo-mobile.svg'; ?>" class="mx-auto d-block img-fluid" alt="falco con sfondo">
      </div>

      <div class="container" style="padding-top:33px;padding-bottom:60px;background-color:#faf9f8;">
        <div class="row">
          <div class="col-md-12">
            <div class="primary fw-bold small">Lavora Con Noi</div>
            <h1 class="noacapo fs-2">Sei un negozio o un privato?</h1>
            <div class="" style="margin-top:24px;">
              <p class="desc-mobile">Sfrutta il nostro sito per raggiungere i tuoi obiettivi. Grazie a PetBuy hai la possibilità di vendere i tuoi prodotti sul nostro sito web.</p>
              <p class="desc-mobile">Incomincia subito registrando i tuoi prodotti e mettendoli online, e noi faremo il lavoro per te!</p>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:50px!important;">
          <div class="col-md-12">
            <a href="#" class="btn btn-light noacapo w-100" role="button">Registra i tuoi prodotti &#8594;</a>
          </div>
        </div>
      </div>

    </section>


    <div class="spazio-standard"></div>


    <div class="container-fluid">
      <!--desktop-->
      <div class="container d-none d-md-block">
        <div class="d-flex justify-content-between mb-3">

          <div class="col-md-6 fw-bold ">
            <h1 class="fs-2 float-start">Categoria tra animali</h1>
          </div>

          <div class="col-md-6 mt-3">
            <div class="small float-end">
            	<a href="#">Vai allo shop <img src="<?php echo get_template_directory_uri() . '/ufficiale/freccia-destra.svg'; ?>" class=""  alt="Descrizione dell'immagine"></a>
            </div>
          </div>

        </div>
      </div>

      <!--mobile-->
      <div class="d-block d-sm-none">
        <div class="d-flex justify-content-between mb-3">

          <div class="col-md-6 fw-bold">
            <h1 class="fs-2 float-start">Categoria tra animali</h1>
          </div>

          <div class="col-md-6">

          </div>

        </div>
      </div>

      <div class="container senza-spazi m-b60px"><!--inizio container-->
        <div class="row g-2 g-md-4">

          <div class="col-md-3 col-6">

              <div class="text-center">
                <a href="#">
                 <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-cani.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
                </a>
              </div>

          </div>

          <div class="col-md-3 col-6">

              <div class="text-center">
                <a href="#">
               	  <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-gatti.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
                </a>
              </div>

          </div>

          <div class="col-md-3 col-6">

              <div class="text-center">
                <a href="#">
                  <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-uccelli.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
                </a>
              </div>

          </div>

          <div class="col-md-3 col-6">

              <div class="text-center">
                <a href="#">
               	  <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-criceti.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
                </a>
              </div>

          </div>

        </div>
      </div><!--fine container-->

    </div>


    <div class="spazio-standard d-none d-md-block"></div>

    <div class="colore-sfondo-galleria d-none d-md-block">
    <div class="container-galleria">
    <div class="container">

      <div class="row">
        <div class="col-md-6">

          <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
               	<img src="<?php echo get_template_directory_uri() . '/ufficiale/crocchette3.svg'; ?>" class="rounded mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
              </div>
              <div class="carousel-item">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/crocchette4.svg'; ?>" class="rounded mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
              </div>
              <div class="carousel-item">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/crocchette3.svg'; ?>" class="rounded mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
              </div>
            </div>
            <!--<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>-->
          </div>

        </div>

        <div class="col-md-6">
          <div class="primary fw-bold small">Offerte Del Momento</div>
          <h1 class="fs-2">Trova i tuoi gusti migliori!</h1>

          <div class="row">
            <div class="col-12">
              <div class="">
                <p>Rendi il tuo migliore amico felice, comprando i nostri snack più deliziosi e tutto al miglior prezzo.</p>
                <p>Le nostre offerte vengono aggiornate ogni giorno, <a href="#" class="text-decoration-underline">attiva la newsletter</a> per non perderti nulla!</p>
              </div>
            </div>
          </div>

          <div class="margine-bottoni d-flex gap-4">
            <div class="">
              <a href="#" class="btn btn-warning noacapo fortablet" role="button">Acquista ora</a>
            </div>
            <div class="">
              <a href="#" class="btn btn-light noacapo fortablet d-block d-md-none d-lg-block" role="button">Altre offerte del momento &#8594;</a>
              <a href="#" class="btn btn-light noacapo fortablet d-none d-md-block d-lg-none" role="button">Altre offerte &#8594;</a>
            </div>
          </div>
        </div>
      </div>

    </div>
    </div>
    </div>


    <div class="d-block d-sm-none" style="margin-top: 33px;">


      <div class="image-stack-container">
        <img src="<?php echo get_template_directory_uri() . '/ufficiale/sfondo-offerte-del-momento.svg'; ?>" alt="Immagine di sfondo" class="background-image">

        <div class="overlay-image-wrapper">
          <img src="<?php echo get_template_directory_uri() . '/ufficiale/crocchette3.svg'; ?>" alt="Immagine da ritagliare" class="clipped-image">
        </div>
      </div>

      <div class="container" style="padding-top:33px;padding-bottom:60px;background-color:#faf9f8;">
        <div class="row">
          <div class="col-md-12">
            <div class="primary fw-bold small">Offerte Del Momento</div>
            <h1 class="noacapo fs-2">Trova i tuoi gusti migliori!</h1>
            <div class="" style="margin-top:24px;">
              <p class="desc-mobile">Rendi il tuo migliore amico felice, comprando i nostri snack più deliziosi e tutto al miglior prezzo.</p>
              <p class="desc-mobile">Le nostre offerte vengono aggiornate ogni giorno, <a href="#" class="text-decoration-underline">attiva la newsletter</a> per non perderti nulla!</p>
            </div>
          </div>
        </div>

        <div class="row gap-4" style="margin-top:50px!important;">
          <div class="col-md-12">
            <a href="#" class="btn btn-warning noacapo w-100" role="button">Acquista ora</a>
          </div>
          <div class="col-md-12">
            <a href="#" class="btn btn-light noacapo w-100" role="button">Altre offerte del momento &#8594;</a>
          </div>
        </div>
      </div>

    </div>


    <div class="spazio-standard d-none d-md-block"></div>




    <!--desktop-->
    <div class="container d-none d-md-block">
        <div class="d-flex justify-content-between mb-3">

          <div class="col-md-6 fw-bold ">
            <h1 class="fs-2 float-start">Le nostre categorie</h1>
          </div>

          <div class="col-md-6 mt-3">
            <div class="small float-end">
            	<a href="#">Vai allo shop <img src="<?php echo get_template_directory_uri() . '/ufficiale/freccia-destra.svg'; ?>" class=""  alt="Descrizione dell'immagine"></a>
            </div>
          </div>

        </div>
      </div>

      <!--mobile-->
      <div class="container d-block d-sm-none" style="margin-top:50px;">
        <div class="d-flex justify-content-between mb-3">

          <div class="col-md-6 fw-bold ">
            <h1 class="fs-2 float-start">Le nostre categorie</h1>
          </div>

          <div class="col-md-6 ">

          </div>

        </div>
      </div>


      <!--22-3-25 categorie prodotti-->
      <div class="container my-5 d-none d-md-block">
        <div class="row same-height">
          <!-- Colonna 1 -->
          <div class="col-md-4">
            <div class="col-content align-to-bottom rounded sfondo-tiragraffi">

            	<img src="<?php echo get_template_directory_uri() . '/ufficiale/tiragraffi.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">

              <a href="#">
                <div class="nostre-categorie rounded overlap font-shop">
                  Accessori & Giocattoli
                  <div class="float-end">
                  <span class="fw-bold">Vai</span> &rarr;
                  </div>
                </div>
              </a>
            </div>
          </div>
          <!-- Colonna 2 -->
          <div class="col-md-4">
            <div class="col-content align-to-bottom rounded sfondo-scatole">

            	<img src="<?php echo get_template_directory_uri() . '/ufficiale/scatole.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">

              <a href="#">
                <div class="nostre-categorie rounded overlap font-shop">
                  Cibo & Snacks
                  <div class="float-end">
                  <span class="fw-bold">Vai</span> &rarr;
                  </div>
                </div>
              </a>
            </div>
          </div>
          <!-- Colonna 3 -->
          <div class="col-md-4">
            <div class="col-content align-to-bottom rounded sfondo-cuccia">

            	<img src="<?php echo get_template_directory_uri() . '/ufficiale/cuccia.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">

              <a href="#">
                <div class="nostre-categorie rounded overlap font-shop">
                  Fornitura & Strutture
                  <div class="float-end">
                  <span class="fw-bold">Vai</span> &rarr;
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!--22-3-25 categorie prodotti-->


      <!--inizio categorie prodotti mobile-->
      <div class="container d-block d-sm-none">
      <div id="carouselExampleIndicators" class="carousel slide pt-0 ps-0 pe-0 pb-5" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">

            <div class="col-content align-to-bottom rounded sfondo-tiragraffi">
             <img src="<?php echo get_template_directory_uri() . '/ufficiale/tiragraffi.svg'; ?>" class="mx-auto d-block img-fluid"/>
                    <a href="#">
          						<div class="nostre-categorie rounded overlap">
          						  Accessori & Giocattoli
          						  <div class="float-end">
          							<span class="fw-bold">Vai</span> &rarr;
          						  </div>
          						</div>
                    </a>
            </div>

          </div>
          <div class="carousel-item">

            <div class="col-content align-to-bottom rounded sfondo-scatole">
             <img src="<?php echo get_template_directory_uri() . '/ufficiale/scatole.svg'; ?>" class="mx-auto d-block img-fluid"/>
                    <a href="#">
          						<div class="nostre-categorie rounded overlap">
          						  Cibo & Snacks
          						  <div class="float-end">
          							<span class="fw-bold">Vai</span> &rarr;
          						  </div>
          						</div>
                    </a>
            </div>

          </div>
          <div class="carousel-item">

            <div class="col-content align-to-bottom rounded sfondo-cuccia">
             <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuccia.svg'; ?>" class="mx-auto d-block img-fluid"/>
                    <a href="#">
          						<div class="nostre-categorie rounded overlap">
          						  Fornitura & Strutture
          						  <div class="float-end">
          							<span class="fw-bold">Vai</span> &rarr;
          						  </div>
          						</div>
                    </a>
            </div>

          </div>
        </div>
        <!--<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>-->
      </div>
    </div>
      <!--fine categorie prodotti mobile-->



    <div class="spazio-standard"></div>


    <!--desktop-->
    <div class="container d-none d-md-block">
      <div class="d-flex justify-content-between mb-3">

        <div class="col-md-6 fw-bold ">
          <h1 class="fs-2 float-start">Post del blog</h1>
        </div>

        <div class="col-md-6 mt-3">
          <div class="small float-end">
          	<a href="#">Vai ai post <img src="<?php echo get_template_directory_uri() . '/ufficiale/freccia-destra.svg'; ?>" class=""  alt="Descrizione dell'immagine"></a>
          </div>
        </div>

      </div>
    </div>

    <!--mobile-->
    <div class="container d-block d-sm-none">
      <div class="d-flex justify-content-between mb-4">

        <div class="col-md-6 fw-bold ">
          <h1 class="fs-2 float-start">Post del blog</h1>
        </div>

        <div class="col-md-6 ">

        </div>

      </div>
    </div>

    <!--desktop-->
    <div class="container d-none d-md-block mt-5">
      <div class="row justify-content-center">

        <div class="col-lg-4 col-md-4 ">

            <div class="">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/post.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
              <div class="fw-bold noacapo font-shop" style="margin-top:20px;">Nuovi sconti del mese di Gennaio</div>
              <div class="acapo small">20 Agosto 2024</div>
            </div>

        </div>

        <div class="col-lg-4 col-md-4 ">

            <div class="">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/post.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
              <div class="fw-bold noacapo font-shop" style="margin-top:20px;">Nuovi sconti del mese di Gennaio</div>
              <div class="acapo small">20 Agosto 2024</div>
            </div>

        </div>

        <div class="col-lg-4 col-md-4 ">

            <div class="">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/post.svg'; ?>" class="mx-auto d-block img-fluid"  alt="Descrizione dell'immagine">
              <div class="fw-bold noacapo font-shop" style="margin-top:20px;">Nuovi sconti del mese di Gennaio</div>
              <div class="acapo small">20 Agosto 2024</div>
            </div>

        </div>

      </div>
    </div><!--fine container-->


    <!--mobile-->
    <div class="container d-block d-sm-none">

      <div id="carouselExampleIndicators" class="carousel slide pt-0 ps-0 pe-0" style="padding-bottom:85px;" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">

            <div class="">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/post.svg'; ?>" class="mx-auto d-block img-fluid"/>
              <div class="fw-bold noacapo" style="margin-top:20px;">Nuovi sconti del mese di Gennaio</div>
              <div class="acapo small">20 Agosto 2024</div>
            </div>

          </div>
          <div class="carousel-item">

            <div class="">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/post.svg'; ?>" class="mx-auto d-block img-fluid"/>
              <div class="fw-bold noacapo" style="margin-top:20px;">Nuovi sconti del mese di Gennaio</div>
              <div class="acapo small">20 Agosto 2024</div>
            </div>

          </div>
          <div class="carousel-item">

            <div class="">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/post.svg'; ?>" class="mx-auto d-block img-fluid"/>
              <div class="fw-bold noacapo" style="margin-top:20px;">Nuovi sconti del mese di Gennaio</div>
              <div class="acapo small">20 Agosto 2024</div>
            </div>

          </div>
        </div>
        <!--<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>-->
      </div>

    </div>



<?php

//get footer.php file
get_footer();


?>
