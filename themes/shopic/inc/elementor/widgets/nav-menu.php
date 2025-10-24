<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class OSF_Elementor_Nav_Menu extends Elementor\Widget_Base{

    public function get_name()
    {
        return 'shopic-nav-menu';
    }

    public function get_title()
    {
        return esc_html__('Shopic Nav Menu', 'shopic');
    }

    public function get_icon()
    {
        return 'eicon-nav-menu';
    }

    public function get_categories()
    {
        return ['opal-addons'];
    }

    protected function register_controls()
    {
        $this -> start_controls_section(
            'nav-menu_style',
            [
                'label' => esc_html__('Menu','shopic'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this -> add_responsive_control(
            'nav_menu_aligrment',
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
                    '{{WRAPPER}} .main-navigation' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'nav_menu_typography',
                'selector' => '{{WRAPPER}} .main-navigation ul.menu li.menu-item a',
            ]
        );

        $this->start_controls_tabs( 'tabs_nav_menu_style' );

        $this->start_controls_tab(
            'tab_nav_menu_normal',
            [
                'label' =>  esc_html__( 'Normal', 'shopic' ),
            ]
        );
        $this->add_control(
            'menu_title_color',
            [
                'label'     => esc_html__( 'Color Menu', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_sub_title_color',
            [
                'label'     => esc_html__( 'Color Sub Menu', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sub_menu_color',
            [
                'label'     => esc_html__( 'Background Dropdown', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_nav_menu_hover',
            [
                'label' =>  esc_html__( 'Hover', 'shopic' ),
            ]
        );
        $this->add_control(
            'menu_title_color_hover',
            [
                'label'     => esc_html__( 'Color Menu', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_sub_title_color_hover',
            [
                'label'     => esc_html__( 'Color Sub Menu', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_item_color_hover',
            [
                'label'     => esc_html__( 'Background Item', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item:hover > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_nav_menu_action',
            [
                'label' =>  esc_html__( 'Active', 'shopic' ),
            ]
        );
        $this->add_control(
            'menu_title_color_action',
            [
                'label'     => esc_html__( 'Color Menu', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item.current-menu-item > a:not(:hover)' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item.current-menu-parent > a:not(:hover)' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item.current-menu-ancestor > a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_sub_title_color_action',
            [
                'label'     => esc_html__( 'Color Sub Menu', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item.current-menu-item > a:not(:hover)' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'menu_item_color_action',
            [
                'label'     => esc_html__( 'Background Item', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .main-navigation ul.menu li.menu-item .sub-menu .menu-item.current-menu-item > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this -> add_control(
            'menu_divider',
            [
                'label'     => esc_html__('Divider','shopic'),
                'type'      => Controls_Manager::SWITCHER,
                'prefix_class' => 'poco-nav-menu-divider-',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'location_vertical',
            [
                'condition'  => ['menu_divider' => 'yes'],
                'label'       => esc_html__( 'Divider Vertical', 'shopic' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => [
                    'top'    => [
                        'title' => esc_html__( 'Top', 'shopic' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => esc_html__( 'Bottom', 'shopic' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ]
            ]
        );
        $this->add_control(
            'location_vertical_value',
            [
                'condition'  => ['menu_divider' => 'yes'],
                'label'     =>  esc_html__('Size','shopic'),
                'type'       => Controls_Manager::SLIDER,
                'show_label' => true,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min'  => - 1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => - 100,
                        'max' => 100,
                    ],
                ],
                'default'   =>  [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-nav-menu-wrapper .main-navigation ul.menu li.menu-item > a:before' => 'top: unset; bottom: unset; {{location_vertical.value}}: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_control(
            'menu_divider_color',
            [
                'condition'  => ['menu_divider' => 'yes'],
                'label'     => esc_html__( 'Color Divider', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav-menu-wrapper .main-navigation ul.menu li.menu-item > a:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_divider_color_active',
            [
                'condition'  => ['menu_divider' => 'yes'],
                'label'     => esc_html__( 'Color Divider Active', 'shopic' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav-menu-wrapper .main-navigation ul.menu li.menu-item.current-menu-item > a:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'wrapper', 'class', 'elementor-nav-menu-wrapper' );
        ?>
        <div <?php echo shopic_elementor_get_render_attribute_string('wrapper', $this);?>>
            <?php shopic_primary_navigation(); ?>
        </div>
        <?php
    }

}
$widgets_manager->register(new OSF_Elementor_Nav_Menu());
