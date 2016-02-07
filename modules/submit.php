<?php
/**
 * @package CF7BS
 * @version 1.3.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

remove_action( 'wpcf7_init', 'wpcf7_add_shortcode_submit' );
add_action( 'wpcf7_init', 'cf7bs_add_shortcode_submit' );

function cf7bs_add_shortcode_submit() {
	wpcf7_add_shortcode( 'submit', 'cf7bs_submit_shortcode_handler' );
}

function cf7bs_submit_shortcode_handler( $tag ) {
	$tag = new WPCF7_Shortcode( $tag );

	$class = wpcf7_form_controls_class( $tag->type );

	$value = isset( $tag->values[0] ) ? $tag->values[0] : '';
	if ( empty( $value ) ) {
		$value = __( 'Send', 'contact-form-7' );
	}

	$size = cf7bs_get_form_property( 'submit_size' );
	if ( ! $size ) {
		$size = cf7bs_get_form_property( 'size' );
	}

	$button = new CF7BS_Button( array(
		'mode'				=> 'submit',
		'id'				=> $tag->get_option( 'id', 'id', true ),
		'class'				=> $tag->get_class_option( $class ),
		'title'				=> $value,
		'type'				=> cf7bs_get_form_property( 'submit_type' ),
		'size'				=> $size,
		'tabindex'			=> $tag->get_option( 'tabindex', 'int', true ),
		'align'				=> $tag->get_option( 'align', '[A-Za-z]+', true ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns' ),
		'form_layout'		=> cf7bs_get_form_property( 'layout' ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width' ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint' ),
	) );

	$html = $button->display( false );

	return $html;
}
