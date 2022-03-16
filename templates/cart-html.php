<?php
/**
 * The template for displaying the cart contents.
 *
 * This template can be overridden by copying it to yourtheme/floating-cart-for-woocommerce/cart-html.php
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package Floating-Cart-For-Woocommerce\Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
		 <div id="floating-cart-for-woocommerce-inner">
			<div id="floating-cart-for-woocommerce-glide" class="glide">
				<div id="floating-cart-for-woocommerce-glide-track" data-glide-el="track" class="glide__track">
					<ul class="woocommerce-mini-cart cart_list product_list_widget glide__slides">
						<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
								$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
								$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<li class="glide__slide woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr__( 'Remove this item', 'floating-cart-for-woocommerce' ),
											esc_attr( $product_id ),
											esc_attr( $cart_item_key ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
									?>
									<?php if ( empty( $product_permalink ) ) : ?>
										<?php echo $thumbnail . wp_kses_post( $product_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php else : ?>
										<a href="<?php echo esc_url( $product_permalink ); ?>" class="floating-cart-for-woocommerce-image-link">
											<?php echo $thumbnail . wp_kses_post( $product_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</a>
									<?php endif; ?>
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</li>
								<?php
							}
						}
						?>
					</ul>
					<div class="glide__arrows" data-glide-el="controls">
						<button class="glide__arrow glide__arrow--left dashicons dashicons-arrow-left-alt2" data-glide-dir="<" title="<?php esc_attr_e( 'prev', 'floating-cart-for-woocommerce' ); ?>"></button>
						<button class="glide__arrow glide__arrow--right dashicons dashicons-arrow-right-alt2" data-glide-dir=">" title="<?php esc_attr_e( 'next', 'floating-cart-for-woocommerce' ); ?>"></button>
					</div>
				</div>
			</div>

			<div class="floating-cart-for-woocommerce-checkout">
				<p class="woocommerce-mini-cart__total total">
					<?php
					/**
					* Hook: woocommerce_widget_shopping_cart_total.
					*
					* @hooked woocommerce_widget_shopping_cart_subtotal - 10
					*/
					do_action( 'woocommerce_widget_shopping_cart_total' );
					?>
				</p>

				<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>
			</div>
		</div>