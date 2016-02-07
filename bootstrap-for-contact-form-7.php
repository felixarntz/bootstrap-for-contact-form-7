<?php
/*
Plugin Name: Bootstrap for Contact Form 7
Plugin URI: http://wordpress.org/plugins/bootstrap-for-contact-form-7/
Description: This plugin modifies the output of the popular Contact Form 7 plugin to be styled in compliance with themes using the Bootstrap CSS framework.
Version: 1.3.1
Author: Felix Arntz
Author URI: http://leaves-and-love.net
License: GNU General Public License v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: bootstrap-for-contact-form-7
Tags: wordpress, plugin, contact form 7, wpcf7, bootstrap, bootstrap 3, bootstrap framework, addon, contact form 7 addon, contact form, cf7bs, css
*/
/**
 * @package CF7BS
 * @version 1.3.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

if ( ! defined( 'WPCF7_AUTOP' ) ) {
	define( 'WPCF7_AUTOP', false );
}

function cf7bs_maybe_init() {
	define( 'CF7BS_VERSION', '1.3.1' );
	define( 'CF7BS_MAINFILE', __FILE__ );
	define( 'CF7BS_PATH', untrailingslashit( plugin_dir_path( CF7BS_MAINFILE ) ) );
	define( 'CF7BS_URL', untrailingslashit( plugin_dir_url( CF7BS_MAINFILE ) ) );
	define( 'CF7BS_BASENAME', plugin_basename( CF7BS_MAINFILE ) );

	load_plugin_textdomain( 'bootstrap-for-contact-form-7' );

	if ( defined( 'WPCF7_VERSION' ) && apply_filters( 'cf7bs_using_bootstrap', true ) ) {
		include_once CF7BS_PATH . '/modifications.php';
		include_once CF7BS_PATH . '/classes/CF7BS_Component.php';
		include_once CF7BS_PATH . '/classes/CF7BS_Button.php';
		include_once CF7BS_PATH . '/classes/CF7BS_Button_Group.php';
		include_once CF7BS_PATH . '/classes/CF7BS_Alert.php';
		include_once CF7BS_PATH . '/classes/CF7BS_Form_Field.php';

		$modules = array(
			'acceptance',
			'submit',
			'count',
			'number',
			'text',
			'checkbox',
			'quiz',
			'textarea',
			'date',
			'file',
			'select',
			'really-simple-captcha',
			'recaptcha',
		);
		foreach ( $modules as $module ) {
			$file = CF7BS_PATH . '/modules/' . $module . '.php';
			if ( file_exists( $file ) ) {
				include_once $file;
			}
		}
	} else {
		add_action( 'admin_notices', 'cf7bs_disabled_notice' );
	}
}
add_action( 'plugins_loaded', 'cf7bs_maybe_init', 50 );

$current_form_id = 0;
$current_form_properties = array();

function cf7bs_get_form_property( $property, $form_id = 0 ) {
	global $current_form_id, $current_form_properties;

	$current_form = $original_form = null;

	if ( ! $form_id ) {
		$form_id = cf7bs_get_current_form_id();
		if ( ! $form_id ) {
			return false;
		}
		$current_form = WPCF7_ContactForm::get_current();
	} else {
		$current_form = WPCF7_ContactForm::get_instance( $form_id );
		$original_form = WPCF7_ContactForm::get_current();
		if ( is_a( $current_form, 'WPCF7_ContactForm' ) && is_callable( array( $current_form, 'id' ) ) && is_a( $original_form, 'WPCF7_ContactForm' ) && is_callable( array( $original_form, 'id' ) ) ) {
			if ( $original_form->id() === $current_form->id() ) {
				$original_form = null;
			}
		}
	}

	if ( $current_form_id != $form_id ) {
		$current_form_id = $form_id;

		$properties = cf7bs_get_default_form_properties();
		if ( is_a( $current_form, 'WPCF7_ContactForm' ) && is_callable( array( $current_form, 'additional_setting' ) ) ) {
			foreach ( $properties as $key => &$value ) {
				$setting = $current_form->additional_setting( $key );
				if ( isset( $setting[0] ) ) {
					$value = $setting[0];
				}
			}
			unset( $key );
			unset( $value );
		}
		$current_form_properties = apply_filters( 'cf7bs_form_' . $form_id . '_properties', $properties );
	}

	if ( null !== $original_form ) {
		if ( is_a( $original_form, 'WPCF7_ContactForm' ) && is_callable( array( $original_form, 'id' ) ) ) {
			WPCF7_ContactForm::get_instance( $original_form->id() );
		}
	}

	if ( isset( $current_form_properties[ $property ] ) ) {
		return $current_form_properties[ $property ];
	}
	return false;
}

function cf7bs_get_default_form_properties() {
	$properties = array(
		'layout'		=> 'default', // 'default', 'inline', 'horizontal'
		'size'			=> 'default', // 'default', 'small', 'large'
		'group_layout'	=> 'default', // 'default', 'inline', 'buttons'
		'group_type'	=> 'default', // 'default', 'primary', 'success', 'info', 'warning', 'danger' (only if group_layout=buttons)
		'submit_size'	=> '', // 'default', 'small', 'large' or leave empty to use value of 'size'
		'submit_type'	=> 'primary', // 'default', 'primary', 'success', 'info', 'warning', 'danger'
		'required_html'	=> '<span class="required">*</span>',
		'grid_columns'	=> 12,
		'label_width'	=> 3, // integer between 1 and 'grid_columns' minus 1
		'breakpoint'	=> 'sm', // xs, sm, md, lg
	);
	return apply_filters( 'cf7bs_default_form_properties', $properties );
}

function cf7bs_apply_field_args_filter( $field_args, $tag_type, $tag_name = '', $form_id = 0 ) {
	if ( ! $form_id ) {
		$form_id = cf7bs_get_current_form_id();
	}

	if ( ! empty( $tag_name ) ) {
		$field_args = apply_filters( 'cf7bs_form_' . $form_id . '_field_' . $tag_type . '_' . $tag_name . '_properties', $field_args );
	}

	return apply_filters( 'cf7bs_form_' . $form_id . '_field_' . $tag_type . '_properties', $field_args );
}

function cf7bs_add_get_parameter() {
	$args = func_get_args();
	if ( is_array( $args[0] ) ) {
		if ( count( $args ) < 2 || $args[1] === false ) {
			$uri = $_SERVER['REQUEST_URI'];
		} else {
			$uri = $args[1];
		}
		foreach ( $args[0] as $key => &$value ) {
			$value = cf7bs_parameter_encode( $value );
		}
		return add_query_arg( $args[0], $uri );
	} else {
		if ( count( $args ) < 3 || $args[2] === false ) {
			$uri = $_SERVER['REQUEST_URI'];
		} else {
			$uri = $args[2];
		}
		if ( count( $args ) < 2 ) {
			return $uri;
		}
		return add_query_arg( $args[0], cf7bs_parameter_encode( $args[1] ), $uri );
	}
	return '';
}

function cf7bs_get_current_form_id() {
	if ( is_callable( array( 'WPCF7_ContactForm', 'get_current' ) ) ) {
		$current_form = WPCF7_ContactForm::get_current();
		if ( is_a( $current_form, 'WPCF7_ContactForm' ) && is_callable( array( $current_form, 'id' ) ) ) {
			return $current_form->id();
		}
	}

	return false;
}

function cf7bs_disabled_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="error">';
	echo '<p>';
	_e( 'The plugin <em>Bootstrap for Contact Form 7</em> has not been initialized since either <em>Contact Form 7</em> is not active or you are not using a Bootstrap theme.', 'bootstrap-for-contact-form-7' );
	echo '<br/>';
	_e( 'Please make sure the conditions for the plugin are met or deactivate it otherwise.', 'bootstrap-for-contact-form-7' );
	echo '</p>';
	echo '</div>';
}
