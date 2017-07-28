<?php
/**
 * Textarea module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

add_action( 'wpcf7_init', 'cf7bs_add_shortcode_textarea', 11 );

function cf7bs_add_shortcode_textarea() {
	$add_func    = function_exists( 'wpcf7_add_form_tag' )    ? 'wpcf7_add_form_tag'    : 'wpcf7_add_shortcode';
	$remove_func = function_exists( 'wpcf7_remove_form_tag' ) ? 'wpcf7_remove_form_tag' : 'wpcf7_remove_shortcode';

	$tags = array(
		'textarea',
		'textarea*',
	);
	foreach ( $tags as $tag ) {
		call_user_func( $remove_func, $tag );
	}

	$features = version_compare( WPCF7_VERSION, '4.7', '<' ) ? true : array(
		'name-attr' => true,
	);

	call_user_func( $add_func, $tags, 'cf7bs_textarea_shortcode_handler', $features );
}

function cf7bs_textarea_shortcode_handler( $tag ) {
	$classname = class_exists( 'WPCF7_FormTag' ) ? 'WPCF7_FormTag' : 'WPCF7_Shortcode';

	$tag_obj = new $classname( $tag );

	if ( empty( $tag_obj->name ) ) {
		return '';
	}

	$mode = $status = 'default';

	$validation_error = wpcf7_get_validation_error( $tag_obj->name );

	$class = wpcf7_form_controls_class( $tag_obj->type );
	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
		$status = 'error';
	}

	// cols is not used since Bootstrap input fields always scale 100%
	//$atts['cols'] = $tag_obj->get_cols_option( '40' );

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

	$rows = $tag_obj->get_rows_option();
	if ( ! $rows ) {
		$rows = 4;
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

	$content = $tag_obj->content;

	$matches = array();
	if ( preg_match( '/\{input_before\}(.*)\{\/input_before\}/imU', $content, $matches ) ) {
		if ( ! empty( $matches[1] ) ) {
			$input_before = $matches[1];
		}
		$content = str_replace( $matches[0], '', $content );
	}

	$matches = array();
	if ( preg_match( '/\{input_after\}(.*)\{\/input_after\}/imU', $content, $matches ) ) {
		if ( ! empty( $matches[1] ) ) {
			$input_after = $matches[1];
		}
		$content = str_replace( $matches[0], '', $content );
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

		$tag = cf7bs_textarea_to_count( $tag, $count_down );

		$handler_func = function_exists( 'wpcf7_count_form_tag_handler' ) ? 'wpcf7_count_form_tag_handler' : 'wpcf7_count_shortcode_handler';

		if ( ! empty( $$count_mode ) ) {
			$$count_mode = call_user_func( $handler_func, $tag ) . ' ' . $$count_mode;
		} else {
			$$count_mode = call_user_func( $handler_func, $tag );
		}
	}

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> $tag_obj->name,
		'id'				=> $tag_obj->get_option( 'id', 'id', true ),
		'class'				=> $tag_obj->get_class_option( $class ),
		'type'				=> 'textarea',
		'value'				=> $value,
		'placeholder'		=> $placeholder,
		'label'				=> $content,
		'help_text'			=> $validation_error,
		'size'				=> cf7bs_get_form_property( 'size', 0, $tag_obj ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag_obj ),
		'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
		'mode'				=> $mode,
		'status'			=> $status,
		'readonly'			=> $tag_obj->has_option( 'readonly' ) ? true : false,
		'minlength'			=> $tag_obj->get_minlength_option(),
		'maxlength'			=> $tag_obj->get_maxlength_option(),
		'tabindex'			=> $tag_obj->get_option( 'tabindex', 'int', true ),
		'wrapper_class'		=> $tag_obj->name,
		'label_class'       => $tag_obj->get_option( 'label_class', 'class', true ),
		'rows' 				=> $rows,
		'input_before'		=> $input_before,
		'input_after'		=> $input_after,
	), $tag_obj->basetype, $tag_obj->name ) );

	$html = $field->display( false );

	return $html;
}

function cf7bs_textarea_to_count( $tag, $count_down = false ) {
	$classname = class_exists( 'WPCF7_FormTag' ) ? 'WPCF7_FormTag' : 'WPCF7_Shortcode';
	$tag_obj = new $classname( $tag );

	$tag_obj->type = 'count';
	$tag_obj->basetype = 'count';
	$tag_obj->options = array();

	if ( $count_down ) {
		$tag_obj->options[] = 'down';
	}

	return $tag;
}
