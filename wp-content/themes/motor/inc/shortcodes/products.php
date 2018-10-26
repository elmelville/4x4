<?php
add_action( 'vc_before_init', 'motor_products_integrate_vc' );
function motor_products_integrate_vc () {
	vc_map( array(
		'name' => esc_html__( 'Products', 'motor' ),
		'base' => 'motor_products',
		'icon' => get_template_directory_uri() . "/img/vc_motor.png",
		'category' => esc_html__( 'Motor', 'motor' ),
		'description' => esc_html__( 'Show multiple products by ID or SKU.', 'motor' ),
		'params' => array(
			array(
				'type' => 'autocomplete',
				'heading' => esc_html__( 'Products 1', 'motor' ),
				'param_name' => 'ids',
				'settings' => array(
					'multiple' => true,
					'sortable' => true,
					'unique_values' => true,
					// In UI show results except selected. NB! You should manually check values in backend
				),
				'save_always' => true,
				'description' => esc_html__( 'To choose custom ordering please set Order by to the Custom OR you can also leave empty this field and show products automatically by Order by field', 'motor' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order by', 'motor' ),
				'param_name' => 'orderby',
				'value' => array(
					esc_html__( 'Custom', 'motor' ) => 'post__in',
					esc_html__( 'Date', 'motor' ) => 'date',
					esc_html__( 'ID', 'motor' ) => 'ID',
					esc_html__( 'Author', 'motor' ) => 'author',
					esc_html__( 'Title', 'motor' ) => 'title',
					esc_html__( 'Modified', 'motor' ) => 'modified',
					esc_html__( 'Random', 'motor' ) => 'rand',
					esc_html__( 'Comment count', 'motor' ) => 'comment_count',
					esc_html__( 'Menu order', 'motor' ) => 'menu_order',
				),
				'std' => 'title',
				'save_always' => true,
				'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s. Default by Title', 'motor' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Sort order', 'motor' ),
				'param_name' => 'order',
				'value' => array(
					'',
					esc_html__( 'Descending', 'motor' ) => 'DESC',
					esc_html__( 'Ascending', 'motor' ) => 'ASC',
				),
				'save_always' => true,
				'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s. Default by ASC', 'motor' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Items per page', 'motor' ),
				'param_name' => 'items_per_page',
				'description' => esc_html__( 'Number of items to show per page. Enter -1 to display all', 'motor' ),
				'value' => '8',
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Large First Item', 'motor' ),
				'param_name' => 'large_first_item',
				'value' => array( esc_html__( 'Yes', 'motor' ) => 'yes' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'View mode', 'motor' ),
				'param_name' => 'view_mode',
				'value' => array(
					esc_html__( 'Gallery', 'motor' ) => 'gallery',
					esc_html__( 'List', 'motor' ) => 'list',
				),
				'std' => 'gallery',
			),
			array(
				'type' => 'hidden',
				'param_name' => 'skus',
			),
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'Css', 'motor' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design options', 'motor' ),
			),
		),
	) );
}



//Filters For autocomplete param:
//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
add_filter( 'vc_autocomplete_motor_products_ids_callback', 'motor_productIdAutocompleteSuggester', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_motor_products_ids_render', 'motor_productIdAutocompleteRender', 10, 1 ); // Render exact product. Must return an array (label,value)
//For param: ID default value filter
add_filter( 'vc_form_fields_render_field_motor_products_ids_param_value', 'motor_productsIdsDefaultValue', 10, 4 ); // Defines default value for param if not provided. Takes from other param value.



/**
 * Suggester for autocomplete by id/name/title/sku
 * @since 4.4
 *
 * @param $query
 *
 * @return array - id's from products with title/sku.
 */
function motor_productIdAutocompleteSuggester( $query ) {
	global $wpdb;
	$product_id = (int) $query;
	$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title, b.meta_value AS sku
				FROM {$wpdb->posts} AS a
				LEFT JOIN ( SELECT meta_value, post_id  FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' ) AS b ON b.post_id = a.ID
				WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.meta_value LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

	$results = array();
	if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
		foreach ( $post_meta_infos as $value ) {
			$data = array();
			$data['value'] = $value['id'];
			$data['label'] = esc_html__( 'Id', 'motor' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'motor' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . esc_html__( 'Sku', 'motor' ) . ': ' . $value['sku'] : '' );
			$results[] = $data;
		}
	}

	return $results;
}




/**
 * Find product by id
 * @since 4.4
 *
 * @param $query
 *
 * @return bool|array
 */
function motor_productIdAutocompleteRender( $query ) {
	$query = trim( $query['value'] ); // get value from requested
	if ( ! empty( $query ) ) {
		// get product
		$product_object = wc_get_product( (int) $query );
		if ( is_object( $product_object ) ) {
			$product_sku = $product_object->get_sku();
			$product_title = $product_object->get_title();
			$product_id = $product_object->get_id();

			$product_sku_display = '';
			if ( ! empty( $product_sku ) ) {
				$product_sku_display = ' - ' . esc_html__( 'Sku', 'motor' ) . ': ' . $product_sku;
			}

			$product_title_display = '';
			if ( ! empty( $product_title ) ) {
				$product_title_display = ' - ' . esc_html__( 'Title', 'motor' ) . ': ' . $product_title;
			}

			$product_id_display = esc_html__( 'Id', 'motor' ) . ': ' . $product_id;

			$data = array();
			$data['value'] = $product_id;
			$data['label'] = $product_id_display . $product_title_display . $product_sku_display;

			return ! empty( $data ) ? $data : false;
		}

		return false;
	}

	return false;
}



/**
 * Replaces product skus to id's.
 * @since 4.4
 *
 * @param $current_value
 * @param $param_settings
 * @param $map_settings
 * @param $atts
 *
 * @return string
 */
function motor_productsIdsDefaultValue( $current_value, $param_settings, $map_settings, $atts ) {
	$value = trim( $current_value );
	if ( strlen( trim( $value ) ) === 0 && isset( $atts['skus'] ) && strlen( $atts['skus'] ) > 0 ) {
		$data = array();
		$skus = $atts['skus'];
		$skus_array = explode( ',', $skus );
		foreach ( $skus_array as $sku ) {
			$id = $this->productIdDefaultValueFromSkuToId( trim( $sku ) );
			if ( is_numeric( $id ) ) {
				$data[] = $id;
			}
		}
		if ( ! empty( $data ) ) {
			$values = explode( ',', $value );
			$values = array_merge( $values, $data );
			$value = implode( ',', $values );
		}
	}

	return $value;
}




class WPBakeryShortCode_motor_products extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {

		$css = '';
		extract( shortcode_atts( array (
			'orderby' => 'date',
			'order' => 'DESC',
			'ids' => '',
			'large_first_item' => '',
			'items_per_page' => 8,
			'view_mode' => 'gallery',
			'skus' => '',
			'css' => ''
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

		ob_start();
		?>

		<?php
		if (($large_first_item == 'yes')) {
			$int_key = 0;
		} else {
			$int_key = 1;
		}
		include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/products-content.php' );
		?>

		<?php

		$output = ob_get_clean();

		return $output;
	}
}