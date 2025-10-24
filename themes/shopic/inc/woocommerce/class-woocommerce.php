<?php
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Shopic_WooCommerce')) :

	/**
	 * The Shopic WooCommerce Integration class
	 */
	class Shopic_WooCommerce {

		public $list_shortcodes;

		private $prefix = 'remove';

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->list_shortcodes = array(
				'recent_products',
				'sale_products',
				'best_selling_products',
				'top_rated_products',
				'featured_products',
				'related_products',
				'product_category',
				'products',
			);
			$this->init_shortcodes();

			add_action('after_setup_theme', array($this, 'setup'));
			add_filter('body_class', array($this, 'woocommerce_body_class'));
			add_action('widgets_init', array($this, 'widgets_init'));
			add_filter('shopic_theme_sidebar', array($this, 'set_sidebar'), 20);
			add_action('wp_enqueue_scripts', array($this, 'woocommerce_scripts'), 20);
			add_filter('woocommerce_enqueue_styles', '__return_empty_array');
			add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
			add_filter('woocommerce_product_thumbnails_columns', array($this, 'thumbnail_columns'));

			if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.3', '<')) {
				add_filter('loop_shop_per_page', array($this, 'products_per_page'));
			}

			// Remove Shop Title
			add_filter('woocommerce_show_page_title', '__return_false');

			add_filter('wc_get_template_part', array($this, 'change_template_part'), 10, 3);

			add_action('wp_ajax_shopic_ajax_search_products', array($this, 'ajax_search_products'));
			add_action('wp_ajax_nopriv_shopic_ajax_search_products', array($this, 'ajax_search_products'));
			add_action('pre_get_product_search_form', array($this, 'ajax_search_result'));
			add_action('wp_footer', array($this, 'ajax_live_search_template'));

			add_filter('shopic_register_nav_menus', [$this, 'add_location_menu']);
			add_filter('wp_nav_menu_items', [$this, 'add_extra_item_to_nav_menu'], 10, 2);

			add_filter('woocommerce_single_product_image_gallery_classes', function ($wrapper_classes) {
				$wrapper_classes[] = 'woocommerce-product-gallery-' . shopic_get_theme_option('single_product_gallery_layout', 'horizontal');

				return $wrapper_classes;
			});

			add_action('woocommerce_grouped_product_list_before_label', array(
				$this,
				'grouped_product_column_image'
			), 10, 1);

			// Elementor Admin
			add_action('admin_action_elementor', array($this, 'register_elementor_wc_hook'), 1);
			add_filter('woocommerce_cross_sells_columns', array($this, 'woocommerce_cross_sells_columns'));
		}

		public function woocommerce_cross_sells_columns() {
			return wc_get_default_products_per_row();
		}

		public function register_elementor_wc_hook() {
			wc()->frontend_includes();
			require_once get_theme_file_path('inc/woocommerce/woocommerce-template-hooks.php');
			require_once get_theme_file_path('inc/woocommerce/template-hooks.php');
		}

		public function add_extra_item_to_nav_menu($items, $args) {
			if ($args->theme_location == 'my-account') {
				$items .= '<li><a href="' . esc_url(wp_logout_url(home_url())) . '">' . esc_html__("Logout", 'shopic') . '</a></li>';
			}

			return $items;
		}

		public function add_location_menu($locations) {
			$locations['my-account'] = esc_html__('My Account', 'shopic');

			return $locations;
		}

		/**
		 * Sets up theme defaults and registers support for various WooCommerce features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 *
		 * @return void
		 * @since 2.4.0
		 */
		public function setup() {
			add_theme_support(
				'woocommerce', apply_filters(
					'shopic_woocommerce_args', array(
						'product_grid' => array(
							'default_columns' => 3,
							'default_rows'    => 4,
							'min_columns'     => 1,
							'max_columns'     => 6,
							'min_rows'        => 1,
						),
					)
				)
			);


			/**
			 * Add 'shopic_woocommerce_setup' action.
			 *
			 * @since  2.4.0
			 */
			do_action('shopic_woocommerce_setup');
		}

		private function init_shortcodes() {
			foreach ($this->list_shortcodes as $shortcode) {
				add_filter('shortcode_atts_' . $shortcode, array($this, 'set_shortcode_attributes'), 10, 3);
				add_action('woocommerce_shortcode_before_' . $shortcode . '_loop', array(
					$this,
					'style_loop_start'
				));
				add_action('woocommerce_shortcode_before_' . $shortcode . '_loop', array(
					$this,
					'shortcode_loop_start'
				));
				add_action('woocommerce_shortcode_after_' . $shortcode . '_loop', array($this, 'style_loop_end'));
				add_action('woocommerce_shortcode_after_' . $shortcode . '_loop', array(
					$this,
					'shortcode_loop_end'
				));
			}
		}

		public function shortcode_loop_end($atts = array()) {
			$function_to_call = $this->prefix . '_filter';
			if (isset($atts['product_layout'])) {
				if ($atts['product_layout'] === 'list') {
					if (!empty($atts['show_rating'])) {
						$function_to_call('shopic_product_template_arg', 'shopic_woocommerce_list_show_rating_arg', 10);
					}
					$function_to_call('wc_get_template_part', 'shopic_woocommerce_change_path_shortcode', 10);
				} elseif ($atts['product_layout'] === 'carousel') {
					echo '</div>';
				} elseif ($atts['product_layout'] === 'list-carousel') {
					if (!empty($atts['show_rating'])) {
						$function_to_call('shopic_product_template_arg', 'shopic_woocommerce_list_show_rating_arg', 10);
					}
					$function_to_call('wc_get_template_part', 'shopic_woocommerce_change_path_shortcode', 10);
					echo '</div>';
				}
			}

			if (!empty($atts['image_size'])) {
				$function_to_call('woocommerce_product_get_image', array($this, 'set_image_size_list'), 10);
			}
		}

		public function shortcode_loop_start($atts = array()) {
			if (isset($atts['product_layout'])) {
				if ($atts['product_layout'] === 'list') {
					if (!empty($atts['show_rating'])) {
						add_filter('shopic_product_template_arg', 'shopic_woocommerce_list_show_rating_arg', 10, 1);
					}
					add_filter('wc_get_template_part', 'shopic_woocommerce_change_path_shortcode', 10, 3);
					if (!empty($atts['image_size'])) {
						$this->list_size = $atts['image_size'];
						add_filter('woocommerce_product_get_image', array($this, 'set_image_size_list'), 10, 2);
					}
				} elseif ($atts['product_layout'] === 'carousel') {
					echo '<div class="woocommerce-carousel" data-settings=\'' . $atts['carousel_settings'] . '\'>';
				} elseif ($atts['product_layout'] === 'list-carousel') {
					if (!empty($atts['show_rating'])) {
						add_filter('shopic_product_template_arg', 'shopic_woocommerce_list_show_rating_arg', 10, 1);
					}
					add_filter('wc_get_template_part', 'shopic_woocommerce_change_path_shortcode', 10, 3);
					echo '<div class="woocommerce-carousel" data-settings=\'' . $atts['carousel_settings'] . '\'>';
				}
			}
		}

		public function style_loop_start($atts = array()) {

            if (isset($atts['product_layout']) &&  ($atts['product_layout'] === 'list' || $atts['product_layout'] === 'list-carousel')) {

				if (!empty($atts['show_category'])) {
					add_action('shopic_product_list_content_before', 'shopic_woocommerce_get_product_category', 10);
				}

				if (!empty($atts['show_button'])) {
					add_action('shopic_product_list_content_after', 'woocommerce_template_loop_add_to_cart', 20);
				}

				if (!empty($atts['show_except'])) {
					add_action('shopic_product_list_content_after', 'shopic_woocommerce_get_product_short_description', 10);
				}
				if (!empty($atts['show_time_sale'])) {
					add_action('shopic_product_list_content_after', 'shopic_woocommerce_time_sale', 30);
				}
			}

		}


		public function style_loop_end($atts = array()) {

            if (isset($atts['product_layout']) &&  ($atts['product_layout'] === 'list' || $atts['product_layout'] === 'list-carousel')) {

				if (!empty($atts['show_category'])) {
					remove_action('shopic_product_list_content_before', 'shopic_woocommerce_get_product_category', 10);
				}

				if (!empty($atts['show_button'])) {
					remove_action('shopic_product_list_content_after', 'woocommerce_template_loop_add_to_cart', 20);
				}

				if (!empty($atts['show_except'])) {
					remove_action('shopic_product_list_content_after', 'shopic_woocommerce_get_product_short_description', 10);
				}
				if (!empty($atts['show_time_sale'])) {
					remove_action('shopic_product_list_content_after', 'shopic_woocommerce_time_sale', 30);
				}
			}

		}

		public function set_shortcode_attributes($out, $pairs, $atts) {
			$out = wp_parse_args($atts, $out);

			return $out;
		}


		/**
		 * Assign styles to individual theme mod.
		 *
		 * @return void
		 * @since 2.1.0
		 * @deprecated 2.3.1
		 */
		public function set_shopic_style_theme_mods() {
			if (function_exists('wc_deprecated_function')) {
				wc_deprecated_function(__FUNCTION__, '2.3.1');
			} else {
				_deprecated_function(__FUNCTION__, '2.3.1');
			}
		}

		/**
		 * Add WooCommerce specific classes to the body tag
		 *
		 * @param array $classes css classes applied to the body tag.
		 *
		 * @return array $classes modified to include 'woocommerce-active' class
		 */
		public function woocommerce_body_class($classes) {
			$classes[] = 'woocommerce-active';

			// Remove `no-wc-breadcrumb` body class.
			$key = array_search('no-wc-breadcrumb', $classes, true);

			if (false !== $key) {
				unset($classes[$key]);
			}

			$style   = shopic_get_theme_option('wocommerce_block_style', 1);
			$layout  = shopic_get_theme_option('woocommerce_archive_layout', 'default');
			$sidebar = shopic_get_theme_option('woocommerce_archive_sidebar', 'left');

			$classes[] = 'product-style-' . $style;

			if (shopic_is_product_archive()) {
				$classes[] = 'shopic-archive-product';

				if (is_active_sidebar('sidebar-woocommerce-shop')) {
					if ($layout == 'default' && $sidebar == 'left') {
						$classes[] = 'shopic-sidebar-left';
					}

					if ($layout == 'canvas') {
						$classes[] = 'shopic-full-width-content shop_filter_canvas';
					}

					if ($layout == 'dropdown') {
						$classes[] = 'shopic-full-width-content shop_filter_dropdown';
					}
				} else {
					$classes[] = 'shopic-full-width-content';
				}

			}
			$classes[] = 'single-product-' . shopic_get_theme_option('wocommerce_single_style', '1');

			if (is_product()) {
				$classes[] = 'shopic-full-width-content';
				if (is_active_sidebar('sidebar-woocommerce-detail')) {
					$classes = array_diff($classes, array(
						'shopic-full-width-content',
					));
				}
			}


			return $classes;
		}

		/**
		 * WooCommerce specific scripts & stylesheets
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_scripts() {
			global $shopic_version;

			$suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
			wp_enqueue_style('shopic-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce.css', array(), $shopic_version);
			wp_style_add_data('shopic-woocommerce-style', 'rtl', 'replace');

			// QuickView
			wp_dequeue_style('yith-quick-view');

			wp_register_script('shopic-header-cart', get_template_directory_uri() . '/assets/js/woocommerce/header-cart' . $suffix . '.js', array(), $shopic_version, true);
			wp_enqueue_script('shopic-header-cart');

			wp_enqueue_script('shopic-handheld-footer-bar', get_template_directory_uri() . '/assets/js/footer' . $suffix . '.js', array(), $shopic_version, true);


			if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.3', '<')) {
				wp_enqueue_style('shopic-woocommerce-legacy', get_template_directory_uri() . '/assets/css/woocommerce/woocommerce-legacy.css', array(), $shopic_version);
				wp_style_add_data('shopic-woocommerce-legacy', 'rtl', 'replace');
			}

			if (is_shop() || is_product() || is_product_taxonomy() || shopic_elementor_check_type('shopic-products')) {
				wp_enqueue_script('tooltipster');
				wp_enqueue_style('tooltipster');
			}

			wp_enqueue_script('shopic-products', get_template_directory_uri() . '/assets/js/woocommerce/main' . $suffix . '.js', array(
				'jquery',
				'tooltipster'
			), $shopic_version, true);

			wp_enqueue_script('shopic-input-quantity', get_template_directory_uri() . '/assets/js/woocommerce/quantity' . $suffix . '.js', array('jquery'), $shopic_version, true);

			if (shopic_is_product_archive() && is_active_sidebar('sidebar-woocommerce-shop')) {
				wp_enqueue_script('shopic-off-canvas', get_template_directory_uri() . '/assets/js/woocommerce/off-canvas' . $suffix . '.js', array(), $shopic_version, true);
			}
			wp_enqueue_script('shopic-cart-canvas', get_template_directory_uri() . '/assets/js/woocommerce/cart-canvas' . $suffix . '.js', array(), $shopic_version, true);

			if (is_product() || is_cart()) {
				wp_enqueue_script('shopic-sticky-add-to-cart', get_template_directory_uri() . '/assets/js/sticky-add-to-cart' . $suffix . '.js', array(), $shopic_version, true);
				wp_enqueue_script('shopic-countdown');

				wp_enqueue_style('magnific-popup');
				wp_enqueue_script('magnific-popup');
				wp_enqueue_script('spritespin');

				wp_enqueue_script('slick');
				wp_enqueue_script('sticky-kit');
				wp_enqueue_script('shopic-single-product', get_template_directory_uri() . '/assets/js/woocommerce/single' . $suffix . '.js', array(
					'jquery',
					'slick',
					'sticky-kit'
				), $shopic_version, true);
			}


		}

		/**
		 * Related Products Args
		 *
		 * @param array $args related products args.
		 *
		 * @return  array $args related products args
		 * @since 1.0.0
		 */
		public function related_products_args($args) {
			$product_items = 4;
			if (is_active_sidebar('sidebar-woocommerce-detail')) {
				$product_items = 3;
			}

			$args = apply_filters(
				'shopic_related_products_args', array(
					'posts_per_page' => $product_items,
					'columns'        => $product_items,
				)
			);

			return $args;
		}

		/**
		 * Product gallery thumbnail columns
		 *
		 * @return integer number of columns
		 * @since  1.0.0
		 */
		public function thumbnail_columns() {
			$columns = shopic_get_theme_option('single_product_gallery_column', 4);

			if (shopic_get_theme_option('single_product_gallery_layout', 'horizontal') == 'vertical') {
				$columns = 1;
			}

			return intval(apply_filters('shopic_product_thumbnail_columns', $columns));
		}

		/**
		 * Products per page
		 *
		 * @return integer number of products
		 * @since  1.0.0
		 */
		public function products_per_page() {
			return intval(apply_filters('shopic_products_per_page', 12));
		}

		/**
		 * Query WooCommerce Extension Activation.
		 *
		 * @param string $extension Extension class name.
		 *
		 * @return boolean
		 */
		public function is_woocommerce_extension_activated($extension = 'WC_Bookings') {
			return class_exists($extension) ? true : false;
		}

		public function change_template_part($template, $slug, $name) {
			if (isset($_GET['layout'])) {
				if ($slug == 'content' && $name == 'product' && $_GET['layout'] == 'list') {
					$template = wc_get_template_part('content', 'product-list');
				}
			}

			return $template;
		}

		public function widgets_init() {
			register_sidebar(array(
				'name'          => esc_html__('WooCommerce Shop', 'shopic'),
				'id'            => 'sidebar-woocommerce-shop',
				'description'   => esc_html__('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'shopic'),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<span class="gamma widget-title">',
				'after_title'   => '</span>',
			));
			register_sidebar(array(
				'name'          => esc_html__('WooCommerce Detail', 'shopic'),
				'id'            => 'sidebar-woocommerce-detail',
				'description'   => esc_html__('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'shopic'),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<span class="gamma widget-title">',
				'after_title'   => '</span>',
			));
		}

		public function set_sidebar($name) {
			$layout = shopic_get_theme_option('woocommerce_archive_layout', 'default');
			if (shopic_is_product_archive()) {
				if (is_active_sidebar('sidebar-woocommerce-shop') && $layout == 'default') {
					$name = 'sidebar-woocommerce-shop';
				} else {
					$name = '';
				}
			}
			if (is_product()) {
				if (is_active_sidebar('sidebar-woocommerce-detail')) {
					$name = 'sidebar-woocommerce-detail';
				} else {
					$name = '';
				}
			}


			return $name;
		}

		public function ajax_search_products() {
			global $woocommerce;

			$search_keyword = $_REQUEST['query'];

			$ordering_args = $woocommerce->query->get_catalog_ordering_args('date', 'desc');
			$suggestions   = array();

			$args = array(
				's'                   => apply_filters('shopic_ajax_search_products_search_query', $search_keyword),
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'orderby'             => $ordering_args['orderby'],
				'order'               => $ordering_args['order'],
				'posts_per_page'      => apply_filters('shopic_ajax_search_products_posts_per_page', 8),

			);

			$products = get_posts($args);

			if (!empty($products)) {
				foreach ($products as $post) {
					$product       = wc_get_product($post);
					$product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()));

					$suggestions[] = apply_filters('shopic_suggestion', array(
						'id'    => $product->get_id(),
						'value' => strip_tags($product->get_title()),
						'url'   => $product->get_permalink(),
						'img'   => esc_url($product_image[0]),
						'price' => $product->get_price_html(),
					), $product);
				}
			} else {
				$suggestions[] = array(
					'id'    => -1,
					'value' => esc_html__('No results', 'shopic'),
					'url'   => '',
				);
			}
			wp_reset_postdata();

			echo json_encode($suggestions);
			die();
		}

		public function ajax_search_result() {
			?>
			<div class="ajax-search-result" style="display:none;">
			</div>
			<?php
		}

		public function ajax_live_search_template() {
			echo <<<HTML
        <script type="text/html" id="tmpl-ajax-live-search-template">
        <div class="product-item-search">
            <# if(data.url){ #>
            <a class="product-link" href="{{{data.url}}}" title="{{{data.title}}}">
            <# } #>
                <# if(data.img){#>
                <img src="{{{data.img}}}" alt="{{{data.title}}}">
                 <# } #>
                <div class="product-content">
                <h3 class="product-title">{{{data.title}}}</h3>
                <# if(data.price){ #>
                {{{data.price}}}
                 <# } #>
                </div>
                <# if(data.url){ #>
            </a>
            <# } #>
        </div>
        </script>
HTML;
		}

		public function grouped_product_column_image($grouped_product_child) {
			echo '<td class="woocommerce-grouped-product-image">' . $grouped_product_child->get_image('thumbnail') . '</td>';
		}

	}

endif;

return new Shopic_WooCommerce();
