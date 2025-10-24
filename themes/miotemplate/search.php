<?php

/*
*
* Template Name: Risultati ricerca
* Description: pagina risultati ricerca
*
*/

//get header.php file
get_header();


?>




<div class="container">

	<?php if ( have_posts() ) : ?>

		<div class="row">
			<div class="col-md-12 mt-5">
				<h1>
					<?php
					/* translators: %s: termine di ricerca */
					printf( esc_html__( 'Risultati della ricerca per: %s', 'il_mio_tema' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</div>
		</div><?php
		/* Avvia il Loop di WordPress. */
		while ( have_posts() ) :
			the_post();

			/**
			 * Recupera la parte del template per visualizzare il contenuto.
			 * A seconda del formato del post, questo sarà:
			 * - 'template-parts/content-single.php' per i post singoli.
			 * - 'template-parts/content-'. get_post_format() . '.php' per i formati di post specifici.
			 * - 'template-parts/content.php' per impostazione predefinita.
			 */
			get_template_part( 'template-parts/content', 'search' );

		endwhile;

		/**
		 * Funzione per la paginazione numerica.
		 */
		the_posts_navigation();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

</div>



<?php

//get footer.php file
get_footer();


?>
