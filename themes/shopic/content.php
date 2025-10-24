<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Functions hooked in to shopic_loop_post action.
	 *
	 * @see shopic_post_thumbnail       - 10
	 * @see shopic_post_header          - 15
	 * @see shopic_post_content         - 30
	 */
	do_action( 'shopic_loop_post' );
	?>

</article><!-- #post-## -->

