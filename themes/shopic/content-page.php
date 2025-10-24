<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to shopic_page action
	 *
	 * @see shopic_page_header          - 10
	 * @see shopic_page_content         - 20
	 *
	 */
	do_action( 'shopic_page' );
	?>
</article><!-- #post-## -->
