<?php

if (!function_exists('shopic_display_comments')) {
	/**
	 * Shopic display comments
	 *
	 * @since  1.0.0
	 */
	function shopic_display_comments() {
		// If comments are open or we have at least one comment, load up the comment template.
		if (comments_open() || 0 !== intval(get_comments_number())) :
			comments_template();
		endif;
	}
}

if (!function_exists('shopic_comment')) {
	/**
	 * Shopic comment template
	 *
	 * @param array $comment the comment array.
	 * @param array $args the comment args.
	 * @param int $depth the comment depth.
	 *
	 * @since 1.0.0
	 */
	function shopic_comment($comment, $args, $depth) {
		if ('div' === $args['style']) {
			$tag       = 'div';
			$add_below = 'comment';
		} else {
			$tag       = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo esc_attr($tag) . ' '; ?><?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
		<div class="comment-body">
		<div class="comment-meta commentmetadata">
			<div class="comment-author vcard">
				<?php echo get_avatar($comment, 128); ?>
				<?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
			</div>
			<?php if ('0' === $comment->comment_approved) : ?>
				<em class="comment-awaiting-moderation"><?php esc_attr_e('Your comment is awaiting moderation.', 'shopic'); ?></em>
				<br/>
			<?php endif; ?>

			<a href="<?php echo esc_url(htmlspecialchars(get_comment_link($comment->comment_ID))); ?>"
			   class="comment-date">
				<?php echo '<time datetime="' . get_comment_date('c') . '">' . get_comment_date() . '</time>'; ?>
			</a>
		</div>
		<?php if ('div' !== $args['style']) : ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
	<?php endif; ?>
		<div class="comment-text">
			<?php comment_text(); ?>
		</div>
		<div class="reply">
			<?php
			comment_reply_link(
				array_merge(
					$args, array(
						'add_below' => $add_below,
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
					)
				)
			);
			?>
			<?php edit_comment_link(esc_html__('Edit', 'shopic'), '  ', ''); ?>
		</div>
		</div>
		<?php if ('div' !== $args['style']) : ?>
			</div>
		<?php endif; ?>
		<?php
	}
}

if (!function_exists('shopic_credit')) {
	/**
	 * Display the theme credit
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function shopic_credit() {
		?>
		<div class="site-info">
			<?php echo apply_filters('shopic_copyright_text', $content = esc_html__('Coppyright', 'shopic') . ' &copy; ' . date('Y') . ' ' . '<a class="site-url" href="' . site_url() . '">' . get_bloginfo('name') . '</a>' . esc_html__('. All Rights Reserved.', 'shopic')); ?>
		</div><!-- .site-info -->
		<?php
	}
}

if (!function_exists('shopic_social')) {
	function shopic_social() {
		$social_list = shopic_get_theme_option('social_text', []);
		if (empty($social_list)) {
			return;
		}
		?>
		<div class="shopic-social">
			<ul>
				<?php

				foreach ($social_list as $social_item) {
					?>
					<li><a href="<?php echo esc_url($social_item); ?>"></a></li>
					<?php
				}
				?>

			</ul>
		</div>
		<?php
	}
}

if (!function_exists('shopic_site_branding')) {
	/**
	 * Site branding wrapper and display
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function shopic_site_branding() {
		?>
		<div class="site-branding">
			<?php echo shopic_site_title_or_logo(); ?>
		</div>
		<?php
	}
}

if (!function_exists('shopic_site_title_or_logo')) {
	/**
	 * Display the site title or logo
	 *
	 * @param bool $echo Echo the string or return it.
	 *
	 * @return string
	 * @since 2.1.0
	 */
	function shopic_site_title_or_logo() {
		ob_start();
		the_custom_logo(); ?>
		<div class="site-branding-text">
			<?php if (is_front_page()) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
										  rel="home"><?php bloginfo('name'); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
										 rel="home"><?php bloginfo('name'); ?></a></p>
			<?php endif; ?>

			<?php
			$description = get_bloginfo('description', 'display');

			if ($description || is_customize_preview()) :
				?>
				<p class="site-description"><?php echo esc_html($description); ?></p>
			<?php endif; ?>
		</div><!-- .site-branding-text -->
		<?php
		$html = ob_get_clean();
		return $html;
	}
}

if (!function_exists('shopic_primary_navigation')) {
	/**
	 * Display Primary Navigation
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function shopic_primary_navigation() {
		?>
		<nav class="main-navigation" role="navigation"
			 aria-label="<?php esc_html_e('Primary Navigation', 'shopic'); ?>">
			<?php
			$args = apply_filters('shopic_nav_menu_args', [
				'fallback_cb'     => '__return_empty_string',
				'theme_location'  => 'primary',
				'container_class' => 'primary-navigation',
			]);
			wp_nav_menu($args);
			?>
		</nav>
		<?php
	}
}

if (!function_exists('shopic_mobile_navigation')) {
	/**
	 * Display Handheld Navigation
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function shopic_mobile_navigation() {
		?>
		<nav class="mobile-navigation" aria-label="<?php esc_html_e('Mobile Navigation', 'shopic'); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'handheld',
					'container_class' => 'handheld-navigation',
				)
			);
			?>
		</nav>
		<?php
	}
}

if (!function_exists('shopic_vertical_navigation')) {
	/**
	 * Display Vertical Navigation
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function shopic_vertical_navigation() {

		if (isset(get_nav_menu_locations()['vertical'])) {
			$string = get_term(get_nav_menu_locations()['vertical'], 'nav_menu')->name;
			?>
			<nav class="vertical-navigation" aria-label="<?php esc_html_e('Vertiacl Navigation', 'shopic'); ?>">
				<div class="vertical-navigation-header">
					<i class="shopic-icon-caret-vertiacl-menu"></i>
					<span class="vertical-navigation-title"><?php echo esc_html($string); ?></span>
				</div>
				<?php

				$args = apply_filters('shopic_nav_menu_args', [
					'fallback_cb'     => '__return_empty_string',
					'theme_location'  => 'vertical',
					'container_class' => 'vertical-menu',
				]);

				wp_nav_menu($args);
				?>
			</nav>
			<?php
		}
	}
}

if (!function_exists('shopic_homepage_header')) {
	/**
	 * Display the page header without the featured image
	 *
	 * @since 1.0.0
	 */
	function shopic_homepage_header() {
		edit_post_link(esc_html__('Edit this section', 'shopic'), '', '', '', 'button shopic-hero__button-edit');
		?>
		<header class="entry-header">
			<?php
			the_title('<h1 class="entry-title">', '</h1>');
			?>
		</header><!-- .entry-header -->
		<?php
	}
}

if (!function_exists('shopic_page_header')) {
	/**
	 * Display the page header
	 *
	 * @since 1.0.0
	 */
	function shopic_page_header() {

		if (is_front_page() || !is_page_template('default')) {
			return;
		}

		?>
		<header class="entry-header">
			<?php
			if (has_post_thumbnail()) {
				shopic_post_thumbnail('full');
			}
			the_title('<h1 class="entry-title">', '</h1>');
			?>
		</header><!-- .entry-header -->
		<?php
	}
}

if (!function_exists('shopic_page_content')) {
	/**
	 * Display the post content
	 *
	 * @since 1.0.0
	 */
	function shopic_page_content() {
		?>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'shopic'),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

if (!function_exists('shopic_post_header')) {
	/**
	 * Display the post header with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function shopic_post_header() {
		?>
		<header class="entry-header">
			<?php

			/**
			 * Functions hooked in to shopic_post_header_before action.
			 */
			do_action('shopic_post_header_before');
			?>

			<?php
			if (is_single()) {
				shopic_categories_link();
				the_title('<h2 class="alpha entry-title">', '</h2>');
				?>
				<div class="entry-meta">
					<?php
					shopic_post_meta();
					?>
				</div>
				<?php
			} else {
				?>
				<div class="entry-meta">
					<?php
					shopic_post_meta();
					?>
				</div>

				<?php
				the_title(sprintf('<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');

			}
			?>

			<?php
			do_action('shopic_post_header_after');
			?>
		</header><!-- .entry-header -->
		<?php
	}
}

if (!function_exists('shopic_post_content')) {
	/**
	 * Display the post content with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function shopic_post_content() {
		?>
		<div class="entry-content">
			<?php

			/**
			 * Functions hooked in to shopic_post_content_before action.
			 *
			 */
			do_action('shopic_post_content_before');


			if (is_search()) {
				the_excerpt();
			} else {
				the_content(
					sprintf(
					/* translators: %s: post title */
						esc_html__('Read More', 'shopic') . ' %s',
						'<span class="screen-reader-text">' . get_the_title() . '</span>'
					)
				);
			}

			/**
			 * Functions hooked in to shopic_post_content_after action.
			 *
			 */
			do_action('shopic_post_content_after');

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'shopic'),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

if (!function_exists('shopic_post_meta')) {
	/**
	 * Display the post meta
	 *
	 * @since 1.0.0
	 */
	function shopic_post_meta() {
		if ('post' !== get_post_type()) {
			return;
		}

		// Posted on.
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date('c')),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date('c')),
			esc_html(get_the_modified_date())
		);

		$posted_on = '<span class="posted-on">' . sprintf('<a href="%1$s" rel="bookmark">%2$s</a>', esc_url(get_permalink()), $time_string) . '</span>';

		// Author.
		$author = sprintf(
			'<span class="post-author"><span>%1$s<a href="%2$s" class="url fn" rel="author">%3$s</a></span></span>',
			esc_html__('By ', 'shopic'),
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			esc_html(get_the_author())
		);


		echo wp_kses(
			sprintf('%1$s %2$s', $posted_on, $author), array(
				'span' => array(
					'class' => array(),
				),
				'a'    => array(
					'href'  => array(),
					'title' => array(),
					'rel'   => array(),
				),
				'time' => array(
					'datetime' => array(),
					'class'    => array(),
				),
			)
		);
	}
}

if (!function_exists('shopic_get_allowed_html')) {
	function shopic_get_allowed_html() {
		return apply_filters(
			'shopic_allowed_html',
			array(
				'br'     => array(),
				'i'      => array(),
				'b'      => array(),
				'u'      => array(),
				'em'     => array(),
				'del'    => array(),
				'a'      => array(
					'href'  => true,
					'class' => true,
					'title' => true,
					'rel'   => true,
				),
				'strong' => array(),
				'span'   => array(
					'style' => true,
					'class' => true,
				),
			)
		);
	}
}

if (!function_exists('shopic_edit_post_link')) {
	/**
	 * Display the edit link
	 *
	 * @since 2.5.0
	 */
	function shopic_edit_post_link() {
		edit_post_link(
			sprintf(
				wp_kses(__('Edit <span class="screen-reader-text">%s</span>', 'shopic'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<div class="edit-link">',
			'</div>'
		);
	}
}

if (!function_exists('shopic_categories_link')) {
	/**
	 * Prints HTML with meta information for the current cateogries
	 */
	function shopic_categories_link() {

		// Get Categories for posts.
		$categories_list = get_the_category_list(' ');

		if ('post' === get_post_type() && $categories_list) {
			// Make sure there's more than one category before displaying.
			echo '<span class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'shopic') . '</span>' . $categories_list . '</span>';
		}
	}
}

if (!function_exists('shopic_post_taxonomy')) {
	/**
	 * Display the post taxonomies
	 *
	 * @since 2.4.0
	 */
	function shopic_post_taxonomy() {
		/* translators: used between list items, there is a space after the comma */

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list('');
		?>
		<aside class="entry-taxonomy">
			<?php if ($tags_list) : ?>
				<div class="tags-links">
					<strong><?php echo esc_html(_n('Tag:', 'Tags:', count(get_the_tags()), 'shopic')); ?></strong>
					<?php printf('%s', $tags_list); ?>
				</div>
			<?php endif;
            shopic_social_share();
			?>
		</aside>
		<?php
	}
}

if (!function_exists('shopic_paging_nav')) {
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function shopic_paging_nav() {
		global $wp_query;

		$args = array(
			'type'      => 'list',
			'next_text' => _x('<i class="shopic-icon shopic-icon-chevron-right"></i>', 'Next post', 'shopic'),
			'prev_text' => _x('<i class="shopic-icon shopic-icon-chevron-left"></i>', 'Previous post', 'shopic'),
		);

		the_posts_pagination($args);
	}
}

if (!function_exists('shopic_post_nav')) {
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function shopic_post_nav() {
		$prev_post = get_previous_post();
		$next_post = get_next_post();

		$thumb_nail_prev = '';
		$thumb_nail_next = '';

		if ($prev_post) {
			$thumb_nail_prev = get_the_post_thumbnail($prev_post->ID, array(110, 110));
		};

		if ($next_post) {
			$thumb_nail_next = get_the_post_thumbnail($next_post->ID, array(110, 110));
		};

		$args = array(
			'next_text' => $thumb_nail_next . '<span class="nav-content"><span class="reader-text">' . esc_html__('NEXT POST', 'shopic') . ' </span>%title' . '</span> ',
			'prev_text' => $thumb_nail_prev . '<span class="nav-content"><span class="reader-text">' . esc_html__('PREV POST', 'shopic') . ' </span>%title' . '</span> ',
		);

		the_post_navigation($args);


	}
}

if (!function_exists('shopic_posted_on')) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @deprecated 2.4.0
	 */
	function shopic_posted_on() {
		_deprecated_function('shopic_posted_on', '2.4.0');
	}
}

if (!function_exists('shopic_homepage_content')) {
	/**
	 * Display homepage content
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @return  void
	 * @since  1.0.0
	 */
	function shopic_homepage_content() {
		while (have_posts()) {
			the_post();

			get_template_part('content', 'homepage');

		} // end of the loop.
	}
}

if (!function_exists('shopic_social_icons')) {
	/**
	 * Display social icons
	 * If the subscribe and connect plugin is active, display the icons.
	 *
	 * @link http://wordpress.org/plugins/subscribe-and-connect/
	 * @since 1.0.0
	 */
	function shopic_social_icons() {
		if (class_exists('Subscribe_And_Connect')) {
			echo '<div class="subscribe-and-connect-connect">';
			subscribe_and_connect_connect();
			echo '</div>';
		}
	}
}

if (!function_exists('shopic_get_sidebar')) {
	/**
	 * Display shopic sidebar
	 *
	 * @uses get_sidebar()
	 * @since 1.0.0
	 */
	function shopic_get_sidebar() {
		get_sidebar();
	}
}

if (!function_exists('shopic_post_thumbnail')) {
	/**
	 * Display post thumbnail
	 *
	 * @param string $size the post thumbnail size.
	 *
	 * @uses has_post_thumbnail()
	 * @uses the_post_thumbnail
	 * @var $size thumbnail size. thumbnail|medium|large|full|$custom
	 * @since 1.5.0
	 */
	function shopic_post_thumbnail($size = 'post-thumbnail') {
		echo '<div class="post-thumbnail">';
		if (has_post_thumbnail()) {
			the_post_thumbnail($size ? $size : 'post-thumbnail');
		}
		if (!is_single()) {
			shopic_categories_link();
		}
		echo '</div>';
	}
}

if (!function_exists('shopic_primary_navigation_wrapper')) {
	/**
	 * The primary navigation wrapper
	 */
	function shopic_primary_navigation_wrapper() {
		echo '<div class="shopic-primary-navigation"><div class="col-full">';
	}
}

if (!function_exists('shopic_primary_navigation_wrapper_close')) {
	/**
	 * The primary navigation wrapper close
	 */
	function shopic_primary_navigation_wrapper_close() {
		echo '</div></div>';
	}
}

if (!function_exists('shopic_header_container')) {
	/**
	 * The header container
	 */
	function shopic_header_container() {
		echo '<div class="col-full">';
	}
}

if (!function_exists('shopic_header_container_close')) {
	/**
	 * The header container close
	 */
	function shopic_header_container_close() {
		echo '</div>';
	}
}

if (!function_exists('shopic_header_custom_link')) {
	function shopic_header_custom_link() {
		echo shopic_get_theme_option('custom-link', '');
	}

}

if (!function_exists('shopic_header_contact_info')) {
	function shopic_header_contact_info() {
		echo shopic_get_theme_option('contact-info', '');
	}

}

if (!function_exists('shopic_header_account')) {
	function shopic_header_account() {

		if (!shopic_get_theme_option('show_header_account', true)) {
			return;
		}

		if (shopic_is_woocommerce_activated()) {
			$account_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
		} else {
			$account_link = wp_login_url();
		}
		?>
		<div class="site-header-account">
			<a href="<?php echo esc_html($account_link); ?>">
				<i class="shopic-icon-user"></i>
				<span class="account-content">
                    <?php
					if (!is_user_logged_in()) {
						esc_attr_e('Sign in', 'shopic');
					} else {
						$user = wp_get_current_user();
						echo esc_html($user->display_name);
					}

					?>
                </span>
			</a>
			<div class="account-dropdown">

			</div>
		</div>
		<?php
	}

}

if (!function_exists('shopic_template_account_dropdown')) {
	function shopic_template_account_dropdown() {
		if (!shopic_get_theme_option('show_header_account', true)) {
			return;
		}
		?>
		<div class="account-wrap" style="display: none;">
			<div class="account-inner <?php if (is_user_logged_in()): echo "dashboard"; endif; ?>">
				<?php if (!is_user_logged_in()) {
					shopic_form_login();
				} else {
					shopic_account_dropdown();
				}
				?>
			</div>
		</div>
		<?php
	}
}

if (!function_exists('shopic_form_login')) {
	function shopic_form_login() {

        if (shopic_is_woocommerce_activated()) {
            $account_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
        } else {
            $account_link = wp_registration_url();
        }
		?>
		<div class="login-form-head">
			<span class="login-form-title"><?php esc_attr_e('Sign in', 'shopic') ?></span>
			<span class="pull-right">
                <a class="register-link" href="<?php echo esc_url($account_link); ?>"
				   title="<?php esc_attr_e('Register', 'shopic'); ?>"><?php esc_attr_e('Create an Account', 'shopic'); ?></a>
            </span>
		</div>
		<form class="shopic-login-form-ajax" data-toggle="validator">
			<p>
				<label><?php esc_attr_e('Username or email', 'shopic'); ?> <span class="required">*</span></label>
				<input name="username" type="text" required placeholder="<?php esc_attr_e('Username', 'shopic') ?>">
			</p>
			<p>
				<label><?php esc_attr_e('Password', 'shopic'); ?> <span class="required">*</span></label>
				<input name="password" type="password" required placeholder="<?php esc_attr_e('Password', 'shopic') ?>">
			</p>
			<button type="submit" data-button-action
					class="btn btn-primary btn-block w-100 mt-1"><?php esc_html_e('Login', 'shopic') ?></button>
			<input type="hidden" name="action" value="shopic_login">
			<?php wp_nonce_field('ajax-shopic-login-nonce', 'security-login'); ?>
		</form>
		<div class="login-form-bottom">
			<a href="<?php echo wp_lostpassword_url(get_permalink()); ?>" class="lostpass-link"
			   title="<?php esc_attr_e('Lost your password?', 'shopic'); ?>"><?php esc_attr_e('Lost your password?', 'shopic'); ?></a>
		</div>
		<?php
	}
}

if (!function_exists('shopic_account_dropdown')) {
	function shopic_account_dropdown() { ?>
		<?php if (has_nav_menu('my-account')) : ?>
			<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e('Dashboard', 'shopic'); ?>">
				<?php
				wp_nav_menu(array(
					'theme_location' => 'my-account',
					'menu_class'     => 'account-links-menu',
					'depth'          => 1,
				));
				?>
			</nav><!-- .social-navigation -->
		<?php else: ?>
			<ul class="account-dashboard">

				<?php if (shopic_is_woocommerce_activated()): ?>
					<li>
						<a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
						   title="<?php esc_html_e('Dashboard', 'shopic'); ?>"><?php esc_html_e('Dashboard', 'shopic'); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>"
						   title="<?php esc_html_e('Orders', 'shopic'); ?>"><?php esc_html_e('Orders', 'shopic'); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url(wc_get_account_endpoint_url('downloads')); ?>"
						   title="<?php esc_html_e('Downloads', 'shopic'); ?>"><?php esc_html_e('Downloads', 'shopic'); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>"
						   title="<?php esc_html_e('Edit Address', 'shopic'); ?>"><?php esc_html_e('Edit Address', 'shopic'); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>"
						   title="<?php esc_html_e('Account Details', 'shopic'); ?>"><?php esc_html_e('Account Details', 'shopic'); ?></a>
					</li>
				<?php else: ?>
					<li>
						<a href="<?php echo esc_url(get_dashboard_url(get_current_user_id())); ?>"
						   title="<?php esc_html_e('Dashboard', 'shopic'); ?>"><?php esc_html_e('Dashboard', 'shopic'); ?></a>
					</li>
				<?php endif; ?>
				<li>
					<a title="<?php esc_html_e('Log out', 'shopic'); ?>" class="tips"
					   href="<?php echo esc_url(wp_logout_url(home_url())); ?>"><?php esc_html_e('Log Out', 'shopic'); ?></a>
				</li>
			</ul>
		<?php endif;

	}
}

if (!function_exists('shopic_header_search_popup')) {
	function shopic_header_search_popup() {
		?>
		<div class="site-search-popup">
			<div class="site-search-popup-wrap">
				<a href="#" class="site-search-popup-close"><i class="shopic-icon-times-circle"></i></a>
				<?php
				if (shopic_is_woocommerce_activated()) {
					shopic_product_search();
				} else {
					?>
					<div class="site-search">
						<?php get_search_form(); ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
}

if (!function_exists('shopic_header_search_button')) {
	function shopic_header_search_button() {
		if (!shopic_get_theme_option('show_header_search', true)) {
			return;
		}
		add_action('wp_footer', 'shopic_header_search_popup', 1);
		?>
		<div class="site-header-search">
			<a href="#" class="button-search-popup"><i class="shopic-icon-search"></i></a>
		</div>
		<?php
	}
}


if (!function_exists('shopic_header_sticky')) {
	function shopic_header_sticky() {
		get_template_part('template-parts/header', 'sticky');
	}
}

if (!function_exists('shopic_mobile_nav')) {
	function shopic_mobile_nav() {
		if (isset(get_nav_menu_locations()['handheld'])) {
			?>
			<div class="shopic-mobile-nav">
				<a href="#" class="mobile-nav-close"><i class="shopic-icon-times"></i></a>
				<?php
				shopic_language_switcher_mobile();
				shopic_mobile_navigation();
				shopic_social();
				?>
			</div>
			<div class="shopic-overlay"></div>
			<?php
		}
	}
}

if (!function_exists('shopic_mobile_nav_button')) {
	function shopic_mobile_nav_button() {
		if (isset(get_nav_menu_locations()['handheld'])) {
			?>
			<a href="#" class="menu-mobile-nav-button">
				<span
					class="toggle-text screen-reader-text"><?php echo esc_attr(apply_filters('shopic_menu_toggle_text', esc_html__('Menu', 'shopic'))); ?></span>
				<i class="shopic-icon-bars"></i>
			</a>
			<?php
		}
	}
}


if (!function_exists('shopic_language_switcher')) {
	function shopic_language_switcher() {
		$languages = apply_filters('wpml_active_languages', []);
		if (!shopic_is_wpml_activated() || count($languages) <= 0) {
			return;
		}
		?>
		<div class="shopic-language-switcher">
			<ul class="menu">
				<li class="item">
					<span>
						<img width="18" height="12"
							 src="<?php echo esc_url($languages[ICL_LANGUAGE_CODE]['country_flag_url']) ?>"
							 alt="<?php esc_attr($languages[ICL_LANGUAGE_CODE]['default_locale']) ?>">
						<?php
						echo esc_html($languages[ICL_LANGUAGE_CODE]['translated_name']);
						?>
					</span>
					<ul class="sub-item">
						<?php
						foreach ($languages as $key => $language) {
							if (ICL_LANGUAGE_CODE === $key) {
								continue;
							}
							?>
							<li>
								<a href="<?php echo esc_url($language['url']) ?>">
									<img width="18" height="12"
										 src="<?php echo esc_url($language['country_flag_url']) ?>"
										 alt="<?php esc_attr($language['default_locale']) ?>">
									<?php echo esc_html($language['translated_name']); ?>
								</a>
							</li>
							<?php
						}
						?>
					</ul>
				</li>
			</ul>
		</div>
		<?php
	}
}

if (!function_exists('shopic_language_switcher_mobile')) {
	function shopic_language_switcher_mobile() {
		$languages = apply_filters('wpml_active_languages', []);
		if (!shopic_is_wpml_activated() || count($languages) <= 0) {
			return;
		}
		?>
		<div class="shopic-language-switcher-mobile">
            <span>
                <img width="18" height="12"
					 src="<?php echo esc_url($languages[ICL_LANGUAGE_CODE]['country_flag_url']) ?>"
					 alt="<?php esc_attr($languages[ICL_LANGUAGE_CODE]['default_locale']) ?>">
            </span>
			<?php
			foreach ($languages as $key => $language) {
				if (ICL_LANGUAGE_CODE === $key) {
					continue;
				}
				?>
				<a href="<?php echo esc_url($language['url']) ?>">
					<img width="18" height="12" src="<?php echo esc_url($language['country_flag_url']) ?>"
						 alt="<?php esc_attr($language['default_locale']) ?>">
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}
}

if (!function_exists('shopic_footer_default')) {
	function shopic_footer_default() {
		get_template_part('template-parts/copyright');
	}
}


if (!function_exists('shopic_pingback_header')) {
	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 */
	function shopic_pingback_header() {
		if (is_singular() && pings_open()) {
			echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
		}
	}
}

if (!function_exists('shopic_social_share')) {
	function shopic_social_share() {
		get_template_part('template-parts/socials');
	}
}

if (!function_exists('modify_read_more_link')) {
	function modify_read_more_link() {
		return '<p class="more-link-wrap"><a class="more-link" href="' . get_permalink() . '">' . esc_html__('Read More', 'shopic') . '<span>+</span></a></p>';
	}
}

add_filter('the_content_more_link', 'modify_read_more_link');

function darken_color($rgb, $darker = 1.1) {

	$hash = (strpos($rgb, '#') !== false) ? '#' : '';
	$rgb  = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
	if (strlen($rgb) != 6) return $hash . '000000';
	$darker = ($darker > 1) ? $darker : 1;

	list($R16, $G16, $B16) = str_split($rgb, 2);

	$R = sprintf("%02X", floor(hexdec($R16) / $darker));
	$G = sprintf("%02X", floor(hexdec($G16) / $darker));
	$B = sprintf("%02X", floor(hexdec($B16) / $darker));

	return $hash . $R . $G . $B;
}


if (!function_exists('shopic_update_comment_fields')) {
	function shopic_update_comment_fields($fields) {

		$commenter = wp_get_current_commenter();
		$req       = get_option('require_name_email');
		$aria_req  = $req ? "aria-required='true'" : '';

		$fields['author']
			= '<p class="comment-form-author">
			<input id="author" name="author" type="text" placeholder="' . esc_attr__("Your Name *", "shopic") . '" value="' . esc_attr($commenter['comment_author']) .
			  '" size="30" ' . $aria_req . ' />
		</p>';

		$fields['email']
			= '<p class="comment-form-email">
			<input id="email" name="email" type="email" placeholder="' . esc_attr__("Email Address *", "shopic") . '" value="' . esc_attr($commenter['comment_author_email']) .
			  '" size="30" ' . $aria_req . ' />
		</p>';

		$fields['url']
			= '<p class="comment-form-url">
			<input id="url" name="url" type="url"  placeholder="' . esc_attr__("Your Website", "shopic") . '" value="' . esc_attr($commenter['comment_author_url']) .
			  '" size="30" />
			</p>';

		return $fields;
	}
}

add_filter('comment_form_default_fields', 'shopic_update_comment_fields');


