<?php

/*
*
* Template Name: Profilo venditore
* Description: pagina profilo venditore
*
*/

//get header.php file
get_header();


?>





<div class="">

	<div class="" style="margin-top:30px;">
		<div class="">
			<div class="container-fluid">

				<div class="row">
					<!--dekstop-->
					<div class="col-md-auto pt-5 pe-0 h-100 z-1 d-none d-md-block larghezza-auto-tablet" style="background-color:#faf9f8;width:19%;color:#807a7a;padding-left:30px; font-size: 15px;">

						<div class="list-group" id="menu-profilo-utente">
								<a href="#" class="menu-item-profilo-utente active mb-4 py-1 noacapo" data-target="negozio" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-profilo.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-profilo.svg'; ?>">
										<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-profilo.svg'; ?>" class="me-2" alt="Profilo">
										Il tuo negozio
								</a>
								<a href="#" class="menu-item-profilo-utente mb-4 py-1" data-target="ordini" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-ordini.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-ordini.svg'; ?>">
										<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-ordini.svg'; ?>" class="me-2" alt="Ordini">
										Ordini
								</a>
								<a href="#" class="menu-item-profilo-utente mb-4 py-1" data-target="statistiche" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-annunci.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-annunci.svg'; ?>">
										<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-annunci.svg'; ?>" class="me-2" alt="Annunci">
										Statistiche
								</a>
								<a href="#" class="menu-item-profilo-utente mb-4 py-1 noacapo" data-target="aggiungi" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-plus.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-plus.svg'; ?>">
										<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-plus.svg'; ?>" class="me-2" alt="Plus">
										Aggiungi nuovi prodotti
								</a>

								<a href="#" class="mb-4" style="display: flex;align-items: center;">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/diamante.svg'; ?>" class="me-2" alt="Diamante" style="width: 24px;height: auto;margin-right: 0.75rem;">
									<div class="gradient-text-from-image">Abbonamenti</div>
								</a>

								<a href="#" class="" style="">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/passa-a-versione-pro.svg'; ?>" class="me-2 img-fluid" alt="abbonamento pro" style="">
								</a>
						</div>


						<hr class="me-4 my-4">

						<div class="mb-5">
								<a href="#" class="noacapo" style="color: #495057;"><img src="<?php echo get_template_directory_uri() . '/ufficiale/esci-da-account.svg'; ?>"> Esci dal tuo account</a>
							</div>

					</div>


					<!--mobile-->
					<div class="list-group d-block d-sm-none mt-3 pe-0" id="menu-profilo-utente">
						<div class="d-flex overflow-auto container" style="gap: 0.3rem;">

							<a href="#" style="background-color:#F3F9FA;" class="btn flex-shrink-0 border-0 d-flex align-items-center justify-content-center menu-item-profilo-utente active" data-target="negozio" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-profilo.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-profilo.svg'; ?>">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-profilo.svg'; ?>" class="me-2" alt="negozio">
									Negozio
							</a>

							<a href="#" style="background-color:#F3F9FA;" class="btn btn-info flex-shrink-0 border-0 d-flex align-items-center justify-content-center menu-item-profilo-utente" data-target="ordini" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-ordini.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-ordini.svg'; ?>">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-ordini.svg'; ?>" class="me-2" alt="ordini">
									Ordini
							</a>
							<a href="#" style="background-color:#F3F9FA;" class="btn btn-info flex-shrink-0 border-0 d-flex align-items-center justify-content-center menu-item-profilo-utente" data-target="statistiche" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-annunci.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-annunci.svg'; ?>">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-annunci.svg'; ?>" class="me-2" alt="statistiche">
									Statistiche
							</a>
							<a href="#" style="background-color:#F3F9FA;" class="btn btn-info flex-shrink-0 border-0 d-flex align-items-center justify-content-center menu-item-profilo-utente" data-target="aggiungi" data-icon-active="<?php echo get_template_directory_uri() . '/ufficiale/icona-arancio-plus.svg'; ?>" data-icon-inactive="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-plus.svg'; ?>">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-grigia-plus.svg'; ?>" class="" alt="aggiungi">
									Aggiungi
							</a>

						</div>
					</div>




					<div id="content-profilo-utente" class="col pr-cont-prof" style="">

						<!--desktop-->
						<div class="position-absolute d-none d-md-block">
							<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-gatto-1-profilo.svg'; ?>" alt="gatto storto" class="z-0" style="margin-top: 180px; margin-left: -300px;">
						</div>



						<div id="negozio-content" class="content-section pt-0 active">


							<!--mobile-->
							<div class="position-absolute d-block d-sm-none">
								<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-gatto-1-profilo-mobile.svg'; ?>" alt="gatto storto" class="z-0" style="top: 0px; left: 50px;">
							</div>


							<div class="scostamento-top"></div>

							<div class="pdn-negozio-venditore" style="">


							  <section class="rounded px-4 py-5 cane-profilo-venditore" style="border: 1px solid #f87537;">
						        <div class="container">
						          <div class="row">
						            <div class="col-md-9">
									        <div class="primary fw-bold small">Vendere Sul Nostro Marketplace</div>
						              <h1 class="">Questo è il tuo negozio</h1>

						              <div class="">


						                  <div class="small" style="text-align: justify;">Sfrutta il nostro sito per raggiunger i tuoi obiettivi. Grazie a PetBuy hai la possibilità di vendere i tuoi prodotti sul nostro sito web.</div>

															<hr>

															<div style="display: flex; align-items: flex-end;">
																<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-logo-1.svg'; ?>" class="me-3" style="width:167px;height:auto;" alt="logo">

																<a href="#" class="text-decoration-underline linktopbar fortablet2">il tuo logo</a>
															</div>


						                </div>
						              </div>

						            </div>
						            <div class="col-md-3">

						            </div>
						          </div>
						      </section>




									<!--desktop-->
									<div class="mt-5 d-none d-md-block"><!--inizio prodotti 1-->
		          		<div class="row">

		                <div class="col-4" style="">



											<div class="scheda-prodotto">
	                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-profilo-venditore.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

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

		                <div class="col-4" style="">


											<div class="scheda-prodotto">
	                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-profilo-venditore.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

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

		                <div class="col-4" style="">


											<div class="scheda-prodotto">
	                      <img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-profilo-venditore.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

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


								<!--desktop-->
								<div class="d-none d-md-block"><!--inizio prodotti 2-->
								<div class="row">

									<div class="col-4" style="">



										<div class="scheda-prodotto">
											<img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-profilo-venditore.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

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

									<div class="col-4" style="">


										<div class="scheda-prodotto">
											<img src="<?php echo get_template_directory_uri() . '/ufficiale/prodotto-profilo-venditore.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

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

									<div class="col-4" style="">

										<a href="#">
											<div class="position-relative">
												<img src="<?php echo get_template_directory_uri() . '/ufficiale/nuovo-prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
												<p class="text-secondary noacapo fortablet2" style="position: absolute; top: 80%;left: 50%;transform: translate(-50%, -50%);">
													Nuovo Prodotto
												</p>
											</div>
										</a>

									</div>

								</div>
							</div><!--fine prodotti 2-->




							<!--mobile-->
							<div class="container senza-spazi mt-5 d-block d-sm-none"><!--inizio container-->
			    		<div class="row g-2 g-md-4">

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
			              <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

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
			              <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

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
			              <img src="<?php echo get_template_directory_uri() . '/ufficiale/osso-stelline.svg'; ?>" class="mx-auto d-block img-thumbnail"/>

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

									<a href="#">
										<div class="position-relative">
											<img src="<?php echo get_template_directory_uri() . '/ufficiale/nuovo-prodotto.svg'; ?>" class="mx-auto d-block img-thumbnail"/>
											<p class="text-secondary noacapo" style="position: absolute; top: 80%;left: 50%;transform: translate(-50%, -50%);">
												Nuovo Prodotto
											</p>
										</div>
									</a>

			    			</div>

			    		</div>
			        </div><!--fine container-->










						    </div>




						</div><!--id negozio-content-->

						<div id="ordini-content" class="content-section">

							pagina ordini

						</div>


						<div id="statistiche-content" class="content-section">

							per dokan

							<!--
							<div class="ps-4 pe-5">

								<div class="rounded mb-5 px-5 py-4" style="border: 1px solid #E0E0E0;background-color:#FAF9F8;">

									<div class="">
									  <table class="table">
										    <thead>
										      <tr>
										        <th scope="col" class="cella1">Il tuo negozio</th>
										        <th scope="col" class="cella1">Views</th>
										        <th scope="col" class="cella1">Clicks</th>
										        <th scope="col" class="cella1">Ordini</th>
										        <th scope="col" class="cella1">Carrello</th>
										        <th scope="col" class="cella1">Carrello</th>
										        <th scope="col" class="cella1">Carrello</th>
										      </tr>
										    </thead>
										    <tbody>
										      <tr>
										        <td class="cella1bis"></td>
										        <td class="cella1bis">1k</td>
										        <td class="cella1bis">23</td>
										        <td class="cella1bis">5</td>
										        <td class="cella1bis">7</td>
										        <td class="cella1bis">9</td>
										        <td class="cella1bis">9</td>
										      </tr>
										    </tbody>
										  </table>
										</div>


								</div>


								<div class="rounded mb-5 sfondo-input-venditore px-1 py-2" style="border: 1px solid #E0E0E0;">

									<div class="accordion" id="productAccordion">
									  <div class="accordion-item">
									    <h2 class="accordion-header" id="headingOne">
									      <button class="accordion-button collapsed px-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="background-color: transparent !important;">

									        <div class="container-fluid">
									          <div class="row">
									            <div class="col-6 d-flex align-items-center justify-content-start">
									              <div class="d-flex align-items-center">
									                <img src="?php echo get_template_directory_uri() . '/ufficiale/img-statistiche-prodotto1.svg'; ?>" class="img-fluid me-3" alt="img prodotto1">
									                <span>Qui ci sarà il titolo del prodotto</span>
									              </div>
									            </div>
									            <div class="col-6 d-flex align-items-center justify-content-end">
									              <div class="d-flex align-items-center">
									                <img src="?php echo get_template_directory_uri() . '/ufficiale/post-seo.svg'; ?>" class="img-fluid me-3" alt="seo1">
									                <img src="?php echo get_template_directory_uri() . '/ufficiale/upload-prodotto1.svg'; ?>" class="img-fluid me-3" alt="upload prodotto1">
									                <img src="?php echo get_template_directory_uri() . '/ufficiale/freccia-chiudi.svg'; ?>" class="img-fluid accordion-arrow" alt="chiudi">
									              </div>
									            </div>
									          </div>
									        </div>
									      </button>
									    </h2>
									    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#productAccordion">
									      <div class="accordion-body px-2 pb-5">


													<div class="">
													  <table class="table tabianca">
														    <thead>
														      <tr>
														        <th scope="col" class="cella2">Statistiche post</th>
														        <th scope="col" class="cella2">Views</th>
														        <th scope="col" class="cella2">Clicks</th>
														        <th scope="col" class="cella2">Ordini</th>
														        <th scope="col" class="cella2">Carrello</th>
														        <th scope="col" class="cella2">Carrello</th>
														        <th scope="col" class="cella2">Carrello</th>
														      </tr>
														    </thead>
														    <tbody>
														      <tr>
														        <td class="cella2bis"></td>
														        <td class="cella2bis">1k</td>
														        <td class="cella2bis">23</td>
														        <td class="cella2bis">5</td>
														        <td class="cella2bis">7</td>
														        <td class="cella2bis">9</td>
														        <td class="cella2bis">9</td>
														      </tr>
														    </tbody>
														  </table>
														</div>



														<div class=" mt-5">
												        <div class="card p-4 border-0">
												            <div class="d-flex justify-content-between align-items-center mb-4">
												                <div class="text-secondary">Performance dei tuoi post</div>
												                <div class="d-flex" style="gap: 1rem;">
												                  <select class="form-select w-auto me-2 border-0 custom-select-arrow" style="background-color:#faf9f8;">
												                    <option selected>Ultimi 30 giorni</option>
												                    <option>Ultimi 6 mesi</option>
												                    <option>Ultimo anno</option>
												                  </select>
												                  <select class="form-select border-0 custom-select-arrow" style="background-color:#faf9f8;width:80px;">
												                    <option selected>Click</option>
												                    <option>Click2</option>
												                    <option>Click3</option>
												                  </select>
												                </div>
												            </div>
												            <div class="chart-container">
												                <canvas id="myLineChart"></canvas>
												            </div>
												        </div>
												    </div>



									      </div>
									    </div>
									  </div>
									</div>


								</div>






							</div>
							-->

						</div>

						<div id="aggiungi-content" class="content-section">

							<div class="pt-4"></div>

							<div class="pdn-negozio-venditore" style="">

							<div class="d-flex align-items-center justify-content-start">
								<div class="fs-2 fw-bold">Descrizione</div> <img src="<?php echo get_template_directory_uri() . '/ufficiale/serpente-descizione-nuovi-prodotti.svg'; ?>" class="" style="" alt="">
							</div>

							<!--desktop-->
							<div class="position-relative d-none d-md-block">
								<div style="position: absolute; top: -60px; right: -140px;">
				            <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-alto-nuovi-prodotti.svg'; ?>" class="" alt="Immagine Alto destra 1">
				        </div>
							</div>


							<form>
								<div class="rounded sfondo-input-venditore mb-5 padd-form-venditore" style="border: 1px solid #E0E0E0;">
							    <div class="row mb-5">
							        <div class="col-12">
							            <label for="productTitle" class="form-label fs-4">Nome prodotto</label>
							            <input type="text" class="form-control" id="productTitle" placeholder="Inserisci il titolo del prodotto" style="height:60px;">
							        </div>
							    </div>

							    <div class="row">
							        <div class="col-6 text-start">
							            <label for="productDescription" class="form-label fs-4">Descrizione</label>
							        </div>

											<!--desktop-->
											<div class="col-6 text-end d-none d-md-block">
												<div class="d-flexd-flex justify-content-end noacapo ">
													<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-upload.svg'; ?>" class="" style="" alt="">
													<label for="file-upload" class="file-upload-label small fortablet2" style="color:#f87537!important;">
													  Carica un file .txt
													</label>
													<input id="file-upload" type="file" style="display:none;" />
												</div>
											</div>

											<div class="col-12">
												<textarea class="form-control" id="productDescription" rows="5"></textarea>
											</div>

											<!--mobile-->
											<div class="col-6 text-start d-block d-sm-none noacapo">
												<div class="d-flexd-flex justify-content-end">
													<img src="<?php echo get_template_directory_uri() . '/ufficiale/icona-upload.svg'; ?>" class="" style="" alt="">
													<label for="file-upload" class="file-upload-label small" style="color:#f87537!important;">
													  Carica un file .txt
													</label>
													<input id="file-upload" type="file" style="display:none;" />
												</div>
											</div>
							    </div>
								</div>

							    <div class="row mb-5">
							        <div class="col-12">
												<div class="d-flex align-items-center justify-content-start">
													<div class="fs-2 fw-bold me-2">Immagini del prodotto</div> <img src="<?php echo get_template_directory_uri() . '/ufficiale/info-img-prodotto-vendit.svg'; ?>" class="" style="" alt="">
												</div>
							        </div>
							    </div>

								<!--desktop-->
								<div class="rounded sfondo-input-venditore mb-5 d-none d-md-block" style="border: 1px solid #E0E0E0;padding: 45px 35px">
									<div class="row g-2 align-items-stretch">
								    <div class="col-3">
								        <div class="ratio ratio-1x1 text-center">
													<label for="carica-img">
								            <img src="<?php echo get_template_directory_uri() . '/ufficiale/clicca-per-caricare.svg'; ?>" class="w-100 h-100 object-fit-contain" alt="">
													</label>

														<input id="carica-img" type="file" style="display:none;" />
								        </div>
								    </div>
								    <div class="col-3">
								        <div class="ratio ratio-1x1 text-center position-relative">
								            <img src="<?php echo get_template_directory_uri() . '/ufficiale/img1-prodotto-vendit.svg'; ?>" class="w-100 h-100 object-fit-contain" alt="">
														<!--tablet-->
														<div class="buttons-container d-flex flex-column justify-content-center align-items-center nascondi-su-tablet">
												      <button type="button" class="btn mb-2 small" style="background-color:#FBFFFF;width:40%;">Modifica</button>
												      <button type="button" class="btn small" style="background-color:#FBFFFF;width:40%;">Rimuovi</button>
												    </div>

														<!--desktop-->
														<div class="buttons-container d-flex flex-column justify-content-center align-items-center nascondi-su-desktop">
												      <button type="button" class="btn mb-2 small fortablet" style="background-color:#FBFFFF;">Modifica</button>
												      <button type="button" class="btn small fortablet" style="background-color:#FBFFFF;">Rimuovi</button>
												    </div>
								        </div>
								    </div>
										<div class="col-3">
										  <div class="ratio ratio-1x1 text-center position-relative">
										    <img src="<?php echo get_template_directory_uri() . '/ufficiale/img2-prodotto-vendit.svg'; ?>" class="w-100 h-100 object-fit-contain" alt="">

												<!--tablet-->
												<div class="buttons-container d-flex flex-column justify-content-center align-items-center nascondi-su-tablet">
													<button type="button" class="btn mb-2 small" style="background-color:#FBFFFF;width:40%;">Modifica</button>
													<button type="button" class="btn small" style="background-color:#FBFFFF;width:40%;">Rimuovi</button>
												</div>

												<!--desktop-->
												<div class="buttons-container d-flex flex-column justify-content-center align-items-center nascondi-su-desktop">
													<button type="button" class="btn mb-2 small fortablet" style="background-color:#FBFFFF;">Modifica</button>
													<button type="button" class="btn small fortablet" style="background-color:#FBFFFF;">Rimuovi</button>
												</div>
										  </div>
										</div>
								    <div class="col-3 d-flex flex-column justify-content-between">
								        <div class="ratio text-center" style="height: calc(50% - 0.5rem);">
								            <img src="<?php echo get_template_directory_uri() . '/ufficiale/img3-prodotto-vendit.svg'; ?>" class="w-100 h-100 object-fit-contain" alt="">
								        </div>
								        <div class="ratio text-center" style="height: calc(50% - 0.5rem);">
								            <img src="<?php echo get_template_directory_uri() . '/ufficiale/img4-prodotto-vendit.svg'; ?>" class="w-100 h-100 object-fit-contain" alt="">
								        </div>
								    </div>
									</div>
								</div>


								<!--mobile-->
								<div class="rounded sfondo-input-venditore mb-5 d-block d-sm-none" style="border: 1px solid #E0E0E0;padding: 45px 35px">
									<div class="row g-4 align-items-stretch">
										<div class="col-12 col-lg-3">
											<div class="ratio ratio-1x1 text-center">
												<label for="carica-img">
													<img src="<?php echo get_template_directory_uri() . '/ufficiale/clicca-per-caricare.svg'; ?>" class="w-100 h-100 object-fit-contain" alt="">
												</label>
												<input id="carica-img" type="file" style="display:none;" />
											</div>
										</div>
										<div class="col-12 col-lg-3">
											<div class="ratio ratio-1x1 text-center position-relative">
												<img src="<?php echo get_template_directory_uri() . '/ufficiale/img1-prodotto-vendit.svg'; ?>" class="w-100 h-100 object-fit-contain" alt="">
												<div class="buttons-container d-flex flex-column justify-content-center align-items-center">
													<button type="button" class="btn mb-2 small" style="background-color:#FBFFFF;width:40%;">Modifica</button>
													<button type="button" class="btn small" style="background-color:#FBFFFF;width:40%;">Rimuovi</button>
												</div>
											</div>
										</div>
										<div class="col-12 col-lg-3">
											<div class="ratio ratio-1x1 text-center position-relative">
												<img src="<?php echo get_template_directory_uri() . '/ufficiale/img2-prodotto-vendit.svg'; ?>" class="w-100 h-100 object-fit-contain" alt="">
												<div class="buttons-container d-flex flex-column justify-content-center align-items-center">
													<button type="button" class="btn mb-2 small" style="background-color:#FBFFFF;width:40%;">Modifica</button>
													<button type="button" class="btn small" style="background-color:#FBFFFF;width:40%;">Rimuovi</button>
												</div>
											</div>
										</div>
										<div class="col-12 col-lg-3 d-flex flex-column justify-content-between">
											<div class=" text-center" style="height: calc(50% - 0.5rem);">
												<img src="<?php echo get_template_directory_uri() . '/ufficiale/img3-prodotto-vendit.svg'; ?>" class="w-100 px-1 object-fit-contain mb-4" alt="">
											</div>
											<div class=" text-center" style="height: calc(50% - 0.5rem);">
												<img src="<?php echo get_template_directory_uri() . '/ufficiale/img4-prodotto-vendit.svg'; ?>" class="w-100 px-1 object-fit-contain" alt="">
											</div>
										</div>
									</div>
								</div>


							    <div class="row">
							        <div class="col-12">
												<div class="d-flex align-items-center justify-content-start">
													<div class="fs-2 fw-bold">Categorie</div> <img src="<?php echo get_template_directory_uri() . '/ufficiale/tartaruga-categorie-nuovi-prodotti.svg'; ?>" class="" style="" alt="">
												</div>
							        </div>
							    </div>

								<div class="rounded sfondo-input-venditore mb-5 padd2-form-venditore padd-form-venditore" style="border: 1px solid #E0E0E0;">
							    <div class="row mb-3">
							        <div class="col-12">
							            <label for="option1" class="form-label fs-4">Prodotto categoria</label>
							        </div>
							        <div class="col-12">
							            <select id="option1" class="form-select" style="height:60px;">
							                <option selected>Cibo & Cani</option>
							                <option value="1">Opzione 1</option>
							                <option value="2">Opzione 2</option>
							            </select>
							        </div>
							    </div>

									<div class="row">
							        <div class="col-12">
							            <label for="option1" class="form-label fs-4">Prodotto categoria</label>
							        </div>
							        <div class="col-12">
							            <select id="option1" class="form-select" style="height:60px;">
							                <option selected>Cibo & Cani</option>
							                <option value="1">Opzione 1</option>
							                <option value="2">Opzione 2</option>
							            </select>
							        </div>
							    </div>
								</div>

								<!--desktop-->
								<div class="position-relative d-none d-md-block">
									<div style="position: absolute; bottom: -150px; left: -150px; z-index: -1;">
											<img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-sx-nuovi-prodotti.svg'; ?>" class="" alt="Immagine Alto destra 1">
									</div>
								</div>

									<div class="row">
							        <div class="col-12">
												<div class="d-flex align-items-center justify-content-start">
													<div class="fs-2 fw-bold noacapo">Spedizione e Consegna</div> <img src="<?php echo get_template_directory_uri() . '/ufficiale/maiale-spedizioni-consegna-nuovi-prodotti.svg'; ?>" class="" style="" alt="">
												</div>
							        </div>
							    </div>

									<div class="rounded sfondo-input-venditore mb-5 padd2-form-venditore padd-form-venditore" style="border: 1px solid #E0E0E0;">
										<div class="row mb-3">
										    <div class="col-12">
										        <label for="option1" class="form-label fs-4">Peso del prodotto</label>
										    </div>
										    <div class="col-12">
										        <div class="position-relative">
										            <select id="option1" class="form-select" style="padding-right: 3rem;height:60px;">
										                <option selected>12.00</option>
										                <option value="1">Opzione 1</option>
										                <option value="2">Opzione 2</option>
										            </select>
										            <span class="" style="position: absolute;top: 50%;right: 2rem;transform: translateY(-50%);pointer-events: none;color: #495057;font-size: 1rem;z-index: 2;">Kg</span>
										        </div>
										    </div>
										</div>

								    <div class="row mb-3">
								        <div class="col-12">
														<!--desktop-->
								            <label class="form-label fs-4 d-none d-md-block">Grandezza pacco (opzionale)</label>
														<!--mobile-->
								            <label class="form-label fs-4 d-block d-sm-none">Grandezza pacco</label>
								        </div>
								    </div>

										<div class="row">
										    <div class="col-md-4">
										        <label for="price1" class="form-label">Lungh.</label>
										        <div class="input-group" style="display: flex;flex-wrap: nowrap;flex-grow: 1;">
										            <input type="number" class="form-control border-end-0" style="height:60px;" id="price1" step="0.01">
										            <span class="input-group-text" style="background-color:#ffffff!important; white-space: nowrap;">cm</span>
										        </div>
										    </div>
										    <div class="col-md-4">
										        <label for="price1" class="form-label">Lungh.</label>
										        <div class="input-group" style="display: flex;flex-wrap: nowrap;flex-grow: 1;">
										            <input type="number" class="form-control border-end-0" style="height:60px;" id="price1" step="0.01">
										            <span class="input-group-text" style="background-color:#ffffff!important; white-space: nowrap;">cm</span>
										        </div>
										    </div>
										    <div class="col-md-4">
										        <label for="price1" class="form-label">Lungh.</label>
										        <div class="input-group" style="display: flex;flex-wrap: nowrap;flex-grow: 1;">
										            <input type="number" class="form-control border-end-0" style="height:60px;" id="price1" step="0.01">
										            <span class="input-group-text" style="background-color:#ffffff!important; white-space: nowrap;">cm</span>
										        </div>
										    </div>
										</div>
									</div>

									<!--desktop-->
									<div class="position-relative d-none d-md-block">
										<div style="position: absolute; bottom: -220px; right: -140px; z-index: -1;">
						            <img src="<?php echo get_template_directory_uri() . '/ufficiale/impronte-basso-nuovi-prodotti.svg'; ?>" class="" alt="Immagine Alto destra 1">
						        </div>
									</div>


									<div class="row">
							        <div class="col-12">
												<div class="d-flex align-items-center justify-content-start">
													<div class="fs-2 fw-bold">Prezzo</div> <img src="<?php echo get_template_directory_uri() . '/ufficiale/cavia-prezzo-nuovi-prodotti.svg'; ?>" class="" style="" alt="">
												</div>
							        </div>
							    </div>

									<div class="rounded sfondo-input-venditore mb-5 padd2-form-venditore padd-form-venditore" style="border: 1px solid #E0E0E0;">
										<div class="row mb-3">
								        <div class="col-12">
								            <label class="form-label fs-4">Peso del prodotto</label>
								        </div>
								    </div>

										<div class="row mb-3">
											<div class="input-group">
											  <span class="input-group-text" style="background-color:#ffffff;">
													<span class="px-3 py-2 rounded" style="background-color:#f3f5f9;">€</span>
												</span>
											  <input type="number" class="form-control border-start-0" style="height:60px;" aria-label="Amount (to the nearest euro)" step="0.01">
											</div>
										</div>
									</div>



									<!--desktop-->
									<div class="row mb-3 d-none d-md-flex justify-content-between align-items-center">
									    <div class="col-6">
									        <button type="submit" class="btn btn-light bg-light border fw-normal text-secondary sopra-salva fortablet2">Salva come bozza</button>
									    </div>
									    <div class="col-6 text-end">
									        <button type="submit" class="btn btn-warning fw-normal fortablet2">Pubblica</button>
									    </div>
									</div>

									<!--mobile-->
									<div class="row mb-3 d-block d-sm-none">
										<div class="col-12 text-center d-flex flex-column-reverse">
											<div class="mt-2">
												<button type="submit" class="btn btn-light bg-light border fw-normal text-secondary sopra-salva w-100">Salva come bozza</button>
											</div>
											<div>
												<button type="submit" class="btn btn-warning fw-normal w-100">Pubblica</button>
											</div>
										</div>
									</div>
							</form>


							<section class="rounded p-5 cane-profilo-venditore d-none" style="border: 1px solid #f87537;">
									<div class="container">
										<div class="row">
											<div class="col-md-12">

												<h1 class="">Nome prodotto</h1>

												<div class="">


														<div class="small" style="text-align: justify;">Sfrutta il nostro sito per raggiunger i tuoi obiettivi. Grazie a PetBuy hai la possibilità di vendere i tuoi prodotti sul nostro sito web.</div>

														<hr>

														<div style="display: flex; align-items: flex-end;">
															<img src="<?php echo get_template_directory_uri() . '/ufficiale/petbuy-logo-1.svg'; ?>" class="me-3" style="width:167px;height:auto;" alt="logo">

															<a href="#" class="text-decoration-underline linktopbar fortablet2">il tuo logo</a>
														</div>


													</div>
												</div>

											</div>
										</div>
								</section>


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
