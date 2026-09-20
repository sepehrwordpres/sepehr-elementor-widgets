/**
 * SEW Dynamic Post Grid Pro
 * AJAX Category Filter + AJAX Load More.
 * Handle: sew-dynamic-post-grid-pro
 *
 * Expects a localized "sewDpgVars" object (from Asset_Manager.php):
 *   { ajaxUrl: '.../admin-ajax.php', nonce: '...' }
 *
 * ---------------------------------------------------------------
 * DEBUG MODE
 * Every step logs to the console under the "[SEW-DPG]" prefix.
 * Open DevTools > Console on the front-end page and you will see,
 * in order: script load, vars check, widgets found, every click,
 * every request sent, every response received (or the exact error).
 * Set DEBUG = false once everything works to silence the logs.
 * ---------------------------------------------------------------
 */
( function () {
	'use strict';

	var DEBUG = true;

	function log() {
		if ( DEBUG && window.console && console.log ) {
			var args = Array.prototype.slice.call( arguments );
			args.unshift( '[SEW-DPG]' );
			console.log.apply( console, args );
		}
	}

	function warn() {
		if ( window.console && console.warn ) {
			var args = Array.prototype.slice.call( arguments );
			args.unshift( '[SEW-DPG]' );
			console.warn.apply( console, args );
		}
	}

	function err() {
		if ( window.console && console.error ) {
			var args = Array.prototype.slice.call( arguments );
			args.unshift( '[SEW-DPG]' );
			console.error.apply( console, args );
		}
	}

	log( 'script file loaded' );

	// One state object per wrapper element, keyed by widget id.
	// Using a plain object instead of relying on "did I already bind
	// listeners on this exact node" -- because with delegated events
	// (see bottom of file) we never need a per-node init step, which
	// removes any chance of "the click handler never got attached
	// because the widget wasn't in the DOM yet when we looked for it".
	var STATE = {};

	function getVars() {
		if ( typeof window.sewDpgVars === 'undefined' ) {
			warn( 'window.sewDpgVars is undefined. This means the script was NOT properly ' +
				'localized by Asset_Manager.php (wp_localize_script), or this handle is not ' +
				'actually being enqueued on this page. AJAX requests will fail their nonce ' +
				'check. Check: is the widget\'s get_script_depends() handle exactly ' +
				'"sew-dynamic-post-grid-pro"? Is Asset_Manager registering the SAME handle?' );
			return { ajaxUrl: '/wp-admin/admin-ajax.php', nonce: '' };
		}
		return window.sewDpgVars;
	}

	function getSettings( wrapper ) {
		var widgetId = wrapper.getAttribute( 'data-widget-id' ) || wrapper.id;

		if ( ! STATE[ widgetId ] ) {
			var parsed = {};
			try {
				parsed = JSON.parse( wrapper.getAttribute( 'data-settings' ) || '{}' );
			} catch ( e ) {
				err( 'Could not parse data-settings JSON for widget', widgetId, e );
			}

			STATE[ widgetId ] = {
				settings:  parsed,
				category:  0,
				page:      1,
				maxPages:  parsed.maxPages || 1,
				isLoading: false,
			};

			log( 'widget initialized', widgetId, STATE[ widgetId ] );
		}

		return STATE[ widgetId ];
	}

	function buildPayload( wrapper, state, page ) {
		var vars     = getVars();
		var settings = state.settings;
		var widgetId = wrapper.getAttribute( 'data-widget-id' ) || wrapper.id;
		var params   = new URLSearchParams();

		params.append( 'action', 'sew_dpg_query' );
		params.append( 'nonce', vars.nonce || '' );
		params.append( 'widget_id', widgetId || '' );
		params.append( 'category', state.category );
		params.append( 'page', page );
		params.append( 'post_type', settings.postType || 'post' );
		params.append( 'orderby', settings.orderby || 'date' );
		params.append( 'order', settings.order || 'DESC' );
		params.append( 'posts_per_page', settings.postsPerPage || 6 );
		params.append( 'show_image', settings.showImage ? 'yes' : '' );
		params.append( 'show_excerpt', settings.showExcerpt ? 'yes' : '' );
		params.append( 'excerpt_length', settings.excerptLength || 20 );
		params.append( 'show_date', settings.showDate ? 'yes' : '' );
		params.append( 'show_read_more', settings.showReadMore ? 'yes' : '' );
		params.append( 'read_more_text', settings.readMoreText || '' );

		( settings.includeCategories || [] ).forEach( function ( id ) {
			params.append( 'include_categories[]', id );
		} );
		( settings.excludeCategories || [] ).forEach( function ( id ) {
			params.append( 'exclude_categories[]', id );
		} );

		if ( ! vars.nonce ) {
			warn( 'Sending AJAX request with an EMPTY nonce -- the server will reject this ' +
				'(check_ajax_referer will fail). See the warning above about sewDpgVars.' );
		}

		return params;
	}

	function setLoading( wrapper, state, isLoading ) {
		state.isLoading = isLoading;

		var grid        = wrapper.querySelector( '.sew-dpg-grid' );
		var loadMoreBtn = wrapper.querySelector( '.sew-dpg-load-more' );

		if ( grid ) {
			grid.classList.toggle( 'is-loading', isLoading );
		}
		if ( loadMoreBtn ) {
			loadMoreBtn.disabled = isLoading;
			loadMoreBtn.textContent = isLoading
				? ( state.settings.loadingText || 'Loading...' )
				: ( state.settings.loadMoreText || 'Load More' );
		}
	}

	/**
	 * Show/hide/replace the load-more control based on current state.
	 * SEW FIX: this used to permanently overwrite the wrap's innerHTML
	 * with an "end" message and never restored the button, so switching
	 * to a category that DID have more pages left Load More broken for
	 * the rest of the session. Now it always rebuilds from scratch.
	 */
	function updateLoadMoreVisibility( wrapper, state ) {
		var loadMoreWrap = wrapper.querySelector( '.sew-dpg-load-more-wrap' );

		if ( ! loadMoreWrap ) {
			// Enable Load More was off entirely for this widget instance.
			return;
		}

		var hasMore = state.page < state.maxPages;

		if ( hasMore ) {
			loadMoreWrap.style.display = '';
			loadMoreWrap.innerHTML =
				'<button type="button" class="sew-dpg-btn sew-dpg-load-more">' +
				escapeHtml( state.settings.loadMoreText || 'Load More' ) +
				'</button>';
		} else if ( state.maxPages > 1 ) {
			// There WAS more than one page (so Load More was relevant here) --
			// now we've reached the end, show the end message.
			loadMoreWrap.style.display = '';
			loadMoreWrap.innerHTML =
				'<p class="sew-dpg-load-more-end">' +
				escapeHtml( state.settings.endText || 'No more posts' ) +
				'</p>';
		} else {
			// Never had more than one page for this query -- hide entirely.
			loadMoreWrap.style.display = 'none';
		}
	}

	function escapeHtml( str ) {
		var div = document.createElement( 'div' );
		div.textContent = str;
		return div.innerHTML;
	}

	function buildAjaxUrl( baseUrl, action ) {
		// SEW FIX: append ?action=... directly to the URL's query string
		// in addition to sending it in the POST body. admin-ajax.php reads
		// $_REQUEST['action'] (GET *or* POST), and WordPress core's own
		// fallback for an empty action is: wp_die( '0', 400 ) -- exactly
		// the 400 + "0" response seen in testing. Putting it on the query
		// string guarantees PHP sees it even if something on the host
		// (proxy/security layer) mishandles the urlencoded POST body.
		var separator = baseUrl.indexOf( '?' ) === -1 ? '?' : '&';
		return baseUrl + separator + 'action=' + encodeURIComponent( action );
	}

	function request( wrapper, state, page, mode ) {
		if ( state.isLoading ) {
			log( 'request ignored, already loading' );
			return;
		}

		var vars = getVars();
		var grid = wrapper.querySelector( '.sew-dpg-grid' );

		if ( ! grid ) {
			err( 'No .sew-dpg-grid element found inside wrapper -- cannot render results.' );
			return;
		}

		setLoading( wrapper, state, true );

		var payload   = buildPayload( wrapper, state, page );
		var targetUrl = buildAjaxUrl( vars.ajaxUrl, 'sew_dpg_query' );

		log( 'sending AJAX request', { mode: mode, page: page, category: state.category, url: targetUrl } );
		log( 'request body', payload.toString() );

		fetch( targetUrl, {
			method: 'POST',
			credentials: 'same-origin',
			// SEW FIX: no manual Content-Type header. Passing a
			// URLSearchParams body lets the browser set the correct
			// "application/x-www-form-urlencoded;charset=UTF-8" header
			// itself. Manually overriding it was the likely cause of
			// PHP receiving an empty $_POST on this host.
			body: payload,
		} )
			.then( function ( response ) {
				log( 'HTTP status', response.status );
				return response.text().then( function ( text ) {
					var json;
					try {
						json = JSON.parse( text );
					} catch ( parseErr ) {
						err( 'Response was not valid JSON. This usually means PHP produced a ' +
							'fatal error, a warning/notice, or check_ajax_referer() died with an ' +
							'HTML 403 page instead of JSON. Raw response below:' );
						err( text );
						throw parseErr;
					}
					return json;
				} );
			} )
			.then( function ( response ) {
				log( 'parsed response', response );

				if ( ! response || ! response.success ) {
					err( 'Server responded with success=false.', response && response.data );
					setLoading( wrapper, state, false );
					return;
				}

				var data = response.data;

				if ( 'filter' === mode ) {
					grid.innerHTML = data.html || '<p class="sew-dpg-no-posts">No posts found.</p>';
				} else {
					grid.insertAdjacentHTML( 'beforeend', data.html || '' );
				}

				state.page     = data.page;
				state.maxPages = data.max_pages;

				log( 'state after response', { page: state.page, maxPages: state.maxPages } );

				updateLoadMoreVisibility( wrapper, state );
				setLoading( wrapper, state, false );
			} )
			.catch( function ( e ) {
				err( 'AJAX request failed:', e );
				setLoading( wrapper, state, false );
			} );
	}

	/* -----------------------------------------------------------
	 * Delegated event listeners on document.
	 * Chosen over per-widget addEventListener() so the grid works
	 * even if this widget's markup is injected into the page AFTER
	 * DOMContentLoaded (popups, tabs, AJAX-loaded templates, etc.) --
	 * no separate "initWidget()" pass is required.
	 * ----------------------------------------------------------- */
	document.addEventListener( 'click', function ( event ) {
		var filterBtn = event.target.closest( '.sew-dpg-filter-btn' );
		if ( filterBtn ) {
			var wrapper = filterBtn.closest( '.sew-dpg-wrapper' );
			if ( ! wrapper ) {
				err( 'Filter button clicked but no ancestor .sew-dpg-wrapper found.' );
				return;
			}

			var state = getSettings( wrapper );
			if ( state.isLoading ) {
				return;
			}

			var category = parseInt( filterBtn.getAttribute( 'data-category' ), 10 ) || 0;
			log( 'filter clicked', { category: category } );

			wrapper.querySelectorAll( '.sew-dpg-filter-btn' ).forEach( function ( b ) {
				b.classList.remove( 'is-active' );
			} );
			filterBtn.classList.add( 'is-active' );

			state.category = category;
			state.page = 1;

			request( wrapper, state, 1, 'filter' );
			return;
		}

		var loadMoreBtn = event.target.closest( '.sew-dpg-load-more' );
		if ( loadMoreBtn ) {
			var lmWrapper = loadMoreBtn.closest( '.sew-dpg-wrapper' );
			if ( ! lmWrapper ) {
				err( 'Load More button clicked but no ancestor .sew-dpg-wrapper found.' );
				return;
			}

			var lmState = getSettings( lmWrapper );
			log( 'load more clicked', { currentPage: lmState.page, maxPages: lmState.maxPages } );

			if ( lmState.isLoading ) {
				return;
			}
			if ( lmState.page >= lmState.maxPages ) {
				log( 'load more ignored: already on last page' );
				return;
			}

			request( lmWrapper, lmState, lmState.page + 1, 'append' );
		}
	} );

	// Sanity check on load: warn loudly if the required localized
	// object or any widget markup is missing, so a broken setup is
	// obvious from the console without clicking anything.
	document.addEventListener( 'DOMContentLoaded', function () {
		var wrappers = document.querySelectorAll( '.sew-dpg-wrapper' );
		log( 'DOMContentLoaded --', wrappers.length, 'widget(s) found on page' );

		if ( wrappers.length === 0 ) {
			warn( 'No .sew-dpg-wrapper elements found on this page at DOMContentLoaded. If the ' +
				'widget is added to the page later (popup, AJAX tab, etc.) this is expected -- ' +
				'delegated click handling will still work once it appears.' );
		}

		getVars(); // Triggers the sewDpgVars warning immediately if missing.
	} );
} )();