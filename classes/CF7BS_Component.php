<?php
/**
 * CF7BS_Component class
 *
 * @package CF7BS
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 * @since 1.0.0
 */

abstract class CF7BS_Component {
	protected $args = array();

	public function __construct( $args = array() ) {
		$this->args = $this->validate_args( $args );
	}

	protected function validate_args( $args, $exclude = array() ) {
		$defaults = $this->get_defaults();
		$args = wp_parse_args( $args, $defaults );
		foreach ( $defaults as $key => $value ) {
			if ( ! in_array( $key, $exclude ) ) {
				if ( is_string( $value ) ) {
					$args[ $key ] = (string) $args[ $key ];
				} elseif ( is_int( $value ) ) {
					$args[ $key ] = intval( $args[ $key ] );
				} elseif ( is_float( $value ) ) {
					$args[ $key ] = floatval( $args[ $key ] );
				} elseif ( is_bool( $value ) ) {
					$args[ $key ] = (bool) $args[ $key ];
				} elseif ( is_array( $value ) ) {
					$args[ $key ] = (array) $args[ $key ];
				} elseif ( is_object( $value ) ) {
					$args[ $key ] = (object) $args[ $key ];
				}
			}
		}

		return $args;
	}

	protected abstract function get_defaults();

	public function get_args()
	{
		return $this->args;
	}
}
