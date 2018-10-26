<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
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
	exit;
}

global $product, $motor_options;

if ( ! $product->is_purchasable() ) {
	return;
}

$catalog_productpage = 'type_1';
if (!empty($motor_options['catalog_prodtype'])) {
	$catalog_productpage = $motor_options['catalog_prodtype'];
}
?>

<?php if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" method="post" enctype='multipart/form-data'>


	 	<?php
			/**
			 * @since 2.1.0.
			 */
			do_action( 'woocommerce_before_add_to_cart_button' );
?>

	 	<?php
		// Availability
	 	$availability      = $product->get_availability();
	 	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

	 	if ($catalog_productpage == 'type_2' && $availability['availability']) :
	 		echo "<p class='prod-info2-stock-label'>".esc_html__('In stock', 'motor')."</p>";
	 	endif;
	 	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
	 	?>
	 	
		<?php
		if ($catalog_productpage == 'type_2') :
			echo "<p class='prod-info2-qty-label'>".esc_html__('Quantity', 'motor')."</p>";
		endif;
		?>

	 	<?php
			/**
			 * @since 3.0.0.
			 */
			do_action( 'woocommerce_before_add_to_cart_quantity' );

	 			woocommerce_quantity_input( array(
	 				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
	 				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
	 				'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
	 			) );

			/**
			 * @since 3.0.0.
			 */
			do_action( 'woocommerce_after_add_to_cart_quantity' );
	 	?>


		<?php if ($motor_options['catalog_request'] == 'yes') : ?>
			<a href="#" class="button request-form-btn"><?php esc_html_e('Request', 'motor'); ?></a>
		<?php else : ?>
			<button value="<?php echo esc_attr( $product->get_id() ); ?>" name="add-to-cart" type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
		<?php endif; ?>


		<?php
			/**
			 * @since 2.1.0.
			 */
			do_action( 'woocommerce_after_add_to_cart_button' );
		?>


		<?php
		if ($catalog_productpage == 'type_2') :
			if ( class_exists( 'YITH_WCWL' ) ) {
				echo do_shortcode('[yith_wcwl_add_to_wishlist]');
			}
			
			motor_show_compare_btn('', 'prod-compare');
		endif;
		?>


	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php else: ?>
	<?php echo wc_get_stock_html( $product ); ?>
<?php endif; ?>
