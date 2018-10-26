<?php
add_action( 'vc_before_init', 'motor_product_categories_integrate_vc' );
function motor_product_categories_integrate_vc () {
	vc_map( array(
		'name' => esc_html__( 'Product categories', 'motor' ),
		'base' => 'motor_product_categories',
		'icon' => get_template_directory_uri() . "/img/vc_motor.png",
		'category' => esc_html__( 'Motor', 'motor' ),
		'description' => esc_html__( 'Display product categories loop', 'motor' ),
		'params' => array(
			array(
				'type' => 'autocomplete',
				'heading' => esc_html__( 'Categories', 'motor' ),
				'param_name' => 'ids',
				'settings' => array(
					'multiple' => true,
					'sortable' => true,
				),
				'save_always' => true,
				'description' => esc_html__( 'To choose custom ordering please set Order by to the Custom OR you can also leave empty this field and show product categories automatically by Order by field', 'motor' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Number', 'motor' ),
				'param_name' => 'number',
				'description' => esc_html__( 'The `number` field is used to display the number of products.', 'motor' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order by', 'motor' ),
				'param_name' => 'orderby',
				'value' => array(
					esc_html__( 'Custom', 'motor' ) => 'include',
					esc_html__( 'ID', 'motor' ) => 'ID',
					esc_html__( 'Name', 'motor' ) => 'name',
					esc_html__( 'Count', 'motor' ) => 'count',
					esc_html__( 'Slug', 'motor' ) => 'slug',
					esc_html__( 'Description', 'motor' ) => 'description',
				),
				'save_always' => true,
				'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'motor' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
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
				'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'motor' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Number', 'motor' ),
				'param_name' => 'hide_empty',
				'description' => esc_html__( 'Hide empty', 'motor' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Type', 'motor' ),
				'param_name' => 'type',
				'value' => array(
					'',
					esc_html__( 'Carousel', 'motor' ) => 'carousel',
					esc_html__( 'Grid', 'motor' ) => 'grid',
				),
				'std' => 'carousel'
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
add_filter( 'vc_autocomplete_motor_product_categories_ids_callback', 'motor_productCategoryCategoryAutocompleteSuggester', 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_motor_product_categories_ids_render', 'motor_productCategoryCategoryRenderByIdExact', 10, 1 ); // Render exact category by id. Must return an array (label,value)


function motor_productCategoryCategoryAutocompleteSuggester( $query, $slug = false ) {
	global $wpdb;
	$cat_id = (int) $query;
	$query = trim( $query );
	$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = 'product_cat' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )", $cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );

	$result = array();
	if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
		foreach ( $post_meta_infos as $value ) {
			$data = array();
			$data['value'] = $slug ? $value['slug'] : $value['id'];
			$data['label'] = esc_html__( 'Id', 'motor' ) . ': ' . $value['id'] . ( ( strlen( $value['name'] ) > 0 ) ? ' - ' . esc_html__( 'Name', 'motor' ) . ': ' . $value['name'] : '' ) . ( ( strlen( $value['slug'] ) > 0 ) ? ' - ' . esc_html__( 'Slug', 'motor' ) . ': ' . $value['slug'] : '' );
			$result[] = $data;
		}
	}

	return $result;
}


function motor_productCategoryCategoryRenderByIdExact( $query ) {
	$query = $query['value'];
	$cat_id = (int) $query;
	$term = get_term( $cat_id, 'product_cat' );

	return motor_productCategoryTermOutput( $term );
}


function motor_productCategoryTermOutput( $term ) {
	$term_slug = $term->slug;
	$term_title = $term->name;
	$term_id = $term->term_id;

	$term_slug_display = '';
	if ( ! empty( $term_slug ) ) {
		$term_slug_display = ' - ' . esc_html__( 'Sku', 'motor' ) . ': ' . $term_slug;
	}

	$term_title_display = '';
	if ( ! empty( $term_title ) ) {
		$term_title_display = ' - ' . esc_html__( 'Title', 'motor' ) . ': ' . $term_title;
	}

	$term_id_display = esc_html__( 'Id', 'motor' ) . ': ' . $term_id;

	$data = array();
	$data['value'] = $term_id;
	$data['label'] = $term_id_display . $term_title_display . $term_slug_display;

	return ! empty( $data ) ? $data : false;
}



class WPBakeryShortCode_motor_product_categories extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {

		$css = '';
		extract( shortcode_atts( array (
			'number' => 0,
			'orderby' => 'name',
			'order' => 'DESC',
			'hide_empty' => false,
			'ids' => '',
			'type' => 'carousel',
			'css' => ''
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

		wp_enqueue_script( 'motor_mThumbnailScroller', get_template_directory_uri( ).'/js/jquery.mThumbnailScroller.js' , array( 'jquery' ), false, true );

		ob_start();

		$include_ids = explode( ', ', $ids );

		$product_categories = get_terms( 'product_cat', array(
			'number' => $number,
			'order' => $order,
			'orderby' => $orderby,
			'hide_empty' => $hide_empty,
			'include' => $include_ids
		) );
		if ( $product_categories ) :
			?>
			<div class="motor_product_categories<?php  echo ' motor_product_categories_'.$type; ?><?php echo esc_attr( $css_class ); ?>">
				<div class="motor_product_categories_list">
					<?php foreach ($product_categories as $category) : ?>
						<div class="motor_product_categories_item">
							<a href="<?php echo get_term_link($category->term_id); ?>">
								<span class="frontcategs-img">
									<?php woocommerce_subcategory_thumbnail( $category ); ?>
								</span>
								<p><?php echo esc_attr($category->name); ?></p>
							</a>
						</div>&nbsp;
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		endif;

		$output = ob_get_clean();

		return $output;
	}
}