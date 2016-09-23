jQuery( document ).ready( function( $ ) {

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
	}

	var count = $("#wp-admin-bar-ln_all_notices ul li").length;
	$( "#wp-admin-bar-ln_menu > a" ).prepend( "<span class='ln-notifications'>" + count + "" );

} );