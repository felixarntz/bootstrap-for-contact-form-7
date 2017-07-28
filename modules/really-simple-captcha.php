<?php
/**
 * Really Simple CAPTCHA module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

add_action( 'wpcf7_init', 'cf7bs_add_shortcode_captcha', 11 );

function cf7bs_add_shortcode_captcha() {
	$add_func    = function_exists( 'wpcf7_add_form_tag' )    ? 'wpcf7_add_form_tag'    : 'wpcf7_add_shortcode';
	$remove_func = function_exists( 'wpcf7_remove_form_tag' ) ? 'wpcf7_remove_form_tag' : 'wpcf7_remove_shortcode';

	$tags = array(
		'captchac',
		'captchar',
	);
	foreach ( $tags as $tag ) {
		call_user_func( $remove_func, $tag );
	}

	$features = version_compare( WPCF7_VERSION, '4.7', '<' ) ? true : array(
		'name-attr' => true,
	);

	call_user_func( $add_func, 'captchac', 'cf7bs_captcha_shortcode_handler', true );

	$features = version_compare( WPCF7_VERSION, '4.7', '<' ) ? true : array(
		'name-attr'    => true,
		'do-not-store' => true,
	);

	call_user_func( $add_func, 'captchar', 'cf7bs_captcha_shortcode_handler', true );
}

function cf7bs_captcha_shortcode_handler( $tag ) {
	$classname = class_exists( 'WPCF7_FormTag' ) ? 'WPCF7_FormTag' : 'WPCF7_Shortcode';

	$tag_obj = new $classname( $tag );

	if ( 'captchac' == $tag_obj->type && ! class_exists( 'ReallySimpleCaptcha' ) ) {
		return '<em>' . __( 'To use CAPTCHA, you need <a href="http://wordpress.org/extend/plugins/really-simple-captcha/">Really Simple CAPTCHA</a> plugin installed.', 'bootstrap-for-contact-form-7' ) . '</em>';
	}

	if ( empty( $tag_obj->name ) ) {
		return '';
	}

	$validation_error = wpcf7_get_validation_error( $tag_obj->name );

	if ( 'captchac' == $tag_obj->type ) {
		if ( $image_sizes_array = preg_grep( '%^size:[smlSML]$%', $tag_obj->options ) ) {
			$tag_obj->options = array_values( array_diff_key( $tag_obj->options, $image_sizes_array ) );
		}
		$size = cf7bs_get_form_property( 'size', 0, $tag_obj );
		$image_size = 'large' == $size ? 'l' : ( 'small' == $size ? 's' : 'm' );
		$tag_obj->options[] = 'size:' . $image_size;

		$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
			'name'				=> function_exists( 'wpcf7_captchac_form_tag_handler' ) ? wpcf7_captchac_form_tag_handler( $tag ) : ( function_exists( 'wpcf7_captcha_form_tag_handler' ) ? wpcf7_captcha_form_tag_handler( $tag ) : wpcf7_captcha_shortcode_handler( $tag ) ),
			'type'				=> 'custom',
			'label'				=> $tag_obj->content,
			'help_text'			=> $validation_error,
			'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag_obj ),
			'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
			'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
			'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
			'tabindex'			=> false,
			'wrapper_class'		=> '',
		), $tag_obj->basetype, $tag_obj->name ) );

		$html = $field->display( false );

		return $html;
	} elseif ( 'captchar' == $tag_obj->type ) {
		$mode = $status = 'default';

		$class = wpcf7_form_controls_class( $tag_obj->type, 'wpcf7-text' );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
			$status = 'error';
		}

		// size is not used since Bootstrap input fields always scale 100%
		//$atts['size'] = $tag_obj->get_size_option( '40' );

		$value = (string) reset( $tag_obj->values );
		$placeholder = '';
		if ( wpcf7_is_posted() ) {
			$value = '';
		}
		if ( $tag_obj->has_option( 'placeholder' ) || $tag_obj->has_option( 'watermark' ) ) {
			$placeholder = $value;
			$value = '';
		}

		$input_before = $input_after = '';
		if ( $tag_obj->has_option( 'include_captchac' ) && class_exists( 'ReallySimpleCaptcha' ) ) {
			$captchac_mode = $tag_obj->get_option( 'include_captchac', '[A-Za-z]+', true );
			if ( $captchac_mode && 'after' == strtolower( $captchac_mode ) ) {
				$captchac_mode = 'input_after';
			} else {
				$captchac_mode = 'input_before';
			}

			$tag = cf7bs_captchar_to_captchac( $tag );

			$$captchac_mode = function_exists( 'wpcf7_captchac_form_tag_handler' ) ? wpcf7_captchac_form_tag_handler( $tag ) : ( function_exists( 'wpcf7_captcha_form_tag_handler' ) ? wpcf7_captcha_form_tag_handler( $tag ) : wpcf7_captcha_shortcode_handler( $tag ) );
		}

		$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
			'name'				=> $tag_obj->name,
			'id'				=> $tag_obj->get_option( 'id', 'id', true ),
			'class'				=> $tag_obj->get_class_option( $class ),
			'type'				=> 'text',
			'value'				=> $value,
			'placeholder'		=> $placeholder,
			'label'				=> $tag_obj->content,
			'help_text'			=> $validation_error,
			'size'				=> cf7bs_get_form_property( 'size', 0, $tag_obj ),
			'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
			'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
			'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
			'mode'				=> $mode,
			'status'			=> $status,
			'maxlength'			=> $tag_obj->get_maxlength_option(),
			'tabindex'			=> $tag_obj->get_option( 'tabindex', 'int', true ),
			'wrapper_class'		=> $tag_obj->name,
			'label_class'       => $tag_obj->get_option( 'label_class', 'class', true ),
			'input_before'		=> $input_before,
			'input_after'		=> $input_after,
			'input_before_class'=> 'input-group-addon input-group-has-image',
			'input_after_class'	=> 'input-group-addon input-group-has-image',
		), $tag_obj->basetype, $tag_obj->name ) );

		$html = $field->display( false );

		return $html;
	}

	return '';
}

function cf7bs_captchar_to_captchac( $tag ) {
	$classname = class_exists( 'WPCF7_FormTag' ) ? 'WPCF7_FormTag' : 'WPCF7_Shortcode';
	$tag_obj = new $classname( $tag );

	$tag_obj->type = 'captchac';
	$tag_obj->basetype = 'captchac';
	$tag_obj->options = array();

	$size = cf7bs_get_form_property( 'size' );
	$image_size = 'large' == $size ? 'l' : ( 'small' == $size ? 's' : 'm' );
	$tag_obj->options[] = 'size:' . $image_size;

	return $tag;
}

function cf7bs_captchar_has_captchac( $tag ) {
	$classname = class_exists( 'WPCF7_FormTag' ) ? 'WPCF7_FormTag' : 'WPCF7_Shortcode';
	$tag_obj = new $classname( $tag );

	$pattern = sprintf( '/^%s(:.+)?$/i', preg_quote( 'include_captchac', '/' ) );
	return (bool) preg_grep( $pattern, $tag_obj->options );
}

add_filter( 'wpcf7_ajax_onload', 'cf7bs_captcha_ajax_refill', 11 );
add_filter( 'wpcf7_ajax_json_echo', 'cf7bs_captcha_ajax_refill', 11 );

function cf7bs_captcha_ajax_refill( $items ) {
	if ( ! is_array( $items ) ) {
		return $items;
	}

	$fes = wpcf7_scan_form_tags( array( 'type' => 'captchar' ) );

	if ( empty( $fes ) ) {
		return $items;
	}

	$refill = array();

	foreach ( $fes as $fe ) {
		if ( cf7bs_captchar_has_captchac( $fe ) ) {
			$fe = cf7bs_captchar_to_captchac( $fe );

			$name = $fe['name'];
			$options = $fe['options'];

			if ( empty( $name ) ) {
				continue;
			}

			$op = wpcf7_captchac_options( $options );
			if ( $filename = wpcf7_generate_captcha( $op ) ) {
				$captcha_url = wpcf7_captcha_url( $filename );
				$refill[ $name ] = $captcha_url;
			}
		}
	}

	if ( count( $refill ) > 0 ) {
		if ( ! isset( $items['captcha'] ) ) {
			$items['captcha'] = $refill;
		} else {
			$items['captcha'] = array_merge( $items['captcha'], $refill );
		}
	}

	return $items;
}
