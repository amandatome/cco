/* global neveWooBooster */
import inView from "in-view";
import axios from 'axios';

import { initializeModal } from "./modal.js";
import { initializeWishList } from "./wish-list.js";

let page = 2;


/**
 * Trigger request once the sentinel is in view.
 */
function initializeInfiniteScroll() {
    inView('.load-more-products').on('enter', get_more_products);
}

/**
 * Request for more posts.
 */
function get_more_products() {
    document.querySelector('.load-more-products .nv-loader').style.display = "block";
    if (typeof parent.wp.customize === 'undefined') {
        request_more_products();
    } else {
        parent.wp.customize.requestChangesetUpdate().then(
            request_more_products
        );
    }
}

/**
 * Request for more products.
 */
function request_more_products() {

    let elem       = document.querySelector( '.load-more-products' );
    let query      = neveWooBooster.infiniteScrollQuery;
    let requestUrl = neveWooBooster.infiniteProductsEndpoint + page + '/';
    let shop       = document.querySelector( '.nv-shop ul.products' );

    if ( typeof wp.customize !== 'undefined' ) {
        requestUrl += '?customize_changeset_uuid=' + wp.customize.settings.changeset.uuid + '&customize_autosaved=on';
    }
    if ( typeof _wpCustomizeSettings !== 'undefined' ) {
        requestUrl += '&customize_preview_nonce=' + _wpCustomizeSettings.nonce.preview;
    }

    let config = {
        headers: {
            'X-WP-Nonce': neveWooBooster.nonce,
            'Content-Type': 'application/json; charset=UTF-8',
        }
    };
    let data = JSON.stringify({
        query: query
    });
    axios.post(requestUrl, data, config ).then( response => {
        let data = response.data;

        if( response.status === 204 ){
            elem.parentNode.removeChild( elem );
        }
        if( response.status === 200 ){
            shop.innerHTML += data.markup;
            initializeModal();
            initializeWishList();
            page++;
            if ( inView.is( document.querySelector( '.load-more-products' ) ) ) {
                request_more_products();
            }
        }
    }).catch( ( error ) => {
        let response = error.response.data;
        console.error( response.message );
        elem.parentNode.removeChild( elem );
    } );
}

export {
    initializeInfiniteScroll
};