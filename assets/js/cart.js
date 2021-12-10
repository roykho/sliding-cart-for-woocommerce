var floatingCartForWooCommerceDomReady = function( callback ) {
	if ( 'loading' !== document.readyState ) {
		callback();
	} else {
		document.addEventListener( 'DOMContentLoaded', callback );
	}
}

var floatingCartForWooCommerce = {
	init: function() {
		var autoHideTimer,
			autoHideDuration = parseInt( document.getElementById( 'floating-cart-for-woocommerce-autohide-duration' ).value, 10 ),
			cart = document.getElementById( 'floating-cart-for-woocommerce-wrapper' );

		function setAutoHide() {
			if ( 0 < parseInt( autoHideDuration, 10 ) ) {
				var time = autoHideDuration * 1000;

				window.clearTimeout( autoHideTimer );
			
				// Set the timer for the cart and slide back when expired.
				autoHideTimer = window.setTimeout( function() { cart.classList.remove( 'show' ); }, time );
			}
		}

		setAutoHide();

		function clearAutoHide() {
			if ( 0 > parseInt( autoHideDuration, 10 ) ) {
				window.clearTimeout( autoHideTimer );
			}
		}

		cart.addEventListener( 'mouseover', function( event ) {
			clearAutoHide();
		} );

		cart.addEventListener( 'mouseout', function( event ) {
			setAutoHide();
		} );

		document.querySelector( '.floating-cart-for-woocommerce-cart-tab' ).addEventListener( 'click', function() {
			// Toggle show/hide on cart.
			if ( cart.classList.contains( 'show' ) ) {
				cart.classList.remove( 'show' );

				clearAutoHide();
			} else {
				cart.classList.add( 'show' );

				setAutoHide();
			}
		} );
	}
}

floatingCartForWooCommerceDomReady( function() {
	floatingCartForWooCommerce.init();
} );
