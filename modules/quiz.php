<?php
/**
 * Quiz module
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

add_action( 'wpcf7_init', 'cf7bs_add_shortcode_quiz', 11 );

function cf7bs_add_shortcode_quiz() {
	$add_func    = function_exists( 'wpcf7_add_form_tag' )    ? 'wpcf7_add_form_tag'    : 'wpcf7_add_shortcode';
	$remove_func = function_exists( 'wpcf7_remove_form_tag' ) ? 'wpcf7_remove_form_tag' : 'wpcf7_remove_shortcode';

	$tags = array(
		'quiz'
	);
	foreach ( $tags as $tag ) {
		call_user_func( $remove_func, $tag );
	}

	$features = version_compare( WPCF7_VERSION, '4.7', '<' ) ? true : array(
		'name-attr'    => true,
		'do-not-store' => true,
	);

	call_user_func( $add_func, $tags, 'cf7bs_quiz_shortcode_handler', $features );
}

function cf7bs_quiz_shortcode_handler( $tag ) {
	$classname = class_exists( 'WPCF7_FormTag' ) ? 'WPCF7_FormTag' : 'WPCF7_Shortcode';

	$tag_obj = new $classname( $tag );

	if ( empty( $tag_obj->name ) ) {
		return '';
	}

	$status = 'default';

	$validation_error = wpcf7_get_validation_error( $tag_obj->name );

	$class = wpcf7_form_controls_class( $tag_obj->type );
	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
		$status = 'error';
	}

	// size is not used since Bootstrap input fields always scale 100%
	//$atts['size'] = $tag_obj->get_size_option( '40' );

	$pipes = $tag_obj->pipes;
	if ( is_a( $pipes, 'WPCF7_Pipes' ) && ! $pipes->zero() ) {
		$pipe = $pipes->random_pipe();
		$question = $pipe->before;
		$answer = $pipe->after;
	} else {
		// default quiz
		$question = '1+1=?';
		$answer = '2';
	}
	$answer = wpcf7_canonicalize( $answer );

	$field = new CF7BS_Form_Field( cf7bs_apply_field_args_filter( array(
		'name'				=> $tag_obj->name,
		'id'				=> $tag_obj->get_option( 'id', 'id', true ),
		'class'				=> $tag_obj->get_class_option( $class ),
		'type'				=> 'text',
		'value'				=> '',
		'placeholder'		=> '',
		'label'				=> $tag_obj->content,
		'help_text'			=> $validation_error,
		'size'				=> cf7bs_get_form_property( 'size', 0, $tag_obj ),
		'grid_columns'		=> cf7bs_get_form_property( 'grid_columns', 0, $tag_obj ),
		'form_layout'		=> cf7bs_get_form_property( 'layout', 0, $tag_obj ),
		'form_label_width'	=> cf7bs_get_form_property( 'label_width', 0, $tag_obj ),
		'form_breakpoint'	=> cf7bs_get_form_property( 'breakpoint', 0, $tag_obj ),
		'status'			=> $status,
		'maxlength'			=> $tag_obj->get_maxlength_option(),
		'tabindex'			=> $tag_obj->get_option( 'tabindex', 'int', true ),
		'wrapper_class'		=> $tag_obj->name,
		'label_class'       => $tag_obj->get_option( 'label_class', 'class', true ),
	), $tag_obj->basetype, $tag_obj->name ) );

	$html = $field->display( false );
	$hidden_html = sprintf( '<input type="hidden" name="_wpcf7_quiz_answer_%1$s" value="%2$s">', $tag_obj->name, wp_hash( $answer, 'wpcf7_quiz' ) );

	return str_replace( '<input', '<p class="wpcf7-quiz-label">' . esc_html( $question ) . '</p>' . $hidden_html . '<input', $html );
}
