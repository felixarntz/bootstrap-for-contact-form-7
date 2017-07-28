<?php
/**
 * Select module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

add_action( 'wpcf7_init', 'cf7bs_add_shortcode_select', 11 );

function cf7bs_add_shortcode_select() {
	$add_func    = function_exists( 'wpcf7_add_form_tag' )    ? 'wpcf7_add_form_tag'    : 'wpcf7_add_shortcode';
	$remove_func = function_exists( 'wpcf7_remove_form_tag' ) ? 'wpcf7_remove_form_tag' : 'wpcf7_remove_shortcode';

	$tags = array(
		'select',
		'select*',
	);
	foreach ( $tags as $tag ) {
		call_user_func( $remove_func, $tag );
	}

	$features = version_compare( WPCF7_VERSION, '4.7', '<' ) ? true : array(
		'name-attr'         => true,
		'selectable-values' => true,
	);

	call_user_func( $add_func, $tags, 'cf7bs_select_shortcode_handler', $features );
}

function cf7bs_select_shortcode_handler( $tag ) {
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

	if ( $tag_obj->is_required() ) {
		$mode = 'required';
	}

	$defaults = array();

	$default_choice = $tag_obj->get_default_option( null, 'multiple=1' );
	foreach ( $default_choice as $value ) {
		$key = array_search( $value, $values, true );
		if ( false !== $key ) {
			$defaults[] = (int) $key + 1;
		}
	}

	if ( $matches = $tag_obj->get_first_match_option( '/^default:([0-9_]+)$/' ) ) {
		$defaults = explode( '_', $matches[1] );
	}

	$defaults = array_unique( $defaults );

	$multiple = $tag_obj->has_option( 'multiple' );
	$include_blank = $tag_obj->has_option( 'include_blank' );
	$first_as_label = $tag_obj->has_option( 'first_as_label' );

	$values = $tag_obj->values;
	$labels = $tag_obj->labels;

	if ( $data = (array) $tag_obj->get_data_option() ) {
		$values = array_merge( $values, array_values( $data ) );
		$labels = array_merge( $labels, array_values( $data ) );
	}

	$empty_select = empty( $values );

	$shifted = false;
	if ( $empty_select || $include_blank ) {
		array_unshift( $labels, '---' );
		array_unshift( $values, '' );
		$shifted = true;
	} elseif ( $first_as_label ) {
		$values[0] = '';
	}

	$options = array();
	$selected = '';
	if ( $multiple ) {
		$selected = array();
	}

	if ( isset( $_POST[ $tag_obj->name ] ) ) {
		$post = $_POST[ $tag_obj->name ];
	} else {
		if ( isset( $_GET[ $tag_obj->name ] ) ) {
			if ( $multiple ) {
				$get = cf7bs_array_decode( rawurldecode( $_GET[ $tag_obj->name ] ) );
			} else {
				$get = rawurldecode( $_GET[ $tag_obj->name ] );
			}
		}
		$post = $multiple ? array() : '';
	}
	$posted = wpcf7_is_posted();

	foreach ( $values as $key => $value ) {
		$options[ $value ] = isset( $labels[ $key ] ) ? $labels[ $key ] : $value;

		if ( $posted && !empty( $post ) ) {
			if ( $multiple && in_array( esc_sql( $value ), (array) $post ) ) {
				$selected[] = $value;
			}
			if ( ! $multiple && $post == esc_sql( $value ) ) {
				$selected = $value;
			}
		} elseif ( isset( $get ) && !empty( $get ) ) {
			if ( $multiple && in_array( esc_sql( $value ), (array) $get ) ) {
				$selected[] = $value;
			}
			if ( ! $multiple && $get == esc_sql( $value ) ) {
				$selected = $value;
			}
		} elseif ( ! $shifted && in_array( (int) $key + 1, (array) $defaults ) || $shifted && in_array( (int) $key, (array) $defaults ) ) {
			if ( $multiple ) {
				$selected[] = $value;
			} else {
				$selected = $value;
			}
		}
	}

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> $tag_obj->name,
		'id'				=> $tag_obj->get_option( 'id', 'id', true ),
		'class'				=> $tag_obj->get_class_option( $class ),
		'type'				=> $multiple ? 'multiselect' : 'select',
		'value'				=> $selected,
		'label'				=> $tag_obj->content,
		'options'			=> $options,
		'help_text'			=> $validation_error,
		'size'				=> cf7bs_get_form_property( 'size', 0, $tag_obj ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag_obj ),
		'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
		'mode'				=> $mode,
		'status'			=> $status,
		'tabindex'			=> $tag_obj->get_option( 'tabindex', 'int', true ),
		'wrapper_class'		=> $tag_obj->name,
		'label_class'       => $tag_obj->get_option( 'label_class', 'class', true ),
	), $tag_obj->basetype, $tag_obj->name ) );

	$html = $field->display( false );

	return $html;
}
