<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Shopic_Customize')) {

    class Shopic_Customize {


        public function __construct() {
            add_action('customize_register', array($this, 'customize_register'));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         */
        public function customize_register($wp_customize) {

            /**
             * Theme options.
             */
            require_once get_theme_file_path('inc/customize-control/editor.php');
            $this->init_shopic_blog($wp_customize);

            $this->init_shopic_social($wp_customize);

            if (shopic_is_woocommerce_activated()) {
                $this->init_woocommerce($wp_customize);
            }

            do_action('shopic_customize_register', $wp_customize);
        }


        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_shopic_blog($wp_customize) {

            $wp_customize->add_section('shopic_blog_archive', array(
                'title' => esc_html__('Blog', 'shopic'),
            ));

            // =========================================
            // Select Style
            // =========================================

            $wp_customize->add_setting('shopic_options_blog_style', array(
                'type'              => 'option',
                'default'           => 'standard',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_blog_style', array(
                'section' => 'shopic_blog_archive',
                'label'   => esc_html__('Blog style', 'shopic'),
                'type'    => 'select',
                'choices' => array(
                    'standard' => esc_html__('Blog Standard', 'shopic'),
                    'grid'     => esc_html__('Blog Grid', 'shopic'),
                ),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_shopic_social($wp_customize) {

            $wp_customize->add_section('shopic_social', array(
                'title' => esc_html__('Socials', 'shopic'),
            ));
            $wp_customize->add_setting('shopic_options_social_share', array(
                'type'       => 'option',
                'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_social_share', array(
                'type'    => 'checkbox',
                'section' => 'shopic_social',
                'label'   => esc_html__('Show Social Share', 'shopic'),
            ));
            $wp_customize->add_setting('shopic_options_social_share_facebook', array(
                'type'       => 'option',
                'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_social_share_facebook', array(
                'type'    => 'checkbox',
                'section' => 'shopic_social',
                'label'   => esc_html__('Share on Facebook', 'shopic'),
            ));
            $wp_customize->add_setting('shopic_options_social_share_twitter', array(
                'type'       => 'option',
                'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_social_share_twitter', array(
                'type'    => 'checkbox',
                'section' => 'shopic_social',
                'label'   => esc_html__('Share on Twitter', 'shopic'),
            ));
            $wp_customize->add_setting('shopic_options_social_share_linkedin', array(
                'type'       => 'option',
                'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_social_share_linkedin', array(
                'type'    => 'checkbox',
                'section' => 'shopic_social',
                'label'   => esc_html__('Share on Linkedin', 'shopic'),
            ));
            $wp_customize->add_setting('shopic_options_social_share_google-plus', array(
                'type'       => 'option',
                'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_social_share_google-plus', array(
                'type'    => 'checkbox',
                'section' => 'shopic_social',
                'label'   => esc_html__('Share on Google+', 'shopic'),
            ));

            $wp_customize->add_setting('shopic_options_social_share_pinterest', array(
                'type'       => 'option',
                'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_social_share_pinterest', array(
                'type'    => 'checkbox',
                'section' => 'shopic_social',
                'label'   => esc_html__('Share on Pinterest', 'shopic'),
            ));
            $wp_customize->add_setting('shopic_options_social_share_email', array(
                'type'       => 'option',
                'capability' => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_social_share_email', array(
                'type'    => 'checkbox',
                'section' => 'shopic_social',
                'label'   => esc_html__('Share on Email', 'shopic'),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_woocommerce($wp_customize) {

            $wp_customize->add_panel('woocommerce', array(
                'title' => esc_html__('Woocommerce', 'shopic'),
            ));

            $wp_customize->add_section('shopic_woocommerce_archive', array(
                'title'      => esc_html__('Archive', 'shopic'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
                'priority'   => 1,
            ));

            $wp_customize->add_setting('shopic_options_woocommerce_archive_layout', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_woocommerce_archive_layout', array(
                'section' => 'shopic_woocommerce_archive',
                'label'   => esc_html__('Layout Style', 'shopic'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Sidebar', 'shopic'),
                    'canvas'   => esc_html__('Canvas Filter', 'shopic'),
                    'dropdown' => esc_html__('Dropdown Filter', 'shopic'),
                ),
            ));

            $wp_customize->add_setting('shopic_options_woocommerce_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'left',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('shopic_options_woocommerce_archive_sidebar', array(
                'section' => 'shopic_woocommerce_archive',
                'label'   => esc_html__('Sidebar Position', 'shopic'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'shopic'),
                    'right' => esc_html__('Right', 'shopic'),

                ),
            ));

            // =========================================
            // Single Product
            // =========================================

            $wp_customize->add_section('shopic_woocommerce_single', array(
                'title'      => esc_html__('Single Product', 'shopic'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('shopic_options_single_product_gallery_layout', array(
                'type'              => 'option',
                'default'           => 'horizontal',
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('shopic_options_single_product_gallery_layout', array(
                'section' => 'shopic_woocommerce_single',
                'label'   => esc_html__('Style', 'shopic'),
                'type'    => 'select',
                'choices' => array(
                    'horizontal' => esc_html__('Horizontal', 'shopic'),
                    'vertical'   => esc_html__('Vertical', 'shopic'),
                    'gallery'    => esc_html__('Gallery', 'shopic'),
                    'sticky'     => esc_html__('Sticky', 'shopic'),
                ),
            ));

            $wp_customize->add_setting('shopic_options_single_product_content_meta', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'shopic_sanitize_editor',
            ));

            $wp_customize->add_control(new Shopic_Customize_Control_Editor($wp_customize, 'shopic_options_single_product_content_meta', array(
                'section' => 'shopic_woocommerce_single',
                'label'   => esc_html__('Single extra description', 'shopic'),
            )));


            // =========================================
            // Product
            // =========================================

            $wp_customize->add_section('shopic_woocommerce_product', array(
                'title'      => esc_html__('Product Block', 'shopic'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('shopic_options_wocommerce_block_style', array(
                'type'              => 'option',
                'default'           => '1',
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('shopic_options_wocommerce_block_style', array(
                'section' => 'shopic_woocommerce_product',
                'label'   => esc_html__('Style', 'shopic'),
                'type'    => 'select',
                'choices' => array(
                    '1' => esc_html__('Style 1', 'shopic'),
                    '2' => esc_html__('Style 2', 'shopic'),
                    '3' => esc_html__('Style 3', 'shopic'),
                ),
            ));

            $wp_customize->add_setting('shopic_options_woocommerce_product_hover', array(
                'type'              => 'option',
                'default'           => 'none',
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('shopic_options_woocommerce_product_hover', array(
                'section' => 'shopic_woocommerce_product',
                'label'   => esc_html__('Animation Image Hover', 'shopic'),
                'type'    => 'select',
                'choices' => array(
                    'none'          => esc_html__( 'None', 'shopic' ),
                    'bottom-to-top' => esc_html__( 'Bottom to Top', 'shopic' ),
                    'top-to-bottom' => esc_html__( 'Top to Bottom', 'shopic' ),
                    'right-to-left' => esc_html__( 'Right to Left', 'shopic' ),
                    'left-to-right' => esc_html__( 'Left to Right', 'shopic' ),
                    'swap'          => esc_html__( 'Swap', 'shopic' ),
                    'fade'          => esc_html__( 'Fade', 'shopic' ),
                    'zoom-in'       => esc_html__( 'Zoom In', 'shopic' ),
                    'zoom-out'      => esc_html__( 'Zoom Out', 'shopic' ),
                ),
            ));

            $wp_customize->add_setting('shopic_options_wocommerce_show_cat', array(
                'type'              => 'option',
                'default'           => 'yes',
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'shopic_options_wocommerce_show_cat',
                    array(
                        'label'    => esc_html__('Show categories', 'shopic'),
                        'section'  => 'shopic_woocommerce_product',
                        'settings' => 'shopic_options_wocommerce_show_cat',
                        'type'     => 'radio',
                        'choices'  => array(
                            'no'  => esc_html__('Hide', 'shopic'),
                            'yes' => esc_html__('Show', 'shopic')
                        )
                    )
                )
            );

            $wp_customize->add_setting('shopic_options_wocommerce_show_rating', array(
                'type'              => 'option',
                'default'           => 'yes',
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'shopic_options_wocommerce_show_rating',
                    array(
                        'label'    => esc_html__('Show rating', 'shopic'),
                        'section'  => 'shopic_woocommerce_product',
                        'settings' => 'shopic_options_wocommerce_show_rating',
                        'type'     => 'radio',
                        'choices'  => array(
                            'no'  => esc_html__('Hide', 'shopic'),
                            'yes' => esc_html__('Show', 'shopic')
                        )
                    )
                )
            );

        }
    }
}
return new Shopic_Customize();
