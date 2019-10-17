import { initializeSliders } from "./sliders.js";
import { initializeRecentlyViewed } from "./recently.js";
import { initVideoAdaptation } from "./video.js";
import { initializeInfiniteScroll } from "./infinite.js";
import { initializeModal } from "./modal.js";
import { initializeWishList } from "./wish-list.js";
import { cartExpansion } from "./expand-cart.js";
import Sticky from "sticky-js";

function run () {
	initializeSliders();
	initializeRecentlyViewed();
	initializeInfiniteScroll();
	addStickies();
	initVideoAdaptation();
	initializeModal();
	initializeWishList();
	toggleShopView();
	cartExpansion();
}

function addStickies () {
	new Sticky(
			'.nv-checkout-fixed-total #order_review, ' +
			'.nv-sidebar-full-width.nv-cart-total-fixed .cart_totals',
			{
				stickyFor: 959,
				marginTop: 20
			}
	);
	new Sticky(
			'.nv-sidebar-left.nv-cart-total-fixed .cart_totals, .nv-sidebar-right.nv-cart-total-fixed .cart_totals',
			{
				stickyFor: 1199,
				marginTop: 20
			}
	);
}

function toggleShopView () {
	let listViewTrigger = document.querySelector('.nv-toggle-list-view');
	let gridViewTrigger = document.querySelector('.nv-toggle-grid-view');
	if (listViewTrigger === null || gridViewTrigger === null) {
		return;
	}
	/* Set onclick event handler for all trigger elements */

	listViewTrigger.addEventListener(
			'click',
			function () {
				listViewTrigger.classList.add('current');
				gridViewTrigger.classList.remove('current');
				let productsWrapper = document.querySelector('.nv-shop');
				if (!productsWrapper.classList.contains('nv-list')) {
					productsWrapper.classList.add('nv-list');
				}
				window.history.replaceState(null, null, '?ref=list');
				changePaginationLinks('list');
			},
			false
	);

	gridViewTrigger.addEventListener(
			'click',
			function () {
				listViewTrigger.classList.remove('current');
				gridViewTrigger.classList.add('current');
				let productsWrapper = document.querySelector('.nv-shop');
				if (productsWrapper.classList.contains('nv-list')) {
					productsWrapper.classList.remove('nv-list');
				}
				window.history.replaceState(null, null, '?ref=grid');
				changePaginationLinks('grid');
			},
			false
	);

}

/**
 * Change pagination links to have persistent view on the next page.
 */
function changePaginationLinks (type) {
	let paginationSelector = document.querySelector('.woocommerce-pagination .page-numbers');
	if (paginationSelector === null) {
		return;
	}
	let pages = paginationSelector.getElementsByTagName('li');
	if (pages === null) {
		return;
	}
	for ( let i = 0; i < pages.length; i++ ) {
		let aTag = pages[i].getElementsByTagName('a')[0];
		if (typeof aTag !== 'undefined') {
			let href = pages[i].getElementsByTagName('a')[0].href;
			let url = new URL(href);
			url.searchParams.set('ref', type);
			pages[i].getElementsByTagName('a')[0].href = url;
		}
	}
}

window.addEventListener(
		'load',
		function () {
			run();
		}
);
