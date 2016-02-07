<?php
/**
 * @package CF7BS
 * @version 1.3.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

remove_action( 'wpcf7_init', 'wpcf7_add_shortcode_select' );
add_action( 'wpcf7_init', 'cf7bs_add_shortcode_select' );

function cf7bs_add_shortcode_select() {
	wpcf7_add_shortcode( array(
		'select',
		'select*',
	), 'cf7bs_select_shortcode_handler', true );
}

function cf7bs_select_shortcode_handler( $tag ) {
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

	if ( $tag->is_required() ) {
		$mode = 'required';
	}

	$defaults = array();

	$default_choice = $tag->get_default_option( null, 'multiple=1' );
	foreach ( $default_choice as $value ) {
		$key = array_search( $value, $values, true );
		if ( false !== $key ) {
			$defaults[] = (int) $key + 1;
		}
	}

	if ( $matches = $tag->get_first_match_option( '/^default:([0-9_]+)$/' ) ) {
		$defaults = explode( '_', $matches[1] );
	}

	$defaults = array_unique( $defaults );

	$multiple = $tag->has_option( 'multiple' );
	$include_blank = $tag->has_option( 'include_blank' );
	$first_as_label = $tag->has_option( 'first_as_label' );

	$values = $tag->values;
	$labels = $tag->labels;

	if ( $data = (array) $tag->get_data_option() ) {
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

	if ( isset( $_POST[ $tag->name ] ) ) {
		$post = $_POST[ $tag->name ];
	} else {
		if ( isset( $_GET[ $tag->name ] ) ) {
			if ( $multiple ) {
				$get = cf7bs_array_decode( rawurldecode( $_GET[ $tag->name ] ) );
			} else {
				$get = rawurldecode( $_GET[ $tag->name ] );
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
		'name'				=> $tag->name,
		'id'				=> $tag->get_option( 'id', 'id', true ),
		'class'				=> $tag->get_class_option( $class ),
		'type'				=> $multiple ? 'multiselect' : 'select',
		'value'				=> $selected,
		'label'				=> $tag->content,
		'options'			=> $options,
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
