/*!
 * Bootstrap for Contact Form 7 (https://wordpress.org/plugins/bootstrap-for-contact-form-7/)
 * By Felix Arntz (https://leaves-and-love.net)
 * Licensed under GNU General Public License v2 (or later) (http://www.gnu.org/licenses/gpl-2.0.html)
 */
( function ( $ ) {
	'use strict';

	window.wpcf7.notValidTip = function( target, message ) {
		var $target = $( target );

		$target.addClass( 'has-error' );
		$( '.wpcf7-not-valid-tip', $target ).remove();

		if ( ! $target.parents( '.wpcf7-form' ).hasClass( 'form-inline' ) ) {
			if ( $target.parents( '.wpcf7-form' ).hasClass( 'form-horizontal' ) ) {
				$target.children( 'div' ).append( '<span class="help-block wpcf7-not-valid-tip">' + message + '</span>' );
			} else {
				$target.append( '<span class="help-block wpcf7-not-valid-tip">' + message + '</span>' );
			}

			if ( $target.is( '.use-floating-validation-tip *' ) ) {
				var fadeOut = function( target ) {
					$( target ).not( ':hidden' ).animate( {
						opacity: 0
					}, 'fast', function() {
						$( this ).css( { 'z-index': -100 } );
					} );
				};

				$target.on( 'mouseover', '.wpcf7-not-valid-tip', function() {
					fadeOut( this );
				} );

				$target.on( 'focus', ':input', function() {
					fadeOut( $( '.wpcf7-not-valid-tip', $target ) );
				} );
			}
		}
	};

	window.wpcf7.clearResponse = function( form ) {
		var $form  = $( form );
		var $close = $form.find( 'div.wpcf7-response-output .close' );

		$form.removeClass( 'invalid spam sent failed' );
		$form.siblings( '.screen-reader-response' ).html( '' ).attr( 'role', '' );

		$( '.wpcf7-not-valid-tip', $form ).remove();
		$( '[aria-invalid]', $form ).attr( 'aria-invalid', 'false' );
		$( '.wpcf7-form-control', $form ).removeClass( 'wpcf7-not-valid' );
		$( 'div.form-group', $form ).removeClass( 'has-error' );
		$( 'img.ajax-loader', $form ).removeClass( 'is-active' );

		$( '.wpcf7-response-output', $form )
			.hide().empty().removeAttr( 'role' )
			.removeClass( 'wpcf7-mail-sent-ok wpcf7-mail-sent-ng wpcf7-validation-errors wpcf7-spam-blocked alert-warning alert-success alert-danger' );

		if ( 0 < $close.length ) {
			$form.find( 'div.wpcf7-response-output' ).append( $close );
		}
	};

	// add Bootstrap Alert classes to response output
	$( function() {
		$( 'div.wpcf7' ).on( 'invalid.wpcf7', function() {
			$( this ).find( 'div.wpcf7-response-output' ).addClass( 'alert-warning' );
		});
		$( 'div.wpcf7' ).on( 'spam.wpcf7', function() {
			$( this ).find( 'div.wpcf7-response-output' ).addClass( 'alert-warning' );
		});
		$( 'div.wpcf7' ).on( 'mailsent.wpcf7', function() {
			$( this ).find( 'div.wpcf7-response-output' ).addClass( 'alert-success' );
		});
		$( 'div.wpcf7' ).on( 'mailfailed.wpcf7', function() {
			$( this ).find( 'div.wpcf7-response-output' ).addClass( 'alert-danger' );
		});

		$( 'div.wpcf7' ).on( 'click', 'div.wpcf7-response-output .close', function( e ) {
			$( this ).parent().hide();
			e.preventDefault();
		});
	});

	// WPCF7 Function Override: Adjusted for Bootstrap Help Block Output and Status Class
	$.fn.wpcf7NotValidTip = function( message ) {
		return this.each( function() {
			var $into = $( this );
			$into.addClass( 'has-error' );

			if ( ! $into.parents( '.wpcf7-form' ).hasClass( 'form-inline' ) ) {
				$into.find( 'span.wpcf7-not-valid-tip' ).remove();
				if ( $into.parents( '.wpcf7-form' ).hasClass( 'form-horizontal' ) ) {
					$into.children( 'div' ).append( '<span class="help-block wpcf7-not-valid-tip">' + message + '</span>' );
				} else {
					$into.append( '<span class="help-block wpcf7-not-valid-tip">' + message + '</span>' );
				}
				$into.slideDown( 'fast' );

				if ( $into.is( '.use-floating-validation-tip *' ) ) {
					$( '.wpcf7-not-valid-tip', $into ).mouseover( function() {
						$( this ).wpcf7FadeOut();
					});

					$( ':input', $into ).focus( function() {
						$( '.wpcf7-not-valid-tip', $into ).not( ':hidden' ).wpcf7FadeOut();
					});
				}
			}
		});
	};

	// WPCF7 Function Override: Different DOM Element is required
	$.fn.wpcf7RefillQuiz = function( quiz ) {
		return this.each( function() {
			var form = $( this );

			$.each( quiz, function( i, n ) {
				form.find( ':input[name="' + i + '"]' ).clearFields();
				form.find( ':input[name="' + i + '"]' ).siblings( 'p.wpcf7-quiz-label' ).text( n[0] );
				form.find( 'input:hidden[name="_wpcf7_quiz_answer_' + i + '"]' ).attr( 'value', n[1] );
			});
		});
	};

	// WPCF7 Function Override: Adjusted for Bootstrap Alert classes and Status Class
	$.fn.wpcf7ClearResponseOutput = function() {
		return this.each(function() {
			var $close = $( this ).find( 'div.wpcf7-response-output .close' );
			$( this ).find( 'div.wpcf7-response-output' ).hide().empty().removeClass( 'wpcf7-mail-sent-ok wpcf7-mail-sent-ng wpcf7-validation-errors wpcf7-spam-blocked alert-warning alert-success alert-danger' ).removeAttr( 'role' );
			if ( 0 < $close.length ) {
				$( this ).find( 'div.wpcf7-response-output' ).append( $close );
			}
			$( this ).find( 'div.form-group' ).removeClass( 'has-error' );
			$( this ).find( 'span.wpcf7-not-valid-tip' ).remove();
			$( this ).find( 'img.ajax-loader' ).removeClass( 'is-active' );
		});
	};

} )( jQuery );
