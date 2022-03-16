jQuery( document ).ready( function( $ ) { 
	'use strict';
	var cart,
		glideTrack,
		glide,
		isMobile;

	$.floatingCartForWooCommerce = {
		init: function() {
			cart       = $( '#floating-cart-for-woocommerce-wrapper' );
			glideTrack = $( '#floating-cart-for-woocommerce-glide-track' );
			isMobile   = floating_cart_for_woocommerce_local.isMobile;

			if ( glideTrack.length ) {
				glide = new Glide( '.glide', {
					type: "slider",
					rewind: false,
					animationDuration: 150,
					perView: 4,
					focusAt: 'center',
					breakpoints: {
						1201: {
							perView: 6
						},
						1025: {
							perView: 5
						},
						769: {
							perView: 4
						},
						481: {
							perView: 3
						},
						320: {
							perView: 2
						}
					}
				} );

				glide.mount();
			}

			if ( isMobile ) {
				$( document ).on( 'click', '.floating-cart-for-woocommerce-cart-tab', function() {
					if ( ! cart.hasClass( 'show' ) ) {
						cart.addClass( 'show' );
					} else {
						cart.removeClass( 'show' );
					}
				} );
			} else {
				$( document ).on( 'mouseover', '.floating-cart-for-woocommerce-cart-tab', function() {
					if ( ! cart.hasClass( 'show' ) ) {
						cart.addClass( 'show' );
					}
				} );
			}

			cart.on( 'mouseleave', function() {
				cart.removeClass( 'show' );
			} );
		},
		refresh_cart: function () {
			$( document ).on( 'added_to_cart removed_from_cart', function( e ) {
				var data = {
					action: 'floating_cart_for_woocommerce_refresh',
					floating_cart_for_woocommerce_ajax_refresh_cart_nonce: floating_cart_for_woocommerce_local.floating_cart_for_woocommerce_ajax_refresh_cart_nonce
				},
				cart = $( '#floating-cart-for-woocommerce-wrapper' );

				$.post( floating_cart_for_woocommerce_local.ajaxurl, data, function( response ) {
					if ( 'added_to_cart' === e.type && ! $( '#floating-cart-for-woocommerce-inner' ).length ) {
						$( '#floating-cart-for-woocommerce-center p.woocommerce-mini-cart__empty-message' ).replaceWith( response.cart_output );
					} else {
						$( '#floating-cart-for-woocommerce-inner', cart ).replaceWith( response.cart_output );
					}

					$.floatingCartForWooCommerce.init();
					glide.update( { startAt: parseInt( response.item_count, 10 ) - 1 } );

					// Show the cart when add to cart is clicked if hidden.
					if ( ! cart.hasClass( 'show' ) ) {
						cart.addClass( 'show' );
					}
				} );
			} );
		}
	}

	$.floatingCartForWooCommerce.init();
	$.floatingCartForWooCommerce.refresh_cart();
} );
