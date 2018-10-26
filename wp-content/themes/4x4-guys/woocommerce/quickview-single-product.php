<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="cont maincont quick-view-modal woocommerce">

	<?php
	/**
	 * woocommerce_before_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 */
	do_action( 'woocommerce_before_main_content' );
	?>

	<?php while ( have_posts() ) : the_post(); ?>


		<?php
		global $motor_options, $product;

		include( trailingslashit( get_template_directory() ) . 'inc/get-options.php' );

		/*if (function_exists('vp_metabox')) {
			$product_description = vp_metabox('motor_meta_product.product_description');
		}*/
		$editor = get_post_meta($product->get_id(), 'motor_product_editor', true);
		if (!empty($editor) && $editor == 'custom') {
			$product_description = get_post_meta($product->get_id(), 'motor_product_description', true);
		}

		$catalog_productpage = 'type_1';
		if (!empty($motor_options['catalog_prodtype'])) {
			$catalog_productpage = $motor_options['catalog_prodtype'];
		}
		?>

		<article id="product-<?php the_ID(); ?>">

			<?php
			if ( post_password_required() ) {
				echo get_the_password_form();
				return;
			}
			?>

			<!-- Product - start -->
			<div <?php post_class('prod product'); ?>>

				<!-- Product Slider - start -->
				<div class="prod-slider-wrap">
					<?php woocommerce_show_product_images(); ?>
				</div>
				<!-- Product Slider - end -->

				<!-- Product Content - start -->
				<div class="prod-cont">
					<?php
					/**
					 * woocommerce_before_single_product hook.
					 *
					 * @hooked wc_print_notices - 10
					 */
					do_action( 'woocommerce_before_single_product' );
					?>

					<a href="<?php the_permalink(); ?>">
						<?php woocommerce_template_single_title(); ?>
					</a>

					<?php if (
						(!empty($editor) && $editor == 'custom' && !empty($product_description)) ||
						(!empty($editor) && $editor == 'default' && get_the_excerpt())
					) : ?>
						<div class="prod-desc">
							<p class="prod-desc-ttl"><span><?php echo esc_html__('Description', 'motor'); ?></span></p>
							<?php woocommerce_template_single_excerpt(); ?>
							<?php woocommerce_template_single_sharing(); ?>

							<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
							<?php do_action( 'woocommerce_single_product_summary' ); ?>
							<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
							<?php
							do_action( 'woocommerce_product_meta_start' );
							do_action( 'woocommerce_product_meta_end' );
							do_action( 'woocommerce_share' );
							?>
						</div>
					<?php endif; ?>

					<?php woocommerce_template_single_meta(); ?>

					<?php if ($catalog_productpage == 'type_2' && $product->is_type('variable')) : ?>
						<div class="prod-info2">
							<?php woocommerce_variable_add_to_cart_2(); ?>
						</div>
					<?php elseif ($catalog_productpage == 'type_2' || $product->is_type('grouped')) : ?>
						<div class="prod-info2">
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
							</dl>
							<?php woocommerce_template_single_add_to_cart(); ?>
						</div>
					<?php else: ?>

						<?php
						if ( $product && $product->is_type('variable') ) :
							woocommerce_template_single_add_to_cart();
						else:
							?>
							<div class="prod-info">
								<div class="prod-price-wrap">
									<p><?php echo esc_html__('Price', 'motor'); ?></p>
									<?php woocommerce_template_single_price(); ?>
								</div>
								<div class="prod-qnt-wrap">
									<p><?php echo esc_html__('Quantity', 'motor'); ?></p>
									<p class="qnt-wrap prod-qnt">
										<a href="#" class="qnt-minus prod-minus"><?php echo esc_html__('-', 'motor'); ?></a>
										<input
											data-qnt-price="<?php echo wc_get_price_to_display($product); ?>"
											data-decimals="<?php echo wc_get_price_decimals(); ?>"
											data-thousand_separator="<?php echo wc_get_price_thousand_separator(); ?>"
											data-decimal_separator="<?php echo wc_get_price_decimal_separator(); ?>"
											data-currency="<?php echo get_woocommerce_currency_symbol(); ?>"
											data-price_format="<?php echo get_woocommerce_price_format(); ?>"
											type="text"
											value="1"
										>
										<a href="#" class="qnt-plus prod-plus"><?php echo esc_html__('+', 'motor'); ?></a>
									</p>
								</div>
								<div class="prod-total-wrap">
									<p><?php echo esc_html__('Total', 'motor'); ?></p>
									<?php woocommerce_template_single_price(); ?>
								</div>
								<?php /*if (!empty($product_shipping)) : ?>
									<div class="prod-shipping-wrap">
										<p><?php echo esc_html__('Shipping', 'motor'); ?></p>
										<p class="prod-shipping"><?php echo esc_attr($product_shipping); ?></p>
									</div>
								<?php endif;*/ ?>
							</div>
							<div class="prod-actions">
								<?php if ( comments_open() ) { ?>
								<div class="prod-rating-wrap">
									<p data-rating="<?php echo round($product->get_average_rating()); ?>" class="prod-rating">
										<i class="fa fa-star-o" title="5"></i><i class="fa fa-star-o" title="4"></i><i class="fa fa-star-o" title="3"></i><i class="fa fa-star-o" title="2"></i><i class="fa fa-star-o" title="1"></i>
									</p>
									<p><span class="prod-rating-count"><?php echo intval($product->get_review_count()); ?></span> <?php echo _n( 'review', 'reviews', $product->get_review_count(), 'motor' ); ?></p>
								</div>
								<?php } ?>

								<?php
								motor_show_compare_btn('', 'prod-compare');
								if ( class_exists( 'YITH_WCWL' ) ) {
									echo do_shortcode('[yith_wcwl_add_to_wishlist]');
								}
								?>
								<div class="prod-add">
									<?php woocommerce_template_single_add_to_cart(); ?>
								</div>
							</div>
						<?php endif; ?>


					<?php endif; ?>

				</div>
				<!-- Product Content - end -->

				<?php motor_product_badge($post->ID, 'prod-badge'); ?>

			</div>
			<!-- Product - end -->

			<?php
			if ($motor_options['catalog_qviewtabs'] == 'show') {
				// Product Tabs
				woocommerce_output_product_data_tabs();
			}
			?>

		</article>

		<?php do_action( 'woocommerce_after_single_product' ); ?>




	<?php endwhile; // end of the loop. ?>

	<?php
	/**
	 * woocommerce_after_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );
	?>
</div>
