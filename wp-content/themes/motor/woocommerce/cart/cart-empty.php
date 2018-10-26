<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="cont maincont">

<h1><span><?php the_title(); ?></span></h1>
<span class="maincont-line1"></span>
<span class="maincont-line2"></span>

<?php get_template_part('template-parts/personal-menu'); ?>

<p class="section-count"><?php echo WC()->cart->get_cart_contents_count(); ?> <?php echo _n( 'ITEM', 'ITEMS', WC()->cart->get_cart_contents_count(), 'motor' ); ?></p>

<?php wc_print_notices(); ?>

<div class="page-cont cart-empty-cont">

<?php do_action( 'woocommerce_cart_is_empty' ); ?>

</div>

</div>