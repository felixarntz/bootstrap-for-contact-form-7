<?php
/**
 * @package CF7BS
 * @version 1.1.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

remove_action( 'wpcf7_init', 'wpcf7_add_shortcode_textarea' );
add_action( 'wpcf7_init', 'cf7bs_add_shortcode_textarea' );

function cf7bs_add_shortcode_textarea()
{
  wpcf7_add_shortcode( array(
    'textarea',
    'textarea*',
  ), 'cf7bs_textarea_shortcode_handler', true );
}

function cf7bs_textarea_shortcode_handler( $tag )
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

  // cols is not used since Bootstrap input fields always scale 100%
  //$atts['cols'] = $tag->get_cols_option( '40' );

  if( $tag->is_required() )
  {
    $mode = 'required';
  }

  $value = (string) reset( $tag->values );
  $placeholder = '';
  if( $tag->has_option( 'placeholder' ) || $tag->has_option( 'watermark' ) )
  {
    $placeholder = $value;
    $value = '';
  }

  if( wpcf7_is_posted() && isset( $_POST[ $tag->name ] ) )
  {
    $value = stripslashes_deep( $_POST[ $tag->name ] );
  }
  elseif( isset( $_GET ) && array_key_exists( $tag->name, $_GET ) )
  {
    $value = stripslashes_deep( rawurldecode( $_GET[ $tag->name ] ) );
  }

  $field = new CF7BS_Form_Field( array(
    'name'              => $tag->name,
    'id'                => $tag->get_option( 'id', 'id', true ),
    'class'             => $tag->get_class_option( $class ),
    'type'              => 'textarea',
    'value'             => $value,
    'placeholder'       => $placeholder,
    'label'             => $tag->content,
    'help_text'         => $validation_error,
    'size'              => cf7bs_get_form_property( 'size' ),
    'form_layout'       => cf7bs_get_form_property( 'layout' ),
    'form_label_width'  => cf7bs_get_form_property( 'label_width' ),
    'form_breakpoint'   => cf7bs_get_form_property( 'breakpoint' ),
    'mode'              => $mode,
    'status'            => $status,
    'readonly'          => $tag->has_option( 'readonly' ) ? true : false,
    'maxlength'         => $tag->get_maxlength_option(),
    'tabindex'          => $tag->get_option( 'tabindex', 'int', true ),
    'wrapper_class'     => $tag->name,
  ) );

  $html = $field->display( false );

  return $html;
}