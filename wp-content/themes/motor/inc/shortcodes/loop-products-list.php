<?php
// Extra post classes
$classes = array();
$classes[] = 'prod-li sectls';

global $product, $post, $motor_options;

$sku = $product->get_sku();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<div class="prod-li-inner">
		
	<a href="<?php the_permalink(); ?>" class="prod-li-img">
		<?php echo woocommerce_get_product_thumbnail(); ?>
	</a>
	<div class="prod-li-cont">
		<?php
		/**
		 * woocommerce_before_shop_loop_item hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );
		?>
		<div class="prod-li-ttl-wrap">
			<p>
				<?php
				$product_categories = get_the_terms( get_the_ID(), 'product_cat' );
				if (!empty($product_categories)) :
					foreach ($product_categories as $key=>$product_category) :
						?>
					<a href="<?php echo get_term_link($product_category); ?>">
						<?php echo esc_attr($product_category->name); ?>
						</a><?php if ($key+1 < count($product_categories)) echo ',&nbsp;'; ?>
					<?php endforeach; ?>
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
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
		</div>
		<div class="prod-li-price-wrap">
			<p><?php echo esc_html__('Price', 'motor'); ?></p>
			<?php if ( $price_html = $product->get_price_html() ) : ?><p class="prod-li-price"><?php echo $price_html; ?></p><?php endif; ?>
		</div>
		<div class="prod-li-qnt-wrap">
			<p><?php echo esc_html__('Quantity', 'motor'); ?></p>
			<p class="qnt-wrap prod-li-qnt">
				<a href="#" class="qnt-minus prod-li-minus"><?php echo esc_html__('-', 'motor'); ?></a>
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
				<a href="#" class="qnt-plus prod-li-plus"><?php echo esc_html__('+', 'motor'); ?></a>
			</p>
		</div>
		<div class="prod-li-total-wrap">
			<p><?php echo esc_html__('Total', 'motor'); ?></p>
			<?php if ( $price_html = $product->get_price_html() ) : ?><p class="prod-li-total"><?php echo $price_html; ?></p><?php endif; ?>
		</div>
		<?php
		/**
		 * woocommerce_after_shop_loop_item hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item' );
		?>
	</div>
	<div class="prod-li-info<?php if (empty($sku)) echo ' no-sku'; ?><?php if (!comments_open()) echo ' no-rating'; ?>">
		<?php if ( comments_open() ) { ?>
		<div class="prod-li-rating-wrap">
			<p data-rating="<?php echo round($product->get_average_rating()); ?>" class="prod-li-rating">
				<i class="fa fa-star-o" title="5"></i><i class="fa fa-star-o" title="4"></i><i class="fa fa-star-o" title="3"></i><i class="fa fa-star-o" title="2"></i><i class="fa fa-star-o" title="1"></i>
			</p>
			<p><span class="prod-li-rating-count"><?php echo intval($product->get_review_count()); ?></span> <?php echo _n( 'review', 'reviews', $product->get_review_count(), 'motor' ); ?></p>
		</div>
		<?php } ?>

		<?php motor_list_info_button(); ?>

		<p class="prod-li-add">
			<?php if ($motor_options['catalog_request'] == 'yes') : ?>
				<a href="#" class="button request-form-btn"><?php esc_html_e('Request', 'motor'); ?></a>
			<?php else : ?>
				<?php woocommerce_template_loop_add_to_cart(); ?>
			<?php endif; ?>
		</p>

		<p class="prod-li-quick-view">
			<a href="#" class="quick-view" data-url="<?php echo admin_url('admin-ajax.php'); ?>" data-file="woocommerce/quickview-single-product.php" data-id="<?php echo get_the_ID(); ?>"></a>
			<i class="fa fa-spinner fa-pulse quick-view-loading"></i>
		</p>

		<?php
		if ( class_exists( 'YITH_WCWL' ) ) {
			echo do_shortcode('[yith_wcwl_add_to_wishlist]');
		}
		?>

		<?php motor_show_compare_btn(); ?>

	</div>

	<?php motor_product_badge(get_the_ID(), 'prod-li-badge'); ?>

	</div>

	<?php motor_list_info(); ?>

</article>
<?php
$int_key++;
?>