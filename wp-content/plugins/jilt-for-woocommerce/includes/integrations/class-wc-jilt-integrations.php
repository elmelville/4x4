<?php
/**
 * WooCommerce Jilt
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@jilt.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Jilt to newer
 * versions in the future. If you wish to customize WooCommerce Jilt for your
 * needs please refer to http://help.jilt.com/jilt-for-woocommerce
 *
 * @package   WC-Jilt/Integrations
 * @author    Jilt
 * @category  Frontend
 * @copyright Copyright (c) 2015-2018, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_2_1 as Framework;

/**
 * Manages Jilt integrations with 3rd party plugins
 *
 * @since 1.3.0
 */
class WC_Jilt_Integrations {


	private $integrations;

	public function __construct() {

		$load_integrations = array(
			'WC_Jilt_Product_Bundles_Integration',
			'WC_Jilt_Composite_Products_Integration',
			'WC_Jilt_Gift_Cards_Integration',
			'WC_Jilt_Paypal_Standard_Integration',
			'WC_Jilt_Advanced_Access_Manager_Integration',
		);

		/**
		 * Allow third party Jilt integrations to be registered.
		 *
		 * @since 1.3.0
		 * @param array $integrations array of string integration class names,
		 *   or WC_Jilt_Integration integration instances
		 */
		$load_integrations = apply_filters( 'wc_jilt_integrations', $load_integrations );

		// Load gateways in order
		foreach ( $load_integrations as $integration ) {
			$load_integration = is_string( $integration ) ? new $integration() : $integration;
			$this->integrations[] = $load_integration;
		}
	}


}
