<?php
/**
 * reCAPTCHA module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.3.0
 */

if ( function_exists( 'wpcf7_recaptcha_add_shortcode_recaptcha' ) ) {
	remove_action( 'wpcf7_init', 'wpcf7_recaptcha_add_shortcode_recaptcha' );
	add_action( 'wpcf7_init', 'cf7bs_recaptcha_add_shortcode_recaptcha' );

	function cf7bs_recaptcha_add_shortcode_recaptcha() {
		$recaptcha = WPCF7_RECAPTCHA::get_instance();

		if ( $recaptcha->is_active() ) {
			wpcf7_add_shortcode( 'recaptcha', 'cf7bs_recaptcha_shortcode_handler' );
		}
	}

	function cf7bs_recaptcha_shortcode_handler( $tag ) {
		$tag_obj = new WPCF7_Shortcode( $tag );

		$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
			'name'				=> wpcf7_recaptcha_shortcode_handler( $tag ),
			'type'				=> 'custom',
			'label'				=> $tag_obj->content,
			'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag_obj ),
			'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
			'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
			'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
			'tabindex'			=> false,
			'wrapper_class'		=> '',
		), $tag_obj->basetype ) );

		$html = $field->display( false );

		return $html;
	}
}
