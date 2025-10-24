<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if (!shopic_is_mailchimp_activated()) {
    return;
}

use Elementor\Group_Control_Border;
use Elementor\Controls_Manager;


class Shopic_Elementor_Mailchimp extends Elementor\Widget_Base {

    public function get_name() {
        return 'shopic-mailchmip';
    }

    public function get_title() {
        return esc_html__( 'MailChimp Sign-Up Form', 'shopic' );
    }

    public function get_categories() {
        return array( 'shopic-addons' );
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_script_depends() {
        return [ 'magnific-popup' ];
    }

    public function get_style_depends() {
        return [ 'magnific-popup' ];
    }


    protected function register_controls() {

        //INPUT
        $this->start_controls_section(
            'mailchip_style_input',
            [
                'label' => esc_html__( 'Input', 'shopic' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_input_style' );

        $this->start_controls_tab(
            'tab_input_normal',
            [
                'label' => esc_html__( 'Normal', 'shopic' ),
            ]
        );

        $this->add_control(
            'input_color',
            [
                'label'     => esc_html__( 'Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="email"]::placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .mc4wp-form-fields input[type="email"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_background',
            [
                'label'     => esc_html__( 'Background Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="email"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_color_border',
            [
                'label'     => esc_html__( 'Color Border', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="email"]' => 'border-color: {{VALUE}}  !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_focus',
            [
                'label' => esc_html__( 'Focus', 'shopic' ),
            ]
        );

        $this->add_control(
            'input_background_focus',
            [
                'label'     => esc_html__( 'Background Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="email"]:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color_focus',
            [
                'label'     => esc_html__( 'Border Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="email"]:focus' => 'border-color: {{VALUE}}  !important;',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'align_input',
            [
                'label'     => esc_html__( 'Alignment', 'shopic' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
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
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="email"]' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(

            Group_Control_Border::get_type(),
            [
                'name'        => 'border_input',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .mc4wp-form-fields input[type="email"]',
                'separator'   => 'before',
            ]
        );


        $this->add_responsive_control(
            'input_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'shopic' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .mc4wp-form-fields input[type="email"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        //Button
        $this->start_controls_section(
            'mailchip_style_button',
            [
                'label' => esc_html__( 'Button', 'shopic' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'shopic' ),
            ]
        );

        $this->add_control(
            'button_bacground',
            [
                'label'     => esc_html__( 'Background Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields button[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => esc_html__( 'Text Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields button[type="submit"]:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );



        $this->add_control(
            'button_border',
            [
                'label'     => esc_html__( 'Border Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields button[type="submit"]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'shopic'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields button[type="submit"] i' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'shopic' ),
            ]
        );

        $this->add_control(
            'button_bacground_hover',
            [
                'label'     => esc_html__( 'Background Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields button[type="submit"]:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label'     => esc_html__( 'Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields button[type="submit"]:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_hover',
            [
                'label'     => esc_html__( 'Border Color', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields button[type="submit"]:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_color_hover',
            [
                'label'     => esc_html__('Icon Color', 'shopic'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .mc4wp-form-fields button[type="submit"]:hover i' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'border_button',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .mc4wp-form-fields button[type="submit"]',
                'separator'   => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'shopic' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .mc4wp-form-fields button[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label'      => __( 'Padding', 'shopic' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .form-style .mc4wp-form .mc4wp-form-fields button[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        echo '<div class="form-style">';
        mc4wp_show_form();

        echo '</div>';
    }
}
$widgets_manager->register(new Shopic_Elementor_Mailchimp());