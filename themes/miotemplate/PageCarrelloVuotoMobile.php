<?php

/*
*
* Template Name: Carrello Vuoto Mobile
* Description: pagina carrello vuoto mobile
*
*/

//get header.php file
get_header();


?>



<!--mobile-->
<div class="d-block d-sm-none">

	<div class="modal-dialog mb-5">




			<div class="modal-content px-1" style="padding-top: 15px;">
					<div class="modal-header">
							<div class="position-relative">
								<div style="position: absolute; top: 0px; right: -330px; z-index: 1;">
									<img src="<?php echo get_template_directory_uri() . '/ufficiale/tre-impronte-pagina-carrello-vuoto-mobile.svg'; ?>" class="" alt="3 impronte">
								</div>
							</div>
					</div>
					<div class=""></div>
					<div class="modal-body">


						<div class="mt-5">
							<img src="<?php echo get_template_directory_uri() . '/ufficiale/pollo-arrabbiato.svg'; ?>" class="mx-auto d-block img-fluid" alt="img pollo arrabbiato"/>
							<div class="mt-2 text-center fs-4">Opss... il tuo carrello è vuoto</div>
						</div>


					</div>
					<div class="modal-footer">
							<a href="#" style="width:100%;" class="btn btn-warning noacapo" role="button">Vai allo shop</a>
					</div>
			</div>

				<div class="position-relative">
					<div style="position: absolute; bottom: -35px; left: 20px; z-index: 1;">
						<img src="<?php echo get_template_directory_uri() . '/ufficiale/due-impronte-pagina-carrello-vuoto-mobile.svg'; ?>" class="" alt="2 impronte">
					</div>
				</div>

	</div>

</div>







<?php

//get footer.php file
get_footer();


?>
