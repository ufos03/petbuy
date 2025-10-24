<?php

/*
*
* Template Name: Accedi
* Description: pagina accedi
*
*/

//get header.php file
//get_header();

//utile per richiamare header-diverso.php
my_custom_get_header();


?>



<!--desktop-->
<div class="container-fluid d-flex flex-column h-100 d-none d-md-block">
        <div class="row flex-grow-1 sfondo-sx p-4">
            <div class="col-5 col-left ">
                <div class="content-wrapper">
                    <form class="ilform" style="">

                    <div class="fw-bold fs-5" style="margin-bottom:40px;">Accedi al tuo account</div>

        				   	<div class="" style="margin-bottom:20px;">
          						<input type="email" class="form-control edit-form-header px-3 border" style="background-color:#f3f9fa;" id="" placeholder="Inserisci email">
          					</div>

                    <!--<div class="input-group mb-3">
    									<input type="password" class="form-control edit-form-header px-3" style="background-color:#f3f9fa;" id="passwordInput" placeholder="Inserisci password">
    									<span class="input-group-text" id="togglePassword" style="background-color:#faf9f8;border:0;"><img src="ufficiale/occhio.svg" style="cursor:pointer;" alt="occhio"/></span>
    								</div>-->

                    <div class="input-group mb-3">
                        <input type="password" class="form-control edit-form-header px-3 border border-end-0" style="background-color:#f3f9fa;" id="passwordInputDesktop" placeholder="Inserisci password">
                        <span class="input-group-text border border-start-0" id="togglePasswordDesktop" style="background-color:#f3f9fa;border:0; cursor:pointer;">
                            <img id="eyeIconDesktop" src="<?php echo get_template_directory_uri() . '/ufficiale/occhio.svg'; ?>" alt="Mostra password"/>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between small">

          						<div class="col-md-6">

                        <div class="float-start">
                          <div class="form-check form-switch category-item">
                            <input class="form-check-input edit-switch-ricorda border-1 border" style="margin-top:1px;" type="checkbox" id="flexSwitchCheckDefault">
                            <label class="form-check-label ms-2 noacapo fortablet2" for="flexSwitchCheckDefault">Ricordati di me</label>
                          </div>
                        </div>

          						</div>

          						<div class="col-md-6">
          							<div class="float-end">
          								<a href="#" style="color:#f87537!important;" class="fortablet2">Forgot password?</a>
          							</div>
          						</div>

          					</div>

                     <a href="#" style="width:100%;margin:20px 0px 0px 0px;" class="btn btn-warning noacapo" role="button">Accedi ora</a>

                      <div class="hr-with-text">
                          <span>Or</span>
                      </div>


                      <a href="#" style="width:100%;background-color:#f3f9fa;" class="btn mb-3 login-con-social" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/google-login.svg'; ?>" class="me-2 py-1" style="margin-left: -20px;" alt=""/>Login con Google</a>

                      <a href="#" style="width:100%;background-color:#f3f9fa;margin-bottom:33px;" class="btn mb-4 login-con-social" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/facebook-login.svg'; ?>" class="me-2 py-1" alt=""/>Login con Facebook</a>


                      <div class="small text-secondary text-center mb-5">Non hai un account? <span class="text-decoration-underline" style="color:#F87537;">Crealo adesso!<span></div>

                  </form>
                </div>
            </div>

            <div class="col-7 rounded col-right" style="background-color:#f87537;padding-top: 80px;">
                <div class="position-relative">
      					  <div style="" class="impronta1-arancio-accedi">
      						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-sx-prodotto1.svg'; ?>" class="" alt="Immagine2 sx prodotto1">
      					  </div>

      					  <div style="" class="impronta2-arancio-accedi">
      						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-sx-prodotto1.svg'; ?>" class="" alt="Immagine1 sx prodotto1">
      					  </div>
                </div>

              <div class="" style="height:100vh;">

                <div class="" style="padding-left:18.5%;padding-right:18.5%;">
                  <div class="fw-bold small text-white mb-auto">Lavora Con Noi</div>

                  <h1 class="text-white fs-2">Sei un negozio o un privato?</h1>

                  <p class="text-white small" style="margin-bottom:60px;">Sfrutta il nostro sito per raggiungere i tuoi obiettivi. Grazie a PetBuy hai la possibilità di vendere i tuoi prodotti sul nostro sito web.</p>
                </div>

                <!--contenitore cane e impronte-->
                <div style="padding-left:52px!important;padding-right:27px!important;">

                  <img src="<?php echo get_template_directory_uri() . '/ufficiale/cane-accedi.webp'; ?>" class="mx-auto d-block img-fluid" alt="cane">

                  <div class="position-relative">
                    <!--gruppo1-->
        					  <div style="" class="impronta-piccola-in-basso-a-sx-accedi">
        						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-piccola-in-basso-a-sx-accedi.svg'; ?>" class="" alt="Immagine2 sx prodotto1">
        					  </div>

        					  <div style="" class="impronta-grande-in-basso-a-sx-accedi">
        						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-grande-in-basso-a-sx-accedi.svg'; ?>" class="" alt="Immagine1 sx prodotto1">
        					  </div>

                    <!--gruppo2-->
                    <div style="" class="impronta-piccola-in-alto-a-sx-accedi">
        						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-piccola-in-alto-a-sx-accedi.svg'; ?>" class="" alt="Immagine2 sx prodotto1">
        					  </div>

        					  <div style="" class="impronta-grande-in-alto-a-sx-accedi">
        						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-grande-in-alto-a-sx-accedi.svg'; ?>" class="" alt="Immagine1 sx prodotto1">
        					  </div>

                    <!--gruppo3-->
                    <div style="" class="impronta-piccola-vicino-cane-dx-accedi">
        						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-piccola-vicino-cane-a-dx-accedi.svg'; ?>" class="" alt="Immagine2 sx prodotto1">
        					  </div>

        					  <div style="" class="impronta-grande-vicino-cane-dx-accedi">
        						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-grande-vicino-cane-a-dx-accedi.svg'; ?>" class="" alt="Immagine1 sx prodotto1">
        					  </div>

                    <!--gruppo4-->
                    <div style="" class="impronta-piccola-dx-accedi">
        						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-piccola-in-alto-a-dx-accedi.svg'; ?>" class="" alt="Immagine2 sx prodotto1">
        					  </div>

        					  <div style="" class="impronta-grande-dx-accedi">
        						  <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta-grande-in-alto-a-dx-accedi.svg'; ?>" class="" alt="Immagine1 sx prodotto1">
        					  </div>
                  </div>

                </div><!--contenitore cane e impronte-->

                <a href="#" style="background-color:#ffffff!important;color:#F87537!important;margin-top:40px;width:60%" class="btn btn-light noacapo larg-mass-tablet" role="button">Registra i tuoi prodotti</a>

              </div><!--colore interno-->
            </div>
        </div>
    </div>



    <!--mobile-->
    <div class="d-block d-sm-none container sfondo-sx ">

      <div class="position-relative">
        <div style="position: absolute; top: 5px; right: 20px; z-index: 1;">
          <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta1-sx-prodotto1.svg'; ?>" class="" alt="Immagine2 sx prodotto1">
        </div>

        <div style="position: absolute; top: 34px; right: 26px; z-index: 1;">
          <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronta2-sx-prodotto1.svg'; ?>" class="" alt="Immagine1 sx prodotto1">
        </div>
      </div>

      <div class="row mt-5">

        <div class="col-sm-12">



          <form class="" style="">

          <div class="fw-bold fs-5" style="margin-bottom:40px;">Accedi al tuo account</div>

          <div class="" style="margin-bottom:20px;">
            <input type="email" class="form-control edit-form-header px-3 border" style="background-color:#f3f9fa;" id="" placeholder="Inserisci email">
          </div>

          <!--<div class="input-group mb-3">
            <input type="password" class="form-control edit-form-header px-3" style="background-color:#f3f9fa;" id="passwordInput" placeholder="Inserisci password">
            <span class="input-group-text" id="togglePassword" style="background-color:#faf9f8;border:0;"><img src="ufficiale/occhio.svg" style="cursor:pointer;" alt="occhio"/></span>
          </div>-->

          <div class="input-group mb-3">
              <input type="password" class="form-control edit-form-header px-3 border border-end-0" style="background-color:#f3f9fa;" id="passwordInputMobile" placeholder="Inserisci password">
              <span class="input-group-text border border-start-0" id="togglePasswordMobile" style="background-color:#f3f9fa;border:0; cursor:pointer;">
                  <img id="eyeIconMobile" src="<?php echo get_template_directory_uri() . '/ufficiale/occhio.svg'; ?>" alt="Mostra password"/>
              </span>
          </div>

          <div class="d-flex justify-content-between small">

            <div class="col-md-6">

              <div class="float-start">
                <div class="form-check form-switch category-item">
                  <input class="form-check-input edit-switch-ricorda border-1 border" style="margin-top:1px;" type="checkbox" id="flexSwitchCheckDefault">
                  <label class="form-check-label ms-2 noacapo" for="flexSwitchCheckDefault">Ricordati di me</label>
                </div>
              </div>

            </div>

            <div class="col-md-6">
              <div class="float-end">
                <a href="#" style="color:#f87537!important;">Forgot password?</a>
              </div>
            </div>

          </div>

           <a href="#" style="width:100%;margin:20px 0px 0px 0px;" class="btn btn-warning noacapo" role="button">Accedi ora</a>

            <div class="hr-with-text">
                <span>Or</span>
            </div>


            <a href="#" style="width:100%;background-color:#f3f9fa;" class="btn mb-3 login-con-social" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/google-login.svg'; ?>" class="me-2 py-1" style="margin-left: -20px;" alt=""/>Login con Google</a>

            <a href="#" style="width:100%;background-color:#f3f9fa;margin-bottom:33px;" class="btn mb-4 login-con-social" role="button"><img src="<?php echo get_template_directory_uri() . '/ufficiale/facebook-login.svg'; ?>" class="me-2 py-1" alt=""/>Login con Facebook</a>


            <div class="small text-secondary text-center mb-5">Non hai un account? <span class="text-decoration-underline" style="color:#F87537;">Crealo adesso!<span></div>

        </form>



        </div>

      </div>

    </div>







<?php

//get footer.php file
//get_footer();
my_custom_get_footer()


?>
