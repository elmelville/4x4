<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp;
$current_url = esc_url(home_url(add_query_arg(array(),$wp->request)));
?>
<div class="section-sort">
	<p><?php echo esc_html__('Sort', 'motor'); ?></p>
	<div class="dropdown-wrap">
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<?php
			switch ($id) {
				case 'menu_order':
					$sort_name = esc_html__('Default', 'motor'); break;
				case 'popularity':
					$sort_name = esc_html__('Popularity', 'motor'); break;
				case 'rating':
					$sort_name = esc_html__('Average rating', 'motor'); break;
				case 'date':
					$sort_name = esc_html__('Newness', 'motor'); break;
				case 'price':
					$sort_name = esc_html__('Price: low to high', 'motor'); break;
				case 'price-desc':
					$sort_name = esc_html__('Price: high to low', 'motor'); break;
				default:
					$sort_name = $name; break;
			}
			?>
			<?php if ( $orderby == $id ) : ?>
			<p class="dropdown-title section-sort-ttl"><?php echo esc_html( $sort_name ); ?></p>
			<?php endif; ?>
		<?php endforeach; ?>
		<ul class="dropdown-list">
			<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<?php
			switch ($id) {
				case 'menu_order':
					$sort_name = esc_html__('Default', 'motor'); break;
				case 'popularity':
					$sort_name = esc_html__('Popularity', 'motor'); break;
				case 'rating':
					$sort_name = esc_html__('Average rating', 'motor'); break;
				case 'date':
					$sort_name = esc_html__('Newness', 'motor'); break;
				case 'price':
					$sort_name = esc_html__('Price: low to high', 'motor'); break;
				case 'price-desc':
					$sort_name = esc_html__('Price: high to low', 'motor'); break;
				default:
					$sort_name = $name; break;
			}
			?>
			<li<?php if ( $orderby == $id ) echo ' class="active"'; ?>>
				<a href="<?php echo esc_url($current_url.'?orderby='.esc_attr( $id )); ?>"><?php echo esc_html( $sort_name ); ?></a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {
			if ( 'orderby' === $key || 'submit' === $key ) {
				continue;
			}
			if ( is_array( $val ) ) {
				foreach( $val as $innerVal ) {
					echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
				}
			} else {
				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
			}
		}
	?>
</div>
