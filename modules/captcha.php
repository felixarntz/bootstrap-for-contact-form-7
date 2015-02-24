<?php
/**
 * @package CF7BS
 * @version 1.1.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

remove_action( 'wpcf7_init', 'wpcf7_add_shortcode_captcha' );
add_action( 'wpcf7_init', 'cf7bs_add_shortcode_captcha' );

function cf7bs_add_shortcode_captcha()
{
  wpcf7_add_shortcode( array(
    'captchac',
    'captchar',
  ), 'cf7bs_captcha_shortcode_handler', true );
}

function cf7bs_captcha_shortcode_handler( $tag )
{
  $tag_obj = new WPCF7_Shortcode( $tag );

  if( 'captchac' == $tag_obj->type && !class_exists( 'ReallySimpleCaptcha' ) )
  {
    return '<em>' . __( 'To use CAPTCHA, you need <a href="http://wordpress.org/extend/plugins/really-simple-captcha/">Really Simple CAPTCHA</a> plugin installed.', 'contact-form-7' ) . '</em>';
  }

  if( empty( $tag_obj->name ) )
  {
    return '';
  }

  $mode = $status = 'default';

  $validation_error = wpcf7_get_validation_error( $tag_obj->name );

  $class = wpcf7_form_controls_class( $tag_obj->type, 'wpcf7-text' );

  if( 'captchac' == $tag_obj->type )
  {
    $field = new CF7BS_Form_Field( array(
      'name'              => $tag_obj->name,
      'id'                => $tag_obj->get_option( 'id', 'id', true ),
      'type'              => 'file',
      'value'             => '1',
      'label'             => $tag_obj->content,
      'help_text'         => $validation_error,
      'form_layout'       => cf7bs_get_form_property( 'layout' ),
      'form_label_width'  => cf7bs_get_form_property( 'label_width' ),
      'form_breakpoint'   => cf7bs_get_form_property( 'breakpoint' ),
      'mode'              => $mode,
      'status'            => $status,
      'tabindex'          => false,
      'wrapper_class'     => $tag_obj->name,
    ) );

    $html = $field->display( false );

    return str_replace( '<input' . ( $tag_obj->get_option( 'id', 'id', true ) != '' ? ' id="' . esc_attr( $tag_obj->get_option( 'id', 'id', true ) ) . '"' : '' ) . ' name="' . esc_attr( $tag_obj->name ) . '" type="file">', wpcf7_captcha_shortcode_handler( $tag ), $html );
  }
  elseif( 'captchar' == $tag_obj->type )
  {
    if( $validation_error )
    {
      $class .= ' wpcf7-not-valid';
      $status = 'error';
    }

    // size is not used since Bootstrap input fields always scale 100%
    //$atts['size'] = $tag->get_size_option( '40' );

    $value = (string) reset( $tag_obj->values );
    $placeholder = '';
    if( wpcf7_is_posted() )
    {
      $value = '';
    }
    if( $tag_obj->has_option( 'placeholder' ) || $tag_obj->has_option( 'watermark' ) )
    {
      $placeholder = $value;
      $value = '';
    }

    $field = new CF7BS_Form_Field( array(
      'name'              => $tag_obj->name,
      'id'                => $tag_obj->get_option( 'id', 'id', true ),
      'class'             => $tag_obj->get_class_option( $class ),
      'type'              => 'text',
      'value'             => $value,
      'placeholder'       => $placeholder,
      'label'             => $tag_obj->content,
      'help_text'         => $validation_error,
      'size'              => cf7bs_get_form_property( 'size' ),
      'form_layout'       => cf7bs_get_form_property( 'layout' ),
      'form_label_width'  => cf7bs_get_form_property( 'label_width' ),
      'form_breakpoint'   => cf7bs_get_form_property( 'breakpoint' ),
      'mode'              => $mode,
      'status'            => $status,
      'maxlength'         => $tag_obj->get_maxlength_option(),
      'tabindex'          => $tag_obj->get_option( 'tabindex', 'int', true ),
      'wrapper_class'     => $tag_obj->name,
    ) );

    $html = $field->display( false );

    return $html;
  }

  return '';
}
