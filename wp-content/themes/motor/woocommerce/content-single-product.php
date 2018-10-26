<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

<?php woocommerce_template_single_title(); ?>
<span class="maincont-line1"></span>
<span class="maincont-line2"></span>

<?php
	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>


<!-- Product - start -->
<div <?php post_class('prod'); ?>>

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
// Product Tabs
woocommerce_output_product_data_tabs();

// Upsell
woocommerce_upsell_display();

// Related Products
woocommerce_output_related_products();
?>

</article>

<?php do_action( 'woocommerce_after_single_product' ); ?>