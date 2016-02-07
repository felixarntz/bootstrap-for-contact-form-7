<?php
/**
 * @package CF7BS
 * @version 1.3.1
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
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
			'grid_columns'		=> cf7bs_get_form_property( 'grid_columns' ),
			'form_layout'		=> cf7bs_get_form_property( 'layout' ),
			'form_label_width'	=> cf7bs_get_form_property( 'label_width' ),
			'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint' ),
			'tabindex'			=> false,
			'wrapper_class'		=> '',
		), $tag_obj->basetype ) );

		$html = $field->display( false );

		return $html;
	}
}
