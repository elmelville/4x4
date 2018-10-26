<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php esc_html__( 'This product is currently out of stock and unavailable.', 'motor' ); ?></p>
	<?php else : ?>


		<dl class="prod-price-values">
			<dt><?php echo esc_html__('Price', 'motor'); ?></dt>
			<dd><?php woocommerce_template_single_price(); ?></dd>
			<?php if ( comments_open() ) : ?>
				<dt><?php echo intval($product->get_review_count()); ?></span> <?php echo _n( 'review', 'reviews', $product->get_review_count(), 'motor' ); ?></dt>
				<dd>
					<p data-rating="<?php echo round($product->get_average_rating()); ?>" class="prod-rating">
						<i class="fa fa-star-o" title="5"></i><i class="fa fa-star-o" title="4"></i><i class="fa fa-star-o" title="3"></i><i class="fa fa-star-o" title="2"></i><i class="fa fa-star-o" title="1"></i>
					</p>
				</dd>
			<?php endif; ?>
			<dt><?php echo esc_html__('Quantity', 'motor'); ?></dt>
			<dd>
				<?php if ( ! $product->is_sold_individually() ) : ?>
					<?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
				<?php endif; ?>
			</dd>
		</dl>

		<table class="variations" cellspacing="0">
			<tbody>
			<?php foreach ( $attributes as $attribute_name => $options ) : ?>
				<tr>
					<td class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>
					<td class="value">
						<?php
						$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
						wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
						echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'motor' ) . '</a>' ) : '';
						?>
					</td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap">
			<?php
				/**
				 * woocommerce_before_single_variation Hook.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );


				woocommerce_single_variation();
				
				woocommerce_single_variation_add_to_cart_button();

				if ( class_exists( 'YITH_WCWL' ) ) {
					echo do_shortcode('[yith_wcwl_add_to_wishlist]');
				}
				
				motor_show_compare_btn('', 'prod-compare');



				/**
				 * woocommerce_after_single_variation Hook.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
