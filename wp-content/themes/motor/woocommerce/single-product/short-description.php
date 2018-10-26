<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$editor = get_post_meta($post->ID, 'motor_product_editor', true);

if (!empty($editor) && $editor == 'custom') :
	$product_description = get_post_meta($post->ID, 'motor_product_description', true);
	?>
	<p><?php echo wp_kses_post(wp_trim_words($product_description, 20)); ?> <a id="prod-showdesc" href="#"><?php echo esc_html__('read more', 'motor'); ?></a></p>
<?php else: ?>
	<?php echo get_the_excerpt(); ?> <a id="prod-showdesc" href="#"><?php echo esc_html__('read more', 'motor'); ?></a>
<?php endif; ?>