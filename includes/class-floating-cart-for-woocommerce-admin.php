<?php
/**
 * Admin class
 *
 * @package Floating_Cart_For_Woocommerce_Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class
 *
 * @since 1.0.0
 */
class Floating_Cart_For_Woocommerce_Admin {
	/**
	 * Reference ID of the admin.
	 *
	 * @var $id
	 */
	protected $id;

	/**
	 * Label of the admin menu.
	 *
	 * @var $label
	 */
	protected $label;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		$this->id    = 'floating_cart_for_woocommerce';
		$this->label = __( 'Floating Cart for WooCommerce', 'floating-cart-for-woocommerce' );

		add_filter( 'woocommerce_products_general_settings', array( $this, 'add_settings' ), 10, 2 );
	}

	/**
	 * Add setting section to products tab.
	 *
	 * @since 1.0.0
	 * @param array $sections Existing sections.
	 * @return array $sections Appended sections.
	 */
	public function add_section( $sections ) {
		$sections[ $this->id ] = $this->label;

		return $sections;
	}

	/**
	 * Add settings.
	 *
	 * @since 1.0.0
	 * @param array  $settings Existing settings.
	 * @return array $settings Modified settings.
	 */
	public function add_settings( $settings ) {
		$settings = array_merge( $settings, apply_filters(
			'floating_cart_for_woocommerce_settings',
			array(
				array(
					'title' => __( 'Floating Cart Settings', 'floating-cart-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => $this->id . '_options',
				),

				array(
					'title'   => __( 'Show only on Shop Pages', 'floating-cart-for-woocommerce' ),
					'desc'    => __( 'Enable to only show the floating cart on shop pages.', 'floating-cart-for-woocommerce' ),
					'id'      => $this->id . '_show_on_shop',
					'default' => 'no',
					'type'    => 'checkbox',
				),

				array(
					'type' => 'sectionend',
					'id'   => $this->id . '_section_end',
				),
			),
			$settings,
		) );

		return $settings;
	}
}

new Floating_Cart_For_Woocommerce_Admin();
