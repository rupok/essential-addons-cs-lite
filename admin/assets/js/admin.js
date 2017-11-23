( function( $ ) {
	'use strict';
	// Init jQuery Ui Tabs
	$( ".eacs-settings-tabs" ).tabs();

	$( '.eacs-get-pro' ).on( 'click', function() {
		swal({
	  		title: '<h2><span>Go</span> Premium',
	  		type: 'warning',
	  		html:
	    		'Purchase <b><a href="https://essential-addons.com/cornerstone/buy.php" target="_blank" rel="nofollow">premium version</a></b> to unlock these pro elements.',
	  		showCloseButton: true,
	  		showCancelButton: false,
	  		focusConfirm: true,
		});
	} );

	// Adding link id after the url
	$('.eacs-settings-tabs ul li a').click(function () {
		var tabUrl = $(this).attr( 'href' );
	  	window.location.hash = tabUrl;
	   	$('html, body').scrollTop(tabUrl);
	});

	// Saving Data With Ajax Request
	$( 'form#eacs-settings' ).on( 'submit', function(e) {
		e.preventDefault();

		$.ajax( {
			url: settings.ajaxurl,
			type: 'post',
			data: {
				action: 'save_settings_with_ajax',
				fields: $( 'form#eacs-settings' ).serialize(),
			},
			success: function( response ) {
				swal(
				  'Settings Saved!',
				  'Click OK to continue',
				  'success'
				);
			},
			error: function() {
				swal(
				  'Oops...',
				  'Something went wrong!',
				  'error'
				);
			}
		} );

	} );

} )( jQuery );
