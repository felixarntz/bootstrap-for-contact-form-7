<?php
/*
Plugin Name: Bootstrap for Contact Form 7
Plugin URI: http://wordpress.org/plugins/bootstrap-for-contact-form-7/
Description: This plugin modifies the output of the popular Contact Form 7 plugin to be styled in compliance with themes using the Bootstrap CSS framework.
Version: 1.1.0
Author: Felix Arntz
Author URI: http://leaves-and-love.net
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
/**
 * @package CF7BS
 * @version 1.1.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

define( 'CF7BS_VERSION', '1.1.0' );
define( 'CF7BS_MAINFILE', __FILE__ );
define( 'CF7BS_PATH', untrailingslashit( plugin_dir_path( CF7BS_MAINFILE ) ) );
define( 'CF7BS_URL', untrailingslashit( plugin_dir_url( CF7BS_MAINFILE ) ) );
define( 'CF7BS_BASENAME', plugin_basename( CF7BS_MAINFILE ) );

if( !defined( 'WPCF7_AUTOP' ) )
{
  define( 'WPCF7_AUTOP', false );
}

function cf7bs_maybe_init()
{
  if( defined( 'WPCF7_VERSION' ) && apply_filters( 'cf7bs_using_bootstrap', true ) )
  {
    include_once CF7BS_PATH . '/modifications.php';
    include_once CF7BS_PATH . '/classes/CF7BS_Component.php';
    include_once CF7BS_PATH . '/classes/CF7BS_Button.php';
    include_once CF7BS_PATH . '/classes/CF7BS_Button_Group.php';
    include_once CF7BS_PATH . '/classes/CF7BS_Alert.php';
    include_once CF7BS_PATH . '/classes/CF7BS_Form_Field.php';

    $modules = array(
      'acceptance',
      'submit',
      'captcha',
      'number',
      'text',
      'checkbox',
      'quiz',
      'textarea',
      'date',
      'file',
      'select',
    );
    foreach( $modules as $module )
    {
      $file = CF7BS_PATH . '/modules/' . $module . '.php';
      if( file_exists( $file ) )
      {
        include_once $file;
      }
    }
  }
}
add_action( 'plugins_loaded', 'cf7bs_maybe_init', 50 );

$current_form_id = 0;
$current_form_properties = array();

function cf7bs_get_form_property( $property, $form_id = 0 )
{
  global $current_form_id, $current_form_properties;

  if( $form_id == 0 )
  {
    $current_form = WPCF7_ContactForm::get_current();
    $form_id = $current_form->id();
  }
  
  if( $current_form_id != $form_id )
  {
    $current_form_id = $form_id;

    $properties = cf7bs_get_default_form_properties();
    $current_form_properties = apply_filters( 'cf7bs_form_' . $form_id . '_properties', $properties );
  }
  
  if( isset( $current_form_properties[ $property ] ) )
  {
    return $current_form_properties[ $property ];
  }
  return false;
}

function cf7bs_get_default_form_properties()
{
  $properties = array(
    'layout'        => 'default', // default, inline, horizontal
    'label_width'   => 3, // integer between 1 and 11
    'breakpoint'    => 'sm', // xs, sm, md, lg
    'size'          => 'default', // default, small, large
    'required_html' => '<span class="required">*</span>',
    'group_layout'  => 'default', // default, inline, buttons
    'group_type'    => 'default', // default, primary, success, info, warning, danger (only if group_layout=buttons)
    'submit_type'   => 'primary', // default, primary, success, info, warning, danger
  );
  return apply_filters( 'cf7bs_default_form_properties', $properties );
}

function cf7bs_add_get_parameter()
{
  $args = func_get_args();
  if( is_array( $args[0] ) )
  {
    if( count( $args ) < 2 || $args[1] === false )
    {
      $uri = $_SERVER['REQUEST_URI'];
    }
    else
    {
      $uri = $args[1];
    }
    foreach( $args[0] as $key => &$value )
    {
      $value = cf7bs_parameter_encode( $value );
    }
    return add_query_arg( $args[0], $uri );
  }
  else
  {
    if( count( $args ) < 3 || $args[2] === false )
    {
      $uri = $_SERVER['REQUEST_URI'];
    }
    else
    {
      $uri = $args[2];
    }
    if( count( $args ) < 2 )
    {
      return $uri;
    }
    return add_query_arg( $args[0], cf7bs_parameter_encode( $args[1] ), $uri );
  }
  return '';
}
