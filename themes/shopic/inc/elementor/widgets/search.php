<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class OSF_Elementor_Search extends Elementor\Widget_Base
{
    public function get_name() {
        return 'shopic-search';
    }

    public function get_title() {
        return esc_html__('Shopic Search Form', 'shopic');
    }

    public function get_icon() {
        return 'eicon-site-search';
    }

    public function get_categories()
    {
        return array('shopic-addons');
    }

    protected function register_controls()
    {
        $this -> start_controls_section(
            'search-form-style',
            [
                'label' => esc_html__('Style','shopic'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'layout_style',
            [
                'label' => esc_html__( 'Layout Style', 'shopic' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'layout-1' => esc_html__('Layout 1', 'shopic'),
                    'layout-2' => esc_html__('Layout 2', 'shopic'),
                ],
                'default' => 'layout-1',
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label'      => esc_html__( 'Border width', 'shopic' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} form input[type=search]' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label'     => esc_html__( 'Border Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_color_focus',
            [
                'label'     => esc_html__( 'Border Color Focus', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_form',
            [
                'label'     => esc_html__( 'Background', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} form input[type=search]' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_color_form',
            [
                'label'     => esc_html__( 'Color Icon', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .widget_product_search form:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(){
        $settings = $this->get_settings_for_display();

        if ($settings['layout_style'] === 'layout-1'):{
            shopic_product_search();
        }
        endif;

        if ($settings['layout_style'] === 'layout-2'):{
            wp_enqueue_script('shopic-search-popup');
            add_action('wp_footer', 'shopic_header_search_popup', 1);
            ?>
            <div class="site-header-search">
                <a href="#" class="button-search-popup">
                    <i class="shopic-icon-search"></i>
                    <span class="content"><?php echo esc_html__('Search', 'shopic'); ?></span>
                </a>
            </div>
            <?php
        }
        endif;
    }
}

$widgets_manager->register(new OSF_Elementor_Search());
