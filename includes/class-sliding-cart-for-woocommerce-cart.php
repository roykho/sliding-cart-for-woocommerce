<?php
/**
 * Cart
 *
 * @package Sliding_Cart_For_WooCommerce_Cart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sliding_Cart_For_WooCommerce_Cart {
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'sliding_cart_for_woocommerce_content', array( $this, 'cart_content' ) );
		add_action( 'wp_footer', array( $this, 'render_cart' ) );
		add_action( 'wp_ajax_nopriv_sliding_cart_for_woocommerce_refresh', array( $this, 'refresh_cart' ) );
		add_action( 'wp_ajax_sliding_cart_for_woocommerce_refresh', array( $this, 'refresh_cart' ) );
	}

	/**
	 * Loads scripts and styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'sliding-cart-for-woocommerce-glide-scripts', plugins_url( 'lib/glide/glide.min.js', dirname( __FILE__ ) ), null, '3.5.2', true );
		wp_enqueue_script( 'sliding-cart-for-woocommerce-scripts', plugins_url( 'assets/js/cart' . $suffix . '.js', dirname( __FILE__ ) ), array( 'jquery' ), SLIDING_CART_FOR_WOOCOMMERCE_VERSION, true );  

		// set the localized variables
		$localized_vars = array(
			'ajaxurl'                                              => admin_url( 'admin-ajax.php' ),
			'sliding_cart_for_woocommerce_ajax_refresh_cart_nonce' => wp_create_nonce( 'sliding_cart_for_woocommerce_ajax_refresh_cart_nonce' ),
			'isMobile'                                             => wp_is_mobile(),
		);

		wp_localize_script( 'sliding-cart-for-woocommerce-scripts', 'sliding_cart_for_woocommerce_local', $localized_vars );

		wp_enqueue_style( 'sliding-cart-for-woocommerce-glide-styles', plugins_url( 'lib/glide/css/glide.core.min.css', dirname( __FILE__ ) ), null, '3.5.2' );

		wp_enqueue_style( 'sliding-cart-for-woocommerce-styles', plugins_url( 'assets/css/cart.css', dirname( __FILE__ ) ), array( 'dashicons' ), SLIDING_CART_FOR_WOOCOMMERCE_VERSION );
	}

	/**
	 * Load the cart content.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function cart_content() {
		wc_get_template( 'cart-html.php', array(), 'sliding-cart-for-woocommerce', SLIDING_CART_FOR_WOOCOMMERCE_TEMPLATE_PATH );
	}

	/**
	 * Renders the cart html on screen.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_cart() {
		$settings = $this->get_settings();

		if ( 'yes' === $settings['show_on_shop'] && ! is_shop() && ! is_product_category() && ! is_product() ) {
			return;
		}

		wc_get_template( 'cart-wrapper-html.php', array(), 'sliding-cart-for-woocommerce', SLIDING_CART_FOR_WOOCOMMERCE_TEMPLATE_PATH );
	}

	/**
	 * Get user settings.
	 *
	 * @since 1.0.0
	 * @return json $settings
	 */
	protected function get_settings() {
		$settings = array(
			'show_on_shop' => get_option( 'sliding_cart_for_woocommerce_show_on_shop', 'yes' ),
		);

		return $settings;
	}

	/**
	 * Cart display on ajax refresh
	 *
	 * @since 1.0.0
	 * @return string $output the HTML of the cart
	 */
	public function refresh_cart() {
		$nonce = $_POST['sliding_cart_for_woocommerce_ajax_refresh_cart_nonce'];

		// Bail if nonce don't match.
		if ( ! wp_verify_nonce( $nonce, 'sliding_cart_for_woocommerce_ajax_refresh_cart_nonce' ) ) {
			wp_die( __( 'Cheatin&#8217; huh?', 'sliding-cart-for-woocommerce' ) );
		}

		ob_start();

		if ( ! WC()->cart->is_empty() ) {
			$this->cart_content();
		} else {
			echo '<p class="woocommerce-mini-cart__empty-message">' . esc_html__( 'No products in the cart.', 'sliding-cart-for-woocommerce' ) . '</p>';
		}

		$cart_output = ob_get_clean();

		$cart = array(
			'item_count'  => count( WC()->cart->get_cart() ),
			'cart_output' => $cart_output,
		);

		wp_send_json( $cart );
	}
}

new Sliding_Cart_For_WooCommerce_Cart();
