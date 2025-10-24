<?php
/**
 * Il file principale del tema.
 *
 * Questo è il file più generico in una gerarchia di temi di WordPress e uno
 * dei due file richiesti per ogni tema (l'altro è style.css).
 * Viene utilizzato per visualizzare una pagina quando nessun altro file più specifico
 * corrisponde alla query. Ad esempio, visualizza una raccolta di articoli.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Il_Tuo_Tema
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header class="page-header">
					<h1 class="page-title"><?php single_post_title(); ?></h1>
				</header><?php
			endif;

			/* Inizia il loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * In base al tuo tema, includi il template part appropriato
				 * per il tipo di contenuto (post, pagina, allegato, ecc.).
				 * Se desideri che vengano sempre visualizzati i post, utilizza semplicemente:
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><?php
get_sidebar();
get_footer();
