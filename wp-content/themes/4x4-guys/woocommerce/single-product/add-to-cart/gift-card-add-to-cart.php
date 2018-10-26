<?php
/**
 * Gift Card product add to cart
 *
 * @author  Yithemes
 * @package YITH WooCommerce Gift Cards
 *
 */
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product->is_purchasable () ) {
	return;
}
$product_id = yit_get_product_id ( $product );

?>
<div class="prod-info">
<div id="gift-card-single-price" class="prod-price-wrap">
	<p>Price</p>
	<?php echo woocommerce_template_single_price(); ?>
</div>
<div class="prod-qnt-wrap">
	<p>Quantity</p>
	<?php if ( ! $product->is_sold_individually () ) : ?>
		<?php woocommerce_quantity_input ( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount ( $_POST['quantity'] ) : 1 ) ); ?>
	<?php endif; ?>
</div>
<div id="gift-card-quantity" class="prod-total-wrap">
	<p>Total</p>
	<?php
	if ( isset( $_POST['quantity'] ) ) {
		woocommerce_template_single_price() * intval( $_POST['quantity'] );
	} else {
		woocommerce_template_single_price();
	}
	?>
</div>
</div>

<div class="prod-actions gift_card">
	<?php
	if ( class_exists( 'YITH_WCWL' ) ) {
		echo do_shortcode('[yith_wcwl_add_to_wishlist]');
	}
	?>
	<div class="prod-add">
		<div class="gift_card_template_button variations_button">
			<button type="submit"
		        	class="single_add_to_cart_button
				               gift_card_add_to_cart_button button alt"><?php echo esc_html ( $product->single_add_to_cart_text () ); ?></button>
			<input type="hidden" name="add-to-cart" value="<?php echo absint ( $product_id ); ?>" />
			<input type="hidden" name="product_id" value="<?php echo absint ( $product_id ); ?>" />
		</div>
	</div>
</div>
