<?php
/**
 * Plugin Name: Sliding Cart for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/sliding-cart-for-woocommerce/
 * Description: Adds a sliding cart to your shop.
 * Version: 1.0.0
 * Author: Roy Ho
 * Author URI: http://royho.me
 * Text Domain: sliding-cart-for-woocommerce
 * Domain Path: /languages
 * Copyright: (c) 2022 Roy Ho
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package sliding-cart-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.Files.FileName

if ( ! class_exists( 'Sliding_Cart_For_Woocommerce' ) ) :
	define( 'SLIDING_CART_FOR_WOOCOMMERCE_VERSION', '1.0.0' );
	define( 'SLIDING_CART_FOR_WOOCOMMERCE_ABSPATH', dirname( __FILE__ ) . '/' );
	define( 'SLIDING_CART_FOR_WOOCOMMERCE_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );

	/**
	 * Sliding Cart for WooCommerce class
	 */
	class Sliding_Cart_For_Woocommerce {
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
				require_once SLIDING_CART_FOR_WOOCOMMERCE_ABSPATH . 'includes/class-sliding-cart-for-woocommerce-admin.php';
			}

			require_once SLIDING_CART_FOR_WOOCOMMERCE_ABSPATH . 'includes/class-sliding-cart-for-woocommerce-cart.php';
		}

		/**
		 * Main Instance.
		 *
		 * Ensures only one instance is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @return Sliding_Cart_For_Woocommerce
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
			wc_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'sliding-cart-for-woocommerce' ), SLIDING_CART_FOR_WOOCOMMERCE_VERSION );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'sliding-cart-for-woocommerce' ), SLIDING_CART_FOR_WOOCOMMERCE_VERSION );
		}
	}
endif;

/**
 * WooCommerce fallback notice.
 *
 * @since 1.0.0
 */
function sliding_cart_for_woocommerce_missing_wc_notice() {
	/* translators: %s WC download URL link. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Sliding Cart for WooCommerce requires WooCommerce to be installed and active. You can download %s here.', 'sliding-cart-for-woocommerce' ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

if ( ! function_exists( 'sliding_cart_for_woocommerce_init' ) ) :
	add_action( 'plugins_loaded', 'sliding_cart_for_woocommerce_init', 10 );

	/**
	 * Initialize the plugin.
	 */
	function sliding_cart_for_woocommerce_init() {
		load_plugin_textdomain( 'sliding-cart-for-woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', 'sliding_cart_for_woocommerce_missing_wc_notice' );
			return;
		}

		$GLOBALS['sliding_cart_for_woocommerce'] = Sliding_Cart_For_Woocommerce::instance();
	}

endif;
