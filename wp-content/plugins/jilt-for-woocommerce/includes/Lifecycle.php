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
 * @package   WC-Jilt
 * @author    Jilt
 * @copyright Copyright (c) 2015-2018, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_2_1 as Framework;

/**
 * The lifecycle handler class.
 *
 * @since 1.5.4
 *
 * @method \WC_Jilt get_plugin()
 */
class Lifecycle extends Framework\Plugin\Lifecycle {


	/**
	 * Handles plugin activation.
	 *
	 * @since 1.5.4
	 */
	public function activate() {

		// if already on v1.5.4 or higher & connected, GET the latest Storefront JS params
		if ( version_compare( $this->get_installed_version(), '1.5.4', '>=' ) && $this->get_integration()->is_jilt_connected()  ) {

			try {

				// get the latest shop data
				$shop_data = $this->get_integration()->get_api()->get_shop();

				// get the currently stored params, and the params we know to look for
				$stored_params = $this->get_integration()->get_storefront_params();
				$known_params  = array(
					'recover_held_orders',
					'capture_email_on_add_to_cart',
					'show_email_usage_notice',
					'show_marketing_consent_opt_in',
					'checkout_consent_prompt',
				);

				// locate each known param from the shop data and set it locally
				foreach ( $known_params as $param ) {

					if ( isset( $shop_data->$param ) ) {

						$value = $shop_data->$param;

						// convert booleans to yes/no
						if ( is_numeric( $value ) ) {
							$value = (bool) $value ? 'yes' : 'no';
						}

						$stored_params[ $param ] = $value;
					}
				}

				// store the updated params
				$this->get_integration()->update_storefront_params( $stored_params );

			} catch ( Framework\SV_WC_Plugin_Exception $exception ) {

				$this->get_plugin()->get_logger()->error( "Error getting the Storefront params from Jilt: {$exception->getMessage()}" );
			}
		}
	}


	/**
	 * Handles plugin deactivation.
	 *
	 * Clears the shop update cron task and un-links the remote Jilt shop.
	 *
	 * @see Framework\Plugin\Lifecycle::deactivate()
	 *
	 * @since 1.5.4
	 */
	public function deactivate() {

		wp_clear_scheduled_hook( 'wc_jilt_shop_update' );

		if ( $this->get_integration()->is_linked() ) {
			$this->get_integration()->unlink_shop();
		}
	}


	/**
	 * Performs install tasks.
	 *
	 * Generates a new Jilt installation ID.
	 *
	 * @since 1.5.4
	 */
	protected function install() {

		$this->get_plugin()->generate_installation_id();
	}


	/**
	 * Handles upgrading the plugin to the current version.
	 *
	 * @since 1.0.6
	 *
	 * @see SV_WC_Plugin::upgrade()
	 *
	 * @param string $installed_version currently installed version
	 */
	protected function upgrade( $installed_version ) {

		// update plugin settings:
		// - debug_mode => log_level, log => INFO, off => OFF
		// - set wc_jilt_shop_domain wp option, if linked shop
		// - current secret key is stashed into a wp option, if linked shop
		if ( version_compare( $installed_version, '1.1.0', '<' ) ) {

			// get existing settings
			$settings = $this->get_integration()->get_settings();

			if ( ! isset( $settings['log_level'] ) ) {
				$settings['log_level'] = $settings['debug_mode'] === 'log' ? 1 : 5; // legacy INFO : OFF
				unset( $settings['debug_mode'] );

				// update to new settings
				$this->get_integration()->update_settings( $settings );
			}

			if ( $this->get_integration()->is_linked() ) {
				$this->get_integration()->set_shop_domain();
				$this->get_integration()->stash_secret_key( $this->get_integration()->get_secret_key() );
			}
		}

		// rename 'log_level' setting to 'log_threshold' and convert to WC 3.0 level values
		if ( version_compare( $installed_version, '1.2.0', '<' ) ) {
			// get existing settings
			$settings = $this->get_integration()->get_settings();

			// default to OFF
			$new_log_threshold = WC_Jilt_Logger::OFF;

			// translate our custom log levels to the new WC 3.0 core equivalent levels
			if ( isset( $settings['log_level'] ) ) {
				switch ( (int) $settings['log_level'] ) {
					case 0:  $new_log_threshold = WC_Jilt_Logger::DEBUG;     break;
					case 1:  $new_log_threshold = WC_Jilt_Logger::INFO;      break;
					case 2:  $new_log_threshold = WC_Jilt_Logger::WARNING;   break;
					case 3:  $new_log_threshold = WC_Jilt_Logger::ERROR;     break;
					case 4:  $new_log_threshold = WC_Jilt_Logger::EMERGENCY; break;
				}
			}

			$settings['log_threshold'] = $new_log_threshold;
			unset( $settings['log_level'] );

			// update to new settings
			$this->get_integration()->update_settings( $settings );
		}

		if ( version_compare( $installed_version, '1.4.0', '<' ) ) {

			$integration = $this->get_integration();

			// Generate the installation id - but only if it does not exist already. This check should not be necessary,
			// you may argue. However, there may be cases where the upgrade fails at a point where the installation id is
			// already generated and oauth client is created, but the credentials never made it to WooCommerce.
			// This check acts as a safety net for such cases, so that we don't lock out users that encountered and error
			// during upgrade and the upgrade needs to re-run. Otherwise, the installation ID would be re-generated on every
			// upgrade attempt and the auth upgrade as well as manually reconnecting would always fail.
			if ( ! $this->get_plugin()->get_installation_id() ) {
				$this->get_plugin()->generate_installation_id();
			}

			// mask the shop domain
			$shop_domain = str_replace( '.', '[.]', $integration->get_linked_shop_domain() );

			update_option( 'wc_jilt_shop_domain', $shop_domain );

			$this->upgrade_auth();
		}

		if ( version_compare( $installed_version, '1.4.1', '<' ) ) {

			if ( $client_secret = $this->get_integration()->get_client_secret() ) {
				$this->get_integration()->stash_secret_key( $client_secret );
			}
		}

		if ( version_compare( $installed_version, '1.4.2', '<' ) ) {

			// repair any invalid installation IDs
			if ( strlen( $this->get_plugin()->get_installation_id() ) !== 64 ) {
				$this->get_plugin()->generate_installation_id();

				// complete the upgrade to oauth process
				$this->upgrade_auth();
			}
		}

		$consumer_key = null;

		if ( version_compare( $installed_version, '1.5.0', '<' ) ) {

			$integration = $this->get_integration();

			try {

				// store UUID in dedicated option and fetch integer ID if not present
				if ( $shop_id = $integration->get_linked_shop_id() ) {

					if ( ! is_numeric( $shop_id ) ) {
						$integration->set_linked_shop_uuid( $shop_id );

						if ( $integration->is_jilt_connected() ) {
							$shop_data = $integration->get_api()->get_shop();

							if ( ! empty( $shop_data ) ) {
								$integration->set_linked_shop_id( $shop_data->id );
							}
						}
					}
				}

				// create a new WC REST API if the shop is already connected to Jilt
				if ( $integration->is_jilt_connected() ) {

					// create a new WC REST API key
					$key = $this->get_plugin()->get_wc_rest_api_handler_instance()->create_key();

					$consumer_key = $key->consumer_key;
				}

			} catch ( Framework\SV_WC_Plugin_Exception $exception ) {

				wc_jilt()->get_logger()->error( "Automatic upgrade to the WC REST API failed: {$exception->getMessage()}" );
			}
		}

		// 1.5.4
		if ( version_compare( $installed_version, '1.5.4', '<' ) ) {

			wc_jilt()->get_logger()->info( 'Updating to v1.5.4' );

			$integration = $this->get_integration();

			$storefront_params = array(
				'recover_held_orders'           => $integration->get_option( 'recover_held_orders', 'no' ),
				'capture_email_on_add_to_cart'  => $integration->get_option( 'capture_email_on_add_to_cart', 'no' ),
				'show_email_usage_notice'       => $integration->get_option( 'show_email_usage_notice', 'no' ),
				'show_marketing_consent_opt_in' => $integration->get_option( 'show_marketing_consent_opt_in', 'no' ),
				'checkout_consent_prompt'       => $integration->get_option( 'checkout_consent_prompt', '' ),
			);

			// TODO: remove these keys from the plugin's woocommerce_jilt_settings option in a future release {CW 2018-08-23}

			// store locally as a single option
			$integration->update_storefront_params( $storefront_params );

			// update the app's Storefront param values if connected
			if ( $integration->is_jilt_connected() ) {

				try {

					$integration->get_api()->update_shop( $storefront_params );

				} catch ( Framework\SV_WC_Plugin_Exception $exception ) {

					wc_jilt()->get_logger()->error( "Could not send site settings to Jilt: {$exception->getMessage()}" );
				}
			}
		}

		// update shop data in Jilt (especially plugin version)
		WC_Jilt_Integration_Admin::update_shop( $consumer_key );
	}


	/**
	 * Upgrade from secret key to oauth
	 *
	 * @since 1.4.2
	 */
	private function upgrade_auth() {

		$integration = $this->get_integration();

		// try upgrading to oauth
		if ( $integration->get_linked_shop_id() && $integration->get_secret_key() ) {

			try {

				$response = $integration->get_api()->update_auth( $integration->get_linked_shop_id(), $this->get_plugin()->get_shop_domain(), $this->get_plugin()->get_callback_url(), $this->get_plugin()->get_installation_id() );
				$token    = $response->token;
				$shop_id  = $token->shop_uuid;

				unset( $token->shop_uuid ); // don't store shop id twice

				$integration->set_access_token( (array) $response->token );
				$integration->set_linked_shop_id( $shop_id );

				update_option( 'wc_jilt_client_id', $response->client_id );

				$integration->set_client_secret( $response->client_secret );

				// remove secret key
				$integration->set_secret_key( null );

				unset( $integration->settings['secret_key'] );

				update_option( $integration->get_option_key(), $integration->settings );

			} catch ( Framework\SV_WC_API_Exception $exception ) {
				wc_jilt()->get_logger()->error( "Automatic upgrade to OAuth failed: {$exception->getMessage()}" );
			}
		}
	}


	/**
	 * Gets the Jilt integration.
	 *
	 * @since 1.5.4
	 *
	 * @return WC_Jilt_Integration
	 */
	protected function get_integration() {

		return $this->get_plugin()->get_integration();
	}


}
