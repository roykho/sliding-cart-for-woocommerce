<?php
/**
 * Cart
 *
 * @package Floating_Cart_For_WooCommerce_Cart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Floating_Cart_For_WooCommerce_Cart {
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		//add_action( 'wp', array( $this, 'set_session' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'wp_footer', array( $this, 'render_cart' ) );
	}

	/**
	 * Sets the WC customer session if one is not set.
	 * This is needed so nonces can be verified.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function set_session() {
		if ( ! is_user_logged_in() ) {
			$wc_session = new WC_Session_Handler();
			if ( ! $wc_session->has_session() ) {
				$wc_session->set_customer_session_cookie( true );
			}
		}
	}

	/**
	 * Loads scripts and styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'floating-cart-for-woocommerce-scripts', plugins_url( 'assets/js/cart' . $suffix . '.js', dirname( __FILE__ ) ), null, FLOATING_CART_FOR_WOOCOMMERCE_VERSION, true );  

		// set the localized variables
		$localized_vars = array(
			'ajaxurl'                              => admin_url( 'admin-ajax.php' ),
			'wc_drop_shop_ajax_add_to_cart_nonce'  => wp_create_nonce( 'wc_drop_shop_ajax_add_to_cart_nonce' ),
			'wc_drop_shop_ajax_refresh_cart_nonce' => wp_create_nonce( 'wc_drop_shop_ajax_refresh_cart_nonce' ),
			'wc_drop_shop_ajax_remove_item_nonce'  => wp_create_nonce( 'wc_drop_shop_ajax_remove_item_nonce' ),
			'select_all_options_msg'               => apply_filters( 'woocommerce_drop_shop_select_all_options_msg', __( 'Please select all options before clicking on add to cart.', 'woocommerce-drop-shop' ) ),
			'is_single'                            => is_product() ? 'true' : 'false'
		);

		wp_localize_script( 'floating-cart-for-woocommerce-scripts', 'floating_cart_for_woocommerce_local', $localized_vars );

		wp_enqueue_style( 'floating-cart-for-woocommerce-styles', plugins_url( 'assets/css/cart.css', dirname( __FILE__ ) ), array( 'dashicons' ), FLOATING_CART_FOR_WOOCOMMERCE_VERSION );
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

		wc_get_template( 'cart-html.php', array(
			'autohide_duration' => $settings['autohide_duration'],
		), 'floating-cart-for-woocommerce', FLOATING_CART_FOR_WOOCOMMERCE_TEMPLATE_PATH );
	}

	/**
	 * Get user settings.
	 *
	 * @since 1.0.0
	 * @return json $settings
	 */
	protected function get_settings() {
		$settings = array(
			'show_on_shop'      => get_option( 'floating_cart_for_woocommerce_show_on_shop', 'yes' ),
			'autohide_duration' => get_option( 'floating_cart_for_woocommerce_autohide_duration', '6' ),
		);

		return $settings;
	}

	/**
	 * Gets the image if one is set to display in the cart.
	 *
	 * @since 1.0.0
	 * @param int $product_id Pass in a product id
	 * @param int $variation_id Pass in a variation id
	 * @param int $image_width Pass in the width of the cart image
	 * @param int $image_height Pass in the height of the cart image
	 * @return string $image The URL of the image
	 */
	public static function get_cart_item_image( $product_id = 1, $variation_id = 1, $image_width = 50, $image_height = 50 ) {
		if ( isset( $variation_id ) && ! empty( $variation_id ) ) {

			// get the variation image
			$attach_id = get_post_meta( $variation_id, '_thumbnail_id', true );

			// get the image source
			$image = wp_get_attachment_image_src( $attach_id, array( $image_width, $image_height ) );

			// if image is found
			if ( $image ) {
			  $image = $image[0];
			} else {
				// get the product image
				$attach_id = get_post_meta( $product_id, '_thumbnail_id', true );

				// get the image source
				$image = wp_get_attachment_image_src( $attach_id, array( $image_width, $image_height ) );

				if ( $image ) {
					$image = $image[0];
				} else {
					return wc_placeholder_img_src();
				}
			}
		} else {
			// get the product image
			$attach_id = get_post_meta( $product_id, '_thumbnail_id', true );

			// get the image source
			$image = wp_get_attachment_image_src( $attach_id, array( $image_width, $image_height ) );

			if ( $image ) {
				$image = $image[0];
			} else {
				return wc_placeholder_img_src();
			}
		}

		return $image;
	}
}

new Floating_Cart_For_WooCommerce_Cart();
