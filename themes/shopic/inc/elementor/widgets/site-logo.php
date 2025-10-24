<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class Shopic_Elementor_Site_Logo extends Elementor\Widget_Base{

    public function get_name()
    {
        return 'shopic-site-logo';
    }

    public function get_title()
    {
        return esc_html__('Shopic Site Logo', 'shopic');
    }

    public function get_icon()
    {
        return 'eicon-site-logo';
    }

    public function get_categories()
    {
        return array('shopic-addons');
    }

    protected function register_controls()
    {

        $this -> start_controls_section(
            'site_logo_content',
            [
                'label' => esc_html__('Image','shopic'),
            ]
        );

        $this -> add_responsive_control(
            'site_logo_aligrment',
            [
                'label'       => esc_html__( 'Alignment', 'shopic' ),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'options'     => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'shopic' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'shopic' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'shopic' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'label_block' => false,
                'selectors'   => [
                    '{{WRAPPER}} .site-branding' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this -> add_responsive_control(
            'site_logo_width',
            [
                'label' => esc_html__( 'Width', 'shopic' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-site-logo-wrapper img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'wrapper', 'class', 'elementor-site-logo-wrapper' );
        ?>
        <div <?php echo shopic_elementor_get_render_attribute_string('wrapper', $this);?>>
            <?php shopic_site_branding(); ?>
        </div>
        <?php
    }

}
$widgets_manager->register(new Shopic_Elementor_Site_Logo());
