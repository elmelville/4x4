<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<div class="cart-subtotal">
		<p class="cart-totals-ttl"><?php esc_html_e( 'Subtotal', 'motor' ); ?></p>
		<p class="cart-totals-val" data-title="<?php esc_html_e( 'Subtotal', 'motor' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></p>
	</div>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<p><?php wc_cart_totals_coupon_label( $coupon ); ?></p>
			<p data-title="<?php wc_cart_totals_coupon_label( $coupon ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></p>
		</div>
	<?php endforeach; ?>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

		<div class="shipping">
		<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

		<table class="shipping-cart-methods" id="shipping-cart-methods">
		<?php wc_cart_totals_shipping_html(); ?>
		</table>

		<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
		</div>

	<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

		<div class="shipping">
			<p><?php esc_html_e( 'Shipping', 'motor' ); ?></p>
			<p data-title="<?php esc_html_e( 'Shipping', 'motor' ); ?>"><?php woocommerce_shipping_calculator(); ?></p>
		</div>

	<?php endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="fee">
			<p><?php echo esc_html( $fee->name ); ?></p>
			<p data-title="<?php echo esc_html( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></p>
		</div>
	<?php endforeach; ?>

	<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :
		$taxable_address = WC()->customer->get_taxable_address();
		$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
				? sprintf( ' <small>(' . esc_html__( 'estimated for %s', 'motor' ) . ')</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
				: '';

		if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
				<div class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
					<p><?php echo esc_html( $tax->label ) . $estimated_text; ?></p>
					<p data-title="<?php echo esc_html( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></p>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="tax-total">
				<p><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></p>
				<p data-title="<?php echo esc_html( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></p>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

	<div class="order-total">
		<p class="cart-totals-ttl"><?php esc_html_e( 'Total', 'motor' ); ?></p>
		<p class="cart-totals-val" data-title="<?php esc_html_e( 'Total', 'motor' ); ?>"><?php wc_cart_totals_order_total_html(); ?></p>
	</div>

	<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
