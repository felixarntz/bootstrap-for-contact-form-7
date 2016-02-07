<?php
/**
 * @package CF7BS
 * @version 1.3.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

function cf7bs_selected( $selected, $current = true, $echo = true ) {
	$result = '';

	if ( $selected == $current ) {
		$result = ' selected';
		if ( ! wpcf7_support_html5() ) {
			$result .= '="selected"';
		}
	}

	if ( $echo ) {
		echo $result;
	}

	return $result;
}

function cf7bs_multiple_selected( $selected, $current = true, $echo = true ) {
	$result = '';

	if ( is_array( $selected ) ) {
		if ( in_array( $current, $selected ) ) {
			$result = ' selected';
			if ( ! wpcf7_support_html5() ) {
				$result .= '="selected"';
			}
		}
	}

	if ( $echo ) {
		echo $result;
	}

	return $result;
}

function cf7bs_checked( $checked, $current = true, $echo = true ) {
	$result = '';

	if ( $checked == $current ) {
		$result = ' checked';
		if ( ! wpcf7_support_html5() ) {
			$result .= '="checked"';
		}
	}

	if ( $echo ) {
		echo $result;
	}

	return $result;
}

function cf7bs_multiple_checked( $checked, $current = true, $echo = true ) {
	$result = '';

	if ( is_array( $checked ) ) {
		if ( in_array( $current, $checked ) ) {
			$result = ' checked';
			if ( ! wpcf7_support_html5() ) {
				$result .= '="checked"';
			}
		}
	}

	if ( $echo ) {
		echo $result;
	}

	return $result;
}

function cf7bs_enqueue_scripts() {
	$in_footer = true;
	if ( 'header' === WPCF7_LOAD_JS ) {
		$in_footer = false;
	}
	wp_enqueue_script( 'contact-form-7-bootstrap', CF7BS_URL . '/assets/scripts.min.js', array( 'jquery', 'jquery-form', 'contact-form-7' ), CF7BS_VERSION, $in_footer );
}
add_action( 'wpcf7_enqueue_scripts', 'cf7bs_enqueue_scripts' );

function cf7bs_enqueue_styles() {
	wp_dequeue_style( 'contact-form-7' );
}
add_action( 'wpcf7_enqueue_styles', 'cf7bs_enqueue_styles' );

function cf7bs_inline_styles() {
	?>
	<style type="text/css">
		div.wpcf7 .screen-reader-response {
			display: none;
		}
		div.wpcf7 img.ajax-loader {
			border: none;
			vertical-align: middle;
			margin-left: 4px;
		}
		div.wpcf7 .form-inline img.ajax-loader {
			display: inline;
		}
		div.wpcf7 div.ajax-error {
			display: none;
		}
		div.wpcf7 .wpcf7-display-none {
			display: none;
		}
		div.wpcf7 .form-inline .form-group {
			max-width: 250px;
		}
		div.wpcf7 .input-group-addon img {
			height: 100%;
			width: auto;
			max-width: none !important;
			border-radius: 5px;
		}
		div.wpcf7 .input-group-addon.input-group-has-image {
			padding: 0;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'cf7bs_inline_styles' );

function cf7bs_form_class_attr( $class = '' ) {
	$layout = cf7bs_get_form_property( 'layout' );
	if ( in_array( $layout, array( 'horizontal', 'inline' ) ) ) {
		if ( ! empty( $class ) ) {
			$class .= ' ';
		}
		$class .= 'form-' . $layout;
	}
	return $class;
}
add_filter( 'wpcf7_form_class_attr', 'cf7bs_form_class_attr' );

function cf7bs_form_novalidate( $novalidate ) {
	if ( wpcf7_support_html5() ) {
		return ' novalidate';
	}
	return '';
}
add_filter( 'wpcf7_form_novalidate', 'cf7bs_form_novalidate' );

function cf7bs_form_response_output( $output, $class, $content, $form_obj ) {
	$type = 'warning';

	if ( false !== strpos( $class, 'wpcf7-display-none' ) ) {
		$type = '';
	} else {
		if ( false !== strpos( $class, 'wpcf7-mail-sent-ng' ) ) {
			$type = 'danger';
		} elseif ( false !== strpos( $class, 'wpcf7-mail-sent-ok' ) ) {
			$type = 'success';
		} else {
			$type = 'warning';
		}
	}

	$alert = new CF7BS_Alert( array(
		'type'			=> $type,
		'class'			=> $class,
		'dismissible'	=> defined( 'CF7BS_ALERT_DISMISSIBLE' ) && CF7BS_ALERT_DISMISSIBLE,
	) );

	return $alert->open( false ) . esc_html( $content ) . $alert->close( false );
}
add_filter( 'wpcf7_form_response_output', 'cf7bs_form_response_output', 10, 4 );

function cf7bs_validation_error( $output, $name, $form_obj ) {
	$alert = new CF7BS_Alert( array(
		'type'			=> 'warning',
		'class'			=> 'wpcf7-not-valid-tip',
		'dismissible'	=> defined( 'CF7BS_ALERT_DISMISSIBLE' ) && CF7BS_ALERT_DISMISSIBLE,
	) );
	$output = str_replace( '<span role="alert" class="wpcf7-not-valid-tip">', $alert->open( false ), $output );
	$output = str_replace( '</span>', $alert->close( false ), $output );
	return $output;
}
add_filter( 'wpcf7_validation_error', 'cf7bs_validation_error', 10, 3 );

function cf7bs_ajax_json_echo( $items, $result ) {
	if ( isset( $items['invalids'] ) ) {
		foreach ( $items['invalids'] as &$invalid ) {
			$invalid['into'] = str_replace( 'span.wpcf7-form-control-wrap', 'div.form-group', $invalid['into'] );
		}
	}
	return $items;
}
add_filter( 'wpcf7_ajax_json_echo', 'cf7bs_ajax_json_echo', 10, 2 );

function cf7bs_default_template( $template, $prop = 'form' ) {
	if ( 'form' == $prop ) {
		$template = cf7bs_default_form_template();
	}
	return $template;
}
add_filter( 'wpcf7_default_template', 'cf7bs_default_template', 10, 2 );

function cf7bs_default_form_template() {
	$template = '[text* your-name]' . __( 'Your Name', 'bootstrap-for-contact-form-7' ) . '[/text*]' . "\n"
		. '[email* your-email]' . __( 'Your Email', 'bootstrap-for-contact-form-7' ) . '[/email*]' . "\n"
		. '[text your-subject]' . __( 'Subject', 'bootstrap-for-contact-form-7' ) . '[/text]' . "\n"
		. '[textarea your-message]' . __( 'Your Message', 'bootstrap-for-contact-form-7' ) . '[/textarea]' . "\n"
		. '[submit "' . __( 'Send', 'bootstrap-for-contact-form-7' ) . '"]';

	return $template;
}

function cf7bs_parameter_encode( $item ) {
	$encoded = '';
	if ( is_object( $item ) ) {
		return '';
	} elseif ( is_array( $item ) ) {
		$encoded = cf7bs_array_encode( $item );
	} else {
		$encoded = $item;
	}
	return rawurlencode( $encoded );
}

function cf7bs_array_encode( $values ) {
	if ( ! is_array( $values ) ) {
		return '';
	}
	$encoded = '';
	foreach ( $values as $value ) {
		if ( ! empty( $encoded ) ) {
			$encoded .= '---';
		}
		$encoded .= $value;
	}
	return $encoded;
}

function cf7bs_array_decode( $values ) {
	if ( ! is_string( $values ) ) {
		return array();
	}
	$decoded = explode( '---', $values );
	return $decoded;
}

function cf7bs_editor_panel_additional_settings( $post ) {
	if ( ! function_exists( 'wpcf7_editor_panel_additional_settings' ) ) {
		return;
	}

	ob_start();
	wpcf7_editor_panel_additional_settings( $post );
	$output = ob_get_clean();

	$output = str_replace( 'http://contactform7.com/additional-settings/', __( 'https://wordpress.org/plugins/bootstrap-for-contact-form-7/other_notes/', 'bootstrap-for-contact-form-7' ), $output );

	echo $output;
}

function cf7bs_editor_panels( $panels ) {
	if ( ! isset( $panels['additional-settings-panel'] ) ) {
		return $panels;
	}

	$panels['additional-settings-panel']['callback'] = 'cf7bs_editor_panel_additional_settings';

	return $panels;
}
add_filter( 'wpcf7_editor_panels', 'cf7bs_editor_panels' );
