<?php

class Shopic_Merlin_Config {

	private $wizard;

	public function __construct() {
		$this->init();
		add_filter('merlin_import_files', [$this, 'import_files']);
		add_action('merlin_after_all_import', [$this, 'after_import_setup'], 10, 1);
		add_filter('merlin_generate_child_functions_php', [$this, 'render_child_functions_php']);

		add_action('import_start', function () {
			add_filter('wxr_importer.pre_process.post_meta', [$this, 'fiximport_elementor'], 10, 1);
		});

		add_action('import_end', function () {
			update_option('elementor_experiment-container', 'active');
			update_option('elementor_experiment-nested-elements', 'active');
		});
	}

	public function fiximport_elementor($post_meta) {
		if ('_elementor_data' === $post_meta['key']) {
			$post_meta['value'] = wp_slash($post_meta['value']);
		}

		return $post_meta;
	}

	public function import_files(){
            return array(
            array(
                'import_file_name'           => 'home 1',
                'home'                       => 'home-1',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-1.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-1/home-1.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_1.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-1',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#FD0202"},{"_id":"secondary","title":"Secondary","color":"#FD0202"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{}',
            ),

            array(
                'import_file_name'           => 'home 10',
                'home'                       => 'home-10',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-10.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-10/home-10.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_10.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-10',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#f1a4b5"},{"_id":"secondary","title":"Secondary","color":"#f1a4b5"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#2a2a2a"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"3"}',
            ),

            array(
                'import_file_name'           => 'home 11',
                'home'                       => 'home-11',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-11.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-11/home-11.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_11.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-11',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#f4a51c"},{"_id":"secondary","title":"Secondary","color":"#FD0202"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"3"}',
            ),

            array(
                'import_file_name'           => 'home 12',
                'home'                       => 'home-12',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-12.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-12/home-12.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_12.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-12',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#ae8875"},{"_id":"secondary","title":"Secondary","color":"#FD0202"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"3"}',
            ),

            array(
                'import_file_name'           => 'home 13',
                'home'                       => 'home-13',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-13.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-13/home-13.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_13.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-13',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#f8cf32"},{"_id":"secondary","title":"Secondary","color":"#FD0202"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"2"}',
            ),

            array(
                'import_file_name'           => 'home 14',
                'home'                       => 'home-14',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-14.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-14/home-14.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_14.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-14',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#ffcd00"},{"_id":"secondary","title":"Secondary","color":"#638a24"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{}',
            ),

            array(
                'import_file_name'           => 'home 2',
                'home'                       => 'home-2',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-2.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-2/home-2.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_2.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-2',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#00A939"},{"_id":"secondary","title":"Secondary","color":"#00A939"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"3"}',
            ),

            array(
                'import_file_name'           => 'home 3',
                'home'                       => 'home-3',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-3.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-3/home-3.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_3.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-3',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#F9BA38"},{"_id":"secondary","title":"Secondary","color":"#00A939"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#635872"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"2"}',
            ),

            array(
                'import_file_name'           => 'home 4',
                'home'                       => 'home-4',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-4.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-4/home-4.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_4.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-4',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#F26C4F"},{"_id":"secondary","title":"Secondary","color":"#00A939"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#635872"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"3"}',
            ),

            array(
                'import_file_name'           => 'home 5',
                'home'                       => 'home-5',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-5.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-5/home-5.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_5.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-5',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#E89C6F"},{"_id":"secondary","title":"Secondary","color":"#FD0202"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"3"}',
            ),

            array(
                'import_file_name'           => 'home 6',
                'home'                       => 'home-6',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-6.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-6/home-6.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_6.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-6',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#F5A44A"},{"_id":"secondary","title":"Secondary","color":"#F5A44A"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#2f353d"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{}',
            ),

            array(
                'import_file_name'           => 'home 7',
                'home'                       => 'home-7',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-7.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-7/home-7.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_7.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-7',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#FFCE61"},{"_id":"secondary","title":"Secondary","color":"#FD0202"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"3"}',
            ),

            array(
                'import_file_name'           => 'home 8',
                'home'                       => 'home-8',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-8.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                'import_rev_slider_file_url' => 'http://source.wpopal.com/shopic/dummy_data/revsliders/home-8/home-8.zip',
                'import_more_revslider_file_url' => [],
                'import_lookbook_revslider_file_url' => [],
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_8.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-8',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#e5ad9c"},{"_id":"secondary","title":"Secondary","color":"#e5ad9c"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#2a2a2a"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"3"}',
            ),

            array(
                'import_file_name'           => 'home 9',
                'home'                       => 'home-9',
                'local_import_file'          => get_theme_file_path('/dummy-data/content.xml'),
                'homepage'                   => get_theme_file_path('/dummy-data/homepage/home-9.xml'),
                'local_import_widget_file'   => get_theme_file_path('/dummy-data/widgets.json'),
                
                'import_preview_image_url'   => get_theme_file_uri('/assets/images/oneclick/home_9.jpg'),
                'preview_url'                => 'https://demo2.pavothemes.com/shopic/home-9',
                'elementor'                  => '{"system_colors":[{"_id":"primary","title":"Primary","color":"#ECBA78"},{"_id":"secondary","title":"Secondary","color":"#FD0202"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}',
                'themeoptions'               => '{"shopic_options_wocommerce_block_style":"3"}',
            ),
            );           
        }

	public function after_import_setup($selected_import) {
		$selected_import = ($this->import_files())[$selected_import];

		// setup Home page
		$home = get_page_by_path($selected_import['home']);
		if ($home->ID) {
			update_option('show_on_front', 'page');
			update_option('page_on_front', $home->ID);
		}

		$this->set_demo_menus();

		// Setup Options
		$options       = $this->get_all_options();
		$theme_options = $options['options'];
		foreach ($theme_options as $key => $option) {
			update_option($key, $option);
		}

		$active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
		update_post_meta($active_kit_id, '_elementor_page_settings', json_decode($selected_import['elementor'], true));
		set_theme_mod('custom_logo', $this->get_attachment('_logo'));

		// Header Footer Builder
		$this->reset_header_footer();
		$this->set_hf($selected_import['home']);

		\Elementor\Plugin::instance()->files_manager->clear_cache();

		$this->update_nav_menu_item();
		$this->remove_quick_table_enable();
	}

	//remove quick_table_enable
	private function remove_quick_table_enable() {
		$qte = get_option('woosc_settings');
		if ($qte) {
			if ($qte['quick_table_enable'] == 'yes') {
				$qte['quick_table_enable'] = 'no';
				update_option('woosc_settings', $qte);
			}
		} else {
			$qte                       = array();
			$qte['quick_table_enable'] = 'no';
			add_option('woosc_settings', $qte);
		}

	}

	private function update_nav_menu_item() {
		$params = array(
			'posts_per_page' => -1,
			'post_type'      => [
				'nav_menu_item',
			],
		);
		$query  = new WP_Query($params);
		while ($query->have_posts()): $query->the_post();
			wp_update_post(array(
				// Update the `nav_menu_item` Post Title
				'ID'         => get_the_ID(),
				'post_title' => get_the_title()
			));
		endwhile;

	}

	private function get_mailchimp_id() {
		$params = array(
			'post_type'      => 'mc4wp-form',
			'posts_per_page' => 1,
		);
		$post   = get_posts($params);

		return isset($post[0]) ? $post[0]->ID : 0;
	}

	private function get_attachment($key) {
		$params = array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'meta_key'       => $key,
		);
		$post   = get_posts($params);
		if ($post) {
			return $post[0]->ID;
		}

		return 0;
	}

	private function init() {
		$this->wizard = new Merlin(
			$config = array(
				// Location / directory where Merlin WP is placed in your theme.
				'merlin_url'         => 'merlin',
				// The wp-admin page slug where Merlin WP loads.
				'parent_slug'        => 'themes.php',
				// The wp-admin parent page slug for the admin menu item.
				'capability'         => 'manage_options',
				// The capability required for this menu to be displayed to the user.
				'dev_mode'           => true,
				// Enable development mode for testing.
				'license_step'       => false,
				// EDD license activation step.
				'license_required'   => false,
				// Require the license activation step.
				'license_help_url'   => '',
				'directory'          => '/inc/merlin',
				// URL for the 'license-tooltip'.
				'edd_remote_api_url' => '',
				// EDD_Theme_Updater_Admin remote_api_url.
				'edd_item_name'      => '',
				// EDD_Theme_Updater_Admin item_name.
				'edd_theme_slug'     => '',
				// EDD_Theme_Updater_Admin item_slug.
			),
			$strings = array(
				'admin-menu'          => esc_html__('Theme Setup', 'shopic'),

				/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
				'title%s%s%s%s'       => esc_html__('%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'shopic'),
				'return-to-dashboard' => esc_html__('Return to the dashboard', 'shopic'),
				'ignore'              => esc_html__('Disable this wizard', 'shopic'),

				'btn-skip'                 => esc_html__('Skip', 'shopic'),
				'btn-next'                 => esc_html__('Next', 'shopic'),
				'btn-start'                => esc_html__('Start', 'shopic'),
				'btn-no'                   => esc_html__('Cancel', 'shopic'),
				'btn-plugins-install'      => esc_html__('Install', 'shopic'),
				'btn-child-install'        => esc_html__('Install', 'shopic'),
				'btn-content-install'      => esc_html__('Install', 'shopic'),
				'btn-import'               => esc_html__('Import', 'shopic'),
				'btn-license-activate'     => esc_html__('Activate', 'shopic'),
				'btn-license-skip'         => esc_html__('Later', 'shopic'),

				/* translators: Theme Name */
				'license-header%s'         => esc_html__('Activate %s', 'shopic'),
				/* translators: Theme Name */
				'license-header-success%s' => esc_html__('%s is Activated', 'shopic'),
				/* translators: Theme Name */
				'license%s'                => esc_html__('Enter your license key to enable remote updates and theme support.', 'shopic'),
				'license-label'            => esc_html__('License key', 'shopic'),
				'license-success%s'        => esc_html__('The theme is already registered, so you can go to the next step!', 'shopic'),
				'license-json-success%s'   => esc_html__('Your theme is activated! Remote updates and theme support are enabled.', 'shopic'),
				'license-tooltip'          => esc_html__('Need help?', 'shopic'),

				/* translators: Theme Name */
				'welcome-header%s'         => esc_html__('Welcome to %s', 'shopic'),
				'welcome-header-success%s' => esc_html__('Hi. Welcome back', 'shopic'),
				'welcome%s'                => esc_html__('This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'shopic'),
				'welcome-success%s'        => esc_html__('You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'shopic'),

				'child-header'         => esc_html__('Install Child Theme', 'shopic'),
				'child-header-success' => esc_html__('You\'re good to go!', 'shopic'),
				'child'                => esc_html__('Let\'s build & activate a child theme so you may easily make theme changes.', 'shopic'),
				'child-success%s'      => esc_html__('Your child theme has already been installed and is now activated, if it wasn\'t already.', 'shopic'),
				'child-action-link'    => esc_html__('Learn about child themes', 'shopic'),
				'child-json-success%s' => esc_html__('Awesome. Your child theme has already been installed and is now activated.', 'shopic'),
				'child-json-already%s' => esc_html__('Awesome. Your child theme has been created and is now activated.', 'shopic'),

				'plugins-header'         => esc_html__('Install Plugins', 'shopic'),
				'plugins-header-success' => esc_html__('You\'re up to speed!', 'shopic'),
				'plugins'                => esc_html__('Let\'s install some essential WordPress plugins to get your site up to speed.', 'shopic'),
				'plugins-success%s'      => esc_html__('The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'shopic'),
				'plugins-action-link'    => esc_html__('Advanced', 'shopic'),

				'import-header'      => esc_html__('Import Content', 'shopic'),
				'import'             => esc_html__('Let\'s import content to your website, to help you get familiar with the theme.', 'shopic'),
				'import-action-link' => esc_html__('Advanced', 'shopic'),

				'ready-header'      => esc_html__('All done. Have fun!', 'shopic'),

				/* translators: Theme Author */
				'ready%s'           => esc_html__('Your theme has been all set up. Enjoy your new theme by %s.', 'shopic'),
				'ready-action-link' => esc_html__('Extras', 'shopic'),
				'ready-big-button'  => esc_html__('View your website', 'shopic'),
				'ready-link-1'      => sprintf('<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__('Explore WordPress', 'shopic')),
				'ready-link-2'      => sprintf('<a href="%1$s" target="_blank">%2$s</a>', 'https://themebeans.com/contact/', esc_html__('Get Theme Support', 'shopic')),
				'ready-link-3'      => sprintf('<a href="%1$s">%2$s</a>', admin_url('customize.php'), esc_html__('Start Customizing', 'shopic')),
			)
		);
		if (shopic_is_elementor_activated()) {
			add_action('widgets_init', [$this, 'widgets_init']);
		}
		if (class_exists('Monster_Widget')) {
			add_action('widgets_init', [$this, 'widget_monster']);
		}
	}

	public function widget_monster() {
		unregister_widget('Monster_Widget');
		require_once get_parent_theme_file_path('/inc/merlin/includes/monster-widget.php');
		register_widget('Shopic_Monster_Widget');
	}

	public function widgets_init() {
		require_once get_parent_theme_file_path('/inc/merlin/includes/recent-post.php');
		register_widget('Shopic_WP_Widget_Recent_Posts');
		if (shopic_is_woocommerce_activated()) {
			require_once get_parent_theme_file_path('/inc/merlin/includes/class-wc-widget-layered-nav.php');
			register_widget('Shopic_Widget_Layered_Nav');
		}
	}

	private function get_all_header_footer() {
		return [
			'home-1'  => [
				'header' => [
					[
						'slug'       => 'headerbuilder-2',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-2'  => [
				'header' => [
					[
						'slug'       => 'headerbuilder-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-2',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-3'  => [
				'header' => [
					[
						'slug'       => 'headerbuilder-3',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-3',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-4'  => [
				'header' => [
					[
						'slug'       => 'headerbuilder-4',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-4',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-5'  => [
				'header' => [
					[
						'slug'       => 'headerbuilder-5',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-5',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-6'  => [
				'header' => [
					[
						'slug'       => 'headerbuilder-6',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-4',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-7'  => [
				'header' => [
					[
						'slug'       => 'headerbuilder-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-6',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-8'  => [
				'header' => [
					[
						'slug'       => 'headerbuilder-3',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-7',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-9'  => [
				'header' => [
					[
						'slug'       => 'headerbuilder-10',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-7',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-10' => [
				'header' => [
					[
						'slug'       => 'headerbuilder-7',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-8',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-11' => [
				'header' => [
					[
						'slug'       => 'headerbuilder-2',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-12' => [
				'header' => [
					[
						'slug'       => 'headerbuilder-1',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-7',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-13' => [
				'header' => [
					[
						'slug'       => 'headerbuilder-8',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-4',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
			'home-14' => [
				'header' => [
					[
						'slug'       => 'headerbuilder-9',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				],
				'footer' => [
					[
						'slug'       => 'footerbuilder-9',
						'ehf_target_include_locations' => [ 'rule' => [ 'basic-global' ], 'specific' => [] ],
					]
				]
			],
		];
	}


	private function reset_header_footer() {
		$footer_args = array(
			'post_type'      => 'elementor-hf',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'ehf_template_type',
					'compare' => 'IN',
					'value'   => ['type_footer', 'type_header']
				),
			)
		);
		$footer      = new WP_Query($footer_args);
		while ($footer->have_posts()) : $footer->the_post();
			update_post_meta(get_the_ID(), 'ehf_target_include_locations', []);
			update_post_meta(get_the_ID(), 'ehf_target_exclude_locations', []);
		endwhile;
		wp_reset_postdata();
	}

	public function set_demo_menus() {
		$main_menu = get_term_by('name', 'Main Menu', 'nav_menu');
		$vertical_menu = get_term_by('name', 'All Departments', 'nav_menu');

		set_theme_mod(
			'nav_menu_locations',
			array(
				'primary'  => $main_menu->term_id,
				'handheld' => $main_menu->term_id,
				'vertical' => $vertical_menu->term_id,
			)
		);
	}

	private function set_hf($home) {
		$all_hf = $this->get_all_header_footer();
		$datas  = $all_hf[$home];
		foreach ($datas as $item) {
			foreach ($item as $object) {
				$hf = get_page_by_path($object['slug'], OBJECT, 'elementor-hf');
				if ($hf) {
					update_post_meta($hf->ID, 'ehf_target_include_locations', $object['ehf_target_include_locations']);
					if (isset($object['ehf_target_exclude_locations'])) {
						update_post_meta($hf->ID, 'ehf_target_exclude_locations', $object['ehf_target_exclude_locations']);
					}
				}
			}
		}
	}

	public function render_child_functions_php() {
		$output
			= "<?php
/**
 * Theme functions and definitions.
 */
		 ";

		return $output;
	}

	public function get_all_options(){
        $options = [];
        $options['options']   = json_decode('{"shopic_options_last_tab":"5","shopic_options_social_share":"1","shopic_options_social_share_facebook":"1","shopic_options_social_share_twitter":"1","shopic_options_social_share_linkedin":"","shopic_options_social_share_google_plus":"1","shopic_options_social_share_pinterest":"","shopic_options_social_share_email":"1","shopic_options_wocommerce_block_style":"1","shopic_options_woocommerce_hide_rating":"","shopic_options_woocommerce_hide_categories":"","shopic_options_woocommerce_archive_layout":"default","shopic_options_woocommerce_archive_sidebar":"left","shopic_options_single_product_gallery_layout":"horizontal","shopic_options_single_product_content_meta":"<div class=\"product-extra-info\">\n<div>\n<ul>\n<li>Free global shipping on all orders</li>\n<li>30 days easy returns if you change your mind</li>\n<li>Order before noon for same day dispatch</li>\n</ul>\n</div>\n<div class=\"brand-wrap\">\n<h5 class=\"title-brand\">Guaranteed Safe Checkout</h5>\n<p><img src=\"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/trust-symbols.png\" class=\"image-responsive\"/>\n</p></div>\n</div>\n","shopic_options_wocommerce_show_cat":"yes","shopic_options_wocommerce_show_rating":"yes"}', true);
        $options['elementor']   = json_decode('{"system_colors":[{"_id":"primary","title":"Primary","color":"#FD0202"},{"_id":"secondary","title":"Secondary","color":"#FD0202"},{"_id":"text","title":"Body","color":"#626262"},{"_id":"accent","title":"Heading","color":"#000000"}],"custom_colors":[{"_id":"227734b","title":"Light","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary"},{"_id":"secondary","title":"Secondary"},{"_id":"text","title":"Text"},{"_id":"accent","title":"Accent"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","site_name":"Shopic","site_description":"Just another WordPress site","page_title_selector":"h1.entry-title","activeItemIndex":1,"container_width":{"unit":"px","size":1290,"sizes":[]},"space_between_widgets":{"unit":"px","size":0,"sizes":[]},"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals/colors?id=primary"},"button_typography_typography":"custom","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_weight":"700","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_background_color":"#E50101","button_padding":{"unit":"px","top":"20","right":"40","bottom":"20","left":"40","isLinked":false},"button_typography_line_height":{"unit":"em","size":1,"sizes":[]},"site_logo":{"url":"https://source.wpopal.com/shopic/wp-content/uploads/2020/09/logo.svg","id":1777},"viewport_mobile":"","viewport_tablet":""}', true);
        return $options;
    } // end get_all_options
}

return new Shopic_Merlin_Config();
