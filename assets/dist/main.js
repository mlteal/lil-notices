jQuery( document ).ready( function( $ ) {

	var count = $("#wp-admin-bar-ln_all_notices ul li").length;
	if ( count >= 0 ) {
		$( "#wp-admin-bar-ln_menu" ).prepend( "<span class='ln-notifications'>" + count + "</span>" );
	}

	if ( count == 0 ) {
		$( "li#wp-admin-bar-ln_menu" ).addClass( "zero-count" );
	}

	$(".ln-notice-item div").replaceWith(function() {
		var msgclass = $( this ).attr('class');
		return "<span class='" + msgclass + "' >" + this.innerHTML + "</span>";
	});

	// we only need to add the click/remove the hover when we're not on "mobile"
	if ( false == $( 'body' ).hasClass( 'mobile' ) ) {

		// disable existing hover functionality so the menu can be toggled instead
		$( '#wp-admin-bar-ln_menu>a' ).on( 'hover', function(e) {
			return false;
		});

		// add a click event to the anchor
		$( '#wp-admin-bar-ln_menu>a' ).on( 'click', function(e) {
			e.preventDefault();
			$( '#wp-admin-bar-ln_menu' ).toggleClass( 'hover' );
		} );

		$( '#wp-admin-bar-ln_menu>span' ).on( 'hover', function(e) {
			return false;
		});

		// add a click event to the span
		$( '#wp-admin-bar-ln_menu>span' ).on( 'click', function(e) {
			e.preventDefault();
			$( '#wp-admin-bar-ln_menu' ).toggleClass( 'hover' );
		} );
	}


} );