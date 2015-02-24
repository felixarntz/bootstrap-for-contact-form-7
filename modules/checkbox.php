<?php
/**
 * @package CF7BS
 * @version 1.1.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

remove_action( 'wpcf7_init', 'wpcf7_add_shortcode_checkbox' );
add_action( 'wpcf7_init', 'cf7bs_add_shortcode_checkbox' );

function cf7bs_add_shortcode_checkbox()
{
  wpcf7_add_shortcode( array(
    'checkbox',
    'checkbox*',
    'radio',
  ), 'cf7bs_checkbox_shortcode_handler', true );
}

function cf7bs_checkbox_shortcode_handler( $tag )
{
  $tag = new WPCF7_Shortcode( $tag );

  if( empty( $tag->name ) )
  {
    return '';
  }

  $mode = $status = 'default';

  $validation_error = wpcf7_get_validation_error( $tag->name );

  $class = wpcf7_form_controls_class( $tag->type );
  if( $validation_error )
  {
    $class .= ' wpcf7-not-valid';
    $status = 'error';
  }

  $exclusive = $tag->has_option( 'exclusive' );
  $multiple = false;

  if( 'checkbox' == $tag->basetype )
  {
    $multiple = !$exclusive;
  }
  else
  {
    $exclusive = false;
  }

  if( $exclusive )
  {
    $class .= ' wpcf7-exclusive-checkbox';
  }

  if( $tag->is_required() )
  {
    $mode = 'required';
  }

  $values = (array) $tag->values;
  $labels = (array) $tag->labels;

  if( $data = (array) $tag->get_data_option() )
  {
    if( $free_text )
    {
      $values = array_merge( array_slice( $values, 0, -1 ), array_values( $data ), array_slice( $values, -1 ) );
      $labels = array_merge( array_slice( $labels, 0, -1 ), array_values( $data ), array_slice( $labels, -1 ) );
    }
    else
    {
      $values = array_merge( $values, array_values( $data ) );
      $labels = array_merge( $labels, array_values( $data ) );
    }
  }

  $defaults = array();
  if( $matches = $tag->get_first_match_option( '/^default:([0-9_]+)$/' ) )
  {
    $defaults = explode( '_', $matches[1] );
  }

  $options = array();
  $checked = '';
  if( $multiple )
  {
    $checked = array();
  }

  if( isset( $_POST[ $tag->name ] ) )
  {
    $post = $_POST[ $tag->name ];
  }
  else
  {
    if( isset( $_GET[ $tag->name ] ) )
    {
      if( $multiple )
      {
        $get = cf7bs_array_decode( rawurldecode( $_GET[ $tag->name ] ) );
      }
      else
      {
        $get = rawurldecode( $_GET[ $tag->name ] );
      }
    }
    $post = $multiple ? array() : '';
  }
  $posted = wpcf7_is_posted();

  foreach( (array) $tag->values as $key => $value )
  {
    $options[ $value ] = isset( $labels[ $key ] ) ? $labels[ $key ] : $value;

    if( $posted && !empty( $post ) )
    {
      if( $multiple && in_array( esc_sql( $value ), (array) $post ) )
      {
        $checked[] = $value;
      }
      if( !$multiple && $post == esc_sql( $value ) )
      {
        $checked = $value;
      }
    }
    elseif( isset( $get ) && !empty( $get ) )
    {
      if( $multiple && in_array( esc_sql( $value ), (array) $get ) )
      {
        $checked[] = $value;
      }
      if( !$multiple && $get == esc_sql( $value ) )
      {
        $checked = $value;
      }
    }
    elseif( in_array( $key + 1, (array) $defaults ) )
    {
      if( $multiple )
      {
        $checked[] = $value;
      }
      else
      {
        $checked = $value;
      }
    }
  }

  $field = new CF7BS_Form_Field( array(
    'name'              => $tag->name,
    'id'                => $tag->get_option( 'id', 'id', true ),
    'class'             => $tag->get_class_option( $class ),
    'type'              => $tag->basetype,
    'value'             => $checked,
    'label'             => $tag->content,
    'options'           => $options,
    'help_text'         => $validation_error,
    'size'              => cf7bs_get_form_property( 'size' ),
    'form_layout'       => cf7bs_get_form_property( 'layout' ),
    'form_label_width'  => cf7bs_get_form_property( 'label_width' ),
    'form_breakpoint'   => cf7bs_get_form_property( 'breakpoint' ),
    'group_layout'      => cf7bs_get_form_property( 'group_layout' ),
    'mode'              => $mode,
    'status'            => $status,
    'tabindex'          => $tag->get_option( 'tabindex', 'int', true ),
    'wrapper_class'     => $tag->name,
  ) );

  $html = $field->display( false );

  return $html;
}