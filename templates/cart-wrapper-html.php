<?php
/**
 * The template for displaying the cart wrapper.
 *
 * This template can be overridden by copying it to yourtheme/sliding-cart-for-woocommerce/cart-wrapper-html.php
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package Sliding-Cart-For-Woocommerce\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="sliding-cart-for-woocommerce-wrapper">
	<div id="sliding-cart-for-woocommerce-center">
		<i class="sliding-cart-for-woocommerce-cart-tab dashicons <?php echo esc_attr( apply_filters( 'sliding_cart_for_woocommerce_cart_tab_icon', 'dashicons-store' ) ); ?>"></i>

		<?php if ( ! WC()->cart->is_empty() ) : ?>
            <?php do_action( 'sliding_cart_for_woocommerce_content' ); ?>
		<?php else : ?>
			<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'sliding-cart-for-woocommerce' ); ?></p>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'sliding_cart_for_woocommerce_after_cart_wrapper' ); ?>
