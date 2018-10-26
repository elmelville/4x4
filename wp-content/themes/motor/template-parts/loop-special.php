<?php
// Product Price

global $product;

$display_price         = $product->get_display_price();
$display_regular_price = $product->get_display_price( $product->get_regular_price() );
$display_sale_price    = $product->get_display_price( $product->get_sale_price() );
if ( $product->get_price() !== '' ) {
	if ( $product->is_on_sale() ) {
		$price =  '<span class="prod-i-price">'. wc_price( $display_sale_price ) . '</span>' . ' <del>' . wc_price( $display_regular_price ) . '</del>'. $product->get_price_suffix();
	} elseif ( $product->get_price() > 0 ) {
		$price = '<span class="prod-i-price">'. wc_price( $display_price ) . '</span>' . $product->get_price_suffix();
	} else {
		$price = '<span class="prod-i-price">'.esc_html__('Free!', 'motor').'</span>';
	}
} else {
	$price = '';
}
?>
<article class="prod-i special<?php if ($int_key == 0) echo ' special-first'; ?>">
	<a href="<?php the_permalink(); ?>" class="prod-i-link">
		<p class="prod-i-img">
			<?php if ($int_key == 0) : ?>
				<?php the_post_thumbnail('shop_single'); ?>
			<?php else: ?>
				<?php the_post_thumbnail('shop_catalog'); ?>
			<?php endif; ?>
		</p>
		<h3><span><?php the_title(); ?></span></h3>
	</a>
	<p class="prod-i-info">
		<?php
		$product_categories = get_the_terms( $post->ID, 'product_cat' );
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
		<?php echo wp_kses_post($price); ?>

		<?php
		woocommerce_template_loop_add_to_cart(array('button_text'=>esc_html__('+ Add to cart', 'motor')));
		?>

	</p>
</article>
<?php
$int_key++;
?>