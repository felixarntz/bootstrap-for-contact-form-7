<?php
/**
 * Acceptance module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

remove_action( 'wpcf7_init', 'wpcf7_add_shortcode_acceptance' );
add_action( 'wpcf7_init', 'cf7bs_add_shortcode_acceptance' );

function cf7bs_add_shortcode_acceptance() {
	wpcf7_add_shortcode( 'acceptance', 'cf7bs_acceptance_shortcode_handler', true );
}

function cf7bs_acceptance_shortcode_handler( $tag ) {
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
	if ( $tag->has_option( 'invert' ) ) {
		$class .= ' wpcf7-invert';
	}

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> $tag->name,
		'id'				=> $tag->get_option( 'id', 'id', true ),
		'class'				=> $tag->get_class_option( $class ),
		'type'				=> 'checkbox',
		'value'				=> $tag->has_option( 'default:on' ) ? '1' : '0',
		'options'			=> array(
			'1'					=> $tag->content,
		),
		'help_text'			=> $validation_error,
		'size'				=> cf7bs_get_form_property( 'size', 0, $tag ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag ),
		'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag ),
		'group_layout'		=> cf7bs_get_form_property( 'group_layout', 0, $tag ),
		'mode'				=> $mode,
		'status'			=> $status,
		'tabindex'			=> $tag->get_option( 'tabindex', 'int', true ),
		'wrapper_class'		=> $tag->name,
		'label_class'       => $tag->get_option( 'label_class', 'class', true ),
	), $tag->basetype, $tag->name ) );

	$html = $field->display( false );

	return $html;
}
