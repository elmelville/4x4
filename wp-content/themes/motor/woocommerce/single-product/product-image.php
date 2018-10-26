<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

?>

<?php
$attachment_ids = $product->get_gallery_image_ids();

if ( $attachment_ids || has_post_thumbnail() ) {
	?>
	<div class="flexslider prod-slider<?php if ( !$attachment_ids && has_post_thumbnail() ) echo ' prod-thumb-only'; ?>" id="prod-slider">
		<ul class="slides">
			<?php
			if (has_post_thumbnail()) {
				$image_link    = wp_get_attachment_url( get_post_thumbnail_id() );
				$image         = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
					'title'	=> get_the_title( get_post_thumbnail_id() )
				) );
				?>
				<li>
					<a data-fancybox-group="prod" class="fancy-img" href="<?php echo esc_url($image_link); ?>"><?php echo wp_kses_post($image); ?></a>
				</li>
			<?php } ?>
			<?php
			if ( $attachment_ids ) {

				foreach ( $attachment_ids as $attachment_id ) {

					$image_link = wp_get_attachment_url( $attachment_id );
					$image_title 	= esc_attr( get_the_title( $attachment_id ) );
					$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_single' ), 0, $attr = array(
						'title'	=> $image_title,
						'alt'	=> $image_title
					) );
					?>
					<li>
						<a data-fancybox-group="prod" class="fancy-img" href="<?php echo esc_url($image_link); ?>"><?php echo wp_kses_post($image); ?></a>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>
<?php } ?>


<?php
if ( $attachment_ids ) {
	?>
	<div class="flexslider prod-thumbs" id="prod-thumbs">
		<ul class="slides">
			<?php
			if (has_post_thumbnail()) {
				$image         = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
					'title'	=> get_the_title( get_post_thumbnail_id() )
				) );
				?>
				<li>
					<?php echo wp_kses_post($image); ?>
				</li>
			<?php } ?>
			<?php
			if ( $attachment_ids ) {
				foreach ( $attachment_ids as $attachment_id ) {

					$image_title 	= esc_attr( get_the_title( $attachment_id ) );
					$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
						'title'	=> $image_title,
						'alt'	=> $image_title
					) );
					?>
					<li>
						<?php echo wp_kses_post($image); ?>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>
	<?php
}
?>