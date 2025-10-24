<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class OSF_Elementor_Header_Group extends Elementor\Widget_Base
{

    public function get_name() {
        return 'shopic-header-group';
    }

    public function get_title() {
        return esc_html__('Shopic Header Group', 'shopic');
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories()
    {
        return array('shopic-addons');
    }

    public function get_script_depends() {
        return ['shopic-elementor-header-group', 'slick', 'shopic-cart-canvas'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'header_group_config',
            [
                'label' => esc_html__('Config', 'shopic'),
            ]
        );

        $this->add_control(
            'show_divider',
            [
                'label' => esc_html__( 'Show Divider', 'shopic' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'after',
                'prefix_class' => 'divider-header-group-action-',
            ]
        );

        $this->add_control(
            'show_search',
            [
                'label' => esc_html__( 'Show search form', 'shopic' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this -> add_control(
            'search_form',
            [
                'condition'  => ['show_search' => 'yes'],
                'label' => esc_html__('Style Search', 'shopic'),
                'type'  => Controls_Manager::SELECT,
                'separator' => 'after',
                'options'   => [
                    '1' => esc_html__('Icon Search', 'shopic'),
                    '2' =>  esc_html__('Form Search', 'shopic'),
                ],
                'default'   => '1',
            ]
        );

        $this -> add_control(
            'search_form_style',
            [
                'condition'  => ['search_form' => '2'],
                'label' => esc_html__('Style Search', 'shopic'),
                'type'  => Controls_Manager::SELECT,
                'options'   => [
                    'style-1' => esc_html__('Style 1', 'shopic'),
                    'style-2' =>  esc_html__('Style 2', 'shopic'),
                ],
                'default'   => 'style-1',
                'prefix_class' => 'search-form-style-content-',
            ]
        );

        $this->add_control(
            'show_account',
            [
                'label' => esc_html__( 'Show account', 'shopic' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this -> add_control(
           'account_style',
            [
               'condition'  => ['show_account' => 'yes'],
                'label' => esc_html__('Show Content', 'shopic'),
                'type'  => Controls_Manager::SWITCHER,
                'separator' => 'after',
                'prefix_class' => 'account-style-content-',
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label' => esc_html__( 'Show wishlist', 'shopic' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $this -> add_control(
            'wishlist_style',
            [
                'condition'  => ['show_wishlist' => 'yes'],
                'label' => esc_html__('Style', 'shopic'),
                'type'  => Controls_Manager::SELECT,
                'separator' => 'after',
                'options'   => [
                    'style-1' => esc_html__('Style 1', 'shopic'),
                    'style-2' =>  esc_html__('Style 2', 'shopic'),
                ],
                'default'   => 'style-1',
                'prefix_class' => 'wishlist-style-content-',
            ]
        );

        $this->add_control(
            'show_cart',
            [
                'label' => esc_html__( 'Show cart', 'shopic' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this -> add_control(
            'cart_dropdown',
            [
                'condition'  => ['show_cart' => 'yes'],
                'label' => esc_html__('Cart Content', 'shopic'),
                'type'  => Controls_Manager::SELECT,
                'options'   => [
                    '1' => esc_html__('Cart Canvas', 'shopic'),
                    '2' =>  esc_html__('Cart Dropdown', 'shopic'),
                ],
                'default'   => '1',
            ]
        );

        $this -> add_control(
            'cart_style',
            [
                'condition'  => ['show_cart' => 'yes'],
                'label' => esc_html__('Style', 'shopic'),
                'type'  => Controls_Manager::SELECT,
                'separator' => 'after',
                'options'   => [
                    'style-1' => esc_html__('Style 1', 'shopic'),
                    'style-2' =>  esc_html__('Style 2', 'shopic'),
                ],
                'default'   => 'style-1',
                'prefix_class' => 'cart-style-content-',
            ]
        );

        $this->end_controls_section();

        $this -> start_controls_section(
            'header-group-style',
            [
                'label' => esc_html__('Icon','shopic'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a i:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:before' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'wrapper', 'class', 'elementor-header-group-wrapper' );
        ?>
        <div <?php echo shopic_elementor_get_render_attribute_string('wrapper', $this);?>>
            <div class="header-group-action">
                <?php if ( $settings['show_search'] === 'yes' ):{
                    if($settings['search_form'] === '1'){
                        shopic_header_search_button();
                    }
                    else{
                        shopic_product_search();
                    }

                }
                endif; ?>

                <?php if ( $settings['show_account'] === 'yes' ):{
                    shopic_header_account();
                }
                endif; ?>

                <?php if ( $settings['show_wishlist'] === 'yes' ):{
                    shopic_header_wishlist();
                }
                endif; ?>

                <?php if ( $settings['show_cart'] === 'yes' ):{
                    if ( shopic_is_woocommerce_activated() ) {
                        ?>
                        <div class="site-header-cart menu">
                            <?php shopic_cart_link(); ?>
                            <?php
                            if ( ! apply_filters( 'woocommerce_widget_cart_is_hidden', is_cart() || is_checkout() ) ) {
                                if ( $settings['cart_dropdown'] === '1' ) {
                                    add_action( 'wp_footer', 'shopic_header_cart_side' );
                                } else {
                                    the_widget( 'WC_Widget_Cart', 'title=' );
                                }
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
                endif; ?>
            </div>
        </div>
        <?php
    }
}

$widgets_manager->register(new OSF_Elementor_Header_Group());
