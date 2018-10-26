<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $motor_options;
$cart_template = $motor_options['catalog_cart'];
?>
<div class="cont maincont">
	<?php do_action( 'woocommerce_before_cart' ); ?>

	<h1><span><?php the_title(); ?></span></h1>
	<span class="maincont-line1"></span>
	<span class="maincont-line2"></span>

	<?php get_template_part('template-parts/personal-menu'); ?>

	<p class="section-count"><?php echo WC()->cart->get_cart_contents_count(); ?> <?php echo _n( 'ITEM', 'ITEMS', WC()->cart->get_cart_contents_count(), 'motor' ); ?></p>

	<?php wc_print_notices(); ?>

	<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

		<?php do_action( 'woocommerce_before_cart_table' ); ?>


		<?php // Cart Template: Modern ?>
		<?php if ($cart_template == 'modern') : ?>


			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<!-- Cart Items - start -->
			<div class="prod-litems section-list cart-list">
				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					$sku = $_product->get_sku();

					global $product;
					if( function_exists( 'wc_get_product' ) ) {
						$product = wc_get_product( $product_id );
					}

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> sectls prod-li">

							<div class="prod-li-inner">
							
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
							if ( ! $_product->is_visible() ) {
								echo wp_kses_post($thumbnail);
							} else {
								printf( '<a class="prod-li-img" href="%s">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail );
							}
							?>
							<div class="prod-li-cont">
								<div class="prod-li-ttl-wrap">
									<p>
										<?php
										$product_categories = get_the_terms( $product_id, 'product_cat' );
										if (!empty($product_categories)) :
											foreach ($product_categories as $key=>$product_category) :
												?>
												<a href="<?php echo get_term_link($product_category); ?>"><?php echo esc_attr($product_category->name); ?></a><?php if ($key+1 < count($product_categories)) echo ',&nbsp;'; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</p>
									<h3>
										<?php
										if ( ! $product_permalink ) {
											echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
										} else {
											echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
										}

										// Meta data
										echo WC()->cart->get_item_data( $cart_item );

										// Backorder notification
										if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
											echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'motor' ) . '</p>';
										}
										?>
									</h3>
								</div>
								<div class="prod-li-price-wrap">
									<p><?php esc_html_e( 'Price', 'motor' ); ?></p>
									<p class="prod-li-price">
										<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										?>
									</p>
								</div>
								<div class="prod-li-qnt-wrap">
									<p><?php esc_html_e( 'Quantity', 'motor' ); ?></p>
									<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'  => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
										'max_value'   => $_product->get_max_purchase_quantity(),
											'min_value'   => '0',
											'price' => wc_get_price_to_display($_product),
										), $_product, false );
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
									?>
								</div>
								<div class="prod-li-total-wrap">
									<p><?php esc_html_e( 'Order amount', 'motor' ); ?></p>
									<p class="prod-li-total">
										<?php
										echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
										?>
									</p>
								</div>
							</div>
							<div class="prod-li-info<?php if (empty($sku)) echo ' no-sku'; ?><?php if (!comments_open()) echo ' no-rating'; ?>">
								<?php if ( comments_open($product_id) ) { ?>
								<div class="prod-li-rating-wrap">
									<p class="prod-li-rating" data-rating="<?php echo round($_product->get_average_rating()); ?>">
										<i class="fa fa-star-o" title="5"></i><i class="fa fa-star-o" title="4"></i><i class="fa fa-star-o" title="3"></i><i class="fa fa-star-o" title="2"></i><i class="fa fa-star-o" title="1"></i>
									</p>
									<p><span class="prod-li-rating-count"><?php echo intval($_product->get_review_count()); ?></span> <?php echo _n( 'review', 'reviews', $product->get_review_count(), 'motor' ); ?></p>
								</div>
								<?php } ?>

								<?php motor_list_info_button(); ?>

								<p class="prod-li-add">
									<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" title="%s" data-product_id="%s" data-product_sku="%s">'.esc_html__('Remove from cart', 'motor').'</a>',
										esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
										esc_html__( 'Remove this item', 'motor' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									), $cart_item_key );
									?>
								</p>

								<p class="prod-li-quick-view">
									<a href="#" class="quick-view" data-url="<?php echo admin_url('admin-ajax.php'); ?>" data-file="woocommerce/quickview-single-product.php" data-id="<?php echo intval($product_id); ?>"></a>
									<i class="fa fa-spinner fa-pulse quick-view-loading"></i>
								</p>

								<?php
								if ( class_exists( 'YITH_WCWL' ) ) {
									echo do_shortcode('[yith_wcwl_add_to_wishlist product_id='.$product_id.']');
								}
								?>

								<?php motor_show_compare_btn($product_id); ?>

							</div>

							<?php motor_product_badge($product_id, 'prod-li-badge'); ?>

							</div>

							<?php motor_list_info(); ?>
	
						</div>
						<?php
					}
				}

				do_action( 'woocommerce_cart_contents' );
				?>


			</div>
			<!-- Cart Items - end -->

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>


			<?php // Cart Template: Classic ?>
		<?php else: ?>



			<table class="shop_table shop_table_responsive cart cart-table" cellspacing="0">
				<thead>
				<tr>
					<th class="product-remove">&nbsp;</th>
					<th class="product-thumbnail">&nbsp;</th>
					<th class="product-name"><?php esc_html_e( 'Product', 'motor' ); ?></th>
					<th class="product-price"><?php esc_html_e( 'Price', 'motor' ); ?></th>
					<th class="product-quantity"><?php esc_html_e( 'Quantity', 'motor' ); ?></th>
					<th class="product-subtotal"><?php esc_html_e( 'Total', 'motor' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

							<td class="product-remove">
								<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
									esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
									esc_html__( 'Remove this item', 'motor' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								), $cart_item_key );
								?>
							</td>

							<td class="product-thumbnail">
								<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $_product->is_visible() ) {
									echo wp_kses_post($thumbnail);
								} else {
									printf( '<a href="%s">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail );
								}
								?>
							</td>

							<td class="product-name" data-title="<?php esc_html_e( 'Product', 'motor' ); ?>">
								<?php
								if ( ! $_product->is_visible() ) {
									echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
								} else {
									echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $_product->get_title() ), $cart_item, $cart_item_key );
								}

								// Meta data
								echo WC()->cart->get_item_data( $cart_item );

								// Backorder notification
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'motor' ) . '</p>';
								}
								?>
							</td>

							<td class="product-price" data-title="<?php esc_html_e( 'Price', 'motor' ); ?>">
								<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								?>
							</td>

							<td class="product-quantity" data-title="<?php esc_html_e( 'Quantity', 'motor' ); ?>">
								<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									$product_quantity = woocommerce_quantity_input( array(
										'input_name'  => "cart[{$cart_item_key}][qty]",
										'input_value' => $cart_item['quantity'],
										'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
										'min_value'   => '0'
									), $_product, false );
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
								?>
							</td>

							<td class="product-subtotal" data-title="<?php esc_html_e( 'Total', 'motor' ); ?>">
								<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
								?>
							</td>
						</tr>
						<?php
					}
				}

				do_action( 'woocommerce_cart_contents' );
				?>
				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</tbody>
			</table>




		<?php endif; ?>


		<div class="cart-actions">

			<?php if ( wc_coupons_enabled() ) { ?>
				<div class="coupon">
					<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'motor' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'motor' ); ?>" />

					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>
			<?php } ?>

			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<div class="cart-collaterals" data-updating-text="<?php esc_html_e('Updating...', 'motor'); ?>">
				<?php do_action( 'woocommerce_cart_collaterals' ); ?>
			</div>

		</div>

		<?php wp_nonce_field( 'woocommerce-cart' ); ?>


		<?php do_action( 'woocommerce_after_cart_table' ); ?>

	</form>


	<?php do_action( 'woocommerce_after_cart' ); ?>

</div>


<?php woocommerce_cross_sell_display(); ?>
