<?php

/**
 * Main class of plugin for admin
 */
class Shopic_Woocommerce_Brand {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action('product_brand_add_form_fields', array($this, 'add_product_brand_image'), 10, 2);
		add_action('created_product_brand', array($this, 'save_product_brand_image'), 10, 2);
		add_action('product_brand_edit_form_fields', array($this, 'update_product_brand_image'), 10, 2);
		add_action('edited_product_brand', array($this, 'updated_product_brand_image'), 10, 2);
		add_action('admin_enqueue_scripts', array($this, 'load_media'));
	}

	public function load_media() {
        global $shopic_version;

		wp_enqueue_media();
        wp_enqueue_script('shoppic-admin-woocommerce-brand-scripts',get_theme_file_uri('assets/js/admin/woocommerce-brand.js'), array('jquery'), $shopic_version, true);
	}

	/*
     * Add a form field in the new product_brand page
     * @since 1.0.0
    */
	public function add_product_brand_image($taxonomy) { ?>
		<div class="form-field term-group">
			<label for="product_brand_logo"><?php esc_html_e('Image', 'shopic'); ?></label>
			<input type="hidden" id="product_brand_logo" name="product_brand_logo" class="custom_media_url" value="">
			<div id="product_brand-image-wrapper"></div>
			<p>
				<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_html_e('Add Image', 'shopic'); ?>"/>
				<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_html_e('Remove Image', 'shopic'); ?>"/>
			</p>
		</div>
		<?php
	}

	/*
     * Save the form field
     * @since 1.0.0
    */
	public function save_product_brand_image($term_id, $tt_id) {
		if (isset($_POST['product_brand_logo']) && '' !== $_POST['product_brand_logo']) {
			$image = $_POST['product_brand_logo'];
			add_term_meta($term_id, 'product_brand_logo', $image, true);
		}
	}

	/*
     * Edit the form field
     * @since 1.0.0
    */
	public function update_product_brand_image($term, $taxonomy) { ?>
		<tr class="form-field term-group-wrap">
			<th scope="row">
				<label for="product_brand_logo"><?php esc_html_e('Image', 'shopic'); ?></label>
			</th>
			<td>
				<?php $image_id = get_term_meta($term->term_id, 'product_brand_logo', true); ?>
				<input type="hidden" id="product_brand_logo" name="product_brand_logo" value="<?php echo esc_attr($image_id); ?>">
				<div id="product_brand-image-wrapper">
					<?php if ($image_id) { ?>
						<?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
					<?php } ?>
				</div>
				<p>
					<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_html_e('Add Image', 'shopic'); ?>"/>
					<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_html_e('Remove Image', 'shopic'); ?>"/>
				</p>
			</td>
		</tr>
		<?php
	}

	/*
     * Update the form field value
     * @since 1.0.0
     */
	public function updated_product_brand_image($term_id, $tt_id) {
		if (isset($_POST['product_brand_logo']) && '' !== $_POST['product_brand_logo']) {
			$image = $_POST['product_brand_logo'];
			update_term_meta($term_id, 'product_brand_logo', $image);
		} else {
			update_term_meta($term_id, 'product_brand_logo', '');
		}
	}

}

new Shopic_Woocommerce_Brand;
