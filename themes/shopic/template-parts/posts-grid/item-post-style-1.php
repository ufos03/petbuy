<div class="column-item">
	<div class="post-inner">

		<?php if (has_post_thumbnail() && '' !== get_the_post_thumbnail()) : ?>
		<div class="post-thumbnail">
		<?php else:?>
			<div class="post-thumbnail no-image">
		<?php endif;?>
			<?php the_post_thumbnail('shopic-post-grid-2'); ?>
			<?php shopic_categories_link();?>
		</div>


		<div class="content-wrap">
			<div class="entry-header">
				<div class="entry-meta">
					<?php shopic_post_meta(); ?>
				</div>
				<?php the_title(sprintf('<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');  ?>
			</div>

			<div class="entry-content">
				<p><?php echo  wp_trim_words(get_the_excerpt(), 20); ?></p>
				<a class="more-link" href="<?php the_permalink() ?>"><?php echo esc_html__('Read More', 'shopic') ?><span>+</span></a>
			</div>
		</div>
	</div>
</div>
