<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Shopic_Elementor' ) ) :

	/**
	 * The Shopic Elementor Integration class
	 */
	class Shopic_Elementor {
		private $suffix = '';

		public function __construct() {
			$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'register_auto_scripts_frontend' ] );
			add_action( 'elementor/init', array( $this, 'add_category' ) );
			add_action( 'wp_enqueue_scripts', [ $this, 'add_scripts' ], 15 );
			add_action( 'elementor/widgets/register', array( $this, 'include_widgets' ) );
			add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'add_js' ] );

			// Custom Animation Scroll
			add_filter( 'elementor/controls/animations/additional_animations', [ $this, 'add_animations_scroll' ] );
			add_filter('wp_enqueue_scripts', [$this, 'add_animations_scroll_style']);

			// Elementor Fix Noitice WooCommerce
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'woocommerce_fix_notice' ) );

			// Backend
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'add_style_editor' ], 99 );

			// Add Icon Custom
			add_action( 'elementor/icons_manager/native', [ $this, 'add_icons_native' ] );
			add_action( 'elementor/controls/register', [ $this, 'add_icons' ] );

			if ( ! shopic_is_elementor_pro_activated() ) {
				require get_theme_file_path('/inc/elementor/custom-css.php');
				require get_theme_file_path('/inc/merlin/includes/elementor-shortcode.php');
			}

			add_filter( 'elementor/fonts/additional_fonts', [ $this, 'additional_fonts' ] );
            add_action('wp_enqueue_scripts', [$this, 'elementor_kit']);
		}

		public function elementor_kit() {
			$active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
			Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
			$myvals = get_post_meta($active_kit_id, '_elementor_page_settings', true);
			if (!empty($myvals)) {
				$css = '';
				foreach ($myvals['system_colors'] as $key => $value) {
					$css .= $value['color'] !== '' ? '--' . $value['_id'] . ':' . $value['color'] . ';' : '';
				}

				$var = "body{{$css}}";
				wp_add_inline_style('shopic-style', $var);
			}
		}

        public function elementor_kit1() {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
            $myvals        = get_post_meta($active_kit_id, '_elementor_page_settings', true);
            if (!empty($myvals)) {
                $css = '';
                $css .= $myvals['system_colors'][0]['color'] !== '' ? '--primary:' . $myvals['system_colors'][0]['color'] . ';' : '';
                $css .= $myvals['system_colors'][0]['color'] !== '' ? '--primary_hover:' . darken_color($myvals['system_colors'][0]['color'], 1.1) . ';' : '';
                $css .= $myvals['system_colors'][1]['color'] !== '' ? '--secondary:' . $myvals['system_colors'][1]['color'] . ';' : '';
                $css .= $myvals['system_colors'][2]['color'] !== '' ? '--text:' . $myvals['system_colors'][2]['color'] . ';' : '';
                $css .= $myvals['system_colors'][3]['color'] !== '' ? '--accent:' . $myvals['system_colors'][3]['color'] . ';' : '';

                $custom_color = $myvals['custom_colors'];

                foreach ($custom_color as $color) {
                    $title = $color["title"];
                    switch ($title) {
                        case "Light":
                            $css .= '--light:' . $color['color'] . ';';
                            break;
                        case "Dark":
                            $css .= '--dark:' . $color['color'] . ';';
                            break;
                        case "Border":
                            $css .= '--border:' . $color['color'] . ';';
                            break;
                    }
                }

                $var = "body{{$css}}";
                wp_add_inline_style('shopic-style', $var);
            }
        }

		public function additional_fonts( $fonts ) {
			$fonts["Bebas Neue"] = 'googlefonts';
			$fonts["GoogleSans"] = 'system';

			return $fonts;
		}

		public function add_js() {
			global $shopic_version;
			wp_enqueue_script( 'shopic-elementor-frontend', get_theme_file_uri( '/assets/js/elementor-frontend.js' ), [], $shopic_version );
		}

		public function add_style_editor() {
			global $shopic_version;
			wp_enqueue_style( 'shopic-elementor-editor-icon', get_theme_file_uri( '/assets/css/admin/elementor/icons.css' ), [], $shopic_version );
		}

		public function add_scripts() {
			global $shopic_version;
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			wp_enqueue_style( 'shopic-elementor', get_template_directory_uri() . '/assets/css/base/elementor.css', '', $shopic_version );
			wp_style_add_data( 'shopic-elementor', 'rtl', 'replace' );

			// Add Scripts
			wp_register_script( 'tweenmax', get_theme_file_uri( '/assets/js/vendor/TweenMax.min.js' ), array( 'jquery' ), '1.11.1' );
			wp_register_script( 'parallaxmouse', get_theme_file_uri( '/assets/js/vendor/jquery-parallax.js' ), array( 'jquery' ), $shopic_version );

			if ( shopic_elementor_check_type( 'animated-bg-parallax' ) ) {
				wp_enqueue_script( 'tweenmax' );
				wp_enqueue_script( 'jquery-panr', get_theme_file_uri( '/assets/js/vendor/jquery-panr' . $suffix . '.js' ), array( 'jquery' ), '0.0.1' );
			}
		}


		public function register_auto_scripts_frontend() {
            global $shopic_version;
            wp_register_script('shopic-elementor-brand', get_theme_file_uri('/assets/js/elementor/brand.js'), array('jquery','elementor-frontend'), $shopic_version, true);
            wp_register_script('shopic-elementor-countdown', get_theme_file_uri('/assets/js/elementor/countdown.js'), array('jquery','elementor-frontend'), $shopic_version, true);
            wp_register_script('shopic-elementor-image-carousel', get_theme_file_uri('/assets/js/elementor/image-carousel.js'), array('jquery','elementor-frontend'), $shopic_version, true);
            wp_register_script('shopic-elementor-posts-grid', get_theme_file_uri('/assets/js/elementor/posts-grid.js'), array('jquery','elementor-frontend'), $shopic_version, true);
            wp_register_script('shopic-elementor-product-tab', get_theme_file_uri('/assets/js/elementor/product-tab.js'), array('jquery','elementor-frontend'), $shopic_version, true);
            wp_register_script('shopic-elementor-products', get_theme_file_uri('/assets/js/elementor/products.js'), array('jquery','elementor-frontend'), $shopic_version, true);
            wp_register_script('shopic-elementor-tab-hover', get_theme_file_uri('/assets/js/elementor/tab-hover.js'), array('jquery','elementor-frontend'), $shopic_version, true);
            wp_register_script('shopic-elementor-testimonial', get_theme_file_uri('/assets/js/elementor/testimonial.js'), array('jquery','elementor-frontend'), $shopic_version, true);
           
        }

		public function add_category() {
			Elementor\Plugin::instance()->elements_manager->add_category(
				'shopic-addons',
				array(
					'title' => esc_html__( 'Shopic Addons', 'shopic' ),
					'icon'  => 'fa fa-plug',
				),
				1 );
		}

		public function add_animations_scroll_style() {
			global $shopic_version;
			$animations =[
				'opal-move-up'    => 'Move Up',
				'opal-move-down'  => 'Move Down',
				'opal-move-left'  => 'Move Left',
				'opal-move-right' => 'Move Right',
				'opal-flip'       => 'Flip',
				'opal-helix'      => 'Helix',
				'opal-scale-up'   => 'Scale',
				'opal-am-popup'   => 'Popup',
			];
			foreach ($animations as $animation => $name) {
				wp_deregister_style('e-animation-' . $animation);
				wp_register_style('e-animation-' . $animation, get_theme_file_uri('/assets/css/animations/' . $animation . '.css'), [], $shopic_version);
			}
		}
		public function add_animations_scroll( $animations ) {
			$animations['Shopic Animation'] = [
				'opal-move-up'    => 'Move Up',
				'opal-move-down'  => 'Move Down',
				'opal-move-left'  => 'Move Left',
				'opal-move-right' => 'Move Right',
				'opal-flip'       => 'Flip',
				'opal-helix'      => 'Helix',
				'opal-scale-up'   => 'Scale',
				'opal-am-popup'   => 'Popup',
			];

			return $animations;
		}

		/**
		 * @param $widgets_manager Elementor\Widgets_Manager
		 */
		public function include_widgets( $widgets_manager ) {
			$files = glob( get_theme_file_path( '/inc/elementor/widgets/*.php' ) );
			foreach ( $files as $file ) {
				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}

			// Button
			add_action( 'elementor/element/button/section_style/after_section_end', function ( $element, $args ) {

				$element->update_control(
					'background_color',
					[
						'global' => [
							'default' => '',
						],
					]
				);
			}, 10, 2 );

			// Text editor
			add_action( 'elementor/element/text-editor/section_style/before_section_end', function ( $element, $args ) {
				/** @var \Elementor\Element_Base $element */
				$element->add_group_control(
					Group_Control_Text_Shadow::get_type(),
					[
						'name' => 'texteditor_shadow',
						'selector' => '{{WRAPPER}} .elementor-text-editor',
					]
				);

			}, 10, 2 );

			// Toggle
			add_action( 'elementor/element/toggle/section_toggle_style_title/after_section_end', function ( $element, $args ) {
				/** @var \Elementor\Element_Base $element */
				// Remove Schema
				$element->update_control( 'title_color', [
					'scheme' => [],
				] );

				$element->update_control( 'tab_active_color', [
					'scheme' => [],
				] );
			}, 10, 2 );

			// Image Box
			add_action( 'elementor/element/image-box/section_style_content/after_section_end', function ( $element, $args ) {
				/** @var \Elementor\Element_Base $element */
				// Remove Schema
				$element->update_control( 'title_color', [
					'scheme' => [],
				] );

				$element->update_control( 'title_typography', [
					'scheme' => [],
				] );

				$element->update_control( 'description_color', [
					'scheme' => [],
				] );

				$element->update_control( 'description_typography', [
					'scheme' => [],
				] );
			}, 10, 2 );

			add_action( 'elementor/element/image-box/section_style_content/before_section_end', function ( $element, $args ) {

				$element->add_control(
					'box_title_decor',
					[
						'label' => esc_html__( 'Decor', 'shopic' ),
						'type' => Controls_Manager::SWITCHER,
						'prefix_class'  => 'box-title-decor-'
					]
				);

				$element->add_control(
					'box_title_decor_color',
					[
						'label' => esc_html__( 'Decor Color', 'shopic' ),
						'type' => Controls_Manager::COLOR,
						'selectors'  => [
							'{{WRAPPER}} .elementor-image-box-title:before'  => 'background: {{VALUE}}'
						],
						'condition' => [
							'box_title_decor!'  => ''
						]
					]
				);

			}, 10, 2 );

			// Icon Box
			add_action( 'elementor/element/icon-box/section_style_content/after_section_end', function ( $element, $args ) {
				/** @var \Elementor\Element_Base $element */
				// Remove Schema
				$element->update_control( 'primary_color', [
					'scheme' => [],
				] );

				$element->update_control( 'title_color', [
					'scheme' => [],
				] );

				$element->update_control( 'title_typography', [
					'scheme' => [],
				] );

				$element->update_control( 'description_color', [
					'scheme' => [],
				] );

				$element->update_control( 'description_typography', [
					'scheme' => [],
				] );
			}, 10, 2 );

			// Icon List
			add_action( 'elementor/element/icon-list/section_text_style/after_section_end', function ( $element, $args ) {
				/** @var \Elementor\Element_Base $element */
				// Remove Schema
				$element->update_control( 'icon_color', [
					'scheme' => [],
				] );

				$element->update_control( 'text_color', [
					'scheme'    => [],
					'selectors' => [
						'{{WRAPPER}} .elementor-icon-list-items .elementor-icon-list-item .elementor-icon-list-text' => 'color: {{VALUE}};',
					],
				] );

				$element->update_control( 'text_color_hover', [
					'scheme'    => [],
					'selectors' => [
						'{{WRAPPER}} .elementor-icon-list-items .elementor-icon-list-item:hover .elementor-icon-list-text' => 'color: {{VALUE}};',
					],
				] );

				$element->update_control( 'icon_typography', [
					'scheme'    => [],
					'selectors' => '{{WRAPPER}} .elementor-icon-list-items .elementor-icon-list-item:hover .elementor-icon-list-text',
				] );

				$element->update_control( 'divider_color', [
					'scheme'  => [],
					'default' => ''
				] );

			}, 10, 2 );

//			Accordion
			add_action( 'elementor/element/accordion/section_title_style/before_section_end', function ( $element, $args ) {

				$element->add_control(
					'style_theme',
					[
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label' => esc_html__( 'Style Theme', 'shopic' ),
						'prefix_class'	=> 'style-theme-'
					]
				);

			},10,2);


//          Divider
            add_action( 'elementor/element/divider/section_divider_style/before_section_end', function ( $element, $args ) {
                $element->add_control(
                    'divider_border_radius',
                    [
                        'label' => esc_html__( 'Border Radius', 'shopic' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} .elementor-divider .elementor-divider-separator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
            },10,2);
		}

		public function woocommerce_fix_notice() {
			if ( shopic_is_woocommerce_activated() ) {
				remove_action( 'woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5 );
				remove_action( 'woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices', 10 );
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
				remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
				remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
				remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );
				remove_action( 'woocommerce_account_content', 'woocommerce_output_all_notices', 10 );
				remove_action( 'woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10 );
			}
		}

		public function add_icons( $manager ) {
            $new_icons = json_decode( '{"shopic-icon-account":"account","shopic-icon-aids":"aids","shopic-icon-anti":"anti","shopic-icon-arrow-down":"arrow-down","shopic-icon-arrow-left":"arrow-left","shopic-icon-arrow-right":"arrow-right","shopic-icon-arrow-up":"arrow-up","shopic-icon-boot":"boot","shopic-icon-box-check":"box-check","shopic-icon-box":"box","shopic-icon-calendar":"calendar","shopic-icon-calorie":"calorie","shopic-icon-camera":"camera","shopic-icon-caret-vertiacl-menu":"caret-vertiacl-menu","shopic-icon-cart":"cart","shopic-icon-computer":"computer","shopic-icon-contact-2":"contact-2","shopic-icon-delivery":"delivery","shopic-icon-discount":"discount","shopic-icon-drone":"drone","shopic-icon-envelope-open-text":"envelope-open-text","shopic-icon-eyecare":"eyecare","shopic-icon-faceskin":"faceskin","shopic-icon-formula":"formula","shopic-icon-free":"free","shopic-icon-gaming":"gaming","shopic-icon-gem":"gem","shopic-icon-headphone":"headphone","shopic-icon-headset":"headset","shopic-icon-help":"help","shopic-icon-hydrating":"hydrating","shopic-icon-id-card":"id-card","shopic-icon-long-arrow-down":"long-arrow-down","shopic-icon-long-arrow-left":"long-arrow-left","shopic-icon-long-arrow-right":"long-arrow-right","shopic-icon-long-arrow-up":"long-arrow-up","shopic-icon-mail":"mail","shopic-icon-map-marker-alt":"map-marker-alt","shopic-icon-map-marker":"map-marker","shopic-icon-money":"money","shopic-icon-networking":"networking","shopic-icon-off":"off","shopic-icon-paper-plane":"paper-plane","shopic-icon-payment":"payment","shopic-icon-pencil-ruler":"pencil-ruler","shopic-icon-person":"person","shopic-icon-phone-1":"phone-1","shopic-icon-phone-volume":"phone-volume","shopic-icon-phone":"phone","shopic-icon-pickup":"pickup","shopic-icon-professionals-1":"professionals-1","shopic-icon-protected":"protected","shopic-icon-quote-1":"quote-1","shopic-icon-quote-2":"quote-2","shopic-icon-returns":"returns","shopic-icon-rocket":"rocket","shopic-icon-search":"search","shopic-icon-shield-alt":"shield-alt","shopic-icon-shoe-prints":"shoe-prints","shopic-icon-smartphone":"smartphone","shopic-icon-speaker":"speaker","shopic-icon-spray-can":"spray-can","shopic-icon-support":"support","shopic-icon-tag":"tag","shopic-icon-tags":"tags","shopic-icon-telephone":"telephone","shopic-icon-tennis-ball":"tennis-ball","shopic-icon-theme-clock":"theme-clock","shopic-icon-tv":"tv","shopic-icon-undo":"undo","shopic-icon-water":"water","shopic-icon-wishlist":"wishlist","shopic-icon-360":"360","shopic-icon-angle-down":"angle-down","shopic-icon-angle-left":"angle-left","shopic-icon-angle-right":"angle-right","shopic-icon-angle-up":"angle-up","shopic-icon-arrow-circle-down":"arrow-circle-down","shopic-icon-arrow-circle-left":"arrow-circle-left","shopic-icon-arrow-circle-right":"arrow-circle-right","shopic-icon-arrow-circle-up":"arrow-circle-up","shopic-icon-bars":"bars","shopic-icon-caret-down":"caret-down","shopic-icon-caret-left":"caret-left","shopic-icon-caret-right":"caret-right","shopic-icon-caret-up":"caret-up","shopic-icon-cart-empty":"cart-empty","shopic-icon-check-square":"check-square","shopic-icon-chevron-circle-left":"chevron-circle-left","shopic-icon-chevron-circle-right":"chevron-circle-right","shopic-icon-chevron-down":"chevron-down","shopic-icon-chevron-left":"chevron-left","shopic-icon-chevron-right":"chevron-right","shopic-icon-chevron-up":"chevron-up","shopic-icon-circle":"circle","shopic-icon-clock":"clock","shopic-icon-cloud-download-alt":"cloud-download-alt","shopic-icon-comment":"comment","shopic-icon-comments":"comments","shopic-icon-contact":"contact","shopic-icon-credit-card":"credit-card","shopic-icon-dot-circle":"dot-circle","shopic-icon-edit":"edit","shopic-icon-envelope":"envelope","shopic-icon-expand-alt":"expand-alt","shopic-icon-external-link-alt":"external-link-alt","shopic-icon-eye":"eye","shopic-icon-file-alt":"file-alt","shopic-icon-file-archive":"file-archive","shopic-icon-filter":"filter","shopic-icon-folder-open":"folder-open","shopic-icon-folder":"folder","shopic-icon-free_ship":"free_ship","shopic-icon-frown":"frown","shopic-icon-gift":"gift","shopic-icon-grid":"grid","shopic-icon-grip-horizontal":"grip-horizontal","shopic-icon-heart-fill":"heart-fill","shopic-icon-heart":"heart","shopic-icon-history":"history","shopic-icon-home":"home","shopic-icon-info-circle":"info-circle","shopic-icon-instagram":"instagram","shopic-icon-level-up-alt":"level-up-alt","shopic-icon-list":"list","shopic-icon-long-arrow-alt-down":"long-arrow-alt-down","shopic-icon-long-arrow-alt-left":"long-arrow-alt-left","shopic-icon-long-arrow-alt-right":"long-arrow-alt-right","shopic-icon-long-arrow-alt-up":"long-arrow-alt-up","shopic-icon-map-marker-check":"map-marker-check","shopic-icon-meh":"meh","shopic-icon-minus-circle":"minus-circle","shopic-icon-minus":"minus","shopic-icon-mobile-android-alt":"mobile-android-alt","shopic-icon-money-bill":"money-bill","shopic-icon-pencil-alt":"pencil-alt","shopic-icon-play-circle":"play-circle","shopic-icon-plus-circle":"plus-circle","shopic-icon-plus":"plus","shopic-icon-quote":"quote","shopic-icon-random":"random","shopic-icon-reply-all":"reply-all","shopic-icon-reply":"reply","shopic-icon-search-plus":"search-plus","shopic-icon-shield-check":"shield-check","shopic-icon-shopping-basket":"shopping-basket","shopic-icon-shopping-cart":"shopping-cart","shopic-icon-sign-out-alt":"sign-out-alt","shopic-icon-smile":"smile","shopic-icon-spinner":"spinner","shopic-icon-square":"square","shopic-icon-star":"star","shopic-icon-store":"store","shopic-icon-sync":"sync","shopic-icon-tachometer-alt":"tachometer-alt","shopic-icon-th-large":"th-large","shopic-icon-th-list":"th-list","shopic-icon-thumbtack":"thumbtack","shopic-icon-times-circle":"times-circle","shopic-icon-times":"times","shopic-icon-trophy-alt":"trophy-alt","shopic-icon-truck":"truck","shopic-icon-user-headset":"user-headset","shopic-icon-user-shield":"user-shield","shopic-icon-user":"user","shopic-icon-video":"video","shopic-icon-adobe":"adobe","shopic-icon-amazon":"amazon","shopic-icon-android":"android","shopic-icon-angular":"angular","shopic-icon-apper":"apper","shopic-icon-apple":"apple","shopic-icon-atlassian":"atlassian","shopic-icon-behance":"behance","shopic-icon-bitbucket":"bitbucket","shopic-icon-bitcoin":"bitcoin","shopic-icon-bity":"bity","shopic-icon-bluetooth":"bluetooth","shopic-icon-btc":"btc","shopic-icon-centos":"centos","shopic-icon-chrome":"chrome","shopic-icon-codepen":"codepen","shopic-icon-cpanel":"cpanel","shopic-icon-discord":"discord","shopic-icon-dochub":"dochub","shopic-icon-docker":"docker","shopic-icon-dribbble":"dribbble","shopic-icon-dropbox":"dropbox","shopic-icon-drupal":"drupal","shopic-icon-ebay":"ebay","shopic-icon-facebook":"facebook","shopic-icon-figma":"figma","shopic-icon-firefox":"firefox","shopic-icon-google-plus":"google-plus","shopic-icon-google":"google","shopic-icon-grunt":"grunt","shopic-icon-gulp":"gulp","shopic-icon-html5":"html5","shopic-icon-jenkins":"jenkins","shopic-icon-joomla":"joomla","shopic-icon-link-brand":"link-brand","shopic-icon-linkedin":"linkedin","shopic-icon-mailchimp":"mailchimp","shopic-icon-opencart":"opencart","shopic-icon-paypal":"paypal","shopic-icon-pinterest-p":"pinterest-p","shopic-icon-reddit":"reddit","shopic-icon-skype":"skype","shopic-icon-slack":"slack","shopic-icon-snapchat":"snapchat","shopic-icon-spotify":"spotify","shopic-icon-trello":"trello","shopic-icon-twitter":"twitter","shopic-icon-vimeo":"vimeo","shopic-icon-whatsapp":"whatsapp","shopic-icon-wordpress":"wordpress","shopic-icon-yoast":"yoast","shopic-icon-youtube":"youtube"}', true );
			$icons     = $manager->get_control( 'icon' )->get_settings( 'options' );
			$new_icons = array_merge(
				$new_icons,
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$manager->get_control( 'icon' )->set_settings( 'options', $new_icons ); 
        }

		public function add_icons_native( $tabs ) {
			global $shopic_version;
			$tabs['opal-custom'] = [
				'name'          => 'shopic-icon',
				'label'         => esc_html__( 'Shopic Icon', 'shopic' ),
				'prefix'        => 'shopic-icon-',
				'displayPrefix' => 'shopic-icon-',
				'labelIcon'     => 'fab fa-font-awesome-alt',
				'ver'           => $shopic_version,
				'fetchJson'     => get_theme_file_uri( '/inc/elementor/icons.json' ),
				'native'        => true,
			];

			return $tabs;
		}
	}

endif;

return new Shopic_Elementor();
