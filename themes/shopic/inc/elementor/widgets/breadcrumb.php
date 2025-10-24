<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!shopic_is_woocommerce_activated()){
	return;
}

class Shopic_WooCommerce_Breadcrumb extends Elementor\Widget_Base  {

	public function get_name() {
		return 'shopic-breadcrumb';
	}

	public function get_title() {
		return __( 'Shopic WooComerce Breadcrumbs', 'shopic' );
	}

	public function get_icon() {
		return 'eicon-product-breadcrumbs';
	}

	public function get_keywords() {
		return [ 'woocommerce-elements', 'shop', 'store', 'breadcrumbs', 'internal links', 'product' ];
	}

	public function get_categories() {
		return array('shopic-addons');
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_rating_style',
			[
				'label' => __( 'Style Breadcrumbs', 'shopic' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_style_warning',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'shopic' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'shopic' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Link Color', 'shopic' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb > a:not(:hover)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
                'label' => __( 'Typography Link', 'shopic' ),
				'name' => 'text_link_typography',
				'selector' => '{{WRAPPER}} .woocommerce-breadcrumb a',
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Typography Text', 'shopic' ),
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .woocommerce-breadcrumb',
            ]
        );

		$this->add_responsive_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'shopic' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'shopic' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'shopic' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'shopic' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .shopic-woocommerce-title' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'section_product_rating_style_title',
            [
                'label' => __( 'Style Title', 'shopic' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color_title',
            [
                'label' => __( 'Title Color', 'shopic' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .shopic-woocommerce-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .shopic-woocommerce-title',
            ]
        );

        $this->add_control(
            'display_title',
            [
                'label' => __( 'Hidden Title', 'shopic' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class'	=> 'hidden-shopic-title-'
            ]
        );

        $this->add_control(
            'display_title_single',
            [
                'label' => __( 'Hidden Title Single', 'shopic' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class'	=> 'hidden-shopic-title-single-'
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => __( 'Margin', 'shopic' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .shopic-woocommerce-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
	}

	protected function render() {
		$args = apply_filters(
			'woocommerce_breadcrumb_defaults',
			array(
				'delimiter'   => '&nbsp;'.'<i class="shopic-icon-angle-right"></i>'.'&nbsp;',
				'wrap_before' => '<nav class="woocommerce-breadcrumb">',
				'wrap_after'  => '</nav>',
				'before'      => '',
				'after'       => '',
				'home'        => _x( 'Home Page', 'breadcrumb', 'shopic' ),
			)
		);
		$breadcrumbs = new WC_Breadcrumb();
		if ( ! empty( $args['home'] ) ) {
			$breadcrumbs->add_crumb( $args['home'], apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) );
		}
		$args['breadcrumb'] = $breadcrumbs->generate();
		/**
		 * WooCommerce Breadcrumb hook
		 *
		 * @see WC_Structured_Data::generate_breadcrumblist_data() - 10
		 */
		do_action( 'woocommerce_breadcrumb', $breadcrumbs, $args );

	    printf('<div class="shopic-woocommerce-title">%s</div>',$args['breadcrumb'][count($args['breadcrumb']) - 1][0]);
		wc_get_template( 'global/breadcrumb.php', $args );
	}

	public function render_plain_content() {}
}

$widgets_manager->register(new Shopic_WooCommerce_Breadcrumb());
