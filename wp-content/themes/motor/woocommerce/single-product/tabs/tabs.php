<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
$editor = get_post_meta($post->ID, 'motor_product_editor', true);
/*if (function_exists('vp_metabox')) {
	$product_description = vp_metabox('motor_meta_product.product_description');
}*/

if (!empty($editor) && $editor == 'custom') :
	$product_description = get_post_meta($post->ID, 'motor_product_description', true);
	if ( ! empty( $tabs ) || !empty($product_description) ) : ?>

		<div class="prod-tabs-wrap">
			<ul class="prod-tabs">
				<?php $int_key = 1; ?>
				<?php if (!empty($product_description)) : ?>
					<li id="prod-desc" class="active" data-prodtab-num="<?php echo intval($int_key); ?>">
						<a data-prodtab="#prod-tab-<?php echo intval($int_key); ?>" href="#"><?php esc_html_e('Description', 'motor'); ?></a>
					</li>
					<?php $int_key++; ?>
				<?php endif; ?>
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<?php
					if ($key == 'description') {
						continue;
					}
					?>
					<li data-prodtab-num="<?php echo intval($int_key); ?>"<?php
					/*if ($key == 'description')
						echo ' id="prod-desc"';*/
					if ($key == 'additional_information')
						echo ' id="prod-props"';
					elseif ($key == 'reviews')
						echo ' id="prod-reviews"';
					?><?php if ($int_key == 1) echo ' class="active"'; ?>>
						<a data-prodtab="#prod-tab-<?php echo intval($int_key); ?>" href="#"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
					</li>
					<?php $int_key++; ?>
				<?php endforeach; ?>
				<?php if ( comments_open() ) {
					?><li class="prod-tabs-addreview"><?php echo esc_html__('Add a review', 'motor'); ?></li><?php
				} ?>
			</ul>
			<div class="prod-tab-cont">
				<?php $int_key = 1; ?>
				<?php if (!empty($product_description)) : ?>
					<p data-prodtab-num="1" class="prod-tab-mob active<?php echo ' prod-tab-mob-description'; ?>" data-prodtab="#prod-tab-<?php echo intval($int_key); ?>"><?php esc_html_e('Description', 'motor'); ?></p>
					<div class="prod-tab page-styling prod-tab-desc" id="prod-tab-<?php echo intval($int_key); ?>">
						<?php call_user_func( 'woocommerce_product_description_tab', 'description', array('title'=>esc_html__('Description', 'motor'), 'priority'=>10, 'callback'=>'woocommerce_product_description_tab') ); ?>
					</div>
					<?php $int_key++; ?>
				<?php endif; ?>
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<?php
					if ($key == 'description') {
						continue;
					}
					?>
					<p data-prodtab-num="<?php echo intval($int_key); ?>" class="prod-tab-mob<?php if ($int_key == 1) echo ' active'; ?><?php echo ' prod-tab-mob-'.$key; ?>" data-prodtab="#prod-tab-<?php echo intval($int_key); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></p>
					<div class="prod-tab<?php
					/*if ($key == 'description')
						echo ' page-styling prod-tab-desc';*/
					if ($key == 'reviews')
						echo ' prod-reviews';
					?>" id="prod-tab-<?php echo intval($int_key); ?>">
						<?php call_user_func( $tab['callback'], $key, $tab ); ?>
					</div>
					<?php $int_key++; ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif;
else:
	if ( ! empty( $tabs ) ) : ?>

		<div class="prod-tabs-wrap">
			<ul class="prod-tabs">
				<?php $int_key = 1; ?>
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<li data-prodtab-num="<?php echo intval($int_key); ?>"<?php
					if ($key == 'description')
						echo ' id="prod-desc"';
					if ($key == 'additional_information')
						echo ' id="prod-props"';
					elseif ($key == 'reviews')
						echo ' id="prod-reviews"';
					?><?php if ($int_key == 1) echo ' class="active"'; ?>>
						<a data-prodtab="#prod-tab-<?php echo intval($int_key); ?>" href="#"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
					</li>
					<?php $int_key++; ?>
				<?php endforeach; ?>
				<?php if ( comments_open() ) {
					?><li class="prod-tabs-addreview"><?php echo esc_html__('Add a review', 'motor'); ?></li><?php
				} ?>
			</ul>
			<div class="prod-tab-cont">
				<?php $int_key = 1; ?>
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<p data-prodtab-num="<?php echo intval($int_key); ?>" class="prod-tab-mob<?php if ($int_key == 1) echo ' active'; ?><?php echo ' prod-tab-mob-'.$key; ?>" data-prodtab="#prod-tab-<?php echo intval($int_key); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></p>
					<div class="prod-tab<?php
					if ($key == 'description')
						echo ' page-styling prod-tab-desc';
					elseif ($key == 'reviews')
						echo ' prod-reviews';
					?>" id="prod-tab-<?php echo intval($int_key); ?>">
						<?php call_user_func( $tab['callback'], $key, $tab ); ?>
					</div>
					<?php $int_key++; ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif;
endif;
