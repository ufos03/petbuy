<?php

/*
*
* Template Name: PageShop
* Description: pagina negozio
*
*/

//get header.php file
get_header();


?>


<!--desktop-->
<div class="container spazi-riga-sidebar px-0 d-none d-md-block">
          <div class="row">
              <div class="col-md-4 col-lg-3 sidebar-col"></div>

              <div class="col-md-8 col-lg-9 content-col">

                <div class="">
                  <div class="row ">

                    <div class="col-md-3 text-secondary float-start noacapo">
                      <div class="switch-risultati-shop">122 Risultati trovati</div>
                    </div>

                    <div class="col-md-9">

                      <div class="float-end">


                          <div class="btn-group switch-shop2" role="group" aria-label="Switch di opzioni">
                            <input type="radio" class="btn-check" name="options-desktop" id="option1-desktop" autocomplete="off" checked data-target-a="contenuto-1a-desktop" data-target-b="contenuto-1b">
                            <label class="btn-outline-secondary d-flex" for="option1-desktop" style="padding-left:3px;border-radius: 20px 0 0 20px;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-utente-grigio.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>

                            <input type="radio" class="btn-check" name="options-desktop" id="option2-desktop" autocomplete="off" data-target-a="contenuto-2a-desktop" data-target-b="contenuto-2b">
                            <label class="btn-outline-secondary d-flex" for="option2-desktop" style="border-radius: 0;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-borsa-spesa.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>

                            <input type="radio" class="btn-check" name="options-desktop" id="option3-desktop" autocomplete="off" data-target-a="contenuto-3a-desktop" data-target-b="contenuto-3b">
                            <label class="btn-outline-secondary d-flex" for="option3-desktop" style="padding-right:3px;border-radius: 0 20px 20px 0; border-left: none;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-negozio.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>
                          </div>


                        <!--
                        <span class="">
                          <span class="">
                            <img src="ufficiale/filtro-risultati-attivo.svg" class="" alt=""/>
                          </span>
                          <span>
                            <img src="ufficiale/icona-borsa-spesa.svg" style="width:25px;" class="" alt=""/>
                          </span>
                          <span>
                            <img src="ufficiale/icona-negozio.svg" style="width:25px;" class="" alt=""/>
                          </span>
                        </span>
                        -->


                        <span class="text-secondary elementi-in-linea">
                          <div class="px-3 elementi-in-linea">
                            <span id="contenuto-1b" class="opzione-contenuto-b active">Annunci</span>
                            <span id="contenuto-2b" class="opzione-contenuto-b">bella</span>
                            <span id="contenuto-3b" class="opzione-contenuto-b">giornata</span>
                          </div>
                          <!--<span class="px-3">Annunci</span>-->
                          <span class="bordo-rilevanza p-2"></span>
                          <span class=""> Filtra per: <a href="" class="px-1">Rilevanza <img src="<?php echo get_template_directory_uri() . '/ufficiale/down-arrow.svg'; ?>" class="frecce" alt=""/></a></span>
                        </span>

                      </div>

                    </div>

                  </div>
                </div>


              </div>
          </div>
    </div>



    <!--mobile-->
	<div class="container spazi-riga-sidebar px-0 d-block d-sm-none">
          <div class="row">
              <div class="col-md-4 col-lg-3 sidebar-col"></div>

              <div class="col-md-8 col-lg-9 content-col">

                <div class="container">
                  <div class="row align-items-center">

                    <div class="col text-secondary noacapo float-start">
                      122 Risultati
                    </div>

                    <div class="col-auto pe-0">

                      <div class="float-end" id="nascondi_in_mobile">


                          <div class="btn-group" role="group" aria-label="Switch di opzioni">
                            <input type="radio" class="btn-check" name="options-mobile" id="option1-mobile" autocomplete="off" checked data-target-a="contenuto-1a-mobile" data-target-b="contenuto-1b">
                            <label class="btn-outline-secondary d-flex" for="option1-mobile" style="padding-left:3px;border-radius: 20px 0 0 20px;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-utente-grigio.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>

                            <input type="radio" class="btn-check" name="options-mobile" id="option2-mobile" autocomplete="off" data-target-a="contenuto-2a-mobile" data-target-b="contenuto-2b">
                            <label class="btn-outline-secondary d-flex" for="option2-mobile" style="border-radius: 0;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-borsa-spesa.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>

                            <input type="radio" class="btn-check" name="options-mobile" id="option3-mobile" autocomplete="off" data-target-a="contenuto-3a-mobile" data-target-b="contenuto-3b">
                            <label class="btn-outline-secondary d-flex" for="option3-mobile" style="padding-right:3px;border-radius: 0 20px 20px 0; border-left: none;">
                              <img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-negozio.svg'; ?>" style="" class="align-self-center img-fluid" alt=""/>
                            </label>
                          </div>


                        <!--
                        <span class="">
                          <span class="">
                            <img src="ufficiale/filtro-risultati-attivo.svg" class="" alt=""/>
                          </span>
                          <span>
                            <img src="ufficiale/icona-borsa-spesa.svg" style="width:25px;" class="" alt=""/>
                          </span>
                          <span>
                            <img src="ufficiale/icona-negozio.svg" style="width:25px;" class="" alt=""/>
                          </span>
                        </span>
                        -->

						<!--
                        <span class="text-secondary elementi-in-linea">
                          <div class="px-3 elementi-in-linea">
                            <span id="contenuto-1b" class="opzione-contenuto-b active">Annunci</span>
                            <span id="contenuto-2b" class="opzione-contenuto-b">bella</span>
                            <span id="contenuto-3b" class="opzione-contenuto-b">giornata</span>
                          </div>

                          <span class="bordo-rilevanza p-2"></span>
                          <span class=""> Filtra per: <a href="" class="px-1">Rilevanza <img src="?php echo get_template_directory_uri() . '/ufficiale/down-arrow.svg'; ?>" class="frecce" alt=""/></a></span>
                        </span>
                        -->

                      </div>

                    </div>

                    <div class="col-auto float-end">
                      <a href="#" class="btn btn-warning noacapo px-4" id="openSidebarBtn" role="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCustom" aria-controls="offcanvasCustom">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/filtri.svg'; ?>" class="filtri" alt=""/>
                      </a>

                      <div style="overflow-y: auto; margin-top:250px;" class="offcanvas offcanvas-start offcanvas-custom" tabindex="-1" id="offcanvasCustom" aria-labelledby="offcanvasCustomLabel">
                        <!--
						<div class="offcanvas-header">
                          <h5 class="offcanvas-title" id="offcanvasCustomLabel">Navigazione Principale</h5>
                          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Chiudi"></button>
                        </div>
						 -->

             <div class="mt-2 mb-5" style="padding-left:45px!important;padding-right:60px!important;">

               <div class="servizi">

                 <a href="#">Filtra per categoria <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                 <a href="#"><div class="small float-end color-arancio mt-1">Reset</div></a>

               </div>

                  <form class="pe-2">
                      <div class="form-check category-item">
                          <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria1">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-porcelli-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                          <label class="form-check-label" for="categoria1">Categoria 1</label>
                          <span class="category-count float-end">123</span>
                      </div>

                      <div class="form-check category-item">
                          <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria2">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-uccelli-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                          <label class="form-check-label" for="categoria2">Categoria 2</label>
                          <span class="category-count float-end">89</span>
                      </div>

                      <div class="form-check category-item">
                          <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria3">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-gatti-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                          <label class="form-check-label" for="categoria3">Categoria 3</label>
                          <span class="category-count float-end">56</span>
                      </div>

                      <div class="form-check category-item">
                          <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria4">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-cani-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                          <label class="form-check-label" for="categoria4">Categoria 4</label>
                          <span class="category-count float-end">34</span>
                      </div>

                      <div class="form-check category-item">
                          <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria5">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-criceti-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                          <label class="form-check-label" for="categoria5">Categoria 5</label>
                          <span class="category-count float-end">23</span>
                      </div>
                  </form>

                 <hr>

                 <div class="servizi">

                   <a href="#">Filtra per prezzo <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>

                  <div id="price-slider-mobile" class="">
                      <div id="price-values-mobile" class="servizi">
                          <span class="text-secondary">Prezzo da:</span> <span id="min-price-mobile"></span> - <span id="max-price-mobile"></span>
                      </div>
                      <div id="price-range-mobile">
                          <div class="handle-circle"></div>
                      </div>
                  </div>

                 <hr>

                 <div class="servizi">

                   <a href="#">Filtra per tags <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>


                 <div class=" mt-3">
             				<div class="gallery-shop">
             					<div class="img-box-shop">
             						<img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-nuovo.svg;' ?>" alt="Immagine 1" class="img-uniform-shop">
             					</div>
             					<div class="img-box-shop">
             						<img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-offerte.svg;' ?>" alt="Immagine 2" class="img-uniform-shop">
             					</div>
             					<div class="img-box-shop">
             						<img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti.svg;' ?>" alt="Immagine 3" class="img-uniform-shop">
             					</div>
             				</div>
             			</div>



                 <!--
                 <div class="custom-row">
                    <div class="col-auto pe-0">
                      <div class="text-start">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-nuovo.svg'; ?>" class="" alt="Immagine 1">
                      </div>
                    </div>
                    <div class="col-auto px-0">
                      <div class="text-center">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-offerte.svg'; ?>" class="" alt="Immagine 2">
                      </div>
                    </div>
                    <div class="col-auto ps-0">
                      <div class="text-end">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti.svg'; ?>" class="" alt="Immagine 3">
                      </div>
                    </div>
                  </div>
                -->

                  <div class="spazi-riga-sidebar"></div>


                    <div class=" mt-3">
              				<div class="gallery-shop">
              					<div class="img-box-shop">
              						<img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-cani-piccoli.svg;' ?>" alt="Immagine 1" class="img-uniform-shop">
              					</div>
              					<div class="img-box-shop">
              						<img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti.svg;' ?>" alt="Immagine 2" class="img-uniform-shop">
              					</div>
              					<div class="img-box-shop">
              						<img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti.svg;' ?>" alt="Immagine 3" class="img-uniform-shop">
              					</div>
              				</div>
              			</div>


                    <!--
                  <div class="custom-row">
                    <div class="col-auto pe-0">
                      <div class="text-start">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-cani-piccoli.svg'; ?>" class="" alt="Immagine 1">
                      </div>
                    </div>
                    <div class="col-auto px-0">
                      <div class="text-center">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti2.svg'; ?>" class="" alt="Immagine 2">
                      </div>
                    </div>
                    <div class="col-auto ps-0">
                      <div class="text-end">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti3.svg'; ?>" class="" alt="Immagine 3">
                      </div>
                    </div>
                  </div>
                  -->




                 <hr>


                 <div class="servizi">

                   <a href="#">Filtra per brand <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>

                 <form class="pe-2">
                     <div class="form-check category-item">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria1">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-porcelli-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                         <label class="form-check-label" for="categoria1">Categoria 1</label>
                         <span class="category-count float-end">123</span>
                     </div>

                     <div class="form-check category-item">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria2">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-uccelli-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                         <label class="form-check-label" for="categoria2">Categoria 2</label>
                         <span class="category-count float-end">89</span>
                     </div>

                     <div class="form-check category-item">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria3">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-gatti-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                         <label class="form-check-label" for="categoria3">Categoria 3</label>
                         <span class="category-count float-end">56</span>
                     </div>

                     <div class="form-check category-item">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria4">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-cani-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                         <label class="form-check-label" for="categoria4">Categoria 4</label>
                         <span class="category-count float-end">34</span>
                     </div>
                 </form>


                 <hr>


                 <div class="servizi">

                   <a href="#">Filtra per feedbacks <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>


                 <div class="row">

                   <div class="">
                     <span class="float-start">
                       <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelline-sidebar.svg'; ?>" class="" alt=""/>
                     </span>
                     <span class="small float-end color-arancio filtro-numero-stelle">
                       3 stelle
                     </span>
                   </div>

                 </div>


                 <hr>


                 <div class="servizi">

                   <a href="#">Filtra per annunci regalo <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                   <!--<div class="small float-end color-arancio">Reset</div>-->

                 </div>


                 <form>
                     <div class="form-check category-item">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria1">
                         <label class="form-check-label" for="categoria1">Annunci regalo</label>
                         <span class="category-count filtro-numero-stelle float-end">21</span>
                     </div>
                 </form>



             </div>

                      </div>
                    </div>

                  </div>
                </div>


              </div>
          </div>
	</div>



	<div class="d-block d-sm-none"><!--inizio spazio switch mobile-->
  	<div id="contenuto-1a-mobile" class="opzione-contenuto-a active"><!--inizio contenuto1 mobile-->


  <div class="container mt-3">
    <div class="row g-2">

      <div class="col-6">

        <div class="scheda-prodotto">
          <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

          <div class="container">
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
      </div>

      </div>

      <div class="col-6">

        <div class="scheda-prodotto">
          <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
          <div class="container ">
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
      </div>

      </div>

      <div class="col-6">

        <div class="scheda-prodotto">
          <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
          <div class="container">
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
      </div>

      </div>

      <div class="col-6">

        <div class="scheda-prodotto">
          <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
          <div class="container">
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
      </div>

      </div>

    </div>
    </div><!--fine container-->



    <!--mobile-->
    <div class="container d-block d-sm-none">
      <div class="row g-2">

        <div class="col-6">

          <div class="scheda-prodotto">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

            <div class="container">
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
        </div>

        </div>

        <div class="col-6">

          <div class="scheda-prodotto">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
            <div class="container ">
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
        </div>

        </div>

        <div class="col-6">

          <div class="scheda-prodotto">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
            <div class="container">
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
        </div>

        </div>

        <div class="col-6">

          <div class="scheda-prodotto">
            <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
            <div class="container">
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
        </div>

        </div>

      </div>
      </div><!--fine container-->

      </div><!--fine contenuto1 mobile-->

      <div id="contenuto-2a-mobile" class="opzione-contenuto-a"><!--inizio contenuto2-->

      	<div class="container mt-2">
    		<div class="row">
      			<div class="col-12">
      				vincenzo cont. mobile 2
      			</div>
      		</div>
      	</div>

      </div><!--fine contenuto2-->

      <div id="contenuto-3a-mobile" class="opzione-contenuto-a"><!--inizio contenuto3-->

      	<div class="container mt-2">
    		<div class="row">
      			<div class="col-12">
      				vincenzo c. cont. mobile 3
      			</div>
      		</div>
      	</div>

      </div><!--fine contenuto3-->

      </div><!--fine spazio switch mobile-->

      <!--mobile-->
      <div class="container mt-5 d-block d-sm-none">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 text-center">
                <a href="#" class="small">Carica altro &#11107;</a>
            </div>
        </div>
    </div>




	<!--desktop-->
	<div class="container spazi-riga-sidebar px-0 d-none d-md-block">
        <div class="row">
            <div class="col-md-4 col-lg-3 sidebar-col">

              <div class="servizi">

                <a href="#" class="noacapo sideshop font-shop">Filtra per categoria <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                <a href="#"><div class="small float-end color-arancio mt-1">Reset</div></a>

              </div>

                 <form>
                     <div class="form-check category-item w-100">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria1">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-porcelli-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                         <label class="form-check-label" for="categoria1">Categoria 1</label>
                         <span class="category-count float-end">123</span>
                     </div>

                     <div class="form-check category-item">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria2">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-uccelli-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                         <label class="form-check-label" for="categoria2">Categoria 2</label>
                         <span class="category-count float-end">89</span>
                     </div>

                     <div class="form-check category-item">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria3">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-gatti-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                         <label class="form-check-label" for="categoria3">Categoria 3</label>
                         <span class="category-count float-end">56</span>
                     </div>

                     <div class="form-check category-item">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria4">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-cani-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                         <label class="form-check-label" for="categoria4">Categoria 4</label>
                         <span class="category-count float-end">34</span>
                     </div>

                     <div class="form-check category-item">
                         <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria5">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-criceti-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                         <label class="form-check-label" for="categoria5">Categoria 5</label>
                         <span class="category-count float-end">23</span>
                     </div>
                 </form>

                <hr>

                <div class="servizi">

                  <a href="#" class="sideshop font-shop">Filtra per prezzo <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                  <!--<div class="small float-end color-arancio">Reset</div>-->

                </div>

                <div id="price-slider-desktop" class="">
                    <div id="price-values-desktop" class="servizi">
                        <span class="text-secondary">Prezzo da:</span> <span id="min-price-desktop"></span> - <span id="max-price-desktop"></span>
                    </div>
                    <div id="price-range-desktop">
                        <div class="handle-circle"></div>
                    </div>
                </div>

                <hr>

                <div class="servizi">

                  <a href="#" class="sideshop font-shop">Filtra per tags <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                  <!--<div class="small float-end color-arancio">Reset</div>-->

                </div>




                <!--div class="">
                  <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-auto pe-0">
                      <div class="text-start">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-nuovo.svg'; ?>" class="riduzione-tablet" alt="Immagine 1">
                      </div>
                    </div>
                    <div class="col-auto px-0">
                      <div class="text-center">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-offerte.svg'; ?>" class="riduzione-tablet" alt="Immagine 2">
                      </div>
                    </div>
                    <div class="col-auto ps-0">
                      <div class="text-end">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti.svg'; ?>" class="riduzione-tablet" alt="Immagine 3">
                      </div>
                    </div>
                  </div>

                  <div class="spazi-riga-sidebar"></div>

                  <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-auto pe-0">
                      <div class="text-start">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-cani-piccoli.svg'; ?>" class="riduzione-tablet" alt="Immagine 1">
                      </div>
                    </div>
                    <div class="col-auto px-0">
                      <div class="text-center">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti2.svg'; ?>" class="riduzione-tablet" alt="Immagine 2">
                      </div>
                    </div>
                    <div class="col-auto ps-0">
                      <div class="text-end">
                        <img src="?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti3.svg'; ?>" class="riduzione-tablet" alt="Immagine 3">
                      </div>
                    </div>
                  </div>
                </div-->



                <div class=" mt-3">
                   <div class="gallery-shop">
                     <div class="img-box-shop">
                       <img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-nuovo.svg;' ?>" alt="Immagine 1" class="img-uniform-shop">
                     </div>
                     <div class="img-box-shop">
                       <img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-offerte.svg;' ?>" alt="Immagine 2" class="img-uniform-shop">
                     </div>
                     <div class="img-box-shop">
                       <img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti.svg;' ?>" alt="Immagine 3" class="img-uniform-shop">
                     </div>
                   </div>
                 </div>


                 <div class="spazi-riga-sidebar"></div>


                   <div class=" mt-3">
                     <div class="gallery-shop">
                       <div class="img-box-shop">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-cani-piccoli.svg;' ?>" alt="Immagine 1" class="img-uniform-shop">
                       </div>
                       <div class="img-box-shop">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti.svg;' ?>" alt="Immagine 2" class="img-uniform-shop">
                       </div>
                       <div class="img-box-shop">
                         <img src="<?php echo get_template_directory_uri() . '/ufficiale/tag-per-tutti.svg;' ?>" alt="Immagine 3" class="img-uniform-shop">
                       </div>
                     </div>
                   </div>





                <hr>


                <div class="servizi">

                  <a href="#" class="sideshop font-shop">Filtra per brand <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                  <!--<div class="small float-end color-arancio">Reset</div>-->

                </div>

                <form class="w-100">
                    <div class="form-check category-item">
                        <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria1">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-porcelli-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                        <label class="form-check-label" for="categoria1">Categoria 1</label>
                        <span class="category-count float-end">123</span>
                    </div>

                    <div class="form-check category-item">
                        <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria2">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-uccelli-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                        <label class="form-check-label" for="categoria2">Categoria 2</label>
                        <span class="category-count float-end">89</span>
                    </div>

                    <div class="form-check category-item">
                        <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria3">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-gatti-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                        <label class="form-check-label" for="categoria3">Categoria 3</label>
                        <span class="category-count float-end">56</span>
                    </div>

                    <div class="form-check category-item">
                        <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria4">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/categoria-cani-semplice.svg'; ?>" class="category-icon-placeholder" alt=""/>
                        <label class="form-check-label" for="categoria4">Categoria 4</label>
                        <span class="category-count float-end">34</span>
                    </div>
                </form>


                <hr>


                <div class="servizi">

                  <a href="#" class="sideshop font-shop">Filtra per feedbacks <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                  <!--<div class="small float-end color-arancio">Reset</div>-->

                </div>


                <div class="row">

                  <div class="">
                    <span class="float-start">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/stelline-sidebar.svg'; ?>" class="" alt=""/>
                    </span>
                    <span class="small float-end color-arancio filtro-numero-stelle">
                      3 stelle
                    </span>
                  </div>

                </div>


                <hr>


                <div class="servizi">

                  <a href="#" class="sideshop font-shop">Filtra per annunci regalo <img src="<?php echo get_template_directory_uri() . '/ufficiale/up-arrow.svg'; ?>" class="frecce" alt=""/></a>
                  <!--<div class="small float-end color-arancio">Reset</div>-->

                </div>


                <form>
                    <div class="form-check category-item">
                        <input class="form-check-input me-2" style="margin-top:1px" type="checkbox" id="categoria1">
                        <label class="form-check-label" for="categoria1">Annunci regalo</label>
                        <span class="category-count filtro-numero-stelle float-end">21</span>
                    </div>
                </form>



            </div>
            <div class="col-md-8 col-lg-9 content-col">

			<div class=""><!--inizio spazio switch-->
            <div id="contenuto-1a-desktop" class="opzione-contenuto-a active"><!--inizio contenuto1-->

              <div class=""><!--inizio prodotti 1-->
          		<div class="row">

                <div class="col-md-4">

                    <div class="scheda-prodotto">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                      <div class="container">
                        <div class="row align-items-start pb-3">
                          <div class="col-md-6">
                            <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                            <div class="acapo small fortablet">$20,99</div>
                          </div>
                          <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                          </div>
                        </div>
                      </div>

                  </div>

                </div>

                <div class="col-md-4">

                    <div class="scheda-prodotto">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                      <div class="container">
                        <div class="row align-items-start pb-3">
                          <div class="col-md-6">
                            <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                            <div class="acapo small fortablet">$20,99</div>
                          </div>
                          <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                          </div>
                        </div>
                      </div>

                  </div>

                </div>

                <div class="col-md-4">

                    <div class="scheda-prodotto">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                      <div class="container">
                        <div class="row align-items-start pb-3">
                          <div class="col-md-6">
                            <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                            <div class="acapo small fortablet">$20,99</div>
                          </div>
                          <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                            <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                          </div>
                        </div>
                      </div>

                  </div>

                </div>

          		</div>
            </div><!--fine prodotti 1-->



            <div class=""><!--inizio prodotti 2-->
            <div class="row">

              <div class="col-md-4 ">

                  <div class="scheda-prodotto">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                    <div class="container">
                      <div class="row align-items-start pb-3">
                        <div class="col-md-6">
                          <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                          <div class="acapo small fortablet">$20,99</div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                        </div>
                      </div>
                    </div>

                </div>

              </div>

              <div class="col-md-4 ">

                  <div class="scheda-prodotto">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                    <div class="container">
                      <div class="row align-items-start pb-3">
                        <div class="col-md-6">
                          <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                          <div class="acapo small fortablet">$20,99</div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                        </div>
                      </div>
                    </div>

                </div>

              </div>

              <div class="col-md-4 ">

                  <div class="scheda-prodotto">
                    <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                    <div class="container">
                      <div class="row align-items-start pb-3">
                        <div class="col-md-6">
                          <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                          <div class="acapo small fortablet">$20,99</div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                          <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                        </div>
                      </div>
                    </div>

                </div>

              </div>

            </div>
          </div><!--fine prodotti 2-->



          <div class=""><!--inizio prodotti 3-->
          <div class="row">

            <div class="col-md-4 ">

                <div class="scheda-prodotto">
                  <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                  <div class="container">
                    <div class="row align-items-start pb-3">
                      <div class="col-md-6">
                        <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                        <div class="acapo small fortablet">$20,99</div>
                      </div>
                      <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                      </div>
                    </div>
                  </div>

              </div>

            </div>

            <div class="col-md-4 ">

                <div class="scheda-prodotto">
                  <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                  <div class="container">
                    <div class="row align-items-start pb-3">
                      <div class="col-md-6">
                        <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                        <div class="acapo small fortablet">$20,99</div>
                      </div>
                      <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                      </div>
                    </div>
                  </div>

              </div>

            </div>

            <div class="col-md-4 ">

                <div class="scheda-prodotto">
                  <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                  <div class="container">
                    <div class="row align-items-start pb-3">
                      <div class="col-md-6">
                        <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                        <div class="acapo small fortablet">$20,99</div>
                      </div>
                      <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                        <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                      </div>
                    </div>
                  </div>

              </div>

            </div>

          </div>
        </div><!--fine prodotti 3-->



        <div class=""><!--inizio prodotti 4-->
        <div class="row">

          <div class="col-md-4 ">

              <div class="scheda-prodotto">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline-nuovo.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                <div class="container">
                  <div class="row align-items-start pb-3">
                    <div class="col-md-6">
                      <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                      <div class="acapo small fortablet">$20,99</div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                    </div>
                  </div>
                </div>

            </div>

          </div>

          <div class="col-md-4 ">

              <div class="scheda-prodotto">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                <div class="container">
                  <div class="row align-items-start pb-3">
                    <div class="col-md-6">
                      <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                      <div class="acapo small fortablet">$20,99</div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                    </div>
                  </div>
                </div>

            </div>

          </div>

          <div class="col-md-4 ">

              <div class="scheda-prodotto">
                <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

                <div class="container">
                  <div class="row align-items-start pb-3">
                    <div class="col-md-6">
                      <div class="noacapo fortablet2 sideshop">Ciotola per cani</div>
                      <div class="acapo small fortablet">$20,99</div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end mt-cuore-pref-tablet">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/carrello-con-sfondo.svg'; ?>" class="cornetta-and-co me-2" alt="Immagine 1">
                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/cuore-con-sfondo.svg'; ?>" class="cornetta-and-co" alt="Immagine 2">
                    </div>
                  </div>
                </div>

            </div>

          </div>

        </div>
      </div><!--fine prodotti 4-->

      </div><!--fine contenuto1-->

      <div id="contenuto-2a-desktop" class="opzione-contenuto-a"><!--inizio contenuto2-->
      	vincenzo cont. 2
      </div><!--fine contenuto2-->

      <div id="contenuto-3a-desktop" class="opzione-contenuto-a"><!--inizio contenuto3-->
      	vincenzo c. cont. 3
      </div><!--fine contenuto3-->


      </div><!--fine spazio switch-->



      <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 text-center">
                <a href="#" class="small">Carica altro &#11107;</a>
            </div>
        </div>
    </div>





            </div>
        </div>
    </div>

























































<?php

//get footer.php file
get_footer();


?>
