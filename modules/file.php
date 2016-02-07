<?php
/**
 * @package CF7BS
 * @version 1.3.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

remove_action( 'wpcf7_init', 'wpcf7_add_shortcode_file' );
add_action( 'wpcf7_init', 'cf7bs_add_shortcode_file' );

function cf7bs_add_shortcode_file() {
	wpcf7_add_shortcode( array(
		'file',
		'file*',
	), 'cf7bs_file_shortcode_handler', true );
}

function cf7bs_file_shortcode_handler( $tag ) {
	$tag = new WPCF7_Shortcode( $tag );

	if ( empty( $tag->name ) ) {
		return '';
	}

	$mode = $status = 'default';

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type );
	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
		$status = 'error';
	}

	// size is not used since Bootstrap input fields always scale 100%
	//$atts['size'] = $tag->get_size_option( '40' );

	if ( $tag->is_required() ) {
		$mode = 'required';
	}

	$value = (string) reset( $tag->values );
	$placeholder = '';
	if ( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) ) {
		$placeholder = $value;
		$value = '';
	} elseif ( empty( $value ) ) {
		$value = $tag->get_default_option();
	}
	if ( wpcf7_is_posted() && isset( $_POST[$tag->name] ) ) {
		$value = stripslashes_deep( $_POST[$tag->name] );
	}

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> $tag->name,
		'id'				=> $tag->get_option( 'id', 'id', true ),
		'class'				=> $tag->get_class_option( $class ),
		'type'				=> 'file',
		'value'				=> '1',
		'label'				=> $tag->content,
		'help_text'			=> $validation_error,
		'size'				=> cf7bs_get_form_property( 'size' ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns' ),
		'form_layout'		=> cf7bs_get_form_property( 'layout' ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width' ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint' ),
		'mode'				=> $mode,
		'status'			=> $status,
		'tabindex'			=> $tag->get_option( 'tabindex', 'int', true ),
		'wrapper_class'		=> $tag->name,
	), $tag->basetype, $tag->name ) );

	$html = $field->display( false );

	return $html;
}
