<?php
/**
 * Count module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.2.0
 */

add_action( 'wpcf7_init', 'cf7bs_add_shortcode_count', 11 );

function cf7bs_add_shortcode_count() {
	$add_func    = function_exists( 'wpcf7_add_form_tag' )    ? 'wpcf7_add_form_tag'    : 'wpcf7_add_shortcode';
	$remove_func = function_exists( 'wpcf7_remove_form_tag' ) ? 'wpcf7_remove_form_tag' : 'wpcf7_remove_shortcode';

	$tags = array(
		'count'
	);
	foreach ( $tags as $tag ) {
		call_user_func( $remove_func, $tag );
	}

	$features = version_compare( WPCF7_VERSION, '4.7', '<' ) ? true : array(
		'name-attr' => true,
	);

	call_user_func( $add_func, $tags, 'cf7bs_count_shortcode_handler', $features );
}

function cf7bs_count_shortcode_handler( $tag ) {
	$classname = class_exists( 'WPCF7_FormTag' ) ? 'WPCF7_FormTag' : 'WPCF7_Shortcode';

	$tag_obj = new $classname( $tag );

	if ( empty( $tag_obj->name ) ) {
		return '';
	}

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> function_exists( 'wpcf7_count_form_tag_handler' ) ? wpcf7_count_form_tag_handler( $tag ) : wpcf7_count_shortcode_handler( $tag ),
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
