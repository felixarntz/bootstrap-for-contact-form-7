<?php
/**
 * Submit module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

add_action( 'wpcf7_init', 'cf7bs_add_shortcode_submit', 11 );

function cf7bs_add_shortcode_submit() {
	$add_func    = function_exists( 'wpcf7_add_form_tag' )    ? 'wpcf7_add_form_tag'    : 'wpcf7_add_shortcode';
	$remove_func = function_exists( 'wpcf7_remove_form_tag' ) ? 'wpcf7_remove_form_tag' : 'wpcf7_remove_shortcode';

	$tags = array(
		'submit'
	);
	foreach ( $tags as $tag ) {
		call_user_func( $remove_func, $tag );
	}

	call_user_func( $add_func, $tags, 'cf7bs_submit_shortcode_handler' );
}

function cf7bs_submit_shortcode_handler( $tag ) {
	$classname = class_exists( 'WPCF7_FormTag' ) ? 'WPCF7_FormTag' : 'WPCF7_Shortcode';

	$tag_obj = new $classname( $tag );

	$class = wpcf7_form_controls_class( $tag_obj->type );

	$value = isset( $tag_obj->values[0] ) ? $tag_obj->values[0] : '';
	if ( empty( $value ) ) {
		$value = __( 'Send', 'contact-form-7' );
	}

	$size = cf7bs_get_form_property( 'submit_size', 0, $tag_obj );
	if ( ! $size ) {
		$size = cf7bs_get_form_property( 'size', 0, $tag_obj );
	}

	$button = new CF7BS_Button( array(
		'mode'				=> 'submit',
		'id'				=> $tag_obj->get_option( 'id', 'id', true ),
		'class'				=> $tag_obj->get_class_option( $class ),
		'title'				=> $value,
		'type'				=> cf7bs_get_form_property( 'submit_type', 0, $tag_obj ),
		'size'				=> $size,
		'tabindex'			=> $tag_obj->get_option( 'tabindex', 'int', true ),
		'align'				=> $tag_obj->get_option( 'align', '[A-Za-z]+', true ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag_obj ),
		'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
	) );

	$html = $button->display( false );

	return $html;
}
