<?php
/**
 * Count module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.2.0
 */

remove_action( 'wpcf7_init', 'wpcf7_add_shortcode_count' );
add_action( 'wpcf7_init', 'cf7bs_add_shortcode_count' );

function cf7bs_add_shortcode_count() {
	wpcf7_add_shortcode( 'count', 'cf7bs_count_shortcode_handler', true );
}

function cf7bs_count_shortcode_handler( $tag ) {
	$tag_obj = new WPCF7_Shortcode( $tag );

	if ( empty( $tag_obj->name ) ) {
		return '';
	}

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> wpcf7_count_shortcode_handler( $tag ),
		'type'				=> 'custom',
		'label'				=> $tag_obj->content,
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag_obj ),
		'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
		'tabindex'			=> false,
		'wrapper_class'		=> '',
	), $tag_obj->basetype, $tag_obj->name ) );

	$html = $field->display( false );

	return $html;
}
