<?php
/**
 * CF7BS_Button_Group class
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

class CF7BS_Button_Group extends CF7BS_Component {
	private $buttons = array();

	public function open( $echo = true ) {
		$output = apply_filters( 'cf7bs_bootstrap_button_group_open', '', $this->args );

		if ( empty( $output ) ) {
			$output = '';

			extract( $this->args );

			$class = 'btn-group';

			if ( 'vertical' == $layout ) {
				$class .= '-vertical';
			} elseif( 'justified' == $layout ) {
				$class .= ' btn-group-justified';
			}

			$sizes = array(
				'mini'		=> 'xs',
				'small'		=> 'sm',
				'large'		=> 'lg',
			);
			if ( isset( $sizes[ $size ] ) ) {
				$class .= ' btn-group-' . $sizes[ $size ];
			}

			if ( ! empty( $id ) ) {
				$id = ' id="' . $id . '"';
			}

			$toggle = '';
			if ( in_array( $mode, array( 'checkbox', 'radio' ) ) ) {
				$toggle = ' data-toggle="buttons"';
			}

			$output .= '<div class="' . $class . '"' . $id . $toggle . '>';
		}

		if ( $echo ) {
			echo $output;
		}
		return $output;
	}

	public function close( $echo = true ) {
		$output = apply_filters( 'cf7bs_bootstrap_button_group_close', '', $this->args );

		if ( empty( $output ) ) {
			$output = '</div>';
		}

		if ( $echo ) {
			echo $output;
		}
		return $output;
	}

	public function insert_button( $args = array(), $echo = true, $shortcode_tag = '' ) {
		$args = (array) $args;
		$args['size'] = 'default'; // size is defined in button group, so set it to 'default' on button
		if ( in_array( $this->args['mode'], array( 'checkbox', 'radio' ) ) ) {
			$args['mode'] = $this->args['mode'];
		}
		$temp = new CF7BS_Button( $args, $shortcode_tag );
		$this->buttons[] = $temp;
		return $temp->display( $echo );
	}

	protected function validate_args( $args, $exclude = array() ) {
		$args = parent::validate_args( $args, $exclude );

		return $args;
	}

	protected function get_defaults() {
		$defaults = array(
			'mode'		=> 'default', // default, checkbox, radio
			'size'		=> 'default', // default, large, small, mini
			'layout'	=> 'default', // default, vertical, justified
			'id'		=> '',
		);
		return apply_filters( 'cf7bs_bootstrap_button_group_defaults', $defaults );
	}

	public function get_contents() {
		return $this->buttons;
	}
}
