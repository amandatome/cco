function handleTransparent() {
	let stickyRows = document.querySelectorAll( '.header--row.is_sticky' );
	if ( stickyRows.length > 0 ) {
		return false;
	}
	let hfgHeader = document.querySelector( '.hfg_header' );
	document.querySelector(
			'.neve-main' ).style.marginTop = '-' + hfgHeader.offsetHeight + 'px';
}

function initHeader() {
	let
			stickyRows = document.querySelectorAll( '.header--row.is_sticky' ),
			headerTag = document.querySelector( 'header.header' ),
			transparent = document.querySelector( '.neve-transparent-header' );

	if ( stickyRows.length > 0 && headerTag !== null ) {
		addPlaceholderAndStickHeader();
		let rowContainer = document.querySelector( '.hfg_header.has-sticky-rows' );
		const observer = new IntersectionObserver( (entries) => {
			if ( entries[0].isIntersecting === true ) {
				rowContainer.classList.remove( 'is-stuck' );
				return false;
			}
			rowContainer.classList.add( 'is-stuck' );
		}, { rootMargin: '20px 0px 25px 0px' } );
		observer.observe( headerTag );
	}
	if ( transparent !== null ) {
		handleTransparent();
	}
}

function addPlaceholderAndStickHeader() {
	let headerPlaceholder = document.querySelector(
			'.sticky-header-placeholder' ),
			hfgHeader = document.querySelector( '.hfg_header' ),
			transparent = document.querySelector( '.neve-transparent-header' );

	if ( headerPlaceholder === null && transparent === null ) {
		headerPlaceholder = document.createElement( 'div' );
		headerPlaceholder.classList.add( 'sticky-header-placeholder' );
		hfgHeader.parentNode.insertBefore(headerPlaceholder, hfgHeader.nextSibling);
	}
	hfgHeader.classList.add( 'has-sticky-rows' );
	if ( headerPlaceholder !== null ) {
		headerPlaceholder.style.height = hfgHeader.offsetHeight + 'px';
	}
}

window.addEventListener(
		'load',
		function() {
			initHeader();
		}
);
window.addEventListener(
		'selective-refresh-content-rendered',
		function() {
			initHeader();
		}
);

/**
 * Do resize events debounced.
 */
let neveResizeTimeout;
window.addEventListener( 'resize', function() {
	clearTimeout( neveResizeTimeout );
	neveResizeTimeout = setTimeout( initHeader, 500 );
} );
