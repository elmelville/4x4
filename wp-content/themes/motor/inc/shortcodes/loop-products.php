<?php
// Product Price

global $product, $motor_options, $post;
?>
	<article class="prod-i special<?php if ($int_key == 0) echo ' special-first'; ?>">

		<?php
		/**
		 * woocommerce_before_shop_loop_item hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );
		?>

		<div class="prod-i-actions">
			<p class="prod-i-quick-view">
				<a title="<?php esc_html_e('Quick View', 'motor'); ?>" href="#" class="quick-view" data-url="<?php echo admin_url('admin-ajax.php'); ?>" data-file="woocommerce/quickview-single-product.php" data-id="<?php echo get_the_ID(); ?>"></a>
				<i class="fa fa-spinner fa-pulse quick-view-loading"></i>
			</p>

			<?php
			if ( class_exists( 'YITH_WCWL' ) ) {
				echo do_shortcode('[yith_wcwl_add_to_wishlist]');
			}
			?>

			<?php motor_show_compare_btn(); ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="prod-i-link">
			<p class="prod-i-img">
				<?php if ($int_key == 0) : ?>
					<?php the_post_thumbnail('shop_single'); ?>
				<?php else: ?>
					<?php the_post_thumbnail('shop_catalog'); ?>
				<?php endif; ?>
			</p>
			<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
			<?php
			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			?>
			<h3><span><?php the_title(); ?></span></h3>
			<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
		</a>
		<p class="prod-i-info">
			<?php
			$product_categories = get_the_terms( get_the_ID(), 'product_cat' );
			if (!empty($product_categories)) :
				?>
				<span class="prod-i-categ">
			<?php
			foreach ($product_categories as $key=>$product_category) :
				?>
				<a href="<?php echo get_term_link($product_category); ?>">
					<?php echo esc_attr($product_category->name); ?>
				</a>
				<?php break; ?>
			<?php endforeach; ?>
			</span>
			<?php endif; ?>

			<?php if ( $price_html = $product->get_price_html() ) : ?>
				<span class="prod-i-price"><?php echo $price_html; ?></span>
			<?php endif; ?>

			<?php if ($motor_options['catalog_request'] == 'yes') : ?>
				<a href="#" class="button request-form-btn"><?php  esc_html_e('Request', 'motor'); ?></a>
			<?php else : ?>
				<?php woocommerce_template_loop_add_to_cart(array('button_text'=>esc_html__('+ Add to cart', 'motor'))); ?>
			<?php endif; ?>

		</p>

		<?php
		/**
		 * woocommerce_after_shop_loop_item hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item' );
		?>

		<?php motor_product_badge(get_the_ID(), 'prod-i-badge'); ?>

	</article>
<?php
$int_key++;
?>