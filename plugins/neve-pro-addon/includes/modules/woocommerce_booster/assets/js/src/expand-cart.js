/**
 * Handles cart expansion.
 *
 * @returns {boolean}
 */
export const cartExpansion  = function () {
	if (typeof jQuery === 'undefined') {
		return false;
	}
	jQuery(document.body).on('added_to_cart', expandCart);
};

const expandCart = function () {
	let cart = document.querySelector('.nv-nav-cart');
	if (cart === null) return;

	cart.style.visibility = 'visible';
	cart.style.opacity = 1;
	setTimeout(
			function () {
				cart.style = null;
			}, 3000
	);
};
