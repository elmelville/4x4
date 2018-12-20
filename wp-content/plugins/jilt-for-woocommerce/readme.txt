=== Jilt for WooCommerce ===
Contributors: jilt, skyverge
Tags: woocommerce, abandoned carts, cart abandonment, lost revenue, save abandoned carts
Requires at least: 4.4
Tested up to: 4.9.8
Requires PHP: 5.4
Stable Tag: 1.5.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Increase sales in your WooCommerce store by connecting to Jilt - save abandoned carts and send lifecycle emails that make you money.

== Description ==

> **Requires: WooCommerce 3.0** or newer

Jilt is built from the ground up to help your store increase sales. This plugin connects Jilt to [WooCommerce](http://www.woocommerce.com) to bring powerful, automated lifecycle emails to your store.

= A complete abandoned cart solution =

[Jilt abandoned cart recovery solution](https://jilt.com/?utm_source=wordpress.org&utm_medium=plugin-listing&utm_content=cta-jilt-abandoned-cart-recovery-app&utm_campaign=jilt-woocommerce-wordpress-plugin) helps your eCommerce store **recover lost revenue** due to cart abandonment. This plugin will track when carts are abandoned on your store and send that data to Jilt. You can then send recovery emails to encourage the customers who abandoned these carts to complete the purchase.

Jilt has already helped merchants recover **over $25,000,000** in lost revenue! You can set up as many campaigns and recovery emails as you'd like, and customize the text and design of every email sent.

= Track abandoned carts =

Jilt will track all abandoned carts in your WooCommerce store, capturing email addresses for customers where possible. This lets you see how many customers enter your purchasing flow, but then leave without completing the order.

You can then send recovery emails to these customers to encourage them to complete their purchases, recovering revenue that would otherwise be lost to your store.

= Recover lost revenue =

Once Jilt tracks an abandoned cart, you can use your campaigns and recovery emails to save this lost revenue. A **campaign** is a collection of recovery emails that can be sent after the cart is abandoned. You can set up as many emails within a campaign as you'd like (e.g., send 3 recovery emails per abandoned cart).

You can also set up as many campaigns as you want &ndash; create a dedicated series of recovery emails for holidays, sales, or other company events.

= Send automated post-purchase follow ups =

Jilt's [post-purchase follow up emails](https://jilt.com/?utm_source=wordpress.org&utm_medium=plugin-listing&utm_content=cta-jilt-post-purchase-followups&utm_campaign=jilt-woocommerce-wordpress-plugin) help you increase repeat sales. You've worked hard to gain new customers, so make the most of them! Send automated post-purchase emails once orders are completed to encourage leaving a review, providing feedback, or purchasing again.

= Built for site performance =

This plugin sends details on all orders and abandoned carts to the Jilt app rather than storing them in your site's database. This ensures that you can track data over time and get valuable insights into cart abandonment and recovery, while your site **stays speedy** and doesn't get bogged down with tons of abandoned cart data.

Jilt for WooCommerce is built by [SkyVerge](http://skyverge.com/?utm_source=wordpress.org&utm_medium=plugin-listing&utm_campaign=jilt-woocommerce-wordpress-plugin), expert WooCommerce developers who have built over 50 official WooCommerce extensions. Jilt for WooCommerce is great for merchants small and large alike, and is built to scale as large as your store can.

= More Details =

**Translators:** the plugin text domain is: `jilt-for-woocommerce`

 - Visit [Jilt.com](https://jilt.com/?utm_source=wordpress.org&utm_medium=plugin-listing&utm_content=cta-visit-jilt.com&utm_campaign=jilt-woocommerce-wordpress-plugin) for more details on Jilt, and to see how Jilt merchants increase revenue by 20% in under 20 minutes.
 - See the [full knowledge base and documentation](http://help.jilt.com/?utm_source=wordpress.org&utm_medium=plugin-listing&utm_campaign=jilt-woocommerce-wordpress-plugin) for questions and set up help.
 - View more of SkyVerge's [WooCommerce extensions](http://profiles.wordpress.org/skyverge/) on WordPress.org
 - View all [WooCommerce extensions](http://www.skyverge.com/shop/?utm_source=wordpress.org&utm_medium=plugin-listing&utm_campaign=jilt-woocommerce-wordpress-plugin) from SkyVerge

== Installation ==

1. Be sure you're running WooCommerce 3.0 or newer in your shop.

2. To install the plugin, you can do one of the following:

    - (Recommended) Search for "Jilt for WooCommerce" under Plugins &gt; Add New
    - Upload the entire `jilt-for-woocommerce` folder to the `/wp-content/plugins/` directory.
    - Upload the .zip file with the plugin under **Plugins &gt; Add New &gt; Upload**

3. Activate the plugin through the 'Plugins' menu in WordPress

4. Click the "Configure" plugin link or go to **Jilt** from the main menu to connect your store to Jilt.

5. Save your settings!

== Frequently Asked Questions ==

= Do I need anything else to use this plugin? =

Yes, a free Jilt account is required to recover abandoned carts for your WooCommerce store. You can [learn more about Jilt pricing here](https://jilt.com/pricing/?utm_source=wordpress.org&utm_medium=plugin-listing&utm_content=cta-faq-learn-more-about-jilt-pricing-here&utm_campaign=jilt-woocommerce-wordpress-plugin) for our paid accounts.

= When is a cart "abandoned"? =

A cart is considered abandoned if a customer has added items to the cart, but has not checked out or shown any cart activity for at least 15 minutes (ie adding more items). At this point, Jilt starts the timers for your recovery emails.

= Which customers will be emailed for abandoned carts? =

Any logged in customer who abandons a cart will receive recovery emails from Jilt. Any guest customer who has entered a full, valid email address in the checkout process will also be sent recovery emails.

= When will post-purchase emails be sent? =

The post purchase trigger is for order **completion**, as this tells us your store is "done" with the order. Once an order is marked "completed", Jilt will schedule post-purchase emails on the schedule you've configured in your campaign.

= Can I include unique coupon codes in my emails? =

Yes! Jilt can automatically create unique coupon codes and include them in your automated emails. This gives you a proven and powerful means of increasing conversion rates and generating more orders!

== Screenshots ==

1. Connect your store to Jilt with a couple clicks
2. Configure Storefront settings once you're connected
3. Set up your campaigns in the Jilt app to start increasing sales!

== Changelog ==

= 2018.09.12 - version 1.5.4 =
 * Tweak - Switch to Jilt's Storefront JS for cart updates and email capture

= 2018.08.09 - version 1.5.3 =
 * Fix - Javascript error on cart page

= 2018.08.09 - version 1.5.2 =
 * Fix - Bug preventing some carts from being sent to Jilt in certain site configurations
 * Fix - Work around the WC REST API being unavailable when certain plugins are active

= 2018.07.25 - version 1.5.1 =
 * Tweak - Encrypt cart/order data sent over the Jilt REST API
 * Tweak - Add a notice when the WooCommerce REST API is disabled by Advanced Access Manager
 * Fix - Ensure coupon minimum amounts are respected when applying a coupon from a recovery URL
 * Fix - Avoid duplicate coupon notifications when applying a coupon from a recovery URL
 * Fix - Fix a bug where registering users sometimes were automatically opted-out of email collection and marketing consent

= 2018.06.27 - version 1.5.0 =
 * Feature - Support Jilt communication via the WooCommerce REST API
 * Tweak - Improved API request logging
 * Tweak - Recover all the carts! Add to cart popovers will now be triggered on custom add-to-cart links when enabled.
 * Tweak - Move Jilt settings to a dedicated top-level page
 * Tweak - Show log threshold setting even if store is not connected to Jilt
 * Misc - Require PHP 5.4 or newer
 * Misc - Require WooCommerce 3.0 or newer
 * Misc - Update SkyVerge plugin framework to version 5.0.1

= 2018.05.25 - version 1.4.5 =
 * Tweak - Customers can be shown a notice informing them about data collected for sending cart reminders
 * Tweak - Customers can opt-out of data collection for sending cart reminders
 * Tweak - Customers can now be asked at checkout for consent to send marketing emails

= 2018.05.18 - version 1.4.4 =
 * Tweak - Better tracking of empty carts

= 2018.05.08 - version 1.4.3 =
 * Tweak - Perform Jilt REST API requests from client browser as much as possible for improved performance
 * Tweak - Send information from WooCommerce Composite Products to Jilt so recovery emails can customize the display of composite products
 * Tweak - Improved support for WP Multisite Directory installs

= 2018.04.11 - version 1.4.2 =
 * Fix - More reliable installation id generation during oauth connection flow
 * Fix - Improve our handling of persistent carts to avoid sending duplicate order data to Jilt when logging in
 * Fix - Improve our handling of persistent carts for users paying with PayPal

= 2018.03.27 - version 1.4.1 =
 * Fix - Ensure that client secrets created during upgrade to 1.4 are stored for later use

= 2018.02.09 - version 1.4.0 =
 * Feature - Add support for connecting to Jilt with one click. Hello, super-simple signup!
 * Feature - Enable add-to-cart popovers to collect email addresses when items are added to the cart.
 * Feature - Add System Status Debug tool for clearing Jilt connection data
 * Feature - Massive performance improvement when creating & updating order data
 * Misc - Add support for WooCommerce 3.3
 * Fix - Ensure that a proper error message is displayed when cart recovery fails
 * Fix - Address a bug with recreating older carts containing Product Bundles
 * Fix - Fix conflict with Gift Cards plugin

= 2017.12.06 - version 1.3.3 =
 * Fix - Fix PHP errors when used with Authorize.net AIM plugin

= 2017.11.22 - version 1.3.2 =
 * Fix - Fix PHP warning when updating orders
 * Fix - Compatibility fix for WooCommerce 2.6

= 2017.11.14 - version 1.3.1 =
 * Fix - Compatibility fix for WooCommerce 3.1.1

= 2017.11.13 - version 1.3.0 =
 * Feature - Send prices inclusive of taxes to Jilt app when appropriate
 * Feature - Reduce checkout friction by enabling post-checkout account registration - customers can register with one click after they've placed their order
 * Feature - Send information from WooCommerce Product Bundles to Jilt so recovery emails can use bundle information or hide bundled items
 * Tweak - Support for UTM parameters in the cart recovery URL, track all the things!
 * Tweak - Send full shop address to Jilt if supported by WooCommerce
 * Fix - Only send known order financial and fulfillment statuses to Jilt

= 2017.08.31 - version 1.2.0 =
 * Tweak - Jilt connection status shown on plugin settings screen
 * Tweak - Orders endpoint added to integration API
 * Tweak - Preferred HTTP Request method transport can be specified in the wc_jilt_http_request_args filter
 * Misc - Added support for WooCommerce 3.1

= 2017.04.04 - version 1.1.0 =
 * Feature - Support for dynamic recovery discounts
 * Feature - Order fees are now supported
 * Feature - Order customer notes are saved and populated when a customer follows a recovery link
 * Feature - Allow recovery emails to be sent for held orders
 * Tweak - Attempt to recover orders that are abandoned during an off-site payment gateway and automatically cancelled by WooCommerce
 * Tweak - Additional logging level for easier troubleshooting
 * Tweak - x-jilt-shop-domain header is now included in all Jilt API requests
 * Tweak - Better handling of staging/dev migrations
 * Tweak - Removing the configured secret key or deactivating the plugin now signals the Jilt app to pause any active recovery campaigns
 * Tweak - Moved the customer email field to the top of the checkout form in WooCommerce 3.0
 * Misc - Added support for WooCommerce 3.0
 * Misc - Removed support for WooCommerce 2.5

= 2016.12.08 - version 1.0.7 =
 * Tweak - Add compatibility with certain on-site iframe payment gateways, like Amazon Payments Advanced
 * Tweak - Improve how placed orders are updated in Jilt
 * Fix - Fix some errant notices
 * Misc: WordPress 4.7 compatibility

= 2016.11.30 - version 1.0.6 =
 * Tweak - Greatly improve how billing/shipping info is handled, especially when a customer logs in at checkout
 * Fix - Fix an issue where a Jilt order was created with an incorrect total price when first adding an item to the cart
 * Tweak - Improve how API requests to Jilt are sent for improved stability and compatibility with different server environments
 * Misc - For developers: updated public JS API to support setting customer data prior to a visitor starting the checkout process

= 2016.11.07 - version 1.0.5 =
 * Fix - Tweak support links so they properly pre-fill WooCommerce as the platform
 * Misc - Update SkyVerge Plugin Framework to v4.5.0

= 2016.10.14 - version 1.0.4 =
 * Fix - Fix issues with duplicate Jilt orders when recreating carts for previously logged in customers

= 2016.10.13 - version 1.0.3 =
 * Tweak - Improve experience when linking/unlinking shop from Jilt
 * Tweak - Set saved shipping/payment method when recreating a guest's cart
 * Tweak - Set previously applied coupons when recreating a guest's cart

= 2016.07.27 - version 1.0.2 =
 * Misc: WordPress 4.6 compatibility

= 2016.07.12 - version 1.0.1 =
 * Fix - Include line item token in API requests
 * Tweak - Add order note when an order is recovered from a Jilt campaign

= 2016.06.24 - version 1.0.0 =
 * Initial release!
