<?php
/**
 * Checkbox module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

add_action( 'wpcf7_init', 'cf7bs_add_shortcode_checkbox', 11 );

function cf7bs_add_shortcode_checkbox() {
	$add_func    = function_exists( 'wpcf7_add_form_tag' )    ? 'wpcf7_add_form_tag'    : 'wpcf7_add_shortcode';
	$remove_func = function_exists( 'wpcf7_remove_form_tag' ) ? 'wpcf7_remove_form_tag' : 'wpcf7_remove_shortcode';

	$tags = array(
		'checkbox',
		'checkbox*',
		'radio',
	);
	foreach ( $tags as $tag ) {
		call_user_func( $remove_func, $tag );
	}

	$features = version_compare( WPCF7_VERSION, '4.7', '<' ) ? true : array(
		'name-attr'                   => true,
		'selectable-values'           => true,
		'multiple-controls-container' => true,
	);

	call_user_func( $add_func, $tags, 'cf7bs_checkbox_shortcode_handler', $features );
}

function cf7bs_checkbox_shortcode_handler( $tag ) {
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

	$exclusive = $tag_obj->has_option( 'exclusive' );
	$free_text = $tag_obj->has_option( 'free_text' );

	$multiple = false;

	if ( 'checkbox' == $tag_obj->basetype ) {
		$multiple = !$exclusive;
	} else {
		$exclusive = false;
	}

	if ( $exclusive ) {
		$class .= ' wpcf7-exclusive-checkbox';
	}

	if ( $tag_obj->is_required() ) {
		$mode = 'required';
	}

	$values = (array) $tag_obj->values;
	$labels = (array) $tag_obj->labels;

	if ( $data = (array) $tag_obj->get_data_option() ) {
		if ( $free_text ) {
			$values = array_merge( array_slice( $values, 0, -1 ), array_values( $data ), array_slice( $values, -1 ) );
			$labels = array_merge( array_slice( $labels, 0, -1 ), array_values( $data ), array_slice( $labels, -1 ) );
		} else {
			$values = array_merge( $values, array_values( $data ) );
			$labels = array_merge( $labels, array_values( $data ) );
		}
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
		$defaults = array_merge( $defaults, explode( '_', $matches[1] ) );
	}

	$defaults = array_unique( $defaults );

	$options = array();
	$checked = '';
	if ( $multiple ) {
		$checked = array();
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

	$count = 0;
	$replace_index = count( (array) $tag_obj->values ) - 1;

	foreach ( (array) $tag_obj->values as $key => $value ) {
		$options[ $value ] = isset( $labels[ $key ] ) ? $labels[ $key ] : $value;
		if ( $free_text && $count == $replace_index ) {
			$options[ $value ] .= ' <input type="text" name="' . sprintf( '_wpcf7_%1$s_free_text_%2$s', $tag_obj->basetype, $tag_obj->name ) . '" class="wpcf7-free-text">';
		}

		if ( $posted && ! empty( $post ) ) {
			if ( $multiple && in_array( esc_sql( $value ), (array) $post ) ) {
				$checked[] = $value;
			}
			if ( ! $multiple && $post == esc_sql( $value ) ) {
				$checked = $value;
			}
		} elseif ( isset( $get ) && ! empty( $get ) ) {
			if ( $multiple && in_array( esc_sql( $value ), (array) $get ) ) {
				$checked[] = $value;
			}
			if ( ! $multiple && $get == esc_sql( $value ) ) {
				$checked = $value;
			}
		} elseif ( in_array( $key + 1, (array) $defaults ) ) {
			if ( $multiple ) {
				$checked[] = $value;
			} else {
				$checked = $value;
			}
		}
		$count++;
	}

	$label = $tag_obj->content;

	if ( count( $options ) < 1 ) {
		if ( $free_text ) {
			$options = array( 'true' => '<input type="text" name="' . sprintf( '_wpcf7_%1$s_free_text_%2$s', $tag_obj->basetype, $tag_obj->name ) . '" class="wpcf7-free-text">' );
		} else {
			$options = array( 'true' => $label );
			$label = '';
		}
	}

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> $tag_obj->name,
		'id'				=> $tag_obj->get_option( 'id', 'id', true ),
		'class'				=> '',
		'type'				=> $tag_obj->basetype,
		'value'				=> $checked,
		'label'				=> $label,
		'options'			=> $options,
		'help_text'			=> $validation_error,
		'size'				=> cf7bs_get_form_property( 'size', 0, $tag_obj ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag_obj ),
		'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
		'group_layout'		=> cf7bs_get_form_property( 'group_layout', 0, $tag_obj ),
		'group_type'		=> cf7bs_get_form_property( 'group_type', 0, $tag_obj ),
		'mode'				=> $mode,
		'status'			=> $status,
		'tabindex'			=> $tag_obj->get_option( 'tabindex', 'int', true ),
		'wrapper_class'		=> $tag_obj->get_class_option( $class . ' ' . $tag_obj->name ),
		'label_class'       => $tag_obj->get_option( 'label_class', 'class', true ),
	), $tag_obj->basetype, $tag_obj->name ) );

	$html = $field->display( false );

	return $html;
}
