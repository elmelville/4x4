<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $motor_options;

$cat_count = sizeof( get_the_terms( $product->get_id(), 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $product->get_id(), 'product_tag' ) );
$product_brands = get_the_terms( $product->get_id(), 'product_brands' );
$product_parts = motor_parts_list($product->get_id());

$props_count = 0;
$props_count_max = 8;
if (!empty($motor_options['catalog_prodprops'])) {
	$props_count_max = $motor_options['catalog_prodprops'];
}
if ($props_count < $props_count_max && $props_count_max !== 0) :
?>
<div class="product_meta prod-props<?php if (!empty($product_parts)) echo ' prod-props-hasparts'; ?>">
<dl>

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( !empty($product_parts) && $props_count < $props_count_max ) : ?>
		<dt class="prod-props-parts-label"><?php esc_html_e( 'Parts:', 'motor' ); ?></dt> <dd class="prod-props-parts-value"><?php
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

	<?php
	if ($props_count < $props_count_max) {
		$categories = wc_get_product_category_list( $product->get_id(), ', ', '<dt>' . _n( 'Category:', 'Categories:', $cat_count, 'motor' ) . '</dt> <dd>', '</dd>' );
		if ($cat_count > 0 && !empty($categories)) {
			echo $categories;
			$props_count++;
		}
	}
	?>

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

	<?php if ( $product->has_weight() && $props_count < $props_count_max ) : ?>
		<dt><?php esc_html_e( 'Weight:', 'motor' ) ?></dt>
		<dd class="product_weight"><?php echo esc_html( wc_format_weight( $product->get_weight() ) ); ?></dd>
		<?php $props_count++; ?>
	<?php endif; ?>

	<?php if ( $product->has_dimensions() && $props_count < $props_count_max ) : ?>
		<dt><?php esc_html_e( 'Dimensions:', 'motor' ) ?></dt>
		<dd class="product_dimensions"><?php echo esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) ); ?></dd>
		<?php $props_count++; ?>
	<?php endif; ?>

	<?php
	$all_props_showed = false;
	if ($props_count < $props_count_max) : ?>
		<?php
		$attributes = $product->get_attributes();

		if (!empty($attributes)) :
			$int_key = 0;
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
				if ($int_key + 1 == count($attributes)) {
					$all_props_showed = true;
				}
				$props_count++;
				$int_key++;
			endforeach; ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($props_count > 0 && !$all_props_showed) : ?>
		<dt><a id="prod-showprops" href="#"><?php esc_html_e('view all details', 'motor'); ?></a></dt>
		<dd></dd>
	<?php endif; ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</dl>
</div>
<?php
endif;