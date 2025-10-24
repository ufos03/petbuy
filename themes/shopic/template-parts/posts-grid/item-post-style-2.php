<div class="column-item">
	<div class="post-inner">
		<div class="post-thumbnail">
			<?php the_post_thumbnail('shopic-post-grid-2'); ?>
		</div>

		<?php
		$categories_list = get_the_category_list(',');

		if ('post' === get_post_type() && $categories_list) {
			// Make sure there's more than one category before displaying.
			echo '<span class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'shopic') . '</span>' . $categories_list . '</span>';
		}
		?>

		<?php the_title(sprintf('<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');  ?>

		</div>
</div>
