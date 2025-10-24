<?php
get_header(); ?>

	<div id="primary" class="content">
		<main id="main" class="site-main" role="main">
			<div class="error-404 not-found">
				<div class="page-content text-center">
					<header class="page-header">
                        <div class="error-img404">
                            <img src="<?php echo get_theme_file_uri('assets/images/404/404.png') ?>" alt="<?php echo esc_attr__('404 Page', 'shopic') ?>">
                        </div>
						<h1 class="page-title"><?php esc_html_e( 'Oops! that link is broken.', 'shopic' ); ?></h1>
					</header><!-- .page-header -->

                    <div class="error-text">
                        <span><?php esc_html_e("Page does not exist or some other error occured. Go to our", 'shopic') ?></span>
                        <a href="<?php echo esc_url(home_url('/')); ?>"
                           class="return-home c-secodary"><?php esc_html_e('Home page', 'shopic'); ?></a>
                    </div>
                    <?php the_widget('WP_Widget_Search', 'title='); ?>

				</div><!-- .page-content -->
			</div><!-- .error-404 -->
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
