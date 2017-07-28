<?php
/**
 * Date module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

add_action( 'wpcf7_init', 'cf7bs_add_shortcode_date', 11 );

function cf7bs_add_shortcode_date() {
	$add_func    = function_exists( 'wpcf7_add_form_tag' )    ? 'wpcf7_add_form_tag'    : 'wpcf7_add_shortcode';
	$remove_func = function_exists( 'wpcf7_remove_form_tag' ) ? 'wpcf7_remove_form_tag' : 'wpcf7_remove_shortcode';

	$tags = array(
		'date',
		'date*',
	);
	foreach ( $tags as $tag ) {
		call_user_func( $remove_func, $tag );
	}

	$features = version_compare( WPCF7_VERSION, '4.7', '<' ) ? true : array(
		'name-attr' => true,
	);

	call_user_func( $add_func, $tags, 'cf7bs_date_shortcode_handler', $features );
}

function cf7bs_date_shortcode_handler( $tag ) {
	$classname = class_exists( 'WPCF7_FormTag' ) ? 'WPCF7_FormTag' : 'WPCF7_Shortcode';

	$tag_obj = new $classname( $tag );

	if ( empty( $tag_obj->name ) ) {
		return '';
	}

	$mode = $status = 'default';

	$validation_error = wpcf7_get_validation_error( $tag_obj->name );

	$class = wpcf7_form_controls_class( $tag_obj->type );
	$class .= ' wpcf7-validates-as-date';
	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
		$status = 'error';
	}

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
	} elseif( isset( $_GET ) && array_key_exists( $tag_obj->name, $_GET ) ) {
		$value = stripslashes_deep( rawurldecode( $_GET[ $tag_obj->name ] ) );
	}

	if ( is_callable( array( $tag_obj, 'get_date_option' ) ) ) {
		$min = $tag_obj->get_date_option( 'min' );
		$max = $tag_obj->get_date_option( 'max' );
	} else {
		$min = $tag_obj->get_option( 'min', 'date', true );
		$max = $tag_obj->get_option( 'max', 'date', true );
	}
	$step = $tag_obj->get_option( 'step', 'int', true );

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> $tag_obj->name,
		'id'				=> $tag_obj->get_option( 'id', 'id', true ),
		'class'				=> $tag_obj->get_class_option( $class ),
		'type'				=> wpcf7_support_html5() ? $tag_obj->basetype : 'text',
		'value'				=> $value,
		'placeholder'		=> $placeholder,
		'label'				=> $tag_obj->content,
		'options'			=> array(
		  'min'					=> $min,
		  'max'					=> $max,
		  'step'				=> $tag_obj->get_option( 'step', 'int', true ),
		),
		'help_text'			=> $validation_error,
		'size'				=> cf7bs_get_form_property( 'size', 0, $tag_obj ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag_obj ),
		'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
		'mode'				=> $mode,
		'status'			=> $status,
		'readonly'			=> $tag_obj->has_option( 'readonly' ) ? true : false,
		'tabindex'			=> $tag_obj->get_option( 'tabindex', 'int', true ),
		'wrapper_class'		=> $tag_obj->name,
		'label_class'       => $tag_obj->get_option( 'label_class', 'class', true ),
	), $tag_obj->basetype, $tag_obj->name ) );

	$html = $field->display( false );

	return $html;
}
