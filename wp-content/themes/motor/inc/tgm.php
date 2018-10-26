<?php
/**
 * Plugin installation and activation for WordPress themes.
 *
 * Please note that this is a drop-in library for a theme or plugin.
 * The authors of this library (Thomas, Gary and Juliette) are NOT responsible
 * for the support of your plugin or theme. Please contact the plugin
 * or theme author for support.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @version    2.6.1
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Plugin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */
require_once( trailingslashit( get_template_directory() ) . '/inc/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'motor_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function motor_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'         => esc_html__('Motor Custom Types', 'motor'),
			'slug'         => 'motor-custom-types',
			'source'       => esc_url('http://real-web.pro/motor/motor-custom-types.zip'),
			'required'     => true,
		),

		array(
			'name'      => esc_html__('WPBakery Visual Composer', 'motor'),
			'slug'      => 'js_composer',
			'required'  => true,
			'source'       => esc_url('http://real-web.pro/motor/js_composer.zip'),
		),

		array(
			'name'      => esc_html__('WOOF - WooCommerce Products Filter', 'motor'),
			'slug'      => 'woocommerce-products-filter',
			'required'  => true,
			'source'       => esc_url('http://real-web.pro/motor/woocommerce-products-filter.zip'),
		),

		array(
			'name'      => esc_html__('WooCommerce', 'motor'),
			'slug'      => 'woocommerce',
			'required'  => true,
		),

		array(
			'name'      => esc_html__('Revolution Slider', 'motor'),
			'slug'      => 'revslider',
			'required'  => false,
			'source'       => esc_url('http://real-web.pro/motor/revslider.zip'),
		),

		array(
			'name'      => esc_html__('Unyson', 'motor'),
			'slug'      => 'unyson',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('WooCommerce Compare List', 'motor'),
			'slug'      => 'woocommerce-compare-list',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('Contact Form 7', 'motor'),
			'slug'      => 'contact-form-7',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('WP User Avatar', 'motor'),
			'slug'      => 'wp-user-avatar',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('YITH WooCommerce Wishlist', 'motor'),
			'slug'      => 'yith-woocommerce-wishlist',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('Kirki', 'motor'),
			'slug'      => 'kirki',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('Ajax Search for WooCommerce', 'motor'),
			'slug'      => 'ajax-search-for-woocommerce',
			'required'  => false,
		),

		array(
			'name'      => esc_html__('Clever Mega Menu', 'motor'),
			'slug'      => 'clever-mega-menu',
			'required'  => false,
		),

	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'motor',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
