<?php
/**
 * Plugin Name: Floating Cart for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/floating-cart-for-woocommerce/
 * Description: Adds a floating cart to your shop.
 * Version: 1.0.0
 * Author: Roy Ho
 * Author URI: http://royho.me
 * Text Domain: floating-cart-for-woocommerce
 * Domain Path: /languages
 * Copyright: (c) 2021 Roy Ho
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package floating-cart-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.Files.FileName

if ( ! class_exists( 'Floating_Cart_For_Woocommerce' ) ) :
	define( 'FLOATING_CART_FOR_WOOCOMMERCE_VERSION', '1.0.0' );
	define( 'FLOATING_CART_FOR_WOOCOMMERCE_ABSPATH', dirname( __FILE__ ) . '/' );

	/**
	 * Floating Cart for WooCommerce class
	 */
	class Floating_Cart_For_Woocommerce {
		/**
		 * The single instance of the class.
		 *
		 * @var $instance
		 * @since 1.0.0
		 */
		protected static $instance = null;

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->includes();
		}

		/**
		 * Load dependencies.
		 *
		 * @since 1.0.0
		 */
		public function includes() {
			if ( is_admin() ) {
				require_once FLOATING_CART_FOR_WOOCOMMERCE_ABSPATH . 'includes/class-floating-cart-for-woocommerce-admin.php';
			} else {
				require_once FLOATING_CART_FOR_WOOCOMMERCE_ABSPATH . 'includes/class-floating-cart-for-woocommerce-cart.php';
				require_once FLOATING_CART_FOR_WOOCOMMERCE_ABSPATH . 'includes/class-floating-cart-for-woocommerce-cart-ajax.php';
			}
		}

		/**
		 * Main Instance.
		 *
		 * Ensures only one instance is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @return Floating_Cart_For_Woocommerce
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __clone() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'floating-cart-for-woocommerce' ), FLOATING_CART_FOR_WOOCOMMERCE_VERSION );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'floating-cart-for-woocommerce' ), FLOATING_CART_FOR_WOOCOMMERCE_VERSION );
		}
	}
endif;

/**
 * WooCommerce fallback notice.
 *
 * @since 1.0.0
 */
function floating_cart_for_woocommerce_missing_wc_notice() {
	/* translators: %s WC download URL link. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Floating Cart for WooCommerce requires WooCommerce to be installed and active. You can download %s here.', 'floating-cart-for-woocommerce' ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

if ( ! function_exists( 'floating_cart_for_woocommerce_init' ) ) :
	add_action( 'plugins_loaded', 'floating_cart_for_woocommerce_init', 10 );

	/**
	 * Initialize the plugin.
	 */
	function floating_cart_for_woocommerce_init() {
		load_plugin_textdomain( 'floating-cart-for-woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', 'floating_cart_for_woocommerce_missing_wc_notice' );
			return;
		}

		$GLOBALS['floating_cart_for_woocommerce'] = Floating_Cart_For_Woocommerce::instance();
	}

endif;
