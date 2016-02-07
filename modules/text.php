<?php
/**
 * @package CF7BS
 * @version 1.3.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

remove_action( 'wpcf7_init', 'wpcf7_add_shortcode_text' );
add_action( 'wpcf7_init', 'cf7bs_add_shortcode_text' );

function cf7bs_add_shortcode_text() {
	wpcf7_add_shortcode( array(
		'text',
		'text*',
		'email',
		'email*',
		'url',
		'url*',
		'tel',
		'tel*',
	), 'cf7bs_text_shortcode_handler', true );
}

function cf7bs_text_shortcode_handler( $tag ) {
	$tag_obj = new WPCF7_Shortcode( $tag );

	if ( empty( $tag_obj->name ) ) {
		return '';
	}

	$mode = $status = 'default';

	$validation_error = wpcf7_get_validation_error( $tag_obj->name );

	$class = wpcf7_form_controls_class( $tag_obj->type, 'wpcf7-text' );
	if ( in_array( $tag_obj->basetype, array( 'email', 'url', 'tel' ) ) ) {
		$class .= ' wpcf7-validates-as-' . $tag_obj->basetype;
	}
	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
		$status = 'error';
	}

	// size is not used since Bootstrap input fields always scale 100%
	//$atts['size'] = $tag_obj->get_size_option( '40' );

	if ( $tag_obj->is_required() ) {
		$mode = 'required';
	}

	$value = (string) reset( $tag_obj->values );
	$placeholder = '';
	if ( $tag_obj->has_option( 'placeholder' ) || $tag_obj->has_option( 'watermark' ) ) {
		$placeholder = $value;
		$value = '';
	}

	$value = $tag_obj->get_default_option( $value );

	if ( wpcf7_is_posted() && isset( $_POST[ $tag_obj->name ] ) ) {
		$value = stripslashes_deep( $_POST[ $tag_obj->name ] );
	} elseif ( isset( $_GET ) && array_key_exists( $tag_obj->name, $_GET ) ) {
		$value = stripslashes_deep( rawurldecode( $_GET[ $tag_obj->name ] ) );
	}

	$input_before = $tag_obj->get_first_match_option( '/input_before:([^\s]+)/' );
	$input_after = $tag_obj->get_first_match_option( '/input_after:([^\s]+)/' );

	if ( is_array( $input_before ) && isset( $input_before[1] ) ) {
		$input_before = str_replace( '---', ' ', $input_before[1] );
	} else {
		$input_before = '';
	}

	if ( is_array( $input_after ) && isset( $input_after[1] ) ) {
		$input_after = str_replace( '---', ' ', $input_after[1] );
	} else {
		$input_after = '';
	}

	if ( $tag_obj->has_option( 'include_count' ) ) {
		$count_mode = 'input_after';
		$count_down = false;
		$count_options = $tag_obj->get_option( 'include_count', '[A-Za-z]+(:[A-Za-z]+)?', true );
		if ( $count_options ) {
			$count_options = explode( ':', $count_options );
			foreach ( $count_options as $count_option ) {
				switch ( $count_option ) {
					case 'down':
					case 'DOWN':
						$count_down = true;
						break;
					case 'before':
					case 'BEFORE':
						$count_mode = 'input_before';
						break;
					default:
				}
			}
		}

		$tag = cf7bs_text_to_count( $tag, $count_down );

		if ( ! empty( $$count_mode ) ) {
			$$count_mode = wpcf7_count_shortcode_handler( $tag ) . ' ' . $$count_mode;
		} else {
			$$count_mode = wpcf7_count_shortcode_handler( $tag );
		}
	}

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> $tag_obj->name,
		'id'				=> $tag_obj->get_option( 'id', 'id', true ),
		'class'				=> $tag_obj->get_class_option( $class ),
		'type'				=> wpcf7_support_html5() ? $tag_obj->basetype : 'text',
		'value'				=> $value,
		'placeholder'		=> $placeholder,
		'label'				=> $tag_obj->content,
		'help_text'			=> $validation_error,
		'size'				=> cf7bs_get_form_property( 'size' ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns' ),
		'form_layout'		=> cf7bs_get_form_property( 'layout' ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width' ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint' ),
		'mode'				=> $mode,
		'status'			=> $status,
		'readonly'			=> $tag_obj->has_option( 'readonly' ) ? true : false,
		'minlength'			=> $tag_obj->get_minlength_option(),
		'maxlength'			=> $tag_obj->get_maxlength_option(),
		'tabindex'			=> $tag_obj->get_option( 'tabindex', 'int', true ),
		'wrapper_class'		=> $tag_obj->name,
		'input_before'		=> $input_before,
		'input_after'		=> $input_after,
	), $tag_obj->basetype, $tag_obj->name ) );

	$html = $field->display( false );

	return $html;
}

function cf7bs_text_to_count( $tag, $count_down = false ) {
	$tag['type'] = 'count';
	$tag['basetype'] = 'count';
	$tag['options'] = array();

	if ( $count_down ) {
		$tag['options'][] = 'down';
	}

	return $tag;
}
