<?php
global $motor_options;
include( trailingslashit( get_template_directory() ) . 'inc/get-options.php' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php
// Favicon
wp_site_icon();
?>
	
<?php wp_head(); ?>
</head>
<body <?php
$sticky_header = '';
if (!empty($motor_options['header_sticky']) && $motor_options['header_sticky']) {
	$sticky_header = 'header-sticky';
}
body_class($sticky_header);
?>>

<div id="page" class="site">

	
<?php
if (!empty($motor_options['header_before'])) {
	$header_before = $motor_options['header_before'];
	if (function_exists('icl_object_id')) {
		$header_before = icl_object_id($motor_options['header_before'], 'page', false, ICL_LANGUAGE_CODE);
	}
	$content = get_post_field('post_content', $header_before);
	if (!empty($content)) {
		echo '<div class="page-styling site-header-before">'.do_shortcode( $content ).'</div>';
	}
}
?>


<?php /* Header - start */ ?>
<div id="masthead" class="header">

	<a href="#" class="header-menutoggle" id="header-menutoggle"><?php echo esc_html__('Menu', 'motor'); ?></a>

	<div class="header-info">

		<?php if ( class_exists( 'WooCommerce' ) && $motor_options['header_profile'] !== 'hide' ) : ?>
		<div class="header-personal">
			<?php if (is_user_logged_in()) : ?>
			<a class="header-gopersonal" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"></a>
			<ul>

				<?php if (!empty($motor_options['compare']['id']) && $motor_options['header_compare'] !== 'hide' && defined( 'WCCM_VERISON' )) : ?>
				<li>
					<a href="<?php echo esc_url($motor_options['compare']['url']); ?>"><?php echo esc_html__('Compare list', 'motor'); ?> <span id="h-personal-compare-count"><?php echo intval($motor_options['compare']['count']); ?></span></a>
				</li>
				<?php endif; ?>

				<?php if ($motor_options['header_wishlist'] !== 'hide' && !empty($motor_options['wishlist']['id']) && class_exists( 'YITH_WCWL' )) : ?>
				<li>
					<a href="<?php echo esc_url($motor_options['wishlist']['url']); ?>"><?php echo esc_html__('Wishlist', 'motor'); ?> <span id="h-personal-wishlist-count"><?php $wishlist_count = YITH_WCWL()->count_products(); echo intval($wishlist_count); ?></span></a>
				</li>
				<?php endif; ?>

				<?php if ($motor_options['header_cart'] !== 'hide') : ?>
				<li class="header-personal-cart">
					<a href="<?php echo esc_url(WC()->cart->get_cart_url()); ?>"><?php echo esc_html__('Shopping Cart', 'motor'); ?> <span><?php echo WC()->cart->get_cart_contents_count()?></span></a>
				</li>
				<li class="header-order">
					<a href="<?php echo esc_url(WC()->cart->get_checkout_url()); ?>"><?php echo esc_html__('Checkout', 'motor'); ?></a>
				</li>
				<?php endif; ?>
				
				<li>
					<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php echo esc_html__('My Account', 'motor'); ?></a>
				</li>
				<li>
					<a href="<?php echo esc_url(wc_customer_edit_account_url()); ?>"><?php echo esc_html__('Settings', 'motor'); ?></a>
				</li>
				<li>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php echo esc_html__('Log out', 'motor'); ?></a>
				</li>
			</ul>
			<?php else : ?>
			<a class="header-gopersonal" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"></a>
			<ul>
				<li>
					<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php esc_html_e('Login / Register','motor'); ?></a>
				</li>
			</ul>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if (class_exists( 'woocommerce' ) && $motor_options['header_cart'] !== 'hide') : ?>
		<div class="header-cart">
			<?php
			motor_cart_link();
			the_widget( 'WC_Widget_Cart', 'title=' );
			?>
		</div>
		<?php endif; ?>

		<?php if (!empty($motor_options['compare']['id']) && $motor_options['header_compare'] !== 'hide' && defined( 'WCCM_VERISON' )) : ?>
		<a title="<?php esc_html_e('Compare', 'motor'); ?>" href="<?php echo esc_url($motor_options['compare']['url']); ?>" class="header-compare"><?php if (!empty($motor_options['compare']['count'])) : ?><span id="h-compare-count"><?php echo intval($motor_options['compare']['count']); ?></span><?php endif; ?></a>
		<?php endif; ?>

		<?php if ($motor_options['header_wishlist'] !== 'hide' && !empty($motor_options['wishlist']['id']) && class_exists( 'YITH_WCWL' )) : ?>
		<a title="<?php esc_html_e('Wishlist', 'motor'); ?>" href="<?php echo esc_url($motor_options['wishlist']['url']); ?>" class="header-favorites"><?php $wishlist_count = YITH_WCWL()->count_products(); if (!empty($wishlist_count)) : ?><span id="h-wishlist-count"><?php echo intval($wishlist_count); ?></span><?php endif; ?></a>
		<?php endif; ?>

		<?php
		// Search Form
		if ($motor_options['header_search'] !== 'hide') : ?>
			<a href="#" class="header-searchbtn" id="header-searchbtn"></a>
			<?php get_search_form(); ?>
		<?php endif; ?>

	</div>

	<p class="header-logo">
		<?php if (!empty($motor_options['header_logo'])) : ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_attr($motor_options['header_logo']); ?>" alt="<?php bloginfo('name'); ?>"></a><?php else: ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="<?php bloginfo('name'); ?>"></a><?php endif; ?>
	</p>

	<?php
	wp_nav_menu( array(
		'theme_location' => 'rw-top-menu',
		'container' => 'nav',
		'container_class' => '',
		'container_id' => 'top-menu',
		'items_wrap' => '<ul>%3$s</ul>',
	) );
	?>

</div>
<?php /* Header - end */ ?>


<?php
if (!empty($motor_options['header_after'])) {
	$header_after = $motor_options['header_after'];
	if (function_exists('icl_object_id')) {
		$header_after = icl_object_id($motor_options['header_after'], 'page', false, ICL_LANGUAGE_CODE);
	}
	$content = get_post_field('post_content', $header_after);
	if (!empty($content)) {
		echo '<div class="page-styling site-header-before">'.do_shortcode( $content ).'</div>';
	}
}
?>


<div id="content" class="site-content">