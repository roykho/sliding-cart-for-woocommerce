<?php
/**
 * Admin class
 *
 * @package Sliding_Cart_For_Woocommerce_Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class
 *
 * @since 1.0.0
 */
class Sliding_Cart_For_Woocommerce_Admin {
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
		$this->id    = 'sliding_cart_for_woocommerce';
		$this->label = __( 'Sliding Cart for WooCommerce', 'sliding-cart-for-woocommerce' );

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
			'sliding_cart_for_woocommerce_settings',
			array(
				array(
					'title' => __( 'Sliding Cart Settings', 'sliding-cart-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => $this->id . '_options',
				),

				array(
					'title'   => __( 'Show only on Shop Pages', 'sliding-cart-for-woocommerce' ),
					'desc'    => __( 'Enable to only show the sliding cart on shop pages.', 'sliding-cart-for-woocommerce' ),
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

new Sliding_Cart_For_Woocommerce_Admin();
