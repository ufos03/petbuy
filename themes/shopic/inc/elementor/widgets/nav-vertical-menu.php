<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;

class OSF_Elementor_Nav_Vertiacl_Menu extends Elementor\Widget_Base{

    public function get_name()
    {
        return 'shopic-nav-vertiacl-menu';
    }

    public function get_title()
    {
        return esc_html__('Shopic Nav Vertiacl Menu', 'shopic');
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
            'nav-vertiacl-menu-style',
            [
                'label' => esc_html__('Config','shopic'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this -> add_control(
            'nav-vertiacl-style',
            [
                'label' => esc_html__('Menu Style', 'shopic'),
                'type'  => Controls_Manager::SELECT,
                'options'   => [
                    'style-1' => esc_html__('Dropdown', 'shopic'),
                    'style-2' =>  esc_html__('Navbar', 'shopic'),
                ],
                'default'   => 'style-1',
                'prefix_class' => 'nav-vertiacl-menu-style-content-',
            ]
        );
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'wrapper', 'class', 'elementor-nav-vertiacl-menu-wrapper' );
        ?>
        <div <?php echo shopic_elementor_get_render_attribute_string('wrapper', $this);?>>
            <?php shopic_vertical_navigation() ?>
        </div>
        <?php
    }

}

$widgets_manager->register(new OSF_Elementor_Nav_Vertiacl_Menu());