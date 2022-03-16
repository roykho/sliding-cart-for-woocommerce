<?php
/**
 * The template for displaying the cart wrapper.
 *
 * This template can be overridden by copying it to yourtheme/floating-cart-for-woocommerce/cart-wrapper-html.php
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package Floating-Cart-For-Woocommerce\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="floating-cart-for-woocommerce-wrapper">
	<div id="floating-cart-for-woocommerce-center">
		<i class="floating-cart-for-woocommerce-cart-tab dashicons <?php echo esc_attr( apply_filters( 'floating_cart_for_woocommerce_cart_tab_icon', 'dashicons-store' ) ); ?>"></i>

		<?php if ( ! WC()->cart->is_empty() ) : ?>
            <?php do_action( 'floating_cart_for_woocommerce_content' ); ?>
		<?php else : ?>
			<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'floating-cart-for-woocommerce' ); ?></p>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'floating_cart_for_woocommerce_after_cart_wrapper' ); ?>
