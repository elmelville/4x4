<?php

// Remove Woocomerce Actions
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

remove_action( 'woocommerce_before_shop_loop', 'wccm_register_add_compare_button_hook' );

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
remove_action( 'woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_single_product_summary', 'wccm_add_single_product_compare_buttton', 35 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

// Compare List
remove_action( 'woocommerce_before_shop_loop', 'wccm_render_catalog_compare_info' );




// All Categories
function motor_hierarchical_category_tree( $cat ) {
	$next = get_categories('hide_empty=1&taxonomy=product_cat&parent=' . $cat);
	if( $next ) :
		foreach( $next as $cat ) :
			$childs = get_categories('hide_empty=1&taxonomy=product_cat&parent=' . $cat->term_id);
			echo '<ul><li'.(!empty($childs) ? ' class="has-childs"' : '').'><a href="' . get_term_link( $cat->term_id ) . '">' . esc_attr($cat->name) . '</a>';
			motor_hierarchical_category_tree( $cat->term_id );
			echo '</li></ul>';
		endforeach;
	endif;
}



// View Mode
/*if(session_id() == '') {
	session_start();
}
if (isset($_GET['view_mode']) && $_GET['view_mode'] == 'gallery') {
	$_SESSION['view_mode'] = 'gallery';
} elseif (isset($_GET['view_mode']) && $_GET['view_mode'] == 'list') {
	$_SESSION['view_mode'] = 'list';
}*/



// Ensure cart contents update when products are added to the cart via AJAX
add_filter( 'woocommerce_add_to_cart_fragments', 'motor_header_add_to_cart_fragment_personal' );
function motor_header_add_to_cart_fragment_personal( $fragments ) {
	ob_start();
	?>
	<li class="header-personal-cart">
		<a href="<?php echo esc_url(WC()->cart->get_cart_url()); ?>"><?php echo esc_html__('Shopping Cart', 'motor'); ?> <span><?php echo WC()->cart->get_cart_contents_count(); ?></span></a>
	</li>
	<?php
	$fragments['li.header-personal-cart'] = ob_get_clean();
	return $fragments;
}



if ( ! function_exists( 'motor_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 * @return array            Fragments to refresh via AJAX
	 */
	function motor_cart_link_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		motor_cart_link();
		$fragments['div.header-cart-inner'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'motor_cart_link_fragment' );

if ( ! function_exists( 'motor_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function motor_cart_link() {
		?>
		<div class="header-cart-inner">
			<p class="header-cart-count">
				<img src="/wp-content/themes/4x4-guys/img/cart.png" alt="">
				<span><?php echo WC()->cart->get_cart_contents_count()?></span>
			</p>
			<p class="header-cart-summ"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></p>
		</div>
		<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-cart-link"></a>
		<?php
	}
}



// Display Price For Variable Product With Same Variations Prices
function motor_woocommerce_available_variation ($value, $object = null, $variation = null) {
	if ($value['price_html'] == '') {
		$value['price_html'] = '<span class="price">' . $variation->get_price_html() . '</span>';
	}
	return $value;
}
add_filter('woocommerce_available_variation', 'motor_woocommerce_available_variation', 10, 3);



// force to make is_cart() returns true, to make right calculations on class-wc-cart.php (WC_Cart::calculate_totals())
// this define fix a bug that not show Shipping calculator when is WAC ajax request
/*add_action('init', 'motor_wac_init');
function motor_wac_init() {
	if ( !empty($_POST['is_cart_ajax']) && !defined( 'WOOCOMMERCE_CART' ) ) {
		define( 'WOOCOMMERCE_CART', true );
	}
}*/







function motor_product_badge($product_id, $class) {
	$product_badges = get_the_terms( $product_id, 'product_badges' );
	if (!empty($product_badges)) {
		foreach ($product_badges as $badge) {
			$badge_color = get_option("taxonomy_badges_".$badge->term_id);
			$badge->color = $badge_color['color'];
		}
	}

	if (!empty($product_badges)) : ?>
		<p<?php if (!empty($class)) echo ' class="'.$class.'"'; ?>>
			<?php foreach ($product_badges as $badge) : ?>
				<span<?php if (!empty($badge->color)) echo ' style="background-color: '.$badge->color.';"'; ?>><?php echo $badge->name; ?></span>
			<?php endforeach; ?>
		</p>
	<?php endif;
}



if ( ! function_exists( 'woocommerce_variable_add_to_cart_2' ) ) {

	/**
	 * Output the variable product add to cart area.
	 *
	 * @subpackage	Product
	 */
	function woocommerce_variable_add_to_cart_2() {
		global $product;

		// Enqueue variation scripts
		wp_enqueue_script( 'wc-add-to-cart-variation' );

		// Get Available variations?
		$get_variations = sizeof( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

		// Load the template
		wc_get_template( 'single-product/add-to-cart/variable2.php', array(
			'available_variations' => $get_variations ? $product->get_available_variations() : false,
			'attributes'           => $product->get_variation_attributes(),
			'selected_attributes'  => $product->get_default_attributes(),
		) );
	}
}


if ( ! function_exists( 'motor_show_compare_btn' ) ) {
	function motor_show_compare_btn($id = '', $class = '') {
		if (!defined( 'WCCM_VERISON' )) {
			return '';
		}

		global $motor_options;

		if (empty($id)) {
			$id = get_the_ID();
		}
		if (empty($class)) {
			$class = 'prod-li-compare';
		}
		if (defined( 'WCCM_VERISON' )) : ?>
			<p class="<?php echo $class; ?>">
				<?php
				if ( in_array( $id, wccm_get_compare_list() ) ) {
					$url = wccm_get_compare_link( $id, 'add-to-list' );
					$remove_url = wccm_get_compare_link( $id, 'remove-from-list' );
					$text = esc_html__( 'View compare', 'motor' );
					echo '<a data-id="'.$id.'" data-text="'.esc_html__( 'Compare', 'allstore' ). '" data-url="'. esc_url( $url ). '" title="'.$text.'" href=".'. wccm_get_compare_page_link( wccm_get_compare_list() ). '." class="prod-li-compare-btn prod-li-compare-added">'.esc_html( $text ).'</a>';
				} else {
					$url = wccm_get_compare_link( $id, 'add-to-list' );
					$text = esc_html__( 'Compare', 'motor' );
					echo '<a data-id="'.intval($id).'" data-text="'.esc_html__( 'View Compare', 'motor' ). '" data-url="'.esc_url($motor_options['compare']['url']).'" data-added="'.esc_html__('Product Added!', 'motor').'" title="'.esc_html__('Compare', 'motor').'" href="'.esc_url( $url ).'" class="prod-li-compare-btn">'.esc_html( $text ).'</a>';
				}
				?>
				<i class="fa fa-spinner fa-pulse prod-li-compare-loading"></i>
			</p>
		<?php endif;
	}
}


if (!function_exists('motor_list_info_button')) {
	function motor_list_info_button() {
		global $product;
		$catalog_listinfo = motor_option('catalog_listinfo');
		if (in_array($catalog_listinfo, array('both', 'info', 'desc'))) :
			?>
			<p class="prod-li-id prod-li-infobtn"><?php esc_html_e('Information', 'motor'); ?> <i class="fa fa-angle-down"></i></p>
			<?php
		elseif ($catalog_listinfo == 'sku') :
			$sku = $product->get_sku();
			if (!empty($sku)) : ?>
				<p class="prod-li-id"><?php esc_html_e('id', 'motor'); ?> <?php echo $sku; ?></p>
				<?php
			endif;
		else :
			return '';
		endif;
	}
}


if (!function_exists('motor_list_info')) {
	function motor_list_info() {
		global $product;
		$catalog_listinfo = motor_option('catalog_listinfo');
		$catalog_listprops = motor_option('catalog_listprops');

		if ($catalog_listinfo == 'hide') {
			return true;
		}

		echo '<div class="page-styling prod-li-informations">';

		if (in_array($catalog_listinfo, array('desc', 'both'))) {
			echo wpautop(get_the_excerpt($product->get_id()));
		}

		if (in_array($catalog_listinfo, array('info', 'both'))) {
			$tag_count = sizeof( get_the_terms( $product->get_id(), 'product_tag' ) );
			$product_brands = get_the_terms( $product->get_id(), 'product_brands' );
			$product_parts = motor_parts_list($product->get_id());

			$props_count = 0;
			$props_count_max = 7;
			if (!empty($catalog_listprops)) {
				$props_count_max = $catalog_listprops;
			}
			if ($props_count < $props_count_max && $props_count_max !== 0) :
				?>
				<dl class="prod-li-props<?php if (!empty($product_parts)) echo ' prod-li-props-hasparts'; ?>">

					<?php if ( !empty($product_parts) && $props_count < $props_count_max ) : ?>
						<dt class="prod-li-props-parts-label"><?php esc_html_e( 'Parts:', 'motor' ); ?></dt> <dd class="prod-li-props-parts-value"><?php
							foreach ($product_parts as $key1=>$part) {
								foreach ($part as $key2=>$part_item) {
									echo '<a href="'.$part_item['link'].'">'.$part_item['name'].'</a>';
									if (($key2+1) < count($part)) {
										echo ' / ';
									} else {
										echo '<br>';
									}
								}
								if (($key1+1) < count($product_parts)) {
									echo ', ';
								}
							}
							?></dd>
						<?php $props_count++; ?>
					<?php endif; ?>

					<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) && $props_count < $props_count_max ) : ?>

						<dt class="sku_wrapper"><?php esc_html_e( 'SKU:', 'motor' ); ?></dt> <dd class="sku"><?php echo ( $sku = $product->get_sku() ) ? esc_attr($sku) : esc_html__( 'N/A', 'motor' ); ?></dd>

						<?php
						$props_count++;
					endif; ?>

					<?php if ( !empty($product_brands) && $props_count < $props_count_max ) : ?>

						<dt><?php esc_html_e( 'Brand:', 'motor' ); ?></dt> <dd><?php
							foreach ($product_brands as $key=>$brand) {
								echo '<a href="'.get_term_link($brand->term_id).'">'.$brand->name.'</a>';
								if (($key+1) < count($product_brands)) {
									echo ', ';
								}
							}
							?></dd>
						<?php $props_count++; ?>

					<?php endif; ?>

					<?php
					if ($props_count < $props_count_max && $props_count < $props_count_max) {
						$tags = wc_get_product_tag_list( $product->get_id(), ', ', '<dt>' . _n( 'Tag:', 'Tags:', $tag_count, 'motor' ) . '</dt> <dd>', '</dd>' );
						if ($tag_count > 0 && !empty($tags)) {
							echo $tags;
							$props_count++;
						}
					}
					?>

					<?php if ( $product->has_weight() && $props_count < $props_count_max ) : ?>
						<dt><?php esc_html_e( 'Weight:', 'motor' ) ?></dt>
						<dd><?php echo esc_html( wc_format_weight( $product->get_weight() ) ); ?></dd>
						<?php $props_count++; ?>
					<?php endif; ?>

					<?php if ( $product->has_dimensions() && $props_count < $props_count_max ) : ?>
						<dt><?php esc_html_e( 'Dimensions:', 'motor' ) ?></dt>
						<dd><?php echo esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) ); ?></dd>
						<?php $props_count++; ?>
					<?php endif; ?>

					<?php
					if ($props_count < $props_count_max) : ?>
						<?php
						$attributes = $product->get_attributes();

						if (!empty($attributes)) :
							foreach ( $attributes as $attribute ) :

								if ($props_count >= $props_count_max) {
									break;
								}

								if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
									continue;
								}
								?>
								<dt><?php echo wc_attribute_label( $attribute->get_name() ); ?>:</dt>
								<dd><?php
									$values = array();

									if ( $attribute->is_taxonomy() ) {
										$attribute_taxonomy = $attribute->get_taxonomy_object();
										$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

										foreach ( $attribute_values as $attribute_value ) {
											$value_name = esc_html( $attribute_value->name );

											if ( $attribute_taxonomy->attribute_public ) {
												$values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
											} else {
												$values[] = $value_name;
											}
										}
									} else {
										$values = $attribute->get_options();

										foreach ( $values as &$value ) {
											$value = make_clickable( esc_html( $value ) );
										}
									}

									echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
									?></dd>
								<?php
								$props_count++;
							endforeach; ?>
						<?php endif; ?>
					<?php endif; ?>

				</dl>
				<?php
			endif;
		}

		echo '</div>';
	}
}


// Product Parts List
function motor_parts_list ($id) {
	$return = array();
	$terms = wc_get_product_terms( $id, 'product_parts', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
	foreach ($terms as $term) {
		$return[$term->term_id][] = array(
			'name' => $term->name,
			'link' => get_term_link( $term ),
		);
		$ancestors = get_ancestors( $term->term_id, 'product_parts' );
		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, 'product_parts' );
			if ( $ancestor ) {
				$return[$term->term_id][] = array(
					'name' => $ancestor->name,
					'link' => get_term_link( $ancestor ),
				);
			}
		}
		$return[$term->term_id] = array_reverse($return[$term->term_id]);
	}
	return $return;
}
