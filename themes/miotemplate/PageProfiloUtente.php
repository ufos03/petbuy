<?php

/*
*
* Template Name: Profilo utente
* Description: pagina profilo utente
*
*/

//get header.php file
get_header();


?>





<div class="">

	<div class="" style="margin-top:30px;">
		<div class="">
			<div class="container-fluid">

				<div class="row sfondo-profilo-mobile">
					<!--dekstop-->
					<div class="col-md-auto pt-5 pe-0 h-100 z-1 d-none d-md-block" style="background-color:#faf9f8;width:19%;color:#807a7a;padding-left:30px; font-size: 15px;">

						<div class="list-group" id="menu-profilo-utente">
								<a href="#" class="menu-item-profilo-utente active mb-4 py-1" data-target="profilo" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-profilo.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-profilo.svg'; ?>">
										<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-profilo.svg'; ?>" class="me-2" alt="Profilo">
										Profilo
								</a>
								<a href="#" class="menu-item-profilo-utente mb-4 py-1" data-target="ordini" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-ordini.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-ordini.svg'; ?>">
										<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-ordini.svg'; ?>" class="me-2" alt="Ordini">
										Ordini
								</a>
								<a href="#" class="menu-item-profilo-utente py-1" data-target="annunci" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-annunci.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-annunci.svg'; ?>">
										<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-annunci.svg'; ?>" class="me-2" alt="Annunci">
										Annunci
								</a>
						</div>


						<hr class="me-4 my-4">

						<div class="mb-5">
								<a href="#" class="" style="color: #495057;"><img src="<?php echo get_template_directory_uri() . '/ufficiale/esci-da-account.svg'; ?>"> Esci dal tuo account</a>
							</div>

					</div>


					<!--mobile-->
					<div class="list-group d-block d-sm-none mt-3 pe-0" id="menu-profilo-utente">
						<div class="d-flex overflow-auto container" style="gap: 0.3rem;">

							<a href="#" style="background-color:#F3F9FA;" class="btn flex-shrink-0 border-0 d-flex align-items-center justify-content-center menu-item-profilo-utente active" data-target="profilo" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-profilo.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-profilo.svg'; ?>">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-profilo.svg'; ?>" class="me-2" alt="Profilo">
									Profilo
							</a>

							<a href="#" style="background-color:#F3F9FA;" class="btn btn-info flex-shrink-0 border-0 d-flex align-items-center justify-content-center menu-item-profilo-utente" data-target="ordini" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-ordini.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-ordini.svg'; ?>">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-ordini.svg'; ?>" class="me-2" alt="Ordini">
									Ordini
							</a>
							<a href="#" style="background-color:#F3F9FA;" class="btn btn-info flex-shrink-0 border-0 d-flex align-items-center justify-content-center menu-item-profilo-utente" data-target="annunci" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-annunci.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-annunci.svg'; ?>">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-annunci.svg'; ?>" class="me-2" alt="Annunci">
									Annunci
							</a>

						</div>
					</div>




					<div id="content-profilo-utente" class="col pr-cont-prof" style="">

						<div id="profilo-content" class="content-section pt-0 active">



							<!--desktop-->
							<div class="position-absolute d-none d-md-block">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-gatto-1-profilo.svg'; ?>" alt="gatto storto" class="z-0" style="margin-top: -28px; margin-left: -300px;">
							</div>

							<!--mobile-->
							<div class="position-absolute d-block d-sm-none">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-gatto-1-profilo-mobile.svg'; ?>" alt="gatto storto" class="z-0" style="top: 0px; left: 50px;">
							</div>

							<form class="scostamento-top">

									<div class="spazi-form" style="">

											<div class="row mb-4">
									<div class="col-12 col-md-6 m-input">
										<div class="input-with-inner-asterisk">
											<input type="text" style="background-color:#faf9f8!important;height:60px!important;" class="form-control edit-form-header ps-4" id="" placeholder="Nome">
											<span class="inner-asterisk">*</span>
										</div>
									</div>
									<div class="col-12 col-md-6">
										<div class="input-with-inner-asterisk">
											<input type="text" style="background-color:#faf9f8!important;height:60px!important;" class="form-control edit-form-header ps-4" id="" placeholder="Cognome">
											<span class="inner-asterisk">*</span>
										</div>
									</div>
								</div>

										<div class="mb-4 input-with-inner-asterisk">
							<input type="text" style="background-color:#faf9f8!important;height:60px!important;" class="form-control edit-form-header ps-4" id="" placeholder="Nome visualizzato">
									<span class="inner-asterisk">*</span>
						</div>

										<hr class="" style="margin-top: 33px!important;margin-bottom: 33px!important;">

										<div class="mb-4 input-with-inner-asterisk">
							<input type="email" style="background-color:#faf9f8!important;height:60px!important;" class="form-control edit-form-header ps-4" id="" placeholder="Indirizzo email">
									<span class="inner-asterisk">*</span>
						</div>

										<hr class="" style="margin-top: 30px!important;margin-bottom: 30px!important;">

										<div class="mb-4">Modifica la tua password</div>

										<div class="mb-4 input-with-inner-asterisk">
							<input type="password" style="background-color:#faf9f8!important;height:60px!important;" class="form-control edit-form-header ps-4" id="" placeholder="Password attuale">
									<span class="inner-asterisk">*</span>
						</div>

										<div class="mb-4 input-with-inner-asterisk">
							<input type="password" style="background-color:#faf9f8!important;height:60px!important;" class="form-control edit-form-header ps-4" id="" placeholder="Nuova password">
									<span class="inner-asterisk">*</span>
						</div>

										<div class="mb-5 input-with-inner-asterisk">
							<input type="password" style="background-color:#faf9f8!important;height:60px!important;" class="form-control edit-form-header ps-4" id="" placeholder="Ripeti nuova password">
									<span class="inner-asterisk">*</span>
						</div>

										<a href="#" style="" class="btn btn-warning noacapo larg-mass" role="button">Aggiorna password</a>

										<hr class="" style="margin-top: 30px;margin-bottom: 30px;">


										<div class="mb-5">

											<div class="row gx-5">
												<div class="col-md-6 m-input" style="">
													<p class="mb-4">Indirizzo di fatturazione</p>
													<div class="rounded position-relative" style="background-color: #faf9f8!important; height: 350px!important;">
														<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-tartaruga-1.svg'; ?>" class="mx-auto d-block img-fluid" alt="tartaruga">
														<p class="text-center" style="margin-top: -60px;">Non ancora impostato<p>
														<div class="icona-modifica">
															<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-modifica.svg'; ?>" class="" alt="icona modifica"></a>
														</div>
													</div>
												</div>

												<div class="col-md-6" style="">
													<p class="mb-4">Indirizzo di spedizione</p>
													<div class="rounded position-relative" style="background-color: #faf9f8!important; height: 350px!important;">
														<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-tartaruga-1.svg'; ?>" class="mx-auto d-block img-fluid" alt="tartaruga">
														<p class="text-center" style="margin-top: -60px;">Non ancora impostato<p>
														<div class="icona-modifica">
															<a href="#"><img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-modifica.svg'; ?>" class="" alt="icona modifica"></a>
														</div>
													</div>
												</div>
											</div>

										</div>

								</div>

							</form>

						</div>

						<div id="ordini-content" class="content-section">

							<div class="spazi-form" style="">

										<div class="mb-5">

											<div class="row gx-5">
												<div class="col-md-6" style="">
													<!--mobile-->
													<p class="my-4 d-block d-sm-none">Ordini effettuati</p>

													<!--desktop-->
													<p class="mb-4 d-none d-md-block">Ordini effettuati</p>

													<div class="rounded position-relative pb-2" style="background-color: #faf9f8!important; display: flex; justify-content: center; align-items: center; flex-direction: column;">
													    <div class="position-relative">
													        <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-profilo-ordini.svg'; ?>" class="mx-auto d-block position-absolute larg-mass-tablet profilo-ordini" style="top:20px;left:-10px;/* Remove or adjust left/top if centering with flexbox */" alt="impronte">

													        <img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-maiale-1.svg'; ?>" class="mx-auto d-block img-fluid" alt="maiale">

													        <p class="text-center" style="">Nessun ordine effettuato<p>

													    </div>
															<!--desktop-->
															<a href="#" style="width:60%;" class="btn btn-warning noacapo d-block mx-auto larg-mass-tablet mb-3 d-none d-md-block" role="button">Vai allo shop</a>

															<!--mobile-->
															<a href="#" style="width:80%;" class="btn btn-warning noacapo d-block mx-auto larg-mass-tablet mb-3 d-block d-sm-none" role="button">Vai allo shop</a>
													</div>
												</div>

												<div class="col-md-6" style="">

												</div>
											</div>

										</div>

							</div>

						</div>


						<div id="annunci-content" class="content-section">

							<div class="spazi-form" style="">

										<div class="mb-5">

											<div class="row gx-5">
												<div class="col-md-6" style="">
													<!--mobile-->
													<p class="my-4 d-block d-sm-none">Annunci</p>

													<!--desktop-->
													<p class="mb-4 d-none d-md-block">Annunci</p>
													<div class="rounded position-relative pb-2" style="background-color: #faf9f8!important;">

														<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-serpente-1.svg'; ?>" class="mx-auto d-block img-fluid" alt="serpente">

														<p class="text-center" style="margin-top: -60px;">Nessun annuncio presente<p>
														<a href="#" style="width:60%;" class="btn btn-warning noacapo d-block mx-auto larg-mass-tablet" role="button">Creane uno</a>
													</div>
												</div>

												<div class="col-md-6" style="">

												</div>
											</div>

										</div>

							</div>

						</div>

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
